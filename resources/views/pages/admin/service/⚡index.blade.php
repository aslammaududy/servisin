<?php

use App\Livewire\Concerns\HasToast;
use App\Livewire\Forms\ServiceForm;
use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use HasToast, WithPagination;

    public ServiceForm $serviceForm;

    public bool $showModal = false;
    public ?int $editingServiceId = null;

    public function mount(): void
    {
        abort_unless(auth()->user()->role === 'admin', 403);
    }

    #[Computed]
    public function services()
    {
        return Service::latest()->paginate(10);
    }

    public function create(): void
    {
        $this->editingServiceId = null;
        $this->serviceForm->reset();
        $this->showModal = true;
    }

    public function edit(int $serviceId): void
    {
        $service = Service::findOrFail($serviceId);
        $this->editingServiceId = $service->id;
        $this->serviceForm->setService($service);
        $this->showModal = true;
    }

    public function save(): void
    {
        if ($this->editingServiceId) {
            $service = Service::findOrFail($this->editingServiceId);
            $this->serviceForm->update($service);
            $this->toastSuccess('Layanan berhasil diperbarui.');
        } else {
            $this->serviceForm->store();
            $this->toastSuccess('Layanan berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->editingServiceId = null;
        $this->serviceForm->reset();
    }

    public function delete(int $serviceId): void
    {
        $service = Service::findOrFail($serviceId);

        if ($service->damageTypes()->exists()) {
            $this->toastError('Layanan tidak dapat dihapus karena memiliki jenis kerusakan terkait.');
            return;
        }

        $service->delete();
        $this->toastSuccess('Layanan berhasil dihapus.');
    }
};
?>

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <x-ui.heading level="h1" size="xl">
                Kelola Layanan
            </x-ui.heading>
            <x-ui.text size="sm" class="mt-1 text-gray-500">
                Tambah atau ubah daftar layanan servis.
            </x-ui.text>
        </div>

        <x-ui.button color="blue" wire:click="create">
            Tambah Layanan
        </x-ui.button>
    </div>

    {{-- Table --}}
    <x-ui.card size="lg" class="!max-w-none overflow-hidden">
        <flux:table :paginate="$this->services">
            <flux:table.columns>
                <flux:table.column>Nama</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            @foreach ($this->services as $service)
                <flux:table.rows>
                    <flux:table.row>
                        <flux:table.cell>
                            <div>
                                <x-ui.text size="sm" class="font-semibold text-neutral-900">
                                    {{ $service->name }}
                                </x-ui.text>
                                <x-ui.text size="xs" class="text-gray-400">
                                    {{ $service->description }}
                                </x-ui.text>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($service->is_active)
                                <x-ui.badge color="green" size="sm">
                                    Aktif
                                </x-ui.badge>
                            @else
                                <x-ui.badge color="red" size="sm">
                                    Nonaktif
                                </x-ui.badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <button
                                    wire:click="edit({{ $service->id }})"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-800"
                                >
                                    Edit
                                </button>
                                <button
                                    wire:click="delete({{ $service->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus layanan '{{ $service->name }}'?"
                                    class="text-sm font-medium text-red-500 hover:text-red-700"
                                >
                                    Hapus
                                </button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
            @endforeach
        </flux:table>
    </x-ui.card>

    {{-- Create/Edit Modal --}}
    @teleport('body')
        <div
            x-data="{ open: $wire.entangle('showModal') }"
            x-show="open"
            x-cloak
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/50" x-on:click="open = false"></div>

                <div
                    x-show="open"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-lg rounded-xl bg-white p-6 shadow-xl"
                    x-on:keydown.escape.window="open = false"
                >
                    <x-ui.heading level="h2" size="lg">
                        {{ $editingServiceId ? 'Edit Layanan' : 'Tambah Layanan' }}
                    </x-ui.heading>
                    <x-ui.text size="sm" class="mt-1 text-gray-500">
                        {{ $editingServiceId ? 'Perbarui informasi layanan.' : 'Isi informasi layanan baru.' }}
                    </x-ui.text>

                    <form wire:submit="save" class="mt-6 space-y-4">
                        <x-ui.field>
                            <x-ui.label>Nama Layanan</x-ui.label>
                            <x-ui.input wire:model="serviceForm.name" placeholder="Contoh: Servis AC" />
                            <x-ui.error name="serviceForm.name" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Deskripsi</x-ui.label>
                            <x-ui.textarea wire:model="serviceForm.description" placeholder="Deskripsi singkat layanan" rows="3" />
                            <x-ui.error name="serviceForm.description" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.checkbox wire:model="serviceForm.is_active" label="Layanan aktif" />
                        </x-ui.field>

                        <div class="flex justify-end gap-3 pt-4">
                            <x-ui.button type="button" x-on:click="open = false">
                                Batal
                            </x-ui.button>
                            <x-ui.button type="submit" color="blue">
                                {{ $editingServiceId ? 'Simpan Perubahan' : 'Tambah Layanan' }}
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endteleport
</div>
