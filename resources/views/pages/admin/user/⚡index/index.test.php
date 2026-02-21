<?php

use App\Models\User;
use Livewire\Livewire;

it('renders successfully for admin users', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->assertSuccessful();
});

it('returns 403 for non-admin users', function () {
    $user = User::factory()->create(['role' => 'user']);

    Livewire::actingAs($user)
        ->test('pages::admin.user.index')
        ->assertForbidden();
});

it('displays users in the table', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->assertSee('John Doe')
        ->assertSee('john@example.com');
});

it('can create a new user', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('create')
        ->assertSet('showModal', true)
        ->set('userForm.name', 'New User')
        ->set('userForm.email', 'newuser@example.com')
        ->set('userForm.phone', '08123456789')
        ->set('userForm.role', 'user')
        ->set('userForm.password', 'password123')
        ->call('save')
        ->assertSet('showModal', false)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'phone' => '08123456789',
        'role' => 'user',
    ]);
});

it('validates name is required when creating', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('create')
        ->set('userForm.name', '')
        ->set('userForm.email', 'test@example.com')
        ->set('userForm.phone', '08123456789')
        ->set('userForm.password', 'password123')
        ->call('save')
        ->assertHasErrors(['userForm.name']);
});

it('validates email is required and valid', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('create')
        ->set('userForm.name', 'Test User')
        ->set('userForm.email', 'invalid-email')
        ->set('userForm.phone', '08123456789')
        ->set('userForm.password', 'password123')
        ->call('save')
        ->assertHasErrors(['userForm.email']);
});

it('validates email uniqueness on create', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['email' => 'existing@example.com']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('create')
        ->set('userForm.name', 'Test User')
        ->set('userForm.email', 'existing@example.com')
        ->set('userForm.phone', '08123456789')
        ->set('userForm.password', 'password123')
        ->call('save')
        ->assertHasErrors(['userForm.email']);
});

it('validates password is required on create', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('create')
        ->set('userForm.name', 'Test User')
        ->set('userForm.email', 'test@example.com')
        ->set('userForm.phone', '08123456789')
        ->set('userForm.password', '')
        ->call('save')
        ->assertHasErrors(['userForm.password']);
});

it('can edit an existing user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
        'role' => 'user',
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('edit', $user->id)
        ->assertSet('showModal', true)
        ->assertSet('userForm.name', 'Original Name')
        ->assertSet('userForm.email', 'original@example.com')
        ->set('userForm.name', 'Updated Name')
        ->set('userForm.role', 'technician')
        ->call('save')
        ->assertSet('showModal', false)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'role' => 'technician',
    ]);
});

it('allows editing without changing password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'Test User']);
    $originalPassword = $user->password;

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('edit', $user->id)
        ->set('userForm.name', 'Updated Name')
        ->set('userForm.password', '')
        ->call('save')
        ->assertHasNoErrors();

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->password)->toBe($originalPassword);
});

it('allows updating own email on edit', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['email' => 'user@example.com']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('edit', $user->id)
        ->set('userForm.email', 'user@example.com')
        ->call('save')
        ->assertHasNoErrors();
});

it('can delete a user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'To Delete']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('delete', $user->id);

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('cannot delete own account', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->call('delete', $admin->id);

    $this->assertDatabaseHas('users', [
        'id' => $admin->id,
    ]);
});

it('can search users by name', function () {
    $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
    User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->set('search', 'John')
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});

it('can filter users by role', function () {
    $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
    User::factory()->create(['name' => 'Regular User', 'role' => 'user']);
    User::factory()->create(['name' => 'Tech User', 'role' => 'technician']);

    Livewire::actingAs($admin)
        ->test('pages::admin.user.index')
        ->set('filterRole', 'technician')
        ->assertSee('Tech User')
        ->assertDontSee('Regular User');
});
