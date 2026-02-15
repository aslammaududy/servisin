<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::booking.detail')
        ->assertStatus(200);
});
