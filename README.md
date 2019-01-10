# MWI Polymorphic Laravel Files
Simple and intuitive polymorphic file management service for Laravel.

This will support any of Laravel's disk drivers: "local", "ftp", "sftp", "s3", "rackspace"

# Installation
```shell
composer require mwi/laravel-files
php artisan mwi:files:install
```

## Alias
To use the facade add to your `config/app.php` aliases
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
}
```

## File Uploads
You can use any number of methods to upload your files.

__*NOTE*__ Addition to any fields for CSRF or HTTP method the following fields **ARE ALWAYS REQUIRED** 

  - `file` obviously contains the file to be uploaded
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
Once you have the package and your views set up there are two methods available for use
```php
/**
 * @param \Illuminate\Http\UploadedFile $file The uploaded file
 * @param Array $data An array requiring at least the following data:
 *                      fileable_type
 *                      fileable_id
 *                      fileable_relationship
 */
MWIFile::upload($request->file('file'), $request->input());

/**
 * @param \App\FileUpload $file The file resource
 */
MWIFile::remove(FileUpload::latest()->first());
```