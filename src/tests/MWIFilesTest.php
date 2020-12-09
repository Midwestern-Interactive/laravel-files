<?php

namespace Tests\Unit;

use MWIFile;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MWIFilesTest extends TestCase
{
    private $file;
    private $disk;
    private $data;
    private $modelsNamespace;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        $this->modelsNamespace = app()->version() < 8 ? 'App' : 'App\Models';

        $this->file = UploadedFile::fake()->image('profile.jpg');
        $this->disk = 'local';
        $this->data = [
            'fileable_type' =>  $this->modelsNamespace . '\User',
            'fileable_id' => 1,
            'fileable_relationship' => 'files'
        ];
    }

    /** @test */
    public function a_file_is_uploaded_to_storage()
    {
        $file_upload = MWIFile::upload($this->file, $this->disk, $this->data);

        Storage::assertExists($file_upload->path);
    }

    /** @test */
    public function a_file_can_be_removed()
    {
        $file_upload = MWIFile::upload($this->file, $this->disk, $this->data);

        MWIFile::remove($file_upload);

        Storage::assertMissing($file_upload->path);
    }
}
