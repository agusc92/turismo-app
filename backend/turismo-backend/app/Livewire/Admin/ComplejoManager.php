<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Complejo;

class ComplejoManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public bool $isEditing   = false;

    public ?int $editingId = null;
    public string $nombre       = '';
    public string $direccion    = '';
    public string $mail         = '';
    public string $redesSociales = '';
    public string $telefono     = '';
    public string $servicio     = '';
    public string $adicional    = '';
    public string $imagen       = '';
    public string $latitud      = '';
    public string $longitud     = '';

    public ?int $confirmId   = null;
    public string $confirmName = '';
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'nombre'       => 'required|string|max:255',
            'direccion'    => 'required|string|max:255',
            'mail'         => 'nullable|email|max:255',
            'redesSociales'=> 'nullable|url|max:500',
            'telefono'     => 'nullable|string|max:50',
            'servicio'     => 'nullable|string',
            'adicional'    => 'nullable|string',
            'imagen'       => 'nullable|url|max:500',
            'latitud'      => 'nullable|numeric',
            'longitud'     => 'nullable|numeric',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nombre','direccion','mail','redesSociales','telefono','servicio','adicional','imagen','latitud','longitud','editingId']);
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $c = Complejo::findOrFail($id);
        $this->editingId    = $c->id;
        $this->nombre       = $c->nombre;
        $this->direccion    = $c->direccion;
        $this->mail         = $c->mail ?? '';
        $this->redesSociales= $c->redesSociales ?? '';
        $this->telefono     = $c->telefono ?? '';
        $this->servicio     = $c->servicio ?? '';
        $this->adicional    = $c->adicional ?? '';
        $this->imagen       = $c->imagen ?? '';
        $this->latitud      = $c->latitud ?? '';
        $this->longitud     = $c->longitud ?? '';
        $this->isEditing    = true; $this->showModal = true; $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['latitud']  = $data['latitud'] ? (float)$data['latitud'] : null;
        $data['longitud'] = $data['longitud'] ? (float)$data['longitud'] : null;
        foreach (['mail','redesSociales','telefono','servicio','adicional','imagen'] as $f)
            $data[$f] = $data[$f] ?: null;

        if ($this->isEditing) {
            Complejo::findOrFail($this->editingId)->update($data);
            $this->toast('Complejo actualizado.');
        } else {
            Complejo::create($data);
            $this->toast('Complejo creado.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; $this->confirmName = $nombre; $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) Complejo::findOrFail($this->confirmId)->delete();
        $this->toast('Complejo eliminado.');
        $this->showConfirm = false; $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; $this->toastType = $type; $this->dispatch('show-toast');
    }

    public function render()
    {
        $complejos = Complejo::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('nombre')
            ->paginate(12);

        return view('livewire.admin.complejo-manager', compact('complejos'));
    }
}
