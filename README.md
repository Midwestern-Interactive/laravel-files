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
To verify the package was set up successfully you can `use MWIFiles` and then call `MWIFiles::verify()` in any method.

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

  - fileable_type is the model namespace your saving the file too
  - fileable_id is the id of the specific resource to attach it too
  - fileable_relationship references the name of the relationship you create in the Model

### Basic Form
The most basic being a simple one off form field. You can have any other number of inputs for your needs.
```html
<form action="{{ route('file-upload') }}" method="POST">
    <input type="file" name="file">
    <input type="hidden" value="\App\User" name="fileable_type">
    <input type="hidden" value="{{ $user->id }}" name="fileable_id">
    <input type="hidden" value="files" name="fileable_relationship">
</form>
```

# Usage
Once you have the package and your template set up there are two methods available for use
```php
/**
 * The following will look for the `file` input in the request and
 * upload to the appropriate location while attaching it to the
 * model and resource specified by the other fields.
 */
MWIFile::upload($request);

/**
 * This method takes the file resource specified and removes it from
 * the filesystem disk specified.
 */
MWIFile::remove($file);
```