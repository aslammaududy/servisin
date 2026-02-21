<?php

use App\Models\DamageType;
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

    DamageType::create([
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

it('can expand a service to show damage types', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    $damageType = DamageType::create([
        'service_id' => $service->id,
        'name' => 'AC Tidak Dingin',
        'description' => 'AC tidak mengeluarkan udara dingin',
        'price' => 150000,
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('toggleDamageTypes', $service->id)
        ->assertSet('expandedServiceId', $service->id)
        ->assertSee('AC Tidak Dingin')
        ->assertSee('Rp 150.000');
});

it('can collapse the expanded damage types section', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('toggleDamageTypes', $service->id)
        ->assertSet('expandedServiceId', $service->id)
        ->call('toggleDamageTypes', $service->id)
        ->assertSet('expandedServiceId', null);
});

it('can create a new damage type', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('toggleDamageTypes', $service->id)
        ->call('createDamageType')
        ->assertSet('showDamageTypeModal', true)
        ->set('damageTypeForm.name', 'AC Tidak Dingin')
        ->set('damageTypeForm.description', 'AC tidak mengeluarkan udara dingin')
        ->set('damageTypeForm.price', 150000)
        ->set('damageTypeForm.is_active', true)
        ->call('saveDamageType')
        ->assertSet('showDamageTypeModal', false)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('damage_types', [
        'service_id' => $service->id,
        'name' => 'AC Tidak Dingin',
        'description' => 'AC tidak mengeluarkan udara dingin',
        'price' => 150000,
        'is_active' => true,
    ]);
});

it('validates damage type name is required', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('toggleDamageTypes', $service->id)
        ->call('createDamageType')
        ->set('damageTypeForm.name', '')
        ->set('damageTypeForm.description', 'Some description')
        ->set('damageTypeForm.price', 150000)
        ->call('saveDamageType')
        ->assertHasErrors(['damageTypeForm.name']);
});

it('validates damage type description is required', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('toggleDamageTypes', $service->id)
        ->call('createDamageType')
        ->set('damageTypeForm.name', 'AC Tidak Dingin')
        ->set('damageTypeForm.description', '')
        ->set('damageTypeForm.price', 150000)
        ->call('saveDamageType')
        ->assertHasErrors(['damageTypeForm.description']);
});

it('can edit an existing damage type', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    $damageType = DamageType::create([
        'service_id' => $service->id,
        'name' => 'AC Tidak Dingin',
        'description' => 'AC tidak dingin',
        'price' => 150000,
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('toggleDamageTypes', $service->id)
        ->call('editDamageType', $damageType->id)
        ->assertSet('showDamageTypeModal', true)
        ->assertSet('damageTypeForm.name', 'AC Tidak Dingin')
        ->set('damageTypeForm.name', 'AC Bocor')
        ->set('damageTypeForm.price', 175000)
        ->call('saveDamageType')
        ->assertSet('showDamageTypeModal', false)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('damage_types', [
        'id' => $damageType->id,
        'name' => 'AC Bocor',
        'price' => 175000,
    ]);
});

it('can delete a damage type', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    $damageType = DamageType::create([
        'service_id' => $service->id,
        'name' => 'AC Tidak Dingin',
        'description' => 'AC tidak dingin',
        'price' => 150000,
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->call('toggleDamageTypes', $service->id)
        ->call('deleteDamageType', $damageType->id);

    $this->assertDatabaseMissing('damage_types', [
        'id' => $damageType->id,
    ]);
});

it('shows damage type count on service rows', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $service = Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan AC',
        'is_active' => true,
    ]);

    DamageType::create([
        'service_id' => $service->id,
        'name' => 'AC Tidak Dingin',
        'description' => 'AC tidak dingin',
        'price' => 150000,
        'is_active' => true,
    ]);

    DamageType::create([
        'service_id' => $service->id,
        'name' => 'AC Bocor',
        'description' => 'AC bocor',
        'price' => 175000,
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::admin.service.index')
        ->assertSee('2 kerusakan');
});
