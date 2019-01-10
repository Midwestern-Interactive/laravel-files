<?php

namespace MWI\LaravelFiles;

use App\FileUpload;
use Illuminate\Support\Facades\Storage;

class MWIFile
{
    private $version = '1.0.3';
    public $data;

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
    public function upload($file, $data)
    {
        $this->data = $data;

        if ($this->data['fileable_type'] && $this->data['fileable_id']) {
            $path = strtolower(substr($this->data['fileable_type'], strrpos($this->data['fileable_type'], "\\") + 1));
            $file_upload = $this->saveFile($file, 'public/' . $path . '/' . $this->data['fileable_id']);

            $model = $this->data['fileable_type']::findOrfail($this->data['fileable_id']);
            $model->{$this->data['fileable_relationship']}()->save($file_upload);

            return $file_upload;
        }

        $file_upload = $this->saveFile($file, 'public/uploads');

        return $file_upload;
    }

    /**
     * Saves a file to storage
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  string $path
     * @return \App\FileUpload
     */
    private function saveFile($file, $path)
    {
        $saved = $file->store($path);

        $file_upload = FileUpload::create([
            'path' => $saved,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->extension(),
            'size' => $file->getClientSize()
        ]);

        return $file_upload;
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
