<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShieldRefreshAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shield:refresh-admin
                            {--user=1 : User ID that should receive the super admin role}
                            {--panel=admin : Filament panel ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Shield super admin and regenerate admin panel permissions.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $userId = (string) $this->option('user');
        $panel = (string) $this->option('panel');

        $superAdminExitCode = $this->call('shield:super-admin', [
            '--user' => $userId,
            '--panel' => $panel,
        ]);

        if ($superAdminExitCode !== self::SUCCESS) {
            return $superAdminExitCode;
        }

        return $this->call('shield:generate', [
            '--all' => true,
            '--ignore-existing-policies' => true,
            '--panel' => $panel,
        ]);
    }
}
