<?php

namespace App\Providers;

use App\Models\AiPersona;
use App\Models\CheatSheet;
use App\Models\CompanyBrief;
use App\Models\InterviewSession;
use App\Models\JobPosting;
use App\Models\MasteryTopic;
use App\Models\UserDocument;
use App\Policies\AiPersonaPolicy;
use App\Policies\CheatSheetPolicy;
use App\Policies\CompanyBriefPolicy;
use App\Policies\InterviewSessionPolicy;
use App\Policies\JobPostingPolicy;
use App\Policies\MasteryTopicPolicy;
use App\Policies\UserDocumentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AiPersona::class => AiPersonaPolicy::class,
        CheatSheet::class => CheatSheetPolicy::class,
        CompanyBrief::class => CompanyBriefPolicy::class,
        InterviewSession::class => InterviewSessionPolicy::class,
        JobPosting::class => JobPostingPolicy::class,
        MasteryTopic::class => MasteryTopicPolicy::class,
        UserDocument::class => UserDocumentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define additional gates if needed
        Gate::define('admin', function ($user) {
            return $user->is_admin ?? false;
        });
    }
}
