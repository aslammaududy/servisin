<?php

use App\Livewire\Concerns\HasToast;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use HasToast, WithPagination;

    public UserForm $userForm;

    public bool $showModal = false;
    public ?int $editingUserId = null;

    public string $search = '';
    public string $filterRole = '';

    public function mount(): void
    {
        abort_unless(auth()->user()->role === 'admin', 403);
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%');
            }))
            ->when($this->filterRole, fn ($query) => $query->where('role', $this->filterRole))
            ->latest()
            ->paginate(10);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterRole(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->editingUserId = null;
        $this->userForm->reset();
        $this->showModal = true;
    }

    public function edit(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $user->id;
        $this->userForm->setUser($user);
        $this->showModal = true;
    }

    public function save(): void
    {
        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $this->userForm->update($user);
            $this->toastSuccess('Pengguna berhasil diperbarui.');
        } else {
            $this->userForm->store();
            $this->toastSuccess('Pengguna berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->editingUserId = null;
        $this->userForm->reset();
        unset($this->users);
    }

    public function delete(int $userId): void
    {
        if ($userId === auth()->id()) {
            $this->toastError('Tidak dapat menghapus akun sendiri.');

            return;
        }

        User::findOrFail($userId)->delete();
        $this->toastSuccess('Pengguna berhasil dihapus.');
        unset($this->users);
    }
};
