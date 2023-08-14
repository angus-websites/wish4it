<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DevRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dev-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrate:refresh and then seed the database with dev data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate:refresh');

        // If you want to run a specific seeder:
        $this->call('db:seed');

        $this->info('Database has been refreshed and seeded!');
    }
}
