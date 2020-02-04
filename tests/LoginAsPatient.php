<?php


namespace Tests;

use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

trait LoginAsPatient {
    public function setUp() :void {
        parent::setUp();
        $user = factory(User::class)->create(['role' => 'patient']);
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', 'Bearer ' . $token);
    }
}
