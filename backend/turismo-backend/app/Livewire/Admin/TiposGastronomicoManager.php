<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoGastronomico;

class TiposGastronomicoManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public bool $isEditing   = false;

    public ?int $editingId = null;
    public string $tipo = '';

    public ?int $confirmId   = null;
    public string $confirmName = '';
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return ['tipo' => 'required|string|max:100|unique:tipo_gastronomicos,tipo' . ($this->isEditing ? ',' . $this->editingId : '')];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['tipo','editingId']);
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $t = TipoGastronomico::findOrFail($id);
        $this->editingId = $t->id; $this->tipo = $t->tipo;
        $this->isEditing = true; $this->showModal = true; $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->isEditing) {
            TipoGastronomico::findOrFail($this->editingId)->update($data);
            $this->toast('Tipo gastronómico actualizado.');
        } else {
            TipoGastronomico::create($data);
            $this->toast('Tipo gastronómico creado.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; $this->confirmName = $nombre; $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) TipoGastronomico::findOrFail($this->confirmId)->delete();
        $this->toast('Tipo gastronómico eliminado.');
        $this->showConfirm = false; $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; $this->toastType = $type; $this->dispatch('show-toast');
    }

    public function render()
    {
        $tipos = TipoGastronomico::where('tipo', 'like', '%' . $this->search . '%')
            ->withCount('gastronomicos')
            ->orderBy('tipo')
            ->paginate(15);

        return view('livewire.admin.tipos-gastronomico-manager', compact('tipos'));
    }
}
