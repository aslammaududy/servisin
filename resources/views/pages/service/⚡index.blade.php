<?php

use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest')] class extends Component {
    public array $selectedServiceIds = [];

    public string $activeTab = '';

    #[Computed]
    public function allServices()
    {
        return Service::where('is_active', true)
            ->with(['damageTypes' => fn ($q) => $q->where('is_active', true)])
            ->get();
    }

    #[Computed]
    public function services()
    {
        return Service::where('is_active', true)
            ->with(['damageTypes' => fn ($q) => $q->where('is_active', true)])
            ->when($this->activeTab !== '', fn ($q) => $q->where('id', $this->activeTab))
            ->when($this->activeTab === '' && ! empty($this->selectedServiceIds), fn ($q) => $q->whereIn('id', $this->selectedServiceIds))
            ->get();
    }

    public function selectTab(string $serviceId): void
    {
        $this->activeTab = $this->activeTab === $serviceId ? '' : $serviceId;
    }

    public function applyFilter(): void
    {
        $this->activeTab = '';
    }

    public function resetFilter(): void
    {
        $this->selectedServiceIds = [];
        $this->activeTab = '';
    }
};
?>

<div>
    {{-- Hero Section with Gradient --}}
    <div class="bg-gradient-to-br from-blue-50 via-white to-blue-100 pt-24 pb-8">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="mb-2">
                <x-ui.heading level="h1" size="xl">
                    Servis Elektronik
                </x-ui.heading>
                <x-ui.text class="mt-1 !text-neutral-500">
                    Pilih layanan perbaikan elektronik terpercaya dengan harga transparan
                </x-ui.text>
            </div>

            {{-- Filter Tabs --}}
            <div class="mt-6 flex flex-wrap gap-2">
                <button
                    wire:click="selectTab('')"
                    @class([
                        'rounded-full px-4 py-1.5 text-sm font-medium transition-colors duration-200 cursor-pointer',
                        'bg-blue-600 text-white shadow-sm' => $activeTab === '',
                        'bg-white text-neutral-600 border border-neutral-200 hover:bg-neutral-50' => $activeTab !== '',
                    ])
                >
                    Semua Layanan
                </button>

                @foreach ($this->allServices as $service)
                    <button
                        wire:click="selectTab('{{ $service->id }}')"
                        wire:key="tab-{{ $service->id }}"
                        @class([
                            'rounded-full px-4 py-1.5 text-sm font-medium transition-colors duration-200 cursor-pointer',
                            'bg-blue-600 text-white shadow-sm' => $activeTab === (string) $service->id,
                            'bg-white text-neutral-600 border border-neutral-200 hover:bg-neutral-50' => $activeTab !== (string) $service->id,
                        ])
                    >
                        {{ $service->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="mx-auto max-w-7xl px-4 py-8 lg:px-8">
        <div class="lg:grid lg:grid-cols-4 lg:gap-8">

            {{-- Sidebar --}}
            <div class="mb-6 lg:col-span-1 lg:mb-0">
                <div class="sticky top-24 space-y-6">
                    <div class="rounded-lg border border-black/10 bg-white p-5">
                        <x-ui.heading level="h3" size="sm">Pilih Layanan</x-ui.heading>

                        <div class="mt-1">
                            <x-ui.text size="sm" class="!text-neutral-400">ESTIMASI HARGA</x-ui.text>
                        </div>

                        @foreach ($this->allServices as $service)
                            @php
                                $minPrice = $service->damageTypes->where('is_active', true)->min('price');
                            @endphp
                            <div wire:key="sidebar-info-{{ $service->id }}" class="mt-2">
                                <div class="flex items-center justify-between">
                                    <x-ui.text size="sm" class="!text-neutral-500">{{ $service->name }}</x-ui.text>
                                    @if ($minPrice)
                                        <x-ui.text size="sm" class="!text-neutral-500">
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        </x-ui.text>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <x-ui.separator class="my-4" />

                        <x-ui.text size="xs" class="!text-neutral-400">Tipe jenis kerusakan</x-ui.text>
                        <x-ui.text size="xs" class="mt-1 !text-neutral-400">Garansi: 30 hari</x-ui.text>

                        <x-ui.separator class="my-4" />

                        <x-ui.checkbox.group wire:model.live="selectedServiceIds">
                            @foreach ($this->allServices as $service)
                                <x-ui.checkbox
                                    wire:key="cb-{{ $service->id }}"
                                    :value="(string) $service->id"
                                    :label="$service->name"
                                />
                            @endforeach
                        </x-ui.checkbox.group>

                        <div class="mt-5 flex flex-col gap-2">
                            <x-ui.button
                                wire:click="applyFilter"
                                color="blue"
                                class="w-full"
                            >
                                Terapkan
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Service Cards Grid --}}
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse ($this->services as $service)
                        <div
                            wire:key="service-{{ $service->id }}"
                            class="group overflow-hidden rounded-lg border border-black/10 bg-white transition-shadow duration-200 hover:shadow-lg"
                        >
                            {{-- Card Image Placeholder --}}
                            @php
                                $gradients = [
                                    'from-blue-100 to-blue-200',
                                    'from-sky-100 to-sky-200',
                                    'from-indigo-100 to-indigo-200',
                                    'from-teal-100 to-teal-200',
                                ];
                                $icons = [
                                    'wrench-screwdriver',
                                    'cog-6-tooth',
                                    'bolt',
                                    'cpu-chip',
                                ];
                                $idx = $loop->index % count($gradients);
                                $minPrice = $service->damageTypes->min('price');
                                $maxPrice = $service->damageTypes->max('price');
                            @endphp
                            <div class="relative h-44 bg-gradient-to-br {{ $gradients[$idx] }} flex items-center justify-center">
                                <x-ui.icon :name="$icons[$idx]" variant="outline" class="!size-16 !text-neutral-400/50 transition-transform duration-300 group-hover:scale-110" />

                                @if ($minPrice)
                                    <div class="absolute top-3 left-3">
                                        <x-ui.badge color="blue" size="sm">
                                            Mulai Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        </x-ui.badge>
                                    </div>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4">
                                <x-ui.heading level="h3" size="md">{{ $service->name }}</x-ui.heading>
                                <x-ui.text size="sm" class="mt-1 !text-neutral-500">
                                    {{ $service->description }}
                                </x-ui.text>

                                @if ($service->damageTypes->isNotEmpty())
                                    <div class="mt-4 border-t border-neutral-100 pt-3">
                                        <div class="mb-2 flex items-center justify-between">
                                            <x-ui.text size="xs" class="font-semibold uppercase tracking-wide !text-neutral-400">
                                                Jenis Kerusakan
                                            </x-ui.text>
                                            <x-ui.text size="xs" class="font-semibold uppercase tracking-wide !text-neutral-400">
                                                Harga
                                            </x-ui.text>
                                        </div>

                                        <div class="space-y-2">
                                            @foreach ($service->damageTypes as $damageType)
                                                <div class="flex items-center justify-between gap-2 text-sm">
                                                    <span class="truncate text-neutral-600">{{ $damageType->name }}</span>
                                                    <span class="shrink-0 font-medium text-neutral-800">
                                                        Rp {{ number_format($damageType->price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <x-ui.icon name="magnifying-glass" class="mx-auto !size-12 !text-neutral-300" />
                            <x-ui.heading level="h3" size="sm" class="mt-4 !text-neutral-400">
                                Tidak ada layanan ditemukan
                            </x-ui.heading>
                            <x-ui.text size="sm" class="mt-1 !text-neutral-400">
                                Coba ubah filter untuk melihat layanan lainnya.
                            </x-ui.text>
                            <x-ui.button wire:click="resetFilter" color="blue" variant="outline" class="mt-4">
                                Reset Filter
                            </x-ui.button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
