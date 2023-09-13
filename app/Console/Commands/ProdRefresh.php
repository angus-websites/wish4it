<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class ProdRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:prod-refresh {--force : Do not ask for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrate:refresh and then seed the database with production data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Only prompt for confirmation in production and when the --force flag is not set
        if (! $this->option('force') && App::environment('production') && ! $this->confirm('This command will refresh the database and replace all existing data. Do you wish to continue?')) {
            $this->info('Command cancelled!');

            return;
        }

        $this->call('migrate:refresh');

        // If you want to run a specific seeder:
        $this->call('db:seed', [
            '--class' => 'ProdSeeder',
        ]);

        $this->info('Database has been refreshed and seeded for production!');
    }
}
