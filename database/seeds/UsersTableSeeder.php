<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        factory(User::class)
            ->create([
                'name' => 'Admin',
                'email' => 'admin@'.parse_url(config('app.url'), PHP_URL_HOST),
            ])
            ->teams()
            ->attach(
                factory(Team::class)
                    ->create([
                        'title' => 'AdminTeam',
                    ])
            );
    }
}
