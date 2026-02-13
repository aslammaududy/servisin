<?php

use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public \App\Livewire\Forms\BookingForm $bookingForm;
    public mixed $services;
    public mixed $service;

    public function mount(): void
    {
        $this->services = Service::all();
    }

    public function save(): void
    {
        dd($this->service);
//        $this->bookingForm->store();
    }
};
?>

<div>
    <form
        wire:submit="save"
        class="mt-8 space-y-4 rounded-lg bg-white p-6 dark:bg-neutral-800/10"
    >
        <x-ui.select
            wire:model="service"
        >
            @foreach($services as $item)
                <x-ui.select.option value="$item->id">{{ $item->name }}</x-ui.select.option>
            @endforeach

        </x-ui.select>

{{--        <x-ui.field>--}}
{{--            <x-ui.label>name</x-ui.label>--}}
{{--            <x-ui.input wire:model="name"/>--}}
{{--            <x-ui.error name="name"/>--}}
{{--        </x-ui.field>--}}

{{--        <x-ui.field>--}}
{{--            <x-ui.label>email address</x-ui.label>--}}
{{--            <x-ui.input--}}
{{--                wire:model="email"--}}
{{--                type="email"--}}
{{--                copyable--}}
{{--            />--}}
{{--            <x-ui.error name="email"/>--}}
{{--        </x-ui.field>--}}
        <x-ui.button
            type="submit"
        >Save changes
        </x-ui.button>
    </form>


</div>
