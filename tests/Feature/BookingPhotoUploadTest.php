<?php

use App\Models\BookingPhoto;
use App\Models\DamageType;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('booking photo is stored on R2 disk', function () {
    Storage::fake('r2');

    $user = User::factory()->create();
    $this->actingAs($user);

    $service = Service::factory()->create();
    $damageType = DamageType::factory()->create(['service_id' => $service->id]);

    Livewire::actingAs($user)
        ->test('pages::booking.create')
        ->set('bookingForm.service_ids', [$service->id])
        ->set('bookingForm.damage_type_ids', [$damageType->id])
        ->set('bookingForm.booking_date', now()->addDay()->toDateString())
        ->set('bookingForm.address', 'Jl. Test No. 123')
        ->set('bookingForm.photo', UploadedFile::fake()->image('photo.jpg', 100, 100)->size(500))
        ->call('save');

    $booking = \App\Models\Booking::first();

    $photo = BookingPhoto::where('booking_id', $booking->id)->first();
    $this->assertNotNull($photo);
    $this->assertEquals('photo.jpg', $photo->original_name);
    $this->assertStringStartsWith('booking/', $photo->path);

    Storage::disk('r2')->assertCount('booking', 1);
});

test('booking photo validation rejects non-image files', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $service = Service::factory()->create();
    $damageType = DamageType::factory()->create(['service_id' => $service->id]);

    Livewire::actingAs($user)
        ->test('pages::booking.create')
        ->set('bookingForm.service_ids', [$service->id])
        ->set('bookingForm.damage_type_ids', [$damageType->id])
        ->set('bookingForm.booking_date', now()->addDay()->toDateString())
        ->set('bookingForm.address', 'Jl. Test No. 123')
        ->set('bookingForm.photo', UploadedFile::fake()->create('document.pdf', 500))
        ->call('save')
        ->assertHasErrors(['bookingForm.photo']);
});

test('booking photo validation rejects files over 1MB', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $service = Service::factory()->create();
    $damageType = DamageType::factory()->create(['service_id' => $service->id]);

    Livewire::actingAs($user)
        ->test('pages::booking.create')
        ->set('bookingForm.service_ids', [$service->id])
        ->set('bookingForm.damage_type_ids', [$damageType->id])
        ->set('bookingForm.booking_date', now()->addDay()->toDateString())
        ->set('bookingForm.address', 'Jl. Test No. 123')
        ->set('bookingForm.photo', UploadedFile::fake()->image('large.jpg')->size(2048))
        ->call('save')
        ->assertHasErrors(['bookingForm.photo']);
});
