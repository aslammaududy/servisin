<?php

use App\Models\Booking;
use App\Models\ComplainPhoto;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('complaint photo is stored on R2 disk', function () {
    Storage::fake('r2');

    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    $booking = Booking::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test('pages::booking.detail', compact('booking'))
        ->set('complaint_message', 'This is a test complaint message that is long enough')
        ->set('complaint_photo', UploadedFile::fake()->image('complaint.jpg', 100, 100)->size(500))
        ->call('submitComplaint');

    $complaint = Complaint::where('booking_id', $booking->id)->first();

    $photo = ComplainPhoto::where('complaint_id', $complaint->id)->first();
    $this->assertNotNull($photo);
    $this->assertEquals('complaint.jpg', $photo->original_name);
    $this->assertStringStartsWith('complaints/', $photo->path);

    Storage::disk('r2')->assertCount('complaints', 1);
});
