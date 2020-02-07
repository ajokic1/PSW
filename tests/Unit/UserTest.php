<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Tests\LoginAsPatient;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use LoginAsPatient;

    /** @test */
    public function update_updates_user_data() {
        $user = User::find(Auth::id());
        $user['first_name'] = 'updated';

        $this->post('/api/user/update', $user->toArray());
        $user = User::find(Auth::id());
        $this->assertTrue($user->first_name == 'updated');
    }


    /** @test */
    public function update_cannot_update_email() {
        $user = User::find(Auth::id());
        $oldEmail = $user->email;
        $user['email'] = 'new@email.com';

        $this->post('/api/user/update', $user->toArray());
        $user = User::find(Auth::id());
        $this->assertTrue($user->email == $oldEmail);
    }
}
