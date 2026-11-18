<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tipo;
use Illuminate\Database\QueryException;

class TiposManager extends Component
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
        return ['tipo' => 'required|string|max:100|unique:tipo_actividades,tipo' . ($this->isEditing ? ',' . $this->editingId : '')];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['tipo','editingId']);
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $t = Tipo::findOrFail($id);
        $this->editingId = $t->id; $this->tipo = $t->tipo;
        $this->isEditing = true; $this->showModal = true; $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->isEditing) {
            Tipo::findOrFail($this->editingId)->update($data);
            $this->toast('Tipo actualizado.');
        } else {
            Tipo::create($data);
            $this->toast('Tipo creado.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; $this->confirmName = $nombre; $this->showConfirm = true;
    }

    public function delete(): void
    {
        if (!$this->confirmId) {
            return;
        }

        $tipo = Tipo::findOrFail($this->confirmId);

        // La base de datos utiliza ON DELETE RESTRICT. Avisamos antes de
        // intentar borrar para evitar una excepción innecesaria.
        if ($tipo->actividades()->exists() || $tipo->usuarios()->exists()) {
            $this->showConfirm = false;
            $this->confirmId = null;
            $this->toast(
                'El tipo no puede eliminarse porque está siendo utilizado por uno o más recursos.',
                'error'
            );
            return;
        }

        try {
            $tipo->delete();
            $this->toast('Tipo eliminado.');
        } catch (QueryException $e) {
            // Cubre posibles relaciones creadas entre la comprobación y el
            // DELETE, sin dejar que Livewire rompa la página.
            $this->toast(
                'El tipo no puede eliminarse porque está siendo utilizado por uno o más recursos.',
                'error'
            );
        }

        $this->showConfirm = false;
        $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; $this->toastType = $type; $this->dispatch('show-toast');
    }

    public function render()
    {
        $tipos = Tipo::where('tipo', 'like', '%' . $this->search . '%')
            ->withCount('actividades')
            ->orderBy('tipo')
            ->paginate(15);

        return view('livewire.admin.tipos-actividad-manager', compact('tipos'));
    }
}
