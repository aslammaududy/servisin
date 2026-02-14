<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::booking.create')
        ->assertStatus(200);
});

it('service_id, damage_type_id, booking_date, and address are required', function () {
    Livewire::test('pages::booking.create')
        ->set([
            'bookingForm.service_id' => '',
            'bookingForm.damage_type_id' => '',
            'bookingForm.booking_date' => '',
            'bookingForm.address' => '',
        ])
        ->call('save')
        ->assertHasErrors([
            'bookingForm.service_id',
            'bookingForm.damage_type_id',
            'bookingForm.booking_date',
            'bookingForm.address',
        ]);
});

it('notes and foto are optional', function () {
    Livewire::test('pages::booking.create')
        ->set([
            'bookingForm.user_id' => 1,
            'bookingForm.service_id' => 1,
            'bookingForm.damage_type_id' => 2,
            'bookingForm.booking_date' => date('Y-m-d'),
            'bookingForm.address' => 'jalan jalan',
        ])->call('save')
        ->assertHasNoErrors();
});
