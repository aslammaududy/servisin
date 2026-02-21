<?php

use App\Models\User;

it('creates an admin user', function () {
    $this->artisan('app:create-admin', [
        '--name' => 'Admin Test',
        '--email' => 'admin@test.com',
        '--phone' => '08123456789',
        '--password' => 'password123',
    ])
        ->expectsOutputToContain('admin@test.com')
        ->assertSuccessful();

    $this->assertDatabaseHas('users', [
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'phone' => '08123456789',
        'role' => 'admin',
    ]);

    $admin = User::where('email', 'admin@test.com')->first();
    expect($admin->email_verified_at)->not->toBeNull();
});

it('prevents duplicate email', function () {
    User::factory()->create(['email' => 'existing@test.com']);

    $this->artisan('app:create-admin', [
        '--name' => 'Admin Test',
        '--email' => 'existing@test.com',
        '--phone' => '08123456789',
        '--password' => 'password123',
    ])
        ->expectsOutputToContain('Email sudah terdaftar')
        ->assertFailed();

    $this->assertDatabaseMissing('users', [
        'name' => 'Admin Test',
        'role' => 'admin',
    ]);
});

it('validates required fields', function () {
    $this->artisan('app:create-admin', [
        '--name' => '',
        '--email' => '',
        '--phone' => '',
        '--password' => '',
    ])->assertFailed();

    $this->assertDatabaseCount('users', 0);
});

it('validates minimum lengths', function () {
    $this->artisan('app:create-admin', [
        '--name' => 'ab',
        '--email' => 'admin@test.com',
        '--phone' => '0812',
        '--password' => 'short',
    ])
        ->expectsOutputToContain('Nama minimal 3 karakter')
        ->assertFailed();

    $this->assertDatabaseCount('users', 0);
});

it('validates email format', function () {
    $this->artisan('app:create-admin', [
        '--name' => 'Admin Test',
        '--email' => 'not-an-email',
        '--phone' => '08123456789',
        '--password' => 'password123',
    ])
        ->expectsOutputToContain('Format email tidak valid')
        ->assertFailed();

    $this->assertDatabaseCount('users', 0);
});
