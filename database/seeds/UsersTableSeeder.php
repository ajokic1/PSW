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
        $data = [
            'email'=>'test@test.test',
            'password'=>\Hash::make(12345678),            
            'first_name'=>'TestSeed',
            'last_name'=>'User',
            'address'=>'Test address',
            'city'=>'Test city',
            'country'=>'Test country',
            'phone_no'=>'123123123',
            'insurance_no'=>'123123123',
            'auth_token'=>'',
        ];
        $user = new \App\User($data);
        $user->save();
    }
}
