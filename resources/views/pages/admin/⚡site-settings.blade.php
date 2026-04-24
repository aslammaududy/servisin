<?php

use App\Models\SiteSetting;
use Livewire\Component;

new class extends Component
{
    use \App\Livewire\Concerns\HasToast;

    public string $bank_name = '';
    public string $bank_account_number = '';
    public string $bank_account_name = '';

    public function mount(): void
    {
        $this->bank_name = SiteSetting::get('bank_name', 'BCA');
        $this->bank_account_number = SiteSetting::get('bank_account_number', '');
        $this->bank_account_name = SiteSetting::get('bank_account_name', '');
    }

    public function save(): void
    {
        $this->validate([
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_name' => 'required|string|max:255',
        ]);

        SiteSetting::set('bank_name', $this->bank_name);
        SiteSetting::set('bank_account_number', $this->bank_account_number);
        SiteSetting::set('bank_account_name', $this->bank_account_name);

        $this->toastSuccess('Pengaturan bank berhasil disimpan');
    }
};
?>

<div class="space-y-6">
    <div>
        <x-ui.heading level="h1" size="xl">
            Pengaturan Situs
        </x-ui.heading>
        <x-ui.text size="sm" class="mt-1 text-gray-500">
            Kelola pengaturan umum aplikasi.
        </x-ui.text>
    </div>

    <x-ui.card size="lg">
        <x-ui.heading level="h2" size="md" class="mb-4">
            Informasi Rekening Bank
        </x-ui.heading>

        <form wire:submit="save" class="space-y-4">
            <x-ui.field>
                <x-ui.label>Nama Bank</x-ui.label>
                <x-ui.input wire:model="bank_name" placeholder="BCA" />
                <x-ui.error name="bank_name" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label>Nomor Rekening</x-ui.label>
                <x-ui.input wire:model="bank_account_number" placeholder="1234567890" />
                <x-ui.error name="bank_account_number" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label>Nama Pemilik Rekening</x-ui.label>
                <x-ui.input wire:model="bank_account_name" placeholder="PT Servisin Indonesia" />
                <x-ui.error name="bank_account_name" />
            </x-ui.field>

            <x-ui.button type="submit" color="blue">
                Simpan Perubahan
            </x-ui.button>
        </form>
    </x-ui.card>
</div>