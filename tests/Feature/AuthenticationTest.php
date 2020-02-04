<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use UsersTableSeeder;

class AuthenticationTest extends TestCase
{

    /**
     * Test registration of new user
     *
     * @return  void
     */
    public function testRegistration() {
        $user = factory(User::class)->make(['role' => 'patient', 'email' => 'test@test.test']);
        $response = $this->json('POST', '/api/register', $user->toArray());
        var_dump($response->getOriginalContent());
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
        $user = factory(User::class)->create(['role' => 'patient', 'email' => 'test@test.test']);
        $response = $this->json('POST', '/api/login', [
            'email' => 'test@test.test',
            'password' => 'password'
        ]);
        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        //Test JWT
        $response = $this
            ->withHeaders(['Authorization' => 'Bearer' .
                \JWTAuth::fromUser(User::where('email','test@test.test')->first())])
            ->json('POST', '/api/logout', []);
        $response
            ->assertStatus(200);
    }


}
