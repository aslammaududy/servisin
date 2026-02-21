<?php

use App\Livewire\Concerns\HasToast;
use App\Livewire\Forms\DamageTypeForm;
use App\Livewire\Forms\ServiceForm;
use App\Models\DamageType;
use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use HasToast, WithPagination;

    public ServiceForm $serviceForm;
    public DamageTypeForm $damageTypeForm;

    public bool $showModal = false;
    public ?int $editingServiceId = null;

    public ?int $expandedServiceId = null;
    public bool $showDamageTypeModal = false;
    public ?int $editingDamageTypeId = null;

    public function mount(): void
    {
        abort_unless(auth()->user()->role === 'admin', 403);
    }

    #[Computed]
    public function services()
    {
        return Service::withCount('damageTypes')->latest()->paginate(10);
    }

    #[Computed]
    public function expandedService()
    {
        if (! $this->expandedServiceId) {
            return null;
        }

        return Service::find($this->expandedServiceId);
    }

    #[Computed]
    public function damageTypes()
    {
        if (! $this->expandedServiceId) {
            return collect();
        }

        return DamageType::where('service_id', $this->expandedServiceId)
            ->latest()
            ->get();
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

        if ($this->expandedServiceId === $serviceId) {
            $this->expandedServiceId = null;
        }
    }

    public function toggleDamageTypes(int $serviceId): void
    {
        $this->expandedServiceId = $this->expandedServiceId === $serviceId ? null : $serviceId;
    }

    public function createDamageType(): void
    {
        $this->editingDamageTypeId = null;
        $this->damageTypeForm->reset();
        $this->showDamageTypeModal = true;
    }

    public function editDamageType(int $damageTypeId): void
    {
        $damageType = DamageType::findOrFail($damageTypeId);
        $this->editingDamageTypeId = $damageType->id;
        $this->damageTypeForm->setDamageType($damageType);
        $this->showDamageTypeModal = true;
    }

    public function saveDamageType(): void
    {
        if ($this->editingDamageTypeId) {
            $damageType = DamageType::findOrFail($this->editingDamageTypeId);
            $this->damageTypeForm->update($damageType);
            $this->toastSuccess('Jenis kerusakan berhasil diperbarui.');
        } else {
            $this->damageTypeForm->store($this->expandedServiceId);
            $this->toastSuccess('Jenis kerusakan berhasil ditambahkan.');
        }

        $this->showDamageTypeModal = false;
        $this->editingDamageTypeId = null;
        $this->damageTypeForm->reset();
        unset($this->damageTypes, $this->services);
    }

    public function deleteDamageType(int $damageTypeId): void
    {
        DamageType::findOrFail($damageTypeId)->delete();
        $this->toastSuccess('Jenis kerusakan berhasil dihapus.');
        unset($this->damageTypes, $this->services);
    }
};
