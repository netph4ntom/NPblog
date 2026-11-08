<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    public function test_password_reset_is_disabled(): void
    {
        $this->assertFalse(Route::has('password.request'));

        $response = $this->get('/forgot-password');
        $response->assertStatus(404);
    }
}
