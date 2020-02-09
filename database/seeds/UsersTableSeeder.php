<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create(['role' => 'patient', 'email' => 'aj.jokic@gmail.com']);
        factory(\App\User::class)->create(['role' => 'admin', 'email' => 'aj.jokic+admin@gmail.com']);
        factory(\App\User::class, 15)->create(['role' => 'patient']);

    }
}
