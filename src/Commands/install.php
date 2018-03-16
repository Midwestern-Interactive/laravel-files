<?php

namespace MWI\LaravelFiles\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'mwi:files:install';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'One Command to Rule Them All... okay it installs MWI Laravel Files.';

    /**
     * Method to run on command
     * @return void
     */
    public function handle()
    {
        $this->runArtisanCalls();

        $this->comment('MWI Laravel Files Installed.');
    }

    /**
     * Run Artisan Commands
     * @return void
     */
    public function runArtisanCalls()
    {
        // Publish Service Providers
        $this->call('vendor:publish', ['--provider' => 'MWI\LaravelFiles\ServiceProvider']);

        // Migrate and Seed
        $this->call('migrate');
    }
}
