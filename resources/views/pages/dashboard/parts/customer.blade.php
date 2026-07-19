<div class="flex items-start justify-between">
    <div>
        <x-ui.heading level="h1" size="xl">
            Booking Saya
        </x-ui.heading>
        <x-ui.text size="sm" class="mt-1 text-gray-500">
            Lihat status pengerjaan booking Anda
        </x-ui.text>
    </div>
</div>

<div class="grid grid-cols-1 gap-4">
    <div class="flex justify-end">
        <x-ui.button type="button" color="blue" href="{{ route('booking.create') }}">
            Buat Booking
        </x-ui.button>
    </div>
</div>
@foreach($this->bookings as $booking)
    <div class="grid grid-cols-1 gap-4">
        <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
            <div class="space-y-1">
                <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                    {{ $booking->created_at->format('d M Y') }}
                </x-ui.text>
                <div class="flex justify-between items-start mt-5">
                    <x-ui.heading level="h3" size="lg">
                        {{ implode(", ", array_unique($this->services[$booking->id])) }}
                    </x-ui.heading>
                    <div class="flex justify-between items-end">
                        <x-ui.text class="text-base text-gray-400 mr-1">
                            Status:
                        </x-ui.text>
                        <x-ui.badge color="sky">
                            {{ $booking->status }}
                        </x-ui.badge>
                    </div>
                </div>
                <x-ui.text size="sm" class="text-gray-400">
                    {{ $booking->notes }}
                </x-ui.text>
                <div class="flex justify-between items-start mt-5">
                    <x-ui.text size="xs" class="opacity-50">
                        Jadwal: {{ $booking->booking_date->format('d M Y H:i') }}
                    </x-ui.text>

                    <x-ui.text size="xs" class="opacity-50">
                        Estimasi: Rp {{ number_format($this->estimatedTotal($booking->id), 0, ',', '.') }}
                    </x-ui.text>

                    <x-ui.text size="xs" class="opacity-50">
                        Teknisi: {{ $booking->technician->name ?? 'Belum ditugaskan' }}
                    </x-ui.text>

                    <x-ui.link :primary="true" class="text-blue-600" variant="ghost"
                               href="{{ route('booking.detail',['booking' => $booking]) }}"
                               target="_blank">
                        Detail
                    </x-ui.link>
                </div>
            </div>
        </x-ui.card>
    </div>
@endforeach
