<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function test_registration_is_disabled_for_security(): void
    {
        $this->assertFalse(Route::has('register'));

        $response = $this->get('/register');
        $response->assertStatus(404);
    }
}
