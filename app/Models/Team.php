<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'owner_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the organization this team belongs to
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the owner of the team
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all members of the team
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Check if a user is a member of the team
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is the owner of the team
     */
    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Get the role of a user in the team
     */
    public function getMemberRole(User $user): ?string
    {
        $member = $this->members()->where('user_id', $user->id)->first();

        return $member?->pivot->role;
    }

    /**
     * Check if user has a specific role or higher in the team
     */
    public function hasRole(User $user, string $role): bool
    {
        if ($this->isOwner($user)) {
            return true; // Team owner has all permissions
        }

        $userRole = $this->getMemberRole($user);
        if (! $userRole) {
            return false;
        }

        $hierarchy = ['viewer' => 1, 'member' => 2, 'lead' => 3];
        $requiredLevel = $hierarchy[$role] ?? 0;
        $userLevel = $hierarchy[$userRole] ?? 0;

        return $userLevel >= $requiredLevel;
    }
}
