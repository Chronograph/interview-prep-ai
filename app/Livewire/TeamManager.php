<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.app')]
#[Title('Team Manager')]
class TeamManager extends Component
{
    use WireUiActions;

    public $teams;

    public $showCreateModal = false;

    public $showEditModal = false;

    public $showInviteModal = false;

    public $showMembersModal = false;

    // Form properties
    public $teamId;

    public $name = '';

    public $description = '';

    public $is_active = true;

    // Invite properties
    public $inviteEmail = '';

    public $inviteRole = 'member';

    public $selectedTeamId;

    // Members management
    public $currentTeamMembers = [];

    // Statistics
    public $totalTeams = 0;

    public $totalMembers = 0;

    public $activeTeams = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->teams = collect();
        $this->totalTeams = 0;
        $this->totalMembers = 0;
        $this->activeTeams = 0;

        $this->refreshTeams();
        $this->calculateStatistics();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
        $this->js('$openModal("createTeamModal")');
    }

    public function openEditModal($teamId)
    {
        $team = Team::find($teamId);
        if ($team && ($team->owner_id === Auth::id() || $team->hasMember(Auth::user()))) {
            $this->teamId = $team->id;
            $this->name = $team->name;
            $this->description = $team->description;
            $this->is_active = $team->is_active;
            $this->showEditModal = true;
            $this->js('$openModal("editTeamModal")');
        }
    }

    public function openInviteModal($teamId)
    {
        $team = Team::find($teamId);
        if ($team && ($team->owner_id === Auth::id() || $team->getMemberRole(Auth::user()) === 'admin')) {
            $this->selectedTeamId = $teamId;
            $this->inviteEmail = '';
            $this->inviteRole = 'member';
            $this->showInviteModal = true;
            $this->js('$openModal("inviteTeamModal")');
        }
    }

    public function openMembersModal($teamId)
    {
        $team = Team::find($teamId);
        if ($team && ($team->owner_id === Auth::id() || $team->hasMember(Auth::user()))) {
            $this->selectedTeamId = $teamId;
            $this->currentTeamMembers = $team->members()->get()->toArray();
            $this->showMembersModal = true;
            $this->js('$openModal("teamMembersModal")');
        }
    }

    public function createTeam()
    {
        $this->validate();

        $user = Auth::user();
        $organization = $user->primaryOrganization();

        $team = Team::create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => Auth::id(),
            'organization_id' => $organization?->id,
            'is_active' => $this->is_active,
        ]);

        // Automatically add the owner as admin member
        $team->members()->attach(Auth::id(), [
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        $this->refreshTeams();
        $this->calculateStatistics();
        $this->showCreateModal = false;
        $this->js('$closeModal("createTeamModal")');

        $this->notification()->success(
            'Team Created!',
            'Your team has been created successfully.'
        );
    }

    public function updateTeam()
    {
        $this->validate();

        $team = Team::find($this->teamId);
        if ($team && ($team->owner_id === Auth::id() || $team->getMemberRole(Auth::user()) === 'admin')) {
            $team->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            $this->refreshTeams();
            $this->showEditModal = false;
            $this->js('$closeModal("editTeamModal")');

            $this->notification()->success(
                'Team Updated!',
                'Team has been updated successfully.'
            );
        }
    }

    public function inviteMember()
    {
        $this->validate([
            'inviteEmail' => 'required|email',
            'inviteRole' => 'required|in:member,admin,viewer',
        ]);

        $team = Team::find($this->selectedTeamId);
        if (! $team || ($team->owner_id !== Auth::id() && $team->getMemberRole(Auth::user()) !== 'admin')) {
            $this->notification()->error('Error', 'You do not have permission to invite members.');

            return;
        }

        // Find user by email
        $user = User::where('email', $this->inviteEmail)->first();
        if (! $user) {
            $this->notification()->error(
                'User Not Found',
                'No user found with this email address.'
            );

            return;
        }

        // Check if already a member
        if ($team->hasMember($user)) {
            $this->notification()->error(
                'Already a Member',
                'This user is already a member of the team.'
            );

            return;
        }

        // Add member to team
        $team->members()->attach($user->id, [
            'role' => $this->inviteRole,
            'joined_at' => now(),
        ]);

        $this->showInviteModal = false;
        $this->js('$closeModal("inviteTeamModal")');
        $this->refreshTeams();

        $this->notification()->success(
            'Member Invited!',
            $user->name.' has been added to the team.'
        );
    }

    public function removeMember($teamId, $userId)
    {
        $team = Team::find($teamId);
        if (! $team || ($team->owner_id !== Auth::id() && $team->getMemberRole(Auth::user()) !== 'admin')) {
            $this->notification()->error('Error', 'You do not have permission to remove members.');

            return;
        }

        // Don't allow removing the owner
        if ($team->owner_id === $userId) {
            $this->notification()->error('Error', 'Cannot remove the team owner.');

            return;
        }

        $user = User::find($userId);
        $team->members()->detach($userId);
        $this->refreshTeams();
        $this->currentTeamMembers = $team->members()->get()->toArray();

        $this->notification()->success(
            'Member Removed',
            $user->name.' has been removed from the team.'
        );
    }

    public function updateMemberRole($teamId, $userId, $newRole)
    {
        $team = Team::find($teamId);
        if (! $team || ($team->owner_id !== Auth::id() && $team->getMemberRole(Auth::user()) !== 'admin')) {
            $this->notification()->error('Error', 'You do not have permission to update roles.');

            return;
        }

        // Don't allow changing the owner's role
        if ($team->owner_id === $userId) {
            $this->notification()->error('Error', 'Cannot change the owner\'s role.');

            return;
        }

        $team->members()->updateExistingPivot($userId, ['role' => $newRole]);
        $this->refreshTeams();
        $this->currentTeamMembers = $team->members()->get()->toArray();

        $this->notification()->success(
            'Role Updated',
            'Member role has been updated successfully.'
        );
    }

    public function deleteTeam($teamId)
    {
        $team = Team::find($teamId);
        if ($team && $team->owner_id === Auth::id()) {
            $teamName = $team->name;
            $team->delete();
            $this->refreshTeams();
            $this->calculateStatistics();

            $this->notification()->success(
                'Team Deleted!',
                '"'.$teamName.'" has been deleted successfully.'
            );
        }
    }

    public function leaveTeam($teamId)
    {
        $team = Team::find($teamId);
        if ($team && $team->hasMember(Auth::user())) {
            // Prevent owner from leaving their own team
            if ($team->owner_id === Auth::id()) {
                $this->notification()->error(
                    'Cannot Leave Team',
                    'You are the owner. Delete the team instead or transfer ownership first.'
                );

                return;
            }

            $team->members()->detach(Auth::id());
            $this->refreshTeams();
            $this->calculateStatistics();

            $this->notification()->success(
                'Left Team',
                'You have left "'.$team->name.'".'
            );
        }
    }

    private function resetForm()
    {
        $this->teamId = null;
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
    }

    private function refreshTeams()
    {
        try {
            $user = Auth::user();
            $organization = $user->primaryOrganization();

            if (! $organization) {
                // If user has no organization, show teams they own or are members of (legacy behavior)
                $ownedTeams = Team::where('owner_id', Auth::id())->with('members', 'owner')->get();
                $memberTeams = Team::whereHas('members', function ($query) {
                    $query->where('user_id', Auth::id());
                })->where('owner_id', '!=', Auth::id())->with('members', 'owner')->get();

                $this->teams = $ownedTeams->merge($memberTeams);
            } else {
                // Filter teams by organization
                $ownedTeams = Team::where('organization_id', $organization->id)
                    ->where('owner_id', Auth::id())
                    ->with('members', 'owner')
                    ->get();

                $memberTeams = Team::where('organization_id', $organization->id)
                    ->whereHas('members', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->where('owner_id', '!=', Auth::id())
                    ->with('members', 'owner')
                    ->get();

                $this->teams = $ownedTeams->merge($memberTeams);
            }
        } catch (\Exception $e) {
            logger()->error('Failed to refresh teams: '.$e->getMessage());
            $this->teams = collect();
        }
    }

    private function calculateStatistics()
    {
        $this->totalTeams = $this->teams ? $this->teams->count() : 0;
        $this->activeTeams = $this->teams ? $this->teams->where('is_active', true)->count() : 0;

        $totalMembers = 0;
        if ($this->teams) {
            foreach ($this->teams as $team) {
                $totalMembers += $team->members->count();
            }
        }
        $this->totalMembers = $totalMembers;
    }

    public function render()
    {
        return view('livewire.team-manager', [
            'teams' => $this->teams ?? collect(),
        ]);
    }
}
