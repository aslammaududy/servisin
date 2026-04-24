<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <x-ui.heading level="h1" size="xl">
                Kelola Pengguna
            </x-ui.heading>
            <x-ui.text size="sm" class="mt-1 text-gray-500">
                Kelola semua akun pengguna, teknisi, dan admin.
            </x-ui.text>
        </div>

        <x-ui.button color="blue" wire:click="create">
            Tambah Teknisi
        </x-ui.button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="w-full sm:max-w-xs">
            <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Cari nama, email, atau telepon..." />
        </div>
        <div class="w-full sm:max-w-[10rem]">
            <x-ui.select wire:model.live="filterRole">
                <x-ui.select.option value="">Semua Role</x-ui.select.option>
                <x-ui.select.option value="admin">Admin</x-ui.select.option>
                <x-ui.select.option value="user">User</x-ui.select.option>
                <x-ui.select.option value="technician">Teknisi</x-ui.select.option>
            </x-ui.select>
        </div>
    </div>

    {{-- Table --}}
    <x-ui.card size="lg" class="!max-w-none overflow-hidden">
        <flux:table :paginate="$this->users">
            <flux:table.columns>
                <flux:table.column>Nama</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>No. Telepon</flux:table.column>
                <flux:table.column>Role</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            @foreach ($this->users as $user)
                <flux:table.rows wire:key="user-{{ $user->id }}">
                    <flux:table.row>
                        <flux:table.cell>
                            <x-ui.text size="sm" class="font-semibold text-neutral-900">
                                {{ $user->name }}
                            </x-ui.text>
                        </flux:table.cell>
                        <flux:table.cell>
                            <x-ui.text size="sm">
                                {{ $user->email }}
                            </x-ui.text>
                        </flux:table.cell>
                        <flux:table.cell>
                            <x-ui.text size="sm">
                                {{ $user->phone }}
                            </x-ui.text>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($user->role === 'admin')
                                <x-ui.badge color="red" size="sm">
                                    Admin
                                </x-ui.badge>
                            @elseif ($user->role === 'technician')
                                <x-ui.badge color="amber" size="sm">
                                    Teknisi
                                </x-ui.badge>
                            @else
                                <x-ui.badge color="blue" size="sm">
                                    User
                                </x-ui.badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <button
                                    wire:click="edit({{ $user->id }})"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-800"
                                >
                                    Edit
                                </button>
                                @if ($user->id !== auth()->id())
                                    <button
                                        wire:click="delete({{ $user->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus pengguna '{{ $user->name }}'?"
                                        class="text-sm font-medium text-red-500 hover:text-red-700"
                                    >
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
            @endforeach
        </flux:table>
    </x-ui.card>

    {{-- Create/Edit User Modal --}}
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
                        {{ $editingUserId ? 'Edit Teknisi' : 'Tambah Teknisi' }}
                    </x-ui.heading>
                    <x-ui.text size="sm" class="mt-1 text-gray-500">
                        {{ $editingUserId ? 'Perbarui informasi teknisi.' : 'Isi informasi teknisi baru.' }}
                    </x-ui.text>

                    <form wire:submit="save" class="mt-6 space-y-4">
                        <x-ui.field>
                            <x-ui.label>Nama</x-ui.label>
                            <x-ui.input wire:model="userForm.name" placeholder="Nama lengkap" />
                            <x-ui.error name="userForm.name" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Email</x-ui.label>
                            <x-ui.input wire:model="userForm.email" type="email" placeholder="email@contoh.com" />
                            <x-ui.error name="userForm.email" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>No. Telepon</x-ui.label>
                            <x-ui.input wire:model="userForm.phone" placeholder="08123456789" />
                            <x-ui.error name="userForm.phone" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Role</x-ui.label>
                            <x-ui.select wire:model="userForm.role">
                                <x-ui.select.option value="user">User</x-ui.select.option>
                                <x-ui.select.option value="technician">Teknisi</x-ui.select.option>
                                <x-ui.select.option value="admin">Admin</x-ui.select.option>
                            </x-ui.select>
                            <x-ui.error name="userForm.role" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>
                                Password
                                @if ($editingUserId)
                                    <span class="text-xs font-normal text-gray-400">(kosongkan jika tidak ingin mengubah)</span>
                                @endif
                            </x-ui.label>
                            <x-ui.input wire:model="userForm.password" type="password" placeholder="Minimal 8 karakter" />
                            <x-ui.error name="userForm.password" />
                        </x-ui.field>

                        <div class="flex justify-end gap-3 pt-4">
                            <x-ui.button type="button" x-on:click="open = false">
                                Batal
                            </x-ui.button>
                            <x-ui.button type="submit" color="blue">
                                {{ $editingUserId ? 'Simpan Perubahan' : 'Tambah Teknisi' }}
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endteleport
</div>
