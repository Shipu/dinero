<?php

namespace App\Console\Commands;

use App\Jobs\MatureJob;
use Illuminate\Console\Command;

class MatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mature:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        MatureJob::dispatch();
    }
}
