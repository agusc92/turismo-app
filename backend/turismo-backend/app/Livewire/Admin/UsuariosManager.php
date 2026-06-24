<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public bool $isEditing   = false;

    public ?int $editingId = null;
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $rol      = 'turista';

    public ?int $confirmId   = null;
    public string $confirmName = '';
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        $passwordRule = $this->isEditing ? 'nullable|min:6' : 'required|min:6';
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email' . ($this->isEditing ? ',' . $this->editingId : ''),
            'password' => $passwordRule,
            'rol'      => 'required|in:admin,turista',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['name','email','password','editingId']);
        $this->rol = 'turista';
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $u = User::findOrFail($id);
        $this->editingId = $u->id;
        $this->name      = $u->name;
        $this->email     = $u->email;
        $this->password  = '';
        $this->rol       = $u->rol ?? 'turista';
        $this->isEditing = true; $this->showModal = true; $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($this->isEditing) {
            User::findOrFail($this->editingId)->update($data);
            $this->toast('Usuario actualizado.');
        } else {
            User::create($data);
            $this->toast('Usuario creado.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; $this->confirmName = $nombre; $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) User::findOrFail($this->confirmId)->delete();
        $this->toast('Usuario eliminado.');
        $this->showConfirm = false; $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; $this->toastType = $type; $this->dispatch('show-toast');
    }

    public function render()
    {
        $usuarios = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.usuarios-manager', compact('usuarios'));
    }
}
