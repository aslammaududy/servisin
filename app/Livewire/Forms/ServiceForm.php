<?php

namespace App\Livewire\Forms;

use App\Models\Service;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ServiceForm extends Form
{
    #[Validate('required|string|max:255', message: 'Nama layanan wajib diisi.')]
    public string $name = '';

    #[Validate('string|max:500')]
    public string $description = '';

    public bool $is_active = true;

    public function setService(Service $service): void
    {
        $this->name = $service->name;
        $this->description = $service->description ?? '';
        $this->is_active = $service->is_active;
    }

    public function store(): void
    {
        $this->validate();

        Service::create($this->only(['name', 'description', 'is_active']));
    }

    public function update(Service $service): void
    {
        $this->validate();

        $service->update($this->only(['name', 'description', 'is_active']));
    }
}
