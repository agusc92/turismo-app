<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoAlojamiento;

class TiposAlojamientoManager extends Component
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
        return [
            'tipo' => 'required|string|max:100|unique:tipo_alojamientos,tipo,' . $this->editingId,
        ];
    }

    protected function messages(): array
    {
        return [
            'tipo.required' => 'El nombre del tipo es obligatorio.',
            'tipo.max'      => 'El nombre es demasiado largo.',
            'tipo.unique'   => 'Este tipo de alojamiento ya se encuentra registrado.',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['tipo', 'editingId']);
        $this->isEditing = false; 
        $this->showModal = true; 
        $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $t = TipoAlojamiento::findOrFail($id);
        $this->editingId = $t->id;
        $this->tipo      = $t->tipo;
        $this->isEditing = true; 
        $this->showModal = true; 
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->isEditing) {
            TipoAlojamiento::findOrFail($this->editingId)->update($data);
            $this->toast('Tipo de alojamiento actualizado.');
        } else {
            TipoAlojamiento::create($data);
            $this->toast('Tipo de alojamiento creado.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; 
        $this->confirmName = $nombre; 
        $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) {
            $t = TipoAlojamiento::findOrFail($this->confirmId);
            // Desvinculamos de la tabla intermedia antes de borrar para evitar inconsistencias
            $t->alojamientos()->detach(); 
            $t->delete();
        }
        $this->toast('Tipo de alojamiento eliminado.');
        $this->showConfirm = false; 
        $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; 
        $this->toastType = $type; 
        $this->dispatch('show-toast');
    }

    public function render()
    {
        $tipos = TipoAlojamiento::where('tipo', 'like', '%' . $this->search . '%')
            ->orderBy('tipo')
            ->paginate(12);

        return view('livewire.admin.tipos-alojamiento-manager', compact('tipos'));
    }
}