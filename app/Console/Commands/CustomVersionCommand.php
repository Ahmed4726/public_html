<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CustomVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display the custom Laravel version';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Replace with the custom version you want to show
        $this->line('Laravel Version: 10.0.0');
    }
}
