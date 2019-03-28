<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        factory(Team::class, 25)
            ->create()
            ->each(function (Team $team): void {
                $team->users()
                    ->attach(
                        factory(User::class, 2)
                            ->create()
                    );
            });
    }
}
