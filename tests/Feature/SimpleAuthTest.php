<?php

namespace Tests\Feature;

use Tests\TestCase;

class SimpleAuthTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => config('app.admin_username'),
            'password' => config('app.admin_password'),
        ]);

        $response->assertRedirect(route('invoices.create'));
    }

    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'wrong',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_authenticated_user_can_access_protected_routes(): void
    {
        $response = $this->withSession(['simple_authenticated' => true])
            ->get('/');

        $response->assertStatus(200);
    }

    public function test_logout_clears_session(): void
    {
        $response = $this->withSession(['simple_authenticated' => true])
            ->post('/logout');

        $response->assertRedirect('/login');
    }
}
