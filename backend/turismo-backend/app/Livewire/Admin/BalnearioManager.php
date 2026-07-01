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
    public string $facebook = '';
    public string $instagram       = '';
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
            'facebook'       => 'nullable|url|max:500',
            'instagram'      => 'nullable|url|max:500',
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
        $this->reset(['nombre','direccion','telefono','facebook','instagram','servicios','mail','accesibilidad','fecha_desde_hasta','imagen','latitud','longitud','editingId']);
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $b = Balneario::findOrFail($id);
        $this->editingId        = $b->id;
        $this->nombre           = $b->nombre;
        $this->direccion        = $b->direccion;
        $this->telefono         = $b->telefono ?? '';
        // Parse combined social field
        $social = $b->redesSociales ?? '';
        $this->facebook = '';
        $this->instagram = '';
        if ($social) {
            $parts = explode('|', $social);
            foreach ($parts as $part) {
                $part = trim($part);
                if (str_starts_with(strtolower($part), 'fb:')) {
                    $this->facebook = trim(substr($part, 3));
                } elseif (str_starts_with(strtolower($part), 'ig:')) {
                    $username = trim(substr($part, 3));
                    $this->instagram = $username ? 'https://www.instagram.com/' . $username . '/' : '';
                }
            }
        }
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
        $validated = $this->validate();

        // Combine social fields into single string
        $socialParts = [];
        if (!empty($validated['instagram'])) {
            $path     = parse_url($validated['instagram'], PHP_URL_PATH);
            $username = trim($path, '/');
            $socialParts[] = 'ig: ' . $username;
        }
        if (!empty($validated['facebook'])) {
            $socialParts[] = 'fb: ' . $validated['facebook'];
        }

        $data = [
            'nombre'            => $validated['nombre'],
            'direccion'         => $validated['direccion'],
            'telefono'          => $validated['telefono'] ?: null,
            'redesSociales'     => $socialParts ? implode(' | ', $socialParts) : null,
            'servicios'         => $validated['servicios'] ?: null,
            'mail'              => $validated['mail'] ?: null,
            'accesibilidad'     => $validated['accesibilidad'] ?: null,
            'fecha_desde_hasta' => $validated['fecha_desde_hasta'] ?: null,
            'imagen'            => $validated['imagen'] ?: null,
            'latitud'           => $validated['latitud'] ? (float)$validated['latitud'] : null,
            'longitud'          => $validated['longitud'] ? (float)$validated['longitud'] : null,
        ];

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
