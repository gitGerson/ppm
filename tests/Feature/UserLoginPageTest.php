<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('user login page is separate from admin login', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Login Santri')
        ->assertSee('Sign Up')
        ->assertDontSee(route('filament.admin.auth.login'));
});

test('guest is redirected to user login for protected frontend pages', function () {
    $this->get(route('pendaftaran'))
        ->assertRedirect(route('login'));
});

test('user can login from user login page', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret-password'),
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'secret-password',
    ])->assertRedirect(route('pendaftaran'));

    $this->assertAuthenticatedAs($user);
});

test('user can register from the shared auth page', function () {
    $this->post(route('register.store'), [
        'name' => 'Santri Baru',
        'email' => 'santri-baru@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'auth_mode' => 'register',
    ])->assertRedirect(route('pendaftaran'));

    $user = User::query()->where('email', 'santri-baru@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Santri Baru');

    $this->assertAuthenticatedAs($user);
});

test('register validates confirmed password', function () {
    $this->from(route('login'))
        ->post(route('register.store'), [
            'name' => 'Santri Baru',
            'email' => 'santri-baru@example.com',
            'password' => 'password',
            'password_confirmation' => 'different-password',
            'auth_mode' => 'register',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('password', null, 'register');
});

test('invalid user login returns validation errors', function () {
    User::factory()->create([
        'email' => 'santri@example.com',
        'password' => Hash::make('secret-password'),
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'santri@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');
});

test('user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});
