<div class="space-y-6">

    @if(auth()->user()->role === 'user')
        @include('pages.dashboard.parts.customer')
    @else

        <div class="flex items-start justify-between">
            <div>
                <x-ui.heading level="h1" size="xl">
                    Dashboard Admin
                </x-ui.heading>
                <x-ui.text size="sm" class="mt-1 text-gray-500">
                    Kelola booking, teknisi dan layanan
                </x-ui.text>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <x-ui.card size="lg">
                <div class="space-y-1">
                    <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                        Total Booking
                    </x-ui.text>
                    <x-ui.heading level="h2" size="lg">
                        {{ $this->bookings->total() }}
                    </x-ui.heading>
                </div>
            </x-ui.card>

            <x-ui.card size="lg">
                <div class="space-y-1">
                    <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                        Booking Aktif
                    </x-ui.text>
                    <x-ui.heading level="h2" size="lg">
                        {{ $this->activeBookings }}
                    </x-ui.heading>
                </div>
            </x-ui.card>

            <x-ui.card size="lg">
                <div class="space-y-1">
                    <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                        Selesai
                    </x-ui.text>
                    <x-ui.heading level="h2" size="lg">
                        {{ $this->completedBookings }}
                    </x-ui.heading>
                </div>
            </x-ui.card>

            <x-ui.card size="lg">
                <div class="space-y-1">
                    <x-ui.text size="sm" class="text-gray-500 uppercase tracking-wide">
                        Completion Rate
                    </x-ui.text>
                    <x-ui.heading level="h2" size="lg">
                        {{ $this->bookingCompletionRate }}
                    </x-ui.heading>
                </div>
            </x-ui.card>

            <x-ui.card size="lg">
                <div class="space-y-1">
                    <x-ui.text size="sm" class="text-gray-500 uppercase">
                        Rata - Rata Selesai
                    </x-ui.text>
                    <x-ui.heading level="h2" size="lg">
                        {{ $this->averageCompletedBooking }}
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
                    <flux:table :paginate="$this->bookings">
                        <flux:table.columns>
                            <flux:table.column>Tanggal</flux:table.column>
                            <flux:table.column>Pelanggan</flux:table.column>
                            <flux:table.column>Layanan</flux:table.column>
                            <flux:table.column>Status</flux:table.column>
                            <flux:table.column>Teknisi</flux:table.column>
                            <flux:table.column>Aksi</flux:table.column>
                        </flux:table.columns>

                        @foreach($this->bookings as $booking)
                            <flux:table.rows>
                                <flux:table.row>
                                    <flux:table.cell variant="strong">
                                        {{ Carbon\Carbon::createFromTimestamp($booking->booking_date)->format('d M Y') }}
                                    </flux:table.cell>
                                    <flux:table.cell variant="strong">{{ $booking->user->name }}</flux:table.cell>
                                    <flux:table.cell
                                        variant="strong">{{ implode(", ", array_unique($this->services[$booking->id])) }}</flux:table.cell>
                                    <flux:table.cell variant="strong">{{ $booking->status }}</flux:table.cell>
                                    <flux:table.cell
                                        variant="strong">{{ $booking->technician->name ?? 'Belum ada' }}</flux:table.cell>
                                    <flux:table.cell variant="strong">
                                        <x-ui.link :primary="true" class="text-blue-600" variant="ghost"
                                                   href="{{ route('booking.detail',['booking' => $booking]) }}"
                                                   target="_blank">
                                            Detail
                                        </x-ui.link>
                                    </flux:table.cell>
                                </flux:table.row>
                            </flux:table.rows>
                        @endforeach
                    </flux:table>
                </x-ui.text>
            </x-ui.card>
        </div>
    @endif

</div>
