<?php

use App\Models\Booking;
use App\Models\User;
use Livewire\Livewire;

it('allows admin to transition assigned to on_progress', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $technician = User::factory()->create(['role' => 'technician']);

    $this->actingAs($admin);
    $booking = Booking::factory()->assigned()->create([
        'user_id' => $admin->id,
        'technician_id' => $technician->id,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::booking.detail', compact('booking'))
        ->set('booking_status', 'Sedang Dikerjakan')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'on_progress']);
});

it('allows technician to transition assigned to on_progress', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $technician = User::factory()->create(['role' => 'technician']);

    $this->actingAs($admin);
    $booking = Booking::factory()->assigned()->create([
        'user_id' => $admin->id,
        'technician_id' => $technician->id,
    ]);

    Livewire::actingAs($technician)
        ->test('pages::booking.detail', compact('booking'))
        ->set('booking_status', 'Sedang Dikerjakan')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'on_progress']);
});

it('allows technician to transition on_progress to done', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $technician = User::factory()->create(['role' => 'technician']);

    $this->actingAs($admin);
    $booking = Booking::factory()->onProgress()->create([
        'user_id' => $admin->id,
        'technician_id' => $technician->id,
    ]);

    Livewire::actingAs($technician)
        ->test('pages::booking.detail', compact('booking'))
        ->set('booking_status', 'Selesai')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'done']);
});

it('rejects technician transition to cancelled', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $technician = User::factory()->create(['role' => 'technician']);

    $this->actingAs($admin);
    $booking = Booking::factory()->assigned()->create([
        'user_id' => $admin->id,
        'technician_id' => $technician->id,
    ]);

    Livewire::actingAs($technician)
        ->test('pages::booking.detail', compact('booking'))
        ->set('booking_status', 'Batal')
        ->assertHasErrors();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'assigned']);
});

it('rejects invalid transition done to assigned', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);
    $booking = Booking::factory()->done()->create([
        'user_id' => $admin->id,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::booking.detail', compact('booking'))
        ->set('booking_status', 'Teknisi Ditugaskan')
        ->assertHasErrors();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'done']);
});

it('allows admin to cancel any booking', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);
    $booking = Booking::factory()->assigned()->create([
        'user_id' => $admin->id,
    ]);

    Livewire::actingAs($admin)
        ->test('pages::booking.detail', compact('booking'))
        ->set('booking_status', 'Batal')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'cancelled']);
});
