<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evento;

class EventosManager extends Component
{
    use WithPagination;

    // Search
    public string $search = '';

    // Modal flags
    public bool $showModal    = false;
    public bool $showConfirm  = false;
    public bool $isEditing    = false;

    // Form fields
    public ?int $editingId = null;
    public string $nombre      = '';
    public string $direccion   = '';
    public string $descripcion = '';
    public string $fecha       = '';
    public string $lugar       = '';
    public string $imagen      = '';
    public bool   $destacado   = false;
    public string $latitud     = '';
    public string $longitud    = '';
    public bool   $habilitado  = true;

    // Confirm
    public ?int    $confirmId   = null;
    public string  $confirmName = '';

    // Toast
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'nombre'    => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'fecha'     => 'required|date',
            'lugar'     => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen'    => 'nullable|url|max:500',
            'destacado' => 'boolean',
            'latitud'   => 'nullable|numeric',
            'longitud'  => 'nullable|numeric',
            'habilitado' => 'boolean',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['nombre','direccion','descripcion','fecha','lugar','imagen','destacado','latitud','longitud','habilitado','editingId']);
        $this->isEditing = false;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $evento = Evento::findOrFail($id);
        $this->editingId   = $evento->id;
        $this->nombre      = $evento->nombre;
        $this->direccion   = $evento->direccion;
        $this->descripcion = $evento->descripcion ?? '';
        $this->fecha       = $evento->fecha ? $evento->fecha->format('Y-m-d') : '';
        $this->lugar       = $evento->lugar;
        $this->imagen      = $evento->imagen ?? '';
        $this->destacado   = (bool) $evento->destacado;
        $this->latitud     = $evento->latitud ?? '';
        $this->longitud    = $evento->longitud ?? '';
        $this->habilitado  = (bool) $evento->habilitado;
        $this->isEditing   = true;
        $this->showModal   = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['latitud']  = $data['latitud']  ? (float)$data['latitud']  : null;
        $data['longitud'] = $data['longitud'] ? (float)$data['longitud'] : null;
        $data['imagen']   = $data['imagen'] ?: null;
        $data['descripcion'] = $data['descripcion'] ?: null;

        if ($this->isEditing) {
            Evento::findOrFail($this->editingId)->update($data);
            $this->toast('Evento actualizado correctamente.');
        } else {
            Evento::create($data);
            $this->toast('Evento creado correctamente.');
        }

        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId   = $id;
        $this->confirmName = $nombre;
        $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) {
            Evento::findOrFail($this->confirmId)->delete();
            $this->toast('Evento eliminado.', 'success');
        }
        $this->showConfirm = false;
        $this->confirmId   = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg  = $msg;
        $this->toastType = $type;
        $this->dispatch('show-toast');
    }

    public function render()
    {
        $eventos = Evento::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('lugar', 'like', '%' . $this->search . '%')
            ->orderByDesc('fecha')
            ->paginate(12);

        return view('livewire.admin.eventos-manager', compact('eventos'));
    }
}
