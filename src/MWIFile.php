<?php

namespace MWI\LaravelFiles;

use Illuminate\Support\Facades\Storage;
class MWIFile
{
    private $version = '1.1.0';

    /** @var string */
    protected $fileUpload;

    public function __construct()
    {
        $this->fileUpload = app()->version() < 8 ? 'App\FileUpload' : 'App\Models\FileUpload';
    }

    /**
     * Returns a string to verify MWIFiles is installed successfully
     *
     * @return string
     */
    public function verify()
    {
        return 'MWI Laravel Files installed successfully using version ' . $this->version;
    }

    /**
     * Create a new file and associate it to the correct model
     *
     * @param  The upload request.
     * @return \App\FileUpload
     */

    /**
     * Create a new file and associate it to the correct model
     *
     * @param  object $file The file object
     * @param  string $disk The disk to store the file to.
     *                      Defaults to 'local', meaning they will
     *                      not be publicly accessible
     *
     * @param  array  $data An array of additional data, Available Params:
     *                      fileable_type          App\User
     *                      fileable_id            1
     *                      fileable_relationship  profilePhoto
     *
     * @return \App\FileUpload|\App\Models\FileUpload
     */
    public function upload($file, $disk = 'local', $data = [])
    {
        if (isset($data['fileable_type']) && isset($data['fileable_id']) && isset($data['fileable_relationship'])) {
            $path = strtolower(substr($data['fileable_type'], strrpos($data['fileable_type'], "\\") + 1));
            $file_upload = $this->saveFile($file, $path . '/' . $data['fileable_id'], $disk, $data['fileable_relationship']);

            $model = $data['fileable_type']::findOrfail($data['fileable_id']);
            $model->{$data['fileable_relationship']}()->save($file_upload);

            return $file_upload;
        }

        $file_upload = $this->saveFile($file, 'uploads', $disk, $data['fileable_relationship']);

        return $file_upload;
    }

    /**
     * Saves a file to storage
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  string $path
     * @return \App\FileUpload|\App\Models\FileUpload
     */
    private function saveFile($file, $path, $disk, $type)
    {
        $saved = $file->store($path, $disk);

        $file_upload = $this->fileUpload::create([
            'disk' => $disk,
            'type' => $type,
            'path' => $saved,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->extension(),
            'size' => $file->getSize(),
        ]);

        return $file_upload;
    }

    /**
     * Download a file, this method assumes default disk roots
     *
     * @param  \App\FileUpload|\App\Models\FileUpload $file
     * @return response|redirect
     */
    public function download($file)
    {
        if ($file->disk === 'local') {
            return response()->download(storage_path('app/' . $file->path));
        }
        return redirect($file->url);
    }

    /**
     * Remove a file
     *
     * @param \App\FileUpload|\App\Models\FileUpload $file
     */
    public function remove($file)
    {
        Storage::delete($file->path);
        return $file->delete();
    }
}
