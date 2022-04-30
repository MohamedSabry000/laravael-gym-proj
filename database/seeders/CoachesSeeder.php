<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoachesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        //to get last 60 row add in DB
        $coachesManager = User::latest()->take(10)->get();

        //give them role => coach
        foreach ($coachesManager as $coachRole) {
            $coachRole->assignRole('coach');
        }
    }
}
