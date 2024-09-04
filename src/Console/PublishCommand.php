<?php

namespace Buckaroo\Laravel\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buckaroo:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Buckaroo file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'buckaroo-config']);
        $this->call('vendor:publish', ['--tag' => 'buckaroo-database']);
        $this->call('vendor:publish', ['--tag' => 'buckaroo-controllers']);
        $this->call('vendor:publish', ['--tag' => 'buckaroo-requests']);
    }
}
