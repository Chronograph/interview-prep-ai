# Organization-Based Architecture

## 🏢 Overview

Your HireCamp Dashboard now uses an **Organization-Based Multi-Tenant Architecture** where:

1. **Organizations** are the billing entities (subscriptions)
2. **Teams** belong to Organizations
3. **Users** belong to Teams (and Organizations)
4. **Role-based access control** at both Organization and Team levels

---

## 📊 Database Structure

```
Organizations
├── id
├── name, slug, description
├── owner_id (User)
├── logo_url, website, industry, size
├── is_active
├── settings (JSON)
├── Stripe billing columns (from Cashier)
└── timestamps

Organization_Members (Pivot)
├── organization_id
├── user_id
├── role: owner | admin | member
├── joined_at
└── timestamps

Teams
├── id
├── organization_id  ← NEW!
├── name, description
├── owner_id (User)
├── is_active
└── timestamps

Team_Members (Pivot)
├── team_id
├── user_id
├── role: lead | member | viewer
├── joined_at
└── timestamps

Users
└── (No billing info - Organizations handle billing)
```

---

## 👥 Role Hierarchy

### Organization Roles

| Role | Permissions |
|------|-------------|
| **Owner** | Full control: billing, settings, delete org, manage all teams & members |
| **Admin** | Manage teams, invite/remove members (no billing access) |
| **Member** | Access teams they belong to, basic features |

### Team Roles

| Role | Permissions |
|------|-------------|
| **Lead** | Manage team members, team settings |
| **Member** | Full access to team resources |
| **Viewer** | Read-only access to team |

---

## 🔑 Key Relationships

### User

```php
// Organizations
$user->ownedOrganizations()      // Organizations user owns
$user->organizations()             // Organizations user is a member of
$user->primaryOrganization()       // First owned org or first membership
$user->currentOrganization()       // First organization membership

// Teams
$user->ownedTeams()               // Teams user owns
$user->teams()                     // Teams user is a member of
```

### Organization

```php
// Relationships
$org->owner                        // Organization owner (User)
$org->members()                    // All members (Users)
$org->teams()                      // All teams in this org

// Role Checks
$org->isOwner($user)              // Is user the owner?
$org->hasMember($user)            // Is user a member?
$org->getMemberRole($user)        // Get user's role
$org->hasRole($user, 'admin')     // Does user have admin role or higher?

// Billing (Cashier)
$org->subscribed('default')        // Has active subscription?
$org->subscription('default')      // Get subscription
$org->onPlan('pro')               // Is on specific plan?
$org->getCurrentPlan()            // Returns: 'free', 'basic', 'pro', or 'enterprise'
$org->canAccessFeature('teams')   // Can access feature based on plan?
$org->getPlanLimit('team_members') // Get limit for resource
$org->hasReachedLimit('team_members', 10) // Check if limit reached
$org->canAddMoreMembers()         // Can add more members based on plan?
```

### Team

```php
// Relationships
$team->organization               // Parent organization
$team->owner                      // Team owner (User)
$team->members()                  // Team members (Users)

// Role Checks
$team->isOwner($user)            // Is user the team owner?
$team->hasMember($user)          // Is user a member?
$team->getMemberRole($user)      // Get user's role
$team->hasRole($user, 'lead')    // Does user have lead role or higher?
```

---

## 💳 Billing Flow

### Who Pays?

**Organizations** are the billing entity:
- Organization owners manage subscriptions
- Subscription limits apply to the entire organization
- All teams within an organization share the same plan limits

### Subscription Plans

Configured in `config/plans.php`:

- **Free**: Limited features, no payment required
- **Basic** ($19/mo): 50 AI interviews, no teams
- **Pro** ($49/mo): Unlimited interviews, 5 team members
- **Enterprise** ($199/mo): Everything unlimited

### Plan Limits

```php
// config/plans.php
'limits' => [
    'ai_interviews' => 50,      // -1 = unlimited
    'resumes' => -1,            // unlimited
    'cheat_sheets' => -1,       // unlimited
    'team_members' => 5,        // max members in organization
]
```

---

## 🔒 Authorization Examples

### Protect Routes by Organization Subscription

```php
// Require organization to be subscribed
Route::get('/premium-feature', function () {
    $org = auth()->user()->primaryOrganization();
    
    if (!$org || !$org->subscribed('default')) {
        return redirect()->route('billing.index')
            ->with('error', 'Your organization needs an active subscription');
    }
    
    // ... feature code
})->middleware('auth');

// Require specific plan
Route::get('/pro-feature', function () {
    $org = auth()->user()->primaryOrganization();
    
    if (!$org || !$org->onPlan('pro')) {
        return redirect()->route('billing.index')
            ->with('error', 'This feature requires a Pro plan');
    }
    
    // ... feature code
})->middleware('auth');
```

