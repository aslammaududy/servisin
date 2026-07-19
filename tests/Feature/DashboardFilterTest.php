<?php

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\DamageType;
use App\Models\Service;
use App\Models\User;
use Livewire\Livewire;

function createBookingWithItem(User $user, int $timestamp): Booking
{
    $service = Service::create(['name' => 'Test Service', 'description' => 'Test', 'is_active' => true]);
    $damageType = DamageType::create([
        'service_id' => $service->id,
        'name' => 'Test Damage',
        'description' => 'Test',
        'price' => 100000,
        'is_active' => true,
    ]);
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'booking_date' => $timestamp,
    ]);
    BookingItem::create([
        'booking_id' => $booking->id,
        'damage_type_id' => $damageType->id,
    ]);

    return $booking;
}

it('filters bookings by dateFrom for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    // Old booking (10 days ago)
    createBookingWithItem($admin, now()->subDays(10)->timestamp);

    // Recent booking (now)
    createBookingWithItem($admin, now()->timestamp);

    Livewire::actingAs($admin)
        ->test('pages::dashboard')
        ->set('dateFrom', now()->subDay()->toDateString())
        ->assertDontSee(now()->subDays(10)->format('d M Y'))
        ->assertSee(now()->format('d M Y'));
});

it('filters bookings by dateTo for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    // Old booking (10 days ago)
    createBookingWithItem($admin, now()->subDays(10)->timestamp);

    // Future booking (10 days from now)
    createBookingWithItem($admin, now()->addDays(10)->timestamp);

    Livewire::actingAs($admin)
        ->test('pages::dashboard')
        ->set('dateTo', now()->toDateString())
        ->assertSee(now()->subDays(10)->format('d M Y'))
        ->assertDontSee(now()->addDays(10)->format('d M Y'));
});

it('resets filters when resetFilters is called', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    createBookingWithItem($admin, now()->subDays(10)->timestamp);
    createBookingWithItem($admin, now()->timestamp);

    Livewire::actingAs($admin)
        ->test('pages::dashboard')
        ->set('dateFrom', now()->toDateString())
        ->call('resetFilters')
        ->assertSee(now()->subDays(10)->format('d M Y'))
        ->assertSee(now()->format('d M Y'));
});
