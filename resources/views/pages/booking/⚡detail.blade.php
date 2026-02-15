<?php

use App\Models\Booking;
use Livewire\Component;

new class extends Component {
    public Booking $booking;

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['bookingItems.damageType.service', 'user', 'technician']);
    }

    #[\Livewire\Attributes\Computed]
    public function status(): string
    {
        return match ($this->booking->status) {
            'pending' => 'Menunggu',
            'assigned' => 'Teknisi Ditugaskan',
            'on_progress' => 'Sedang Dikerjakan',
            'done' => 'Selesai',
            'cancelled' => 'Batal'
        };
    }

    #[\Livewire\Attributes\Computed]
    public function estimatedTotal(): int
    {
        return $this->booking->bookingItems->reduce(function (?int $carry, \App\Models\BookingItem $item) {
            return $carry + $item->damageType->price;
        }, 0);
    }
};
?>

<div class="space-y-6">

    {{-- ========================= --}}
    {{-- ROW 1 : 4 SUMMARY CARDS --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    Booking ID
                </x-ui.text>
                <x-ui.heading level="h3" size="lg">
                    #{{ $booking->id }}
                </x-ui.heading>
                <x-ui.text size="xs" class="text-gray-400">
                    Dibuat {{ $booking->created_at->format('d M Y') }}
                </x-ui.text>
            </div>
        </x-ui.card>

        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    Status
                </x-ui.text>
                <x-ui.heading level="h3" size="lg" class="text-orange-600">
                    {{ $this->status }}
                </x-ui.heading>
                <x-ui.text size="xs" class="text-gray-400">
                    Update {{ Carbon\Carbon::now()->format('d M Y H:i') }}
                </x-ui.text>
            </div>
        </x-ui.card>

        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    Jadwal
                </x-ui.text>
                <x-ui.heading level="h3" size="lg">
                    {{ Carbon\Carbon::createFromTimestamp($booking->booking_date)->format('d M Y') }}
                </x-ui.heading>
                <x-ui.text size="xs" class="text-gray-400">
                    {{ Carbon\Carbon::createFromTimestamp($booking->booking_date)->format('H:i') }}
                </x-ui.text>
            </div>
        </x-ui.card>

        <x-ui.card size="lg">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    Total Estimasi
                </x-ui.text>
                <x-ui.heading level="h3" size="lg">
                    Rp {{ number_format($this->estimatedTotal, 0, ',', '.') }}
                </x-ui.heading>
                <x-ui.text size="xs" class="text-gray-400">
                    Termasuk ongkir
                </x-ui.text>
            </div>
        </x-ui.card>

    </div>


    {{-- ========================= --}}
    {{-- ROW 2 : 2 DETAIL CARDS --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <x-ui.card size="lg">
            <x-ui.heading level="h3" size="md" class="mb-6">
                Informasi Booking
            </x-ui.heading>

            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <x-ui.text size="sm" class="text-gray-600">Layanan</x-ui.text>
                    <x-ui.text size="sm" class="font-medium text-right">
                        {{ $booking->service }}
                    </x-ui.text>
                </div>

                <div class="flex justify-between items-start">
                    <x-ui.text size="sm" class="text-gray-600">Kerusakan</x-ui.text>
                    <x-ui.text size="sm" class="font-medium text-right">
                        Mati total
                    </x-ui.text>
                </div>

                <div class="flex justify-between items-start">
                    <x-ui.text size="sm" class="text-gray-600">Jadwal</x-ui.text>
                    <x-ui.text size="sm" class="font-medium text-right">
                        {{ Carbon\Carbon::createFromTimestamp($booking->booking_date)->format('d M Y H:i') }}
                    </x-ui.text>
                </div>

                <div class="flex justify-between items-start">
                    <x-ui.text size="sm" class="text-gray-600">Status</x-ui.text>
                    <x-ui.text size="sm" class="font-medium text-orange-600 text-right">
                        {{ $this->status }}
                    </x-ui.text>
                </div>

                <div class="flex justify-between items-start">
                    <x-ui.text size="sm" class="text-gray-600">Estimasi</x-ui.text>
                    <x-ui.text size="sm" class="font-medium text-right">
                        Rp {{ number_format($this->estimatedTotal, 0, ',', '.') }}
                    </x-ui.text>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card size="lg">
            <x-ui.heading level="h3" size="md" class="mb-6">
                Detail Lokasi
            </x-ui.heading>

            <div class="space-y-4">

                <div class="flex justify-between items-start">
                    <x-ui.text size="sm" class="text-gray-600">Ongkir</x-ui.text>
                    <x-ui.text size="sm" class="font-medium text-right">
                        Rp 0
                    </x-ui.text>
                </div>

                <div class="flex justify-between items-start">
                    <x-ui.text size="sm" class="text-gray-600">Alamat</x-ui.text>

                </div>

                <div class="flex justify-between items-end">
                    &nbsp;
                    <x-ui.text size="sm" class="font-medium text-right max-w-xs">
                        {{ $booking->address }}
                    </x-ui.text>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- ========================= --}}
    {{-- ROW 3 : TEKNISI (FULL WIDTH) --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 gap-4">
        <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
            <x-ui.heading level="h3" size="md" class="mb-4">
                Teknisi
            </x-ui.heading>

            <x-ui.text size="sm" class="text-gray-500">
                @if($booking->status == 'assigned')
                    {{ $booking->technician->name }}
                @else
                    Belum Ditugaskan
                @endif
            </x-ui.text>
        </x-ui.card>
    </div>

    {{-- ========================= --}}
    {{-- ROW 4 : DESKRIPSI KERUSAKAN (FULL WIDTH) --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 gap-4">
        <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
            <x-ui.heading level="h3" size="md" class="mb-4">
                Deskripsi Kerusakan
            </x-ui.heading>

            <x-ui.text size="sm" class="text-gray-700 leading-relaxed">
                {{ $booking->notes ?? 'Tidak ada catatan' }}
            </x-ui.text>
        </x-ui.card>
    </div>
</div>
