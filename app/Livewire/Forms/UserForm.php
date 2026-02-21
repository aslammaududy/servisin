<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Form;

class UserForm extends Form
{
    public ?int $userId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $role = 'user';

    public string $password = '';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email'.($this->userId ? ','.$this->userId : ''),
            'phone' => 'required|string|min:10|max:255',
            'role' => 'required|in:admin,user,technician',
            'password' => $this->userId ? 'nullable|string|min:8' : 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.required' => 'No. telepon wajib diisi.',
            'phone.min' => 'No. telepon minimal 10 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }

    public function setUser(User $user): void
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
        $this->password = '';
    }

    public function store(): void
    {
        $this->userId = null;
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'password' => $this->password,
        ]);

        $user->markEmailAsVerified();
    }

    public function update(User $user): void
    {
        $this->userId = $user->id;
        $this->validate();

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
        ]);

        if ($this->password !== '') {
            $user->update(['password' => $this->password]);
        }
    }
}
