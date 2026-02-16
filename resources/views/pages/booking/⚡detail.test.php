<?php

use Livewire\Livewire;

it('booking detail page renders successfully', function () {
    $user = \App\Models\User::factory()->create();

    $booking_page = Livewire::actingAs($user)
        ->test('pages::booking.create')
        ->set([
            'bookingForm.service_ids' => [1, 2],
            'bookingForm.damage_type_ids' => [1,2,3],
            'bookingForm.booking_date' => '2026-03-15 10:00:00',
            'bookingForm.address' => 'Jl. Merdeka No. 123, Jakarta',
            'bookingForm.notes' => 'AC di ruang tamu lantai 2',
        ])
        ->call('save');

    $booking = \App\Models\Booking::first();

    Livewire::test('pages::booking.detail', compact('booking'))
        ->assertStatus(200);
});
