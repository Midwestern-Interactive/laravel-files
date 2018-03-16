<?php

namespace MWI\LaravelFiles;

use App\FileUpload;
use Illuminate\Support\Facades\Storage;

class MWIFile
{
    private $version = '1.0.0';

    /**
     * Returns a string to verify MWIFiles is installed successfully
     * @return string
     */
    public function verify()
    {
        return 'MWI Laravel Files installed successfully using version ' . $this->version;
    }

    /**
     * Create a new file and associate it to the correct model.
     * @param  The upload request.
     * @return \App\FileUpload
     */
    public function upload($request)
    {
        $saved = $request->file('file')->store('public/uploads');
        $file = FileUpload::create([
            'path' => $saved,
        ]);

        if ($request->has('fileable_type') && $request->has('fileable_id')) {
            $model = $request->fileable_type::findOrfail($request->fileable_id);
            $model->{$request->fileable_relationship}()->save($file);
        }

        return $file;
    }

    /**
     * Remove One File for many file relationships
     * @param  string $name
     * @return \App\Role 
     */
    public function remove($file)
    {
        return $file->delete();
    }
}
