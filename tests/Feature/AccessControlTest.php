<?php

use App\Models\User;

it('redirects guests away from protected admin and siswa routes', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));

    $this->get(route('siswa.transactions.index'))
        ->assertRedirect(route('login'));
});

it('redirects authenticated users to their dashboard based on role', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertRedirect(route('admin.dashboard'));

    auth()->logout();

    $siswa = User::factory()->create([
        'role' => 'siswa',
    ]);

    $this->actingAs($siswa)
        ->get(route('dashboard'))
        ->assertRedirect(route('siswa.dashboard'));
});

it('forbids siswa users from accessing admin routes', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
    ]);

    $this->actingAs($siswa)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

it('forbids admin users from accessing siswa routes', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get(route('siswa.transactions.index'))
        ->assertForbidden();
});
