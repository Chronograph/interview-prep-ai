<div class="py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Team Management</h1>
                    <p class="text-gray-600 mt-1">Collaborate with your team on interview preparation</p>
                </div>
                <x-button primary wire:click="openCreateModal" icon="plus">
                    Create Team
                </x-button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Teams</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalTeams ?? 0 }}</p>
                        <p class="text-xs text-gray-500">Teams you own or are a member of</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Members</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalMembers ?? 0 }}</p>
                        <p class="text-xs text-gray-500">Across all your teams</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Teams</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $activeTeams ?? 0 }}</p>
                        <p class="text-xs text-gray-500">Currently active</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teams Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($teams as $team)
                <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                    <div class="card-body">
                        <!-- Team Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="card-title text-xl">
                                    {{ $team->name }}
                                    @if($team->owner_id === Auth::id())
                                        <div class="badge badge-primary badge-sm">Owner</div>
                                    @endif
                                </h2>
                                @if(!$team->is_active)
                                    <div class="badge badge-ghost badge-sm mt-1">Inactive</div>
                                @endif
                            </div>

                            <!-- Dropdown Menu -->
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </label>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                    <li><a wire:click="openMembersModal({{ $team->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        View Members
                                    </a></li>
                                    @if($team->owner_id === Auth::id() || $team->getMemberRole(Auth::user()) === 'admin')
                                        <li><a wire:click="openInviteModal({{ $team->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                            Invite Member
                                        </a></li>
                                        <li><a wire:click="openEditModal({{ $team->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit Team
                                        </a></li>
                                    @endif
                                    @if($team->owner_id === Auth::id())
                                        <li><a wire:click="deleteTeam({{ $team->id }})" class="text-error">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete Team
                                        </a></li>
                                    @else
                                        <li><a wire:click="leaveTeam({{ $team->id }})" class="text-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Leave Team
                                        </a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Team Description -->
                        <p class="text-base-content/70 text-sm mb-4 line-clamp-2">
                            {{ $team->description ?: 'No description provided.' }}
                        </p>

                        <!-- Team Info -->
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="text-base-content/70">{{ $team->members->count() }} members</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-base-content/70">{{ $team->owner->name }}</span>
                            </div>
                        </div>

                        <!-- Member Avatars -->
                        <div class="flex -space-x-2 mt-4">
                            @foreach($team->members->take(5) as $member)
                                <div class="avatar placeholder">
                                    <div class="w-8 rounded-full bg-primary text-primary-content ring ring-base-100">
                                        <span class="text-xs">{{ substr($member->name, 0, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                            @if($team->members->count() > 5)
                                <div class="avatar placeholder">
                                    <div class="w-8 rounded-full bg-base-300 text-base-content ring ring-base-100">
                                        <span class="text-xs">+{{ $team->members->count() - 5 }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Teams Yet</h3>
                            <p class="text-gray-600 mb-6">Create your first team to start collaborating on interview preparation.</p>
                            <x-button primary wire:click="openCreateModal" icon="plus">
                                Create Your First Team
                            </x-button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Team Modal -->
    <x-modal name="createTeamModal" title="Create New Team">
        <form wire:submit.prevent="createTeam">
            <div class="space-y-4">
                <x-input
                    wire:model="name"
                    label="Team Name"
                    placeholder="Enter team name"
                    required
                />
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                <x-textarea
                    wire:model="description"
                    label="Description"
                    placeholder="Describe the purpose of this team"
                    rows="3"
                />
                @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                <x-checkbox
                    wire:model="is_active"
                    label="Active Team"
                />
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-button flat onclick="$closeModal('createTeamModal')">Cancel</x-button>
                <x-button primary type="submit">Create Team</x-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Team Modal -->
    <x-modal name="editTeamModal" title="Edit Team">
        <form wire:submit.prevent="updateTeam">
            <div class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Team Name *</span>
                    </label>
                    <input type="text" wire:model="name" class="input input-bordered w-full" placeholder="Enter team name" required>
                    @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea wire:model="description" class="textarea textarea-bordered h-24" placeholder="Describe the purpose of this team"></textarea>
                    @error('description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" wire:model="is_active" class="checkbox checkbox-primary">
                        <span class="label-text">Active Team</span>
                    </label>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="$closeModal('editTeamModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Team</button>
            </div>
        </form>
    </x-modal>

    <!-- Invite Member Modal -->
    <x-modal name="inviteTeamModal" title="Invite Team Member">
        <form wire:submit.prevent="inviteMember">
            <div class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email Address *</span>
                    </label>
                    <input type="email" wire:model="inviteEmail" class="input input-bordered w-full" placeholder="member@example.com" required>
                    @error('inviteEmail') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Role *</span>
                    </label>
                    <select wire:model="inviteRole" class="select select-bordered w-full">
                        <option value="member">Member</option>
                        <option value="admin">Admin</option>
                        <option value="viewer">Viewer</option>
                    </select>
                    @error('inviteRole') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="alert alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <h3 class="font-bold">Role Permissions</h3>
                        <div class="text-xs">
                            <strong>Admin:</strong> Can manage team and members<br>
                            <strong>Member:</strong> Can view and collaborate<br>
                            <strong>Viewer:</strong> Read-only access
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="$closeModal('inviteTeamModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Send Invite</button>
            </div>
        </form>
    </x-modal>

    <!-- Team Members Modal -->
    <x-modal name="teamMembersModal" title="Team Members" size="lg">
        <div class="space-y-4">
            @if(!empty($currentTeamMembers))
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentTeamMembers as $member)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="w-10 rounded-full bg-primary text-primary-content">
                                                    <span class="text-sm">{{ substr($member['name'], 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $member['name'] }}</div>
                                                <div class="text-sm opacity-50">{{ $member['email'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="select select-bordered select-sm"
                                                wire:change="updateMemberRole({{ $selectedTeamId }}, {{ $member['id'] }}, $event.target.value)"
                                                @if($member['id'] === Auth::id() || (isset($teams->firstWhere('id', $selectedTeamId)->owner_id) && $teams->firstWhere('id', $selectedTeamId)->owner_id === $member['id'])) disabled @endif>
                                            <option value="member" @if($member['pivot']['role'] === 'member') selected @endif>Member</option>
                                            <option value="admin" @if($member['pivot']['role'] === 'admin') selected @endif>Admin</option>
                                            <option value="viewer" @if($member['pivot']['role'] === 'viewer') selected @endif>Viewer</option>
                                        </select>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($member['pivot']['joined_at'])->format('M d, Y') }}</td>
                                    <td>
                                        @php
                                            $team = $teams->firstWhere('id', $selectedTeamId);
                                            $isOwner = $team && $team->owner_id === $member['id'];
                                        @endphp
                                        @if(!$isOwner)
                                            <button wire:click="removeMember({{ $selectedTeamId }}, {{ $member['id'] }})"
                                                    class="btn btn-ghost btn-sm text-error">
                                                Remove
                                            </button>
                                        @else
                                            <span class="badge badge-primary">Owner</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-base-content/70">
                    No members in this team yet.
                </div>
            @endif
        </div>

        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="$closeModal('teamMembersModal')">Close</button>
        </div>
    </x-modal>
</div>

