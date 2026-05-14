<?php

use App\Models\User;

test('guest cannot access profile edit page', function () {
    $this->get(route('profile.edit'))
        ->assertRedirect(route('login'));
});

test('authenticated user can see profile edit page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Edit Profil')
        ->assertSee($user->email);
});

test('authenticated user can update profile', function () {
    $user = User::factory()->create([
        'name' => 'Nama Lama',
        'email' => 'lama@example.com',
    ]);

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Nama Baru',
            'email' => 'baru@example.com',
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status');

    $user->refresh();

    expect($user->name)->toBe('Nama Baru')
        ->and($user->email)->toBe('baru@example.com');
});

test('profile email must be unique', function () {
    User::factory()->create([
        'email' => 'taken@example.com',
    ]);

    $user = User::factory()->create([
        'email' => 'current@example.com',
    ]);

    $this->actingAs($user)
        ->from(route('profile.edit'))
        ->patch(route('profile.update'), [
            'name' => 'Current User',
            'email' => 'taken@example.com',
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHasErrors('email');
});
