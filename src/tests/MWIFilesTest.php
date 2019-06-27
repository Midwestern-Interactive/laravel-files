<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MWI\LaravelFiles\MWIFile;
use Tests\TestCase;

class MWIFilesTest extends TestCase
{
    private $file;
    private $disk;
    private $data;

    public function setUp()
    {
        parent::setup();

        Storage::fake();

        $this->file = UploadedFile::fake()->image('profile.jpg');
        $this->disk = 'local';
        $this->data = [
            'fileable_type' => '\App\User',
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
    function a_file_can_be_removed()
    {
        $file_upload = MWIFile::upload($this->file, $this->disk, $this->data);

        MWIFile::remove($file_upload);

        Storage::assertMissing($file_upload->path);
    }
}
