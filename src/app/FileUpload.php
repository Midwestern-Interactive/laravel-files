<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path'
    ];

    /**
     * Get all of the owning filable models.
     */
    public function fileable()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute() {
        return Storage::url($this->path);
    }
}