### Check Organization Roles

```php
// In controllers
public function manageTeam(Team $team)
{
    $org = $team->organization;
    
    // Check if user is org admin or owner
    if (!$org->hasRole(auth()->user(), 'admin')) {
        abort(403, 'You must be an organization admin');
    }
    
    // ... management code
}
```

### Check Team Roles

```php
// In controllers
public function editTeam(Team $team)
{
    // Check if user is team lead or owner
    if (!$team->hasRole(auth()->user(), 'lead')) {
        abort(403, 'You must be a team lead');
    }
    
    // ... edit code
}
```

### Check Feature Access

```php
// In Livewire components
public function mount()
{
    $org = auth()->user()->primaryOrganization();
    
    if (!$org || !$org->canAccessFeature('ai_personas')) {
        session()->flash('error', 'AI Personas require a Pro plan');
        return redirect()->route('billing.index');
    }
}
```

### Check Usage Limits

```php
// Before creating AI interview
public function startInterview()
{
    $org = auth()->user()->primaryOrganization();
    $currentUsage = /* get current month's interview count */;
    
    if ($org->hasReachedLimit('ai_interviews', $currentUsage)) {
        $this->notification()->error(
            'Limit Reached',
            'You\'ve reached your monthly AI interview limit. Please upgrade your plan.'
        );
        return;
    }
    
    // ... start interview
}
```

---

## 🚀 Typical User Flows

### 1. New User Signs Up

```php
// After registration
$user = Auth::user();

// Option A: Create their own organization
$org = Organization::create([
    'name' => $user->name . "'s Organization",
    'owner_id' => $user->id,
]);

// Add user as owner
$org->members()->attach($user->id, [
    'role' => 'owner',
    'joined_at' => now(),
]);

// Option B: Join existing organization via invite
// (invitation system to be implemented)
```

### 2. Organization Owner Invites Team Members

```php
// In OrganizationManager component
public function inviteMember($email, $role = 'member')
{
    $org = auth()->user()->primaryOrganization();
    
    // Check if org can add more members
    if (!$org->canAddMoreMembers()) {
        $this->notification()->error(
            'Member Limit Reached',
            'Please upgrade your plan to add more members.'
        );
        return;
    }
    
    $user = User::where('email', $email)->first();
    
    $org->members()->attach($user->id, [
        'role' => $role,
        'joined_at' => now(),
    ]);
}
```

### 3. Create Team within Organization

```php
public function createTeam($name)
{
    $org = auth()->user()->primaryOrganization();
    
    $team = Team::create([
        'organization_id' => $org->id,
        'name' => $name,
        'owner_id' => auth()->id(),
    ]);
    
    // Add creator as team lead
    $team->members()->attach(auth()->id(), [
        'role' => 'lead',
        'joined_at' => now(),
    ]);
}
```

---

## 📝 Migration Guide (From Old Structure)

If you had existing data:

```sql
-- Teams now require organization_id
-- You'll need to create organizations for existing teams

INSERT INTO organizations (name, slug, owner_id, created_at, updated_at)
SELECT 
    CONCAT(u.name, "'s Organization"),
    CONCAT(LOWER(REPLACE(u.name, ' ', '-')), '-', t.id),
    t.owner_id,
    t.created_at,
    t.updated_at
FROM teams t
JOIN users u ON t.owner_id = u.id
WHERE t.organization_id IS NULL;

-- Link teams to organizations
UPDATE teams t
SET organization_id = (
    SELECT id FROM organizations WHERE owner_id = t.owner_id LIMIT 1
)
WHERE organization_id IS NULL;

-- Add organization members from team members
INSERT INTO organization_members (organization_id, user_id, role, joined_at)
SELECT DISTINCT t.organization_id, tm.user_id, 'member', tm.joined_at
FROM team_members tm
JOIN teams t ON tm.team_id = t.id
LEFT JOIN organization_members om ON om.organization_id = t.organization_id AND om.user_id = tm.user_id
WHERE om.id IS NULL;
```

---

## 🎯 Next Steps

1. **Update BillingManager** to work with Organizations
2. **Create OrganizationManager** Livewire component
3. **Update TeamManager** to work within Organizations
4. **Create invitation system** for adding members
5. **Update all authorization** to check organization roles
6. **Add organization switcher** if users belong to multiple orgs

---

**Built for enterprise-grade multi-tenancy with proper role-based access control!** 🎉

