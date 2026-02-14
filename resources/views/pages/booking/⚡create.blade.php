<?php

use App\Models\Service;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithFileUploads;
    use \App\Livewire\Concerns\HasToast;

    public \App\Livewire\Forms\BookingForm $bookingForm;
    public mixed $services;

    public function mount(): void
    {
        $this->services = Service::all();
        $this->bookingForm->user_id = auth()->id();
    }

    //reset select jenis kerusakan setiap kali layanan dipilih / dipilih ulang
    //sehingga tidak salah pilih jenis kerusakan atau tidak sesuai dengan layanan
    public function updatedBookingFormServiceId(): void
    {
        $this->bookingForm->damage_type_id = null;
    }

    #[\Livewire\Attributes\Computed]
    public function damageTypes()
    {
        if (! $this->bookingForm->service_id) {
            return collect();
        }

        return \App\Models\DamageType::where('service_id', $this->bookingForm->service_id)->get();
    }

    public function save(): void
    {
        $this->bookingForm->store();

        session()->flash('notify', [
            'content' => 'Booking berhasil dibuat!',
            'type' => 'success'
        ]);

        $this->redirect('/dashboard');
    }
};
?>

<div>
    <form
        wire:submit="save"
        class="mt-8 space-y-4 rounded-lg bg-white p-6 dark:bg-neutral-800/10"
    >
        <x-ui.field>
            <x-ui.label>Layanan</x-ui.label>
            <x-ui.select
                wire:model.live="bookingForm.service_id"
                searchable="true"
                placeholder="Pilih Layanan"
            >
                @foreach($services as $item)
                    <x-ui.select.option value="{{$item->id}}">{{ $item->name }}</x-ui.select.option>
                @endforeach
            </x-ui.select>
            <x-ui.error name="bookingForm.service_id"/>
            <x-ui.error name="bookingForm.status"/>
        </x-ui.field>

        <x-ui.field>
            <x-ui.label>Jenis Kerusakan</x-ui.label>
            <x-ui.description>Pilih Layanan terlebih dahulu</x-ui.description>
            <x-ui.select
                wire:model="bookingForm.damage_type_id"
                wire:key="damage-types-{{ $this->bookingForm->service_id ?? 'none' }}"
                searchable="true"
                placeholder="Pilih Jenis Kerusakan"
            >
                @foreach($this->damageTypes as $item)
                    <x-ui.select.option value="{{$item->id}}">{{ $item->name }}</x-ui.select.option>
                @endforeach
            </x-ui.select>
            <x-ui.error name="bookingForm.damage_type_id"/>
        </x-ui.field>

        <x-ui.field>
            <x-ui.label>Jadwal Kunjungan</x-ui.label>
            <x-ui.input wire:model="bookingForm.booking_date" min="{{ \Carbon\Carbon::today()->addDay() }}" type="datetime-local"/>
            <x-ui.error name="bookingForm.booking_date"/>
        </x-ui.field>

        <x-ui.field>
            <x-ui.label>Alamat Lengkap</x-ui.label>
            <x-ui.textarea
                wire:model="bookingForm.address"
            />
            <x-ui.error name="bookingForm.address"/>
        </x-ui.field>

        <x-ui.field>
            <x-ui.label>Deskripsi Kerusakan (Optional)</x-ui.label>
            <x-ui.textarea
                wire:model="bookingForm.notes"
            />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label>Foto Kerusakan (Optional)</x-ui.label>
            <x-ui.input wire:model="bookingForm.photo" type="file"/>
            <x-ui.error name="bookingForm.photo"/>
        </x-ui.field>

        <x-ui.button
            type="submit"
            color="blue"
        >
            Kirim Booking
        </x-ui.button>

        <x-ui.button
            type="button"
            href="{{ url('/') }}"
        >
            Batal
        </x-ui.button>
    </form>


</div>
