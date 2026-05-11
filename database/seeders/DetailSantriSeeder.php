<?php

namespace Database\Seeders;

use App\Models\DetailSantri;
use App\Models\User;
use Illuminate\Database\Seeder;

class DetailSantriSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->create()
            ->each(function (User $user): void {
                DetailSantri::factory()
                    ->for($user)
                    ->create();
            });
    }
}
