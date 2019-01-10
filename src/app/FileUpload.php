<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
        'original_filename',
        'mime_type',
        'extension',
        'size'
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
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }
}
