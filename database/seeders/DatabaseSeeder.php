<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \DB::transaction(function () {
            $this->call(WordSeeder::class);

            User::factory()->admin()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin12345'),
            ]);

            User::factory(10)->create();
        });
    }
}
