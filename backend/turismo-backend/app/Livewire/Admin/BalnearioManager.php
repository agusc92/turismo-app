<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Balneario;

class BalnearioManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public bool $isEditing   = false;

    public ?int $editingId = null;
    public string $nombre          = '';
    public string $direccion       = '';
    public string $telefono        = '';
    public string $redesSociales   = '';
    public string $servicios       = '';
    public string $mail            = '';
    public string $accesibilidad   = '';
    public string $fecha_desde_hasta = '';
    public string $imagen          = '';
    public string $latitud         = '';
    public string $longitud        = '';

    public ?int $confirmId   = null;
    public string $confirmName = '';
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'nombre'           => 'required|string|max:255',
            'direccion'        => 'required|string|max:255',
            'telefono'         => 'nullable|string|max:50',
            'redesSociales'    => 'nullable|url|max:500',
            'servicios'        => 'nullable|string',
            'mail'             => 'nullable|email|max:255',
            'accesibilidad'    => 'nullable|string',
            'fecha_desde_hasta'=> 'nullable|string|max:100',
            'imagen'           => 'nullable|url|max:500',
            'latitud'          => 'nullable|numeric',
            'longitud'         => 'nullable|numeric',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nombre','direccion','telefono','redesSociales','servicios','mail','accesibilidad','fecha_desde_hasta','imagen','latitud','longitud','editingId']);
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $b = Balneario::findOrFail($id);
        $this->editingId        = $b->id;
        $this->nombre           = $b->nombre;
        $this->direccion        = $b->direccion;
        $this->telefono         = $b->telefono ?? '';
        $this->redesSociales    = $b->redesSociales ?? '';
        $this->servicios        = $b->servicios ?? '';
        $this->mail             = $b->mail ?? '';
        $this->accesibilidad    = $b->accesibilidad ?? '';
        $this->fecha_desde_hasta = $b->fecha_desde_hasta ?? '';
        $this->imagen           = $b->imagen ?? '';
        $this->latitud          = $b->latitud ?? '';
        $this->longitud         = $b->longitud ?? '';
        $this->isEditing        = true; $this->showModal = true; $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['latitud']  = $data['latitud'] ? (float)$data['latitud'] : null;
        $data['longitud'] = $data['longitud'] ? (float)$data['longitud'] : null;
        foreach (['telefono','redesSociales','servicios','mail','accesibilidad','fecha_desde_hasta','imagen'] as $f)
            $data[$f] = $data[$f] ?: null;

        if ($this->isEditing) {
            Balneario::findOrFail($this->editingId)->update($data);
            $this->toast('Balneario actualizado.');
        } else {
            Balneario::create($data);
            $this->toast('Balneario creado.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; $this->confirmName = $nombre; $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) Balneario::findOrFail($this->confirmId)->delete();
        $this->toast('Balneario eliminado.');
        $this->showConfirm = false; $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; $this->toastType = $type; $this->dispatch('show-toast');
    }

    public function render()
    {
        $balnearios = Balneario::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('nombre')
            ->paginate(12);

        return view('livewire.admin.balneario-manager', compact('balnearios'));
    }
}
