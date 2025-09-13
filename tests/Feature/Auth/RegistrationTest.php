<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    // Test registrasi dinonaktifkan karena fitur register tidak digunakan
    
    public function test_registration_screen_disabled(): void
    {
        $response = $this->get('/register');
        
        // Expect 404 karena route register dinonaktifkan
        $response->assertStatus(404);
    }

    // public function test_new_users_can_register(): void
    // {
    //     $response = Volt::test('auth.register')
    //         ->set('name', 'Test User')
    //         ->set('email', 'test@example.com')
    //         ->set('password', 'password')
    //         ->set('password_confirmation', 'password')
    //         ->call('register');

    //     $response
    //         ->assertHasNoErrors()
    //         ->assertRedirect(route('dashboard', absolute: false));

    //     $this->assertAuthenticated();
    // }
}
