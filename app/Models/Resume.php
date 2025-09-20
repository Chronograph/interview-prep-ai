<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Resume extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'summary',
        'experience',
        'education',
        'skills',
        'certifications',
        'projects',
        'raw_content',
        'file_path',
        'file_type',
        'is_primary',
    ];

    protected $casts = [
        'experience' => 'array',
        'education' => 'array',
        'skills' => 'array',
        'certifications' => 'array',
        'projects' => 'array',
        'is_primary' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
