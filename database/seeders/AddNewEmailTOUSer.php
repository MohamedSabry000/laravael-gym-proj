<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddNewEmailTOUSer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::latest()->take(0)->get();

        // give them role => user
        foreach ($users as $userRole) {
            $userRole->assignRole('user');
        }
    }
}
