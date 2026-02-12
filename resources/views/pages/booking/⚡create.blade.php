<?php

use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public \App\Livewire\Forms\BookingForm $bookingForm;
    public \Illuminate\Database\Eloquent\Collection $services;

    public function mount(): void
    {
        $this->services = Service::all();
    }

    public function save(): void
    {
        dd($this->services->first());
//        $this->bookingForm->store();
    }
};
?>

<div>
    <form
        wire:submit="save"
        class="mt-8 space-y-4 rounded-lg bg-white p-6 dark:bg-neutral-800/10"
    >
        <x-ui.autocomplete
            label="Search Products"
            wire:model="services"
            placeholder="Find products..."
            description="Search through our product catalog"
        >
            @foreach($services as $item)
                <x-ui.autocomplete.item>{{ $item->name }}</x-ui.autocomplete.item>
            @endforeach

        </x-ui.autocomplete>

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
