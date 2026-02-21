<?php

namespace App\Livewire\Forms;

use App\Models\DamageType;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DamageTypeForm extends Form
{
    #[Validate('required|string|max:255', message: 'Nama jenis kerusakan wajib diisi.')]
    public string $name = '';

    #[Validate('required|string|max:255', message: 'Deskripsi wajib diisi.')]
    public string $description = '';

    #[Validate('required', message: 'Harga wajib diisi.')]
    public string $price = '';

    public bool $is_active = true;

    public function setDamageType(DamageType $damageType): void
    {
        $this->name = $damageType->name;
        $this->description = $damageType->description ?? '';
        $this->price = $damageType->price;
        $this->is_active = $damageType->is_active;
    }

    public function store(int $serviceId): void
    {
        $this->validate();

        $this->price = (int)str_replace(',', '', $this->price);

        DamageType::create([
            'service_id' => $serviceId,
            ...$this->only(['name', 'description', 'price', 'is_active']),
        ]);
    }

    public function update(DamageType $damageType): void
    {
        $this->validate();

        $damageType->update($this->only(['name', 'description', 'price', 'is_active']));
    }
}
