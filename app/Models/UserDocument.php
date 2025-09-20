<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'document_type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'parsed_content',
        'key_skills',
        'experience_highlights',
        'education_details',
        'achievements',
        'ai_summary',
        'is_primary',
        'is_active',
        'last_analyzed_at',
        'usage_count',
    ];

    protected $casts = [
        'parsed_content' => 'array',
        'key_skills' => 'array',
        'experience_highlights' => 'array',
        'education_details' => 'array',
        'achievements' => 'array',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'last_analyzed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function markAsPrimary(): void
    {
        // Remove primary flag from other documents of same type
        static::where('user_id', $this->user_id)
              ->where('document_type', $this->document_type)
              ->where('id', '!=', $this->id)
              ->update(['is_primary' => false]);
        
        $this->update(['is_primary' => true]);
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function needsAnalysis(): bool
    {
        return $this->last_analyzed_at === null || 
               $this->last_analyzed_at->diffInDays(now()) > 30;
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDocumentTypeDisplayAttribute(): string
    {
        return match($this->document_type) {
            'resume' => 'Resume',
            'portfolio' => 'Portfolio',
            'cover_letter' => 'Cover Letter',
            'transcript' => 'Transcript',
            'certificate' => 'Certificate',
            'other' => 'Other Document',
            default => ucfirst($this->document_type)
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            // Delete the actual file when model is deleted
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
        });
    }
}