<?php

namespace Tests;

use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

trait LoginAsAdmin {
    public function setUp() :void {
        parent::setUp();
        $user = factory(User::class)->create(['role' => 'admin', 'id' => '1']);
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', 'Bearer ' . $token);
    }
}
