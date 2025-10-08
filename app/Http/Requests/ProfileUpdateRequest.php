<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic Information
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],

            // Professional Information
            'current_title' => ['nullable', 'string', 'max:255'],
            'current_company' => ['nullable', 'string', 'max:255'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:50'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'github_url' => ['nullable', 'url', 'max:500'],
            'portfolio_url' => ['nullable', 'url', 'max:500'],
            'website_url' => ['nullable', 'url', 'max:500'],

            // Target Information
            'target_roles' => ['nullable', 'array'],
            'target_roles.*' => ['string', 'max:255'],
            'target_companies' => ['nullable', 'array'],
            'target_companies.*' => ['string', 'max:255'],
            'target_salary_min' => ['nullable', 'integer', 'min:0'],
            'target_salary_max' => ['nullable', 'integer', 'min:0', 'gte:target_salary_min'],
            'target_locations' => ['nullable', 'array'],
            'target_locations.*' => ['string', 'max:255'],

            // Skills and Education
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', 'max:100'],
            'certifications' => ['nullable', 'array'],
            'certifications.*' => ['string', 'max:255'],
            'education' => ['nullable', 'array'],
            'education.*.degree' => ['nullable', 'string', 'max:255'],
            'education.*.institution' => ['nullable', 'string', 'max:255'],
            'education.*.year' => ['nullable', 'integer', 'min:1950', 'max:'.(date('Y') + 10)],
            'education.*.field_of_study' => ['nullable', 'string', 'max:255'],

            // Interview Preferences
            'preferred_interview_times' => ['nullable', 'array'],
            'preferred_interview_times.*' => ['string', 'max:50'],
            'interview_availability' => ['nullable', 'string', 'max:500'],
            'interview_preparation_level' => ['nullable', 'string', Rule::in(['beginner', 'intermediate', 'advanced'])],

            // Notification Settings
            'email_notifications' => ['nullable', 'boolean'],
            'interview_reminders' => ['nullable', 'boolean'],
            'weekly_digest' => ['nullable', 'boolean'],
            'marketing_emails' => ['nullable', 'boolean'],

            // Privacy Settings
            'profile_visibility' => ['nullable', 'string', Rule::in(['public', 'private', 'connections'])],
            'show_email' => ['nullable', 'boolean'],
            'show_phone' => ['nullable', 'boolean'],
            'show_location' => ['nullable', 'boolean'],

            // Legacy fields (keeping for backward compatibility)
            'current_role' => ['nullable', 'string', 'max:255'],
            'preferred_interview_types' => ['nullable', 'array'],
            'preferred_interview_types.*' => ['string', 'in:technical,behavioral,case_study,system_design,coding'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'target_salary_max.gte' => 'The maximum target salary must be greater than or equal to the minimum target salary.',
            'education.*.year.min' => 'The graduation year must be after 1950.',
            'education.*.year.max' => 'The graduation year cannot be more than 10 years in the future.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert empty strings to null for nullable fields
        $nullableFields = [
            'phone', 'location', 'timezone', 'bio', 'current_title', 'current_company',
            'linkedin_url', 'github_url', 'portfolio_url', 'website_url',
            'target_salary_min', 'target_salary_max', 'interview_availability',
        ];

        foreach ($nullableFields as $field) {
            if ($this->has($field) && $this->input($field) === '') {
                $this->merge([$field => null]);
            }
        }

        // Ensure arrays are properly formatted
        $arrayFields = ['target_roles', 'target_companies', 'target_locations', 'skills', 'certifications', 'preferred_interview_times'];

        foreach ($arrayFields as $field) {
            if ($this->has($field) && ! is_array($this->input($field))) {
                $value = $this->input($field);
                if (is_string($value)) {
                    // Split comma-separated values
                    $this->merge([$field => array_filter(array_map('trim', explode(',', $value)))]);
                } else {
                    $this->merge([$field => []]);
                }
            }
        }
    }
}
