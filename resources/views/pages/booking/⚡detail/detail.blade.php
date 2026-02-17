<div class="space-y-6">

    {{-- ========================= --}}
    {{-- ROW 1 : 4 SUMMARY CARDS --}}
    {{-- ========================= --}}
    <div
        class="grid grid-cols-1 sm:grid-cols-2 {{ auth()->user()->role === 'user' ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-4">

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
                    {{ $booking->status }}
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

        @if(auth()->user()->role === 'user')
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
        @endif
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
                        {{ $this->services }}
                    </x-ui.text>
                </div>

                <div class="flex justify-between items-start">
                    <x-ui.text size="sm" class="text-gray-600 mr-2">Kerusakan</x-ui.text>
                    <x-ui.text size="sm" class="font-medium text-right">
                        {{ $this->damages }}
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
                        {{ $booking->status }}
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

            @if(auth()->user()->role === 'admin')
                <x-ui.select
                    wire:model.live="technician_id"
                    placeholder="Pilih Teknisi"
                    clearable
                >
                    @foreach($this->technicians as $technician)
                        <x-ui.select.option value="{{$technician->id}}">{{ $technician->name }}</x-ui.select.option>
                    @endforeach
                </x-ui.select>
            @endif
            @if(auth()->user()->role === 'user')
                <x-ui.text size="sm" class="text-gray-500">
                    @if($booking->status == 'Teknisi Ditugaskan')
                        {{ $booking->technician->name }}
                    @else
                        Belum Ditugaskan
                    @endif
                </x-ui.text>
            @endif
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

    @if(auth()->user()->role === 'admin')
        <div class="grid grid-cols-1 gap-4">
            <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
                <x-ui.heading level="h3" size="md" class="mb-4">
                    Update Status
                </x-ui.heading>

                <x-ui.select
                    wire:model.live="booking_status"
                    placeholder="Pilih Status"
                    clearable
                >
                    @foreach($statuses as $status)
                        <x-ui.select.option value="{{$status}}" >{{ $status }}</x-ui.select.option>
                    @endforeach
                </x-ui.select>
            </x-ui.card>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4">
        <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
            <x-ui.heading level="h3" size="md" class="mb-4">
                Timeline
            </x-ui.heading>

            <div class="space-y-4">
                @foreach($booking->bookingEvents as $event)
                    <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
                        <div class="flex justify-between items-start">
                            <x-ui.text size="sm" class="text-gray-600">
                                @if($event->status === 'created')
                                    <x-ui.badge color="sky">
                                        Booking dibuat
                                    </x-ui.badge>
                                    @else
                                    <x-ui.badge color="sky">
                                        {{ $event->status }}
                                    </x-ui.badge>
                                @endif
                            </x-ui.text>
                            <x-ui.text size="sm" class="font-medium text-right">
                                {{ $event->created_at->format('d M Y H:i') }}
                            </x-ui.text>
                        </div>
                        <x-ui.text size="sm" class="font-medium mt-2">
                            Oleh: {{ $event->changedBy->role }}
                        </x-ui.text>
                        @if($event->status == 'Teknisi Ditugaskan')
                            <x-ui.text size="sm" class="font-medium mt-2">
                                Teknisi: {{ $booking->technician?->name }}
                            </x-ui.text>
                            <x-ui.text size="sm" class="font-medium mt-2">
                                Penugasan: Round Robin
                            </x-ui.text>
                        @endif
                    </x-ui.card>
                @endforeach
            </div>
        </x-ui.card>
    </div>
</div>
