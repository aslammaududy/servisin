<?php

use App\Models\Booking;
use App\Models\User;
use App\Services\BookingExportService;

it('exports all bookings to CSV when no filters applied', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Booking::factory()->count(3)->create(['user_id' => $user->id]);

    $service = new BookingExportService;
    $response = $service->toCsv(null, null, null);

    expect($response)->toBeInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class);
});

it('exports filtered bookings to CSV', function () {

    $user = User::factory()->create();
    $this->actingAs($user);

    // Old booking
    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->subDays(10)->timestamp,
    ]);

    // Recent booking
    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->timestamp,
    ]);

    $service = new BookingExportService;
    $response = $service->toCsv(now()->subDay()->toDateString(), null, null);

    // Capture the streamed CSV output
    ob_start();
    $response->send();
    $csvContent = ob_get_clean();

    // Should contain header row and the recent booking, but not the old one
    expect($csvContent)->toContain('ID,Tanggal,Pelanggan')
        ->and($csvContent)->toContain(now()->format('d M Y'))
        ->and($csvContent)->not->toContain(now()->subDays(10)->format('d M Y'));
});
