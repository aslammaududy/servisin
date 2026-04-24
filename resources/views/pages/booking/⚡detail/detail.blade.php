<div class="space-y-6">
    <div class="flex items-start justify-between">
        <div>
            <x-ui.heading level="h1" size="xl">
                @if(auth()->user()->role === 'technician')
                    Detail Job
                @else
                    Detail Booking
                @endif
            </x-ui.heading>
        </div>
    </div>

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
                    @if(auth()->user()->role === 'admin')
                        <div class="flex justify-between items-end">
                            <x-ui.input wire:model="shipping_fee" x-mask:dynamic="$money($input)" placeholder="1000">
                                <x-slot name="prefix">Rp</x-slot>
                            </x-ui.input>
                        </div>
                    @else
                        <x-ui.text size="sm" class="font-medium text-right">
                            Rp {{ number_format($booking->shipping_fee, 0, ',', '.') }}
                        </x-ui.text>
                    @endif
                </div>
                @if(auth()->user()->role === 'admin')
                    <div class="flex justify-between items-end">
                        &nbsp;

                        <x-ui.button
                            color="blue"
                            size="xs"
                            type="button"
                            wire:click="setShippingFee"
                        >
                            Simpan
                        </x-ui.button>
                    </div>
                @endif
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
            @else
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
                        <x-ui.select.option value="{{$status}}">{{ $status }}</x-ui.select.option>
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

    {{-- ========================= --}}
    {{-- ROW 5 : KOMPLAIN (FULL WIDTH) --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 gap-4">
        <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
            <x-ui.heading level="h3" size="md" class="mb-4">
                Komplain
            </x-ui.heading>

            @if(auth()->user()->role === 'user')
                <form wire:submit="submitComplaint" class="mb-6 space-y-4">
                    <x-ui.field>
                        <x-ui.label>Pesan Komplain</x-ui.label>
                        <x-ui.textarea wire:model="complaint_message" placeholder="Tuliskan komplain Anda..." />
                        <x-ui.error name="complaint_message" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.label>Foto Komplain (Optional)</x-ui.label>
                        <x-ui.input wire:model="complaint_photo" type="file"/>
                        <x-ui.error name="complaint_photo"/>
                    </x-ui.field>

                    <x-ui.button type="submit" color="blue">
                        Kirim Komplain
                    </x-ui.button>
                </form>
            @endif

            <div class="space-y-4">
                @forelse($this->complaints as $complaint)
                    <x-ui.card size="lg" class="!max-w-none">
                        <div class="flex justify-between items-start">
                            <div>
                                <x-ui.text size="sm" class="font-medium">{{ $complaint->user->name }}</x-ui.text>
                                <x-ui.badge size="sm" class="mt-1">{{ $complaint->status }}</x-ui.badge>
                            </div>
                            <x-ui.text size="sm" class="text-gray-500">
                                {{ $complaint->created_at->format('d M Y H:i') }}
                            </x-ui.text>
                        </div>
                        <x-ui.text size="sm" class="mt-3 text-gray-700">
                            {{ $complaint->message }}
                        </x-ui.text>
                        @if($complaint->complainPhotos->count())
                            <div class="mt-3 flex gap-2">
                                @foreach($complaint->complainPhotos as $photo)
                                    <a href="{{ asset('storage/' . $photo->path) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                                        Lihat Foto
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </x-ui.card>
                @empty
                    <x-ui.text size="sm" class="text-gray-500">
                        Belum ada komplain.
                    </x-ui.text>
                @endforelse
            </div>
        </x-ui.card>
    </div>

    {{-- ========================= --}}
    {{-- ROW 6 : BUKTI PEMBAYARAN (FULL WIDTH) --}}
    {{-- ========================= --}}
    @if(auth()->user()->role === 'user' && $booking->status === 'Selesai')
        <div class="grid grid-cols-1 gap-4">
            <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
                <x-ui.heading level="h3" size="md" class="mb-4">
                    Unggah Bukti Pembayaran
                </x-ui.heading>

                @if($this->booking->paymentProofs->where('status', '!=', 'rejected')->count() === 0)
                    <form wire:submit="uploadPaymentProof" class="space-y-4">
                        <x-ui.field>
                            <x-ui.label>Bukti Pembayaran</x-ui.label>
                            <x-ui.description>Unggah foto bukti transfer ke {{ bank_account()['bank_name'] }} a.n. {{ bank_account()['account_name'] }} - {{ bank_account()['account_number'] }}</x-ui.description>
                            <x-ui.input wire:model="payment_proof" type="file"/>
                            <x-ui.error name="payment_proof"/>
                        </x-ui.field>

                        <x-ui.button type="submit" color="blue">
                            Unggah Bukti Pembayaran
                        </x-ui.button>
                    </form>
                @else
                    <x-ui.text size="sm" class="text-green-600">
                        Bukti pembayaran sudah diunggah dan sedang diverifikasi.
                    </x-ui.text>
                @endif
            </x-ui.card>
        </div>
    @endif

    @if(auth()->user()->role === 'admin')
        <div class="grid grid-cols-1 gap-4">
            <x-ui.card size="lg" class="lg:col-span-2 !max-w-none">
                <x-ui.heading level="h3" size="md" class="mb-4">
                    Bukti Pembayaran
                </x-ui.heading>

                @forelse($this->booking->paymentProofs as $proof)
                    <div class="mb-4 p-4 border rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <x-ui.text size="sm" class="font-medium">{{ $proof->original_name }}</x-ui.text>
                            <x-ui.badge size="sm" color="{{ $proof->status === 'verified' ? 'green' : ($proof->status === 'rejected' ? 'red' : 'yellow') }}">
                                {{ ucfirst($proof->status) }}
                            </x-ui.badge>
                        </div>
                        <a href="{{ asset('storage/' . $proof->path) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                            Lihat Bukti Pembayaran
                        </a>
                    </div>
                @empty
                    <x-ui.text size="sm" class="text-gray-500">
                        Belum ada bukti pembayaran diunggah.
                    </x-ui.text>
                @endforelse
            </x-ui.card>
        </div>
    @endif
</div>
