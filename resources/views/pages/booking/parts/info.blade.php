<div class="mt-8 lg:mt-0 lg:col-span-2">
    <div class="sticky top-24 space-y-6">
        {{-- Panduan Booking --}}
        <div class="rounded-lg bg-white p-6 dark:bg-neutral-800/10">
            <div class="flex items-center gap-2 mb-4">
                <x-ui.icon name="information-circle" class="!size-5 !text-blue-500" />
                <x-ui.heading level="h3" size="sm">Panduan Booking</x-ui.heading>
            </div>

            <ol class="space-y-4">
                <li class="flex gap-3">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">1</span>
                    <div>
                        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-200">Pilih Layanan</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Pilih satu atau lebih layanan yang Anda butuhkan.</p>
                    </div>
                </li>
                <li class="flex gap-3">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">2</span>
                    <div>
                        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-200">Pilih Jenis Kerusakan</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Tentukan jenis kerusakan sesuai layanan yang dipilih.</p>
                    </div>
                </li>
                <li class="flex gap-3">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">3</span>
                    <div>
                        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-200">Tentukan Jadwal</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Pilih tanggal dan waktu kunjungan teknisi. Minimal H+1 dari hari ini.</p>
                    </div>
                </li>
                <li class="flex gap-3">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">4</span>
                    <div>
                        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-200">Lengkapi Detail</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Isi alamat lengkap, deskripsi kerusakan, dan upload foto jika ada.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Informasi Pembayaran --}}
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-5 dark:border-blue-500/30 dark:bg-blue-500/10">
            <div class="flex items-center gap-2 mb-3">
                <x-ui.icon name="bars-4" class="!size-5 !text-blue-500" />
                <x-ui.heading level="h3" size="xs" class="!text-blue-800 dark:!text-blue-300">Ringkasan Pesanan</x-ui.heading>
            </div>
            <ul class="space-y-2 text-xs text-blue-700 dark:text-blue-300/80">
                <li class="flex items-start gap-2">
                    <x-ui.icon name="banknotes" class="!size-4 mt-0.5 shrink-0 !text-blue-500" />
                    <span>Estimasi biaya akan dikonfirmasi admin berdasarkan jenis kerusakan.</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-ui.icon name="arrow-path" class="!size-4 mt-0.5 shrink-0 !text-blue-500" />
                    <span>Metode pembayaran menggunakan <strong>COD</strong>.</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-ui.icon name="arrows-right-left" class="!size-4 mt-0.5 shrink-0 !text-blue-500" />
                    <span>Status booking menunggu admin.</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-ui.icon name="bell" class="!size-4 mt-0.5 shrink-0 !text-blue-500" />
                    <span>Notifikasi via Dashboard.</span>
                </li>
            </ul>
        </div>

        {{-- Informasi Penting --}}
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 dark:border-amber-500/30 dark:bg-amber-500/10">
            <div class="flex items-center gap-2 mb-3">
                <x-ui.icon name="exclamation-triangle" class="!size-5 !text-amber-500" />
                <x-ui.heading level="h3" size="xs" class="!text-amber-800 dark:!text-amber-300">Informasi Penting</x-ui.heading>
            </div>
            <ul class="space-y-2 text-xs text-amber-700 dark:text-amber-300/80">
                <li class="flex items-start gap-2">
                    <x-ui.icon name="clock" class="!size-4 mt-0.5 shrink-0 !text-amber-500" />
                    <span>Jam operasional teknisi: <strong>08:00 - 17:00 WIB</strong></span>
                </li>
                <li class="flex items-start gap-2">
                    <x-ui.icon name="camera" class="!size-4 mt-0.5 shrink-0 !text-amber-500" />
                    <span>Foto kerusakan membantu teknisi mempersiapkan peralatan yang tepat.</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-ui.icon name="phone" class="!size-4 mt-0.5 shrink-0 !text-amber-500" />
                    <span>Teknisi akan menghubungi Anda sebelum kunjungan untuk konfirmasi.</span>
                </li>
            </ul>
        </div>

        {{-- Butuh Bantuan --}}
        <div class="rounded-lg bg-white p-5 dark:bg-neutral-800/10">
            <div class="flex items-center gap-2 mb-2">
                <x-ui.icon name="chat-bubble-left-right" class="!size-5 !text-neutral-400" />
                <x-ui.heading level="h3" size="xs">Butuh Bantuan?</x-ui.heading>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                Hubungi customer service kami jika Anda mengalami kendala saat membuat booking.
            </p>
        </div>
    </div>
</div>
