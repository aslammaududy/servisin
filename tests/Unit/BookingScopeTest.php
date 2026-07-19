<?php

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(
    Tests\TestCase::class,
    RefreshDatabase::class,
);

it('filters bookings by dateFrom', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Booking before the filter date
    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->subDays(10)->timestamp,
    ]);

    // Booking on the filter date
    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->timestamp,
    ]);

    $dateFrom = now()->toDateString();
    $results = Booking::dateFrom($dateFrom)->get();

    expect($results)->toHaveCount(1);
});

it('filters bookings by dateTo', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Booking before the filter date
    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->subDays(5)->timestamp,
    ]);

    // Booking after the filter date
    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->addDays(5)->timestamp,
    ]);

    $dateTo = now()->toDateString();
    $results = Booking::dateTo($dateTo)->get();

    expect($results)->toHaveCount(1);
});

it('filters bookings by status', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Booking::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
    Booking::factory()->done()->create(['user_id' => $user->id]);

    $results = Booking::ofStatus('done')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->getRawOriginal('status'))->toBe('done');
});

it('chains dateFrom and dateTo correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->subDays(10)->timestamp,
    ]);

    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->timestamp,
    ]);

    Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => now()->addDays(10)->timestamp,
    ]);

    $results = Booking::dateFrom(now()->subDay()->toDateString())
        ->dateTo(now()->addDay()->toDateString())
        ->get();

    expect($results)->toHaveCount(1);
});

it('skips filtering when parameters are null', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Booking::factory()->create(['user_id' => $user->id]);
    Booking::factory()->create(['user_id' => $user->id]);

    $results = Booking::dateFrom(null)->dateTo(null)->ofStatus(null)->get();

    expect($results)->toHaveCount(2);
});
