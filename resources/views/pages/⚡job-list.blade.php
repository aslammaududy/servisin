<?php

use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;

    public bool $is_available;

    public function mount(): void
    {
        $this->is_available = auth()->user()->is_available_for_job;
    }

    #[\Livewire\Attributes\Computed]
    public function jobs()
    {
        return \App\Models\BookingItem::with('damageType.service')
            ->whereRelation('booking', 'technician_id', auth()->id())
            ->groupBy('booking_id')
            ->paginate(10);
    }

    #[\Livewire\Attributes\Computed]
    public function services(): array
    {
        $booking_item_ids = $this->jobs->pluck('booking_id');
        $services = [];

        \App\Models\Booking::with('bookingItems.damageType.service')
            ->whereIn('id', $booking_item_ids)
            ->get()
            ->each(function (\App\Models\Booking $booking) use (&$services) {
                return $booking->bookingItems->each(function (\App\Models\BookingItem $bookingItem) use ($booking, &$services) {
                    $services[$booking->id][] = $bookingItem->damageType->service->name;
                });
            });

        return $services;
    }

    public function updatedIsAvailable(bool $available): void
    {
        auth()->user()->update(['is_available_for_job' => $available]);
    }

};
?>

<div class="space-y-6">
    <div class="flex items-start justify-between">
        <div>
            <x-ui.heading level="h1" size="xl">
                Job Teknisi
            </x-ui.heading>
            <x-ui.text size="sm" class="mt-1 text-gray-500">
                Daftar booking yang ditugaskan kepada Anda
            </x-ui.text>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
            <x-ui.heading level="h3" size="md" class="mb-4">
                Ketersediaan
            </x-ui.heading>
            <div class="flex justify-between items-start">
                <x-ui.text size="sm" class="text-gray-700 leading-relaxed">
                    Atur apakah Anda siap menerima job baru
                </x-ui.text>
                <x-ui.checkbox label="Tersedia" size="xs" wire:model.live="is_available"/>
            </div>
        </x-ui.card>
    </div>

    @foreach($this->jobs as $job)
        <div class="grid grid-cols-1 gap-4">
            <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
                <div class="space-y-1">
                    <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                        {{ Carbon\Carbon::createFromTimestamp($job->booking->booking_date)->format('d M Y H:i') }}
                    </x-ui.text>
                    <x-ui.heading level="h3" size="lg">
                        {{ implode(", ", array_unique($this->services[$job->booking_id])) }}
                    </x-ui.heading>
                    <x-ui.text size="sm" class="text-gray-400">
                        {{ $job->booking->notes }}
                    </x-ui.text>
                    <div class="flex justify-between items-start mt-5">
                        <x-ui.text size="xs" class="opacity-50">
                            {{ $job->booking->address }}
                        </x-ui.text>

                        <x-ui.link :primary="true" class="text-blue-600" variant="ghost"
                                   href="{{ route('booking.detail',['booking' => $job->booking]) }}"
                                   target="_blank">
                            Detail
                        </x-ui.link>
                    </div>
                </div>
            </x-ui.card>
        </div>
    @endforeach
</div>
