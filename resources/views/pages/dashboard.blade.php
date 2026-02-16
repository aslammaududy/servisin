<?php

use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;


    #[\Livewire\Attributes\Computed]
    public function bookingItems()
    {
        return \App\Models\BookingItem::with(['booking.user', 'booking.technician', 'damageType.service'])
            ->has('booking')
            ->groupBy('service_id')
            ->paginate(10);
    }

}

?>
<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    Total Booking
                </x-ui.text>
                <x-ui.heading level="h2" size="lg">
                    2
                </x-ui.heading>
            </div>
        </x-ui.card>

        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    Booking Aktif
                </x-ui.text>
                <x-ui.heading level="h2" size="lg">
                    2
                </x-ui.heading>
            </div>
        </x-ui.card>

        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    Selesai
                </x-ui.text>
                <x-ui.heading level="h2" size="lg">
                    2
                </x-ui.heading>
            </div>
        </x-ui.card>

        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    Completion Rate
                </x-ui.text>
                <x-ui.heading level="h2" size="lg">
                    2
                </x-ui.heading>
            </div>
        </x-ui.card>

        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase">
                    Rata - Rata Selesai
                </x-ui.text>
                <x-ui.heading level="h2" size="lg">
                    2
                </x-ui.heading>
            </div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
            <x-ui.heading level="h3" size="md" class="mb-4">
                Teknisi
            </x-ui.heading>

            <x-ui.text size="sm" class="text-gray-500">
                <flux:table :paginate="$this->bookingItems">
                    <flux:table.columns>
                        <flux:table.column>Tanggal</flux:table.column>
                        <flux:table.column>Pelanggan</flux:table.column>
                        <flux:table.column>Layanan</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Teknisi</flux:table.column>
                        <flux:table.column>Aksi</flux:table.column>
                    </flux:table.columns>

                    @foreach($this->bookingItems as $item)
                        <flux:table.rows>
                            <flux:table.row>
                                <flux:table.cell variant="strong">
                                    {{ Carbon\Carbon::createFromTimestamp($item->booking->booking_date)->format('d M Y') }}
                                </flux:table.cell>
                                <flux:table.cell variant="strong">{{ $item->booking->user->name }}</flux:table.cell>
                                <flux:table.cell variant="strong">{{ $item->damageType->service->name }}</flux:table.cell>
                                <flux:table.cell variant="strong">{{ $item->booking->status }}</flux:table.cell>
                                <flux:table.cell variant="strong">{{ $item->booking->technician?->name }}</flux:table.cell>
                                <flux:table.cell variant="strong">
                                    <a href="{{ route('booking.detail',['booking' => $item->booking]) }}"
                                       target="_blank">
                                        Detail
                                    </a>
                                </flux:table.cell>
                            </flux:table.row>
                        </flux:table.rows>
                    @endforeach
                </flux:table>
            </x-ui.text>
        </x-ui.card>
    </div>
</div>
