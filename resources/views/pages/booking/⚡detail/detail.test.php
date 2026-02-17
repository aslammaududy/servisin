<?php

use Livewire\Livewire;

it('booking detail page renders successfully', function () {
    $user = \App\Models\User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::booking.create')
        ->set([
            'bookingForm.service_ids' => [1, 2],
            'bookingForm.damage_type_ids' => [1, 2, 3],
            'bookingForm.booking_date' => '2026-03-15 10:00:00',
            'bookingForm.address' => 'Jl. Merdeka No. 123, Jakarta',
            'bookingForm.notes' => 'AC di ruang tamu lantai 2',
        ])
        ->call('save');

    $booking = \App\Models\Booking::first();

    Livewire::test('pages::booking.detail', compact('booking'))
        ->assertStatus(200);
});

it('technician_id can be changed', function () {
    $user = \App\Models\User::factory()->create();
    $technician = \App\Models\User::create([
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => fake()->password(),
        'phone' => fake()->phoneNumber(),
        'role' => 'technician',
    ]);

    Livewire::actingAs($user)
        ->test('pages::booking.create')
        ->set([
            'bookingForm.service_ids' => [1, 2],
            'bookingForm.damage_type_ids' => [1, 2, 3],
            'bookingForm.booking_date' => '2026-03-15 10:00:00',
            'bookingForm.address' => 'Jl. Merdeka No. 123, Jakarta',
            'bookingForm.notes' => 'AC di ruang tamu lantai 2',
        ])
        ->call('save');

    $booking = \App\Models\Booking::first();

    Livewire::test('pages::booking.detail', compact('booking'))
        ->set('technician_id', $technician->id)
        ->assertSet('technician_id', $technician->id);

    $this->assertDatabaseHas('bookings', [
        'technician_id' => $technician->id,
    ]);

});

it('booking_status can be changed', function () {
    $user = \App\Models\User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::booking.create')
        ->set([
            'bookingForm.service_ids' => [1, 2],
            'bookingForm.damage_type_ids' => [1, 2, 3],
            'bookingForm.booking_date' => '2026-03-15 10:00:00',
            'bookingForm.address' => 'Jl. Merdeka No. 123, Jakarta',
            'bookingForm.notes' => 'AC di ruang tamu lantai 2',
        ])
        ->call('save');

    $booking = \App\Models\Booking::first();

    Livewire::test('pages::booking.detail', compact('booking'))
        ->set('booking_status', 'Selesai')
        ->assertSet('booking_status', 'Selesai');

    $this->assertDatabaseHas('bookings', [
        'status' => 'done',
    ]);

});
