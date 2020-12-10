<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disk',
        'type',
        'path',
        'original_filename',
        'mime_type',
        'extension',
        'size',
        'optimized_path',
    ];

    /**
     * Get all of the owning filable models.
     */
    public function fileable()
    {
        return $this->morphTo();
    }

    /**
     * Get the URL of the files
     * This will return an absolute url
     */
    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getOptimizedUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->optimized_path);
    }
}
