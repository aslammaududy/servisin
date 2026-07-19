<?php

use App\Models\Booking;

it('allows pending to assigned transition', function () {
    $booking = new Booking;
    $booking->setRawAttributes(['status' => 'pending']);

    expect($booking->canTransitionTo('assigned'))->toBeTrue();
});

it('allows assigned to on_progress transition', function () {
    $booking = new Booking;
    $booking->setRawAttributes(['status' => 'assigned']);

    expect($booking->canTransitionTo('on_progress'))->toBeTrue();
});

it('allows on_progress to done transition', function () {
    $booking = new Booking;
    $booking->setRawAttributes(['status' => 'on_progress']);

    expect($booking->canTransitionTo('done'))->toBeTrue();
});

it('allows any status to cancelled', function () {
    $statuses = ['pending', 'assigned', 'on_progress', 'done'];

    foreach ($statuses as $status) {
        $booking = new Booking;
        $booking->setRawAttributes(['status' => $status]);
        expect($booking->canTransitionTo('cancelled'))->toBeTrue();
    }
});

it('rejects assigned to done without on_progress', function () {
    $booking = new Booking;
    $booking->setRawAttributes(['status' => 'assigned']);

    expect($booking->canTransitionTo('done'))->toBeFalse();
});

it('rejects done to assigned', function () {
    $booking = new Booking;
    $booking->setRawAttributes(['status' => 'done']);

    expect($booking->canTransitionTo('assigned'))->toBeFalse();
});

it('rejects pending to done', function () {
    $booking = new Booking;
    $booking->setRawAttributes(['status' => 'pending']);

    expect($booking->canTransitionTo('done'))->toBeFalse();
});

it('rejects cancelled to any status', function () {
    $statuses = ['pending', 'assigned', 'on_progress', 'done'];

    foreach ($statuses as $status) {
        $booking = new Booking;
        $booking->setRawAttributes(['status' => 'cancelled']);
        expect($booking->canTransitionTo($status))->toBeFalse();
    }
});

it('rejects on_progress to assigned', function () {
    $booking = new Booking;
    $booking->setRawAttributes(['status' => 'on_progress']);

    expect($booking->canTransitionTo('assigned'))->toBeFalse();
});

it('rejects done to on_progress', function () {
    $booking = new Booking;
    $booking->setRawAttributes(['status' => 'done']);

    expect($booking->canTransitionTo('on_progress'))->toBeFalse();
});

it('exposes allowed transitions as static property', function () {
    expect(Booking::$allowedTransitions)->toBeArray()
        ->and(Booking::$allowedTransitions)->toHaveKey('assigned')
        ->and(Booking::$allowedTransitions['assigned'])->toContain('on_progress');
});
