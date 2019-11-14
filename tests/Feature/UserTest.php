<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use UsersTableSeeder;

class UserTest extends TestCase
{
    /**
     * Test registration of new user
     *
     * @return  void
     */
    public function testRegistration() {
        $response = $this->json('POST', '/api/register', [
            'email'=>'aj.jokic@gmail.com',
            'password'=>'12345678',            
            'first_name'=>'Test',
            'last_name'=>'User',
            'address'=>'Test address',
            'city'=>'Test city',
            'country'=>'Test country',
            'phone_no'=>'123123123',
            'insurance_no'=>'123123123',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'emailNotVerified' => true,
            ]);
    }

    /**
     * Test login
     * 
     * @return void
     */
    public function testLogin() {
        $this->seed(UsersTableSeeder::class);
        $response = $this->json('POST', '/api/login', [
            'email' => 'test@test.test',
            'password' => '12345678'
        ]);
        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => false,
                'emailNotVerified' => true,
            ]);

        //Test JWT
        $response = $this
            ->withHeaders(['Authorization' => 'Bearer' . 
                \JWTAuth::fromUser(\App\User::where('email','test@test.test')->first())]) 
            ->json('POST', '/api/logout', []);
        $response
            ->assertStatus(200);
    }

    
}
