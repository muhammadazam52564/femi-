<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        User::create([
            'name'              => 'Admin',
            'email'             => 'muhammadazam52564@gmail.com',
            'password'          => bcrypt('123456'),
            'email_verified_at' => Carbon::now(),
            'role'              => 1
        ]);

        User::create([
            'name'              => 'Muneeb ur rehman',
            'email'             => 'muneeburryhman@gmail.com',
            'password'          => bcrypt('123456'),
            'email_verified_at' => Carbon::now(),
            'role'              => 1
        ]);

        User::create([
            'name'              => 'user 1',
            'email'             => 'user1@gmail.com',
            'password'          => bcrypt('123456'),
            'email_verified_at' => Carbon::now(),
            'status'            => 1,
            'role'              => 3
        ]);
    }
}
