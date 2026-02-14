<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::booking.create')
        ->assertStatus(200);
});

it('service_ids, damage_type_ids, booking_date, and address are required', function () {
    Livewire::test('pages::booking.create')
        ->set([
            'bookingForm.service_ids' => [],
            'bookingForm.damage_type_ids' => [],
            'bookingForm.booking_date' => '',
            'bookingForm.address' => '',
        ])
        ->call('save')
        ->assertHasErrors([
            'bookingForm.service_ids',
            'bookingForm.damage_type_ids',
            'bookingForm.booking_date',
            'bookingForm.address',
        ]);
});

it('notes and foto are optional', function () {
    Livewire::test('pages::booking.create')
        ->set([
            'bookingForm.user_id' => 1,
            'bookingForm.service_ids' => [1],
            'bookingForm.damage_type_ids' => [2],
            'bookingForm.booking_date' => date('Y-m-d'),
            'bookingForm.address' => 'jalan jalan',
        ])->call('save')
        ->assertHasNoErrors();
});

it('booking date can be save as datetime', function () {
    Livewire::test('pages::booking.create')
        ->set([
            'bookingForm.user_id' => 1,
            'bookingForm.service_ids' => [1],
            'bookingForm.damage_type_ids' => [2],
            'bookingForm.booking_date' => date('Y-m-d H:i:s'),
            'bookingForm.address' => 'jalan jalan',
        ])->call('save')
        ->assertHasNoErrors();
});

/*
|--------------------------------------------------------------------------
| Test: Booking saves to database
|--------------------------------------------------------------------------
|
| This test follows the TDD approach:
|
| STEP 1: Set up the data (Arrange)
|   - Create real records that the form depends on (User, Service, DamageType)
|   - Why? Because store() inserts into bookings, booking_items, etc.
|     Those tables have columns like user_id, damage_type_id
|     that reference these records.
|
| STEP 2: Fill the form and submit (Act)
|   - Use Livewire::test() to simulate filling the form
|   - This is exactly what happens when a user fills the form in the browser
|
| STEP 3: Check the database (Assert)
|   - assertDatabaseHas('table', [...]) checks that a row exists
|     in the given table with the given column values
|   - We check ALL 3 tables that store() writes to:
|     bookings, booking_items (no photo since it's optional)
|
*/
it('saves booking to database with valid data', function () {

    // ===== STEP 1: ARRANGE — create the records the form needs =====

    // Create a user — this is who is "logged in" making the booking
    // We use the UserFactory because it already exists in database/factories/
    $user = \App\Models\User::factory()->create();

    // Create a service — no factory exists, so we create it inline
    // This is the "Layanan" dropdown in the form
    $service = \App\Models\Service::create([
        'name' => 'Servis AC',
        'description' => 'Perbaikan dan perawatan AC',
        'is_active' => true,
    ]);

    // Create a damage type linked to the service above
    // This is the "Jenis Kerusakan" dropdown (filtered by service_ids)
    $damageType = \App\Models\DamageType::create([
        'service_id' => $service->id,
        'name' => 'AC Tidak Dingin',
        'description' => 'AC menyala tapi tidak mengeluarkan udara dingin',
        'price' => 150000,
        'is_active' => true,
    ]);

    // ===== STEP 2: ACT — fill the form and submit =====

    // actingAs() simulates being logged in as $user
    // This is important because mount() sets bookingForm.user_id = auth()->id()
    Livewire::actingAs($user)
        ->test('pages::booking.create')
        ->set([
            // We don't set user_id here — mount() sets it from auth()->id()
            'bookingForm.service_ids' => [$service->id],
            'bookingForm.damage_type_ids' => [$damageType->id],
            'bookingForm.booking_date' => '2026-03-15 10:00:00',
            'bookingForm.address' => 'Jl. Merdeka No. 123, Jakarta',
            'bookingForm.notes' => 'AC di ruang tamu lantai 2',
        ])
        ->call('save')
        ->assertHasNoErrors()            // Make sure no validation errors
        ->assertRedirect('/dashboard');  // Verify it redirects after saving

    // ===== STEP 3: ASSERT — check the database =====

    // Check the bookings table has our booking
    // assertDatabaseHas('table_name', ['column' => 'expected_value'])
    //
    // Note: We skip booking_date here because the Booking model casts it
    // as 'timestamp' (Unix integer), so the stored value won't match
    // the datetime string we sent. We verify the other columns instead.
    $this->assertDatabaseHas('bookings', [
        'user_id' => $user->id,
        'address' => 'Jl. Merdeka No. 123, Jakarta',
        'notes' => 'AC di ruang tamu lantai 2',
    ]);

    // Check the booking_items table has the service + damage type
    $this->assertDatabaseHas('booking_items', [
        'damage_type_id' => $damageType->id,
    ]);

    // Verify booking_date was saved (we check via the model instead of
    // assertDatabaseHas because the 'timestamp' cast stores it as a Unix integer)
    expect(\App\Models\Booking::first()->booking_date)->not->toBeNull();

    // We didn't upload a photo, so booking_photos should be empty
    $this->assertDatabaseCount('booking_photos', 0);
});
