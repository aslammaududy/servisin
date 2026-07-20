<?php

use App\Models\Booking;
use App\Models\BookingPaymentProof;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('payment proof is stored on R2 disk', function () {
    Storage::fake('r2');

    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    $booking = Booking::factory()->done()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test('pages::booking.detail', compact('booking'))
        ->set('payment_proof', UploadedFile::fake()->image('proof.jpg', 100, 100)->size(1000))
        ->call('uploadPaymentProof');

    $proof = BookingPaymentProof::where('booking_id', $booking->id)->first();
    $this->assertNotNull($proof);
    $this->assertEquals('proof.jpg', $proof->original_name);
    $this->assertEquals('pending', $proof->status);
    $this->assertStringStartsWith('payment_proofs/', $proof->path);

    Storage::disk('r2')->assertCount('payment_proofs', 1);
});

test('payment proof validation requires image', function () {
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    $booking = Booking::factory()->done()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test('pages::booking.detail', compact('booking'))
        ->set('payment_proof', UploadedFile::fake()->create('document.pdf', 1000))
        ->call('uploadPaymentProof')
        ->assertHasErrors(['payment_proof']);
});
