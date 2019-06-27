# MWI Polymorphic Laravel Files
Simple and intuitive polymorphic file management service for Laravel.

This will support any of Laravel's disk drivers: "local", "ftp", "sftp", "s3", "rackspace"

# Installation
```shell
composer require mwi/laravel-files
php artisan mwi:files:install
```

## Alias
If you would like to use the facade, add to your `config/app.php` aliases
```php
'aliases' => [
    // ...
    'MWIFile' => MWI\LaravelFiles\Facades\MWIFile::class,
    // ...
],
```

## Service Provider
If you're on laravel 5.5 or later the service provider will be automatically loaded and you can skip this step, if not, add to your `config/app.php` providers
```php
'providers' => [
    // ...
    MWI\LaravelFiles\ServiceProvider::class,
    // ...
],
```

## Verification
To verify the package was set up successfully you can `use MWIFile` and then call `MWIFile::verify()` in any method.

It should return the version of the service if successful.

# Set Up
Any model you would like to incorporate files just needs the relationship added
```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // ...

    /**
     * Relationships
     */
    public function files()
    {
        return $this->morphMany(FileUpload::class, 'fileable');
    }

    // Specific type of relationship
    public function photos()
    {
        // Where types value is equal to the value of `fileable_relationship` when saving
        return $this->morphMany(FileUpload::class, 'fileable')->where('type', 'photos');
    }
}
```

## File Uploads
You can use any number of methods to upload your files.

__*NOTE*__ Addition to any fields for CSRF or HTTP method the following fields **ARE ALWAYS REQUIRED** 

  - `file` **REQUIRED** Contains the file to be uploaded
  - `fileable_type` is the model namespace your saving the file too
  - `fileable_id` is the id of the specific resource to attach it too
  - `fileable_relationship` references the name of the relationship you create in the Model

### Basic Form
The most basic being a simple one off form field. You can have any other number of inputs for your needs.
```html
<form action="{{ route('file-upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <input type="hidden" value="\App\User" name="fileable_type">
    <input type="hidden" value="{{ $user->id }}" name="fileable_id">
    <input type="hidden" value="files" name="fileable_relationship">
</form>
```

# Usage
Once you have the package and your views set up there are three methods available for use
```php
/**
 * @param  \Illuminate\Http\UploadedFile  $file  The uploaded file
 *
 * @param  string                         $disk  The disk in which to upload the file too,
 *                                               defaults to local, meaning it will not be publicly accessible.
 *                                               Change to `public` for public files like profile photos.
 *                                               It's recommend to use `config('filesystems.default')` as a standard
 *                                               and then chagne as necessary for specific use cases
 *
 * @param  Array                          $data  An array requiring at least the following data, note that
 *                                               if this data is not all present it will simply upload the file
 *                                               and not be associtaed to a specific model:
 *                                               fileable_type
 *                                               fileable_id
 *                                               fileable_relationship
 */
MWIFile::upload($request->file('file'), 'local', $request->input());

/**
 * @param \App\FileUpload $file The file resource
 *
 * Note that if the file is publicly accessiblethis
 * will redirect to it rather than initialize a download
 */
MWIFile::download(FileUpload::latest()->first());

/**
 * @param \App\FileUpload $file The file resource
 *
 * This just removed the relationship to the file,
 * it does NOT delete the file from the filesystem
 */
MWIFile::remove(FileUpload::latest()->first());
```