<?php

use App\Models\Service;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully for admin users', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->assertSuccessful();
});

it('returns 403 for non-admin users', function () {
    $user = User::factory()->create(['role' => 'user']);

    Livewire::actingAs($user)
        ->test('pages::admin.service.index')
        ->assertForbidden();
});

it('displays services in the table', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->assertSee('Servis AC')
        ->assertSee('Perbaikan AC');
});

it('can create a new service', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('create')
        ->assertSet('showModal', true)
        ->set('serviceForm.name', 'Servis Kulkas')
        ->set('serviceForm.description', 'Perbaikan kulkas')
        ->set('serviceForm.is_active', true)
        ->call('save')
        ->assertSet('showModal', false)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('services', [
        'name' => 'Servis Kulkas',
        'description' => 'Perbaikan kulkas',
        'is_active' => true,
    ]);
});

it('validates name is required when creating', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('create')
        ->set('serviceForm.name', '')
        ->call('save')
        ->assertHasErrors(['serviceForm.name']);
});

it('can edit an existing service', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('edit', $service->id)
        ->assertSet('showModal', true)
        ->assertSet('serviceForm.name', 'Servis AC')
        ->set('serviceForm.name', 'Servis AC Updated')
        ->call('save')
        ->assertSet('showModal', false)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'name' => 'Servis AC Updated',
    ]);
});

it('can delete a service without related damage types', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('delete', $service->id);

    $this->assertDatabaseMissing('services', [
        'id' => $service->id,
    ]);
});

it('cannot delete a service with related damage types', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    \App\Models\DamageType::create([
        'service_id' => $service->id,
        'name' => 'AC Tidak Dingin',
        'description' => 'AC tidak dingin',
        'price' => 150000,
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('delete', $service->id);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
    ]);
});
