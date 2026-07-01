<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tipo;
use App\Models\Alojamiento;

class AlojamientosManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public bool $isEditing   = false;

    public ?int $editingId = null;
    public string $nombre         = '';
    public string $direccion      = '';
    public string $telefono       = '';
    public string $facebook        = '';
    public string $instagram       = '';
    public string $paginaWeb      = '';
    public string $mail           = '';
    public bool   $mascotas       = false;
    public string $periodoApertura = '';
    public string $tipo           = '';
    public string $imagen         = '';
    public string $latitud        = '';
    public string $longitud       = '';

    public ?int $confirmId   = null;
    public string $confirmName = '';
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'nombre'         => 'required|string|max:255',
            'direccion'      => 'required|string|max:255',
            'tipo'           => 'required|string|max:100',
            'mascotas'       => 'boolean',
            'telefono'       => 'nullable|string|max:50',
            'facebook'       => 'nullable|url|max:500',
            'instagram'      => 'nullable|url|max:500',
            'paginaWeb'      => 'nullable|url|max:500',
            'mail'           => 'nullable|email|max:255',
            'periodoApertura'=> 'nullable|string|max:255',
            'imagen'         => 'nullable|url|max:500',
            'latitud'        => 'nullable|numeric',
            'longitud'       => 'nullable|numeric',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nombre','direccion','telefono','facebook','instagram','paginaWeb','mail','mascotas','periodoApertura','tipo','imagen','latitud','longitud','editingId']);
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $a = Alojamiento::findOrFail($id);
        $this->editingId      = $a->id;
        $this->nombre         = $a->nombre;
        $this->direccion      = $a->direccion;
        $this->telefono       = $a->telefono ?? '';
        // Parse combined social field
        $social = $a->redesSociales ?? '';
        $this->facebook = '';
        $this->instagram = '';
        if ($social) {
                            foreach ($parts as $part) {
                    $part = trim($part);
                    if (preg_match('/^fb:\s*(.+)$/i', $part, $match)) {
                        $this->facebook = trim($match[1]);
                    } elseif (preg_match('/^ig:\s*(.+)$/i', $part, $match)) {
                        $username = trim($match[1]);
                        $this->instagram = $username ? 'https://www.instagram.com/' . $username . '/' : '';
                    }
                }
        }
        $this->paginaWeb      = $a->paginaWeb ?? '';
        $this->mail           = $a->mail ?? '';
        $this->mascotas       = (bool)$a->mascotas;
        $this->periodoApertura = $a->periodoApertura ?? '';
        $this->tipo           = $a->tipo;
        $this->imagen         = $a->imagen ?? '';
        $this->latitud        = $a->latitud ?? '';
        $this->longitud       = $a->longitud ?? '';
        $this->isEditing      = true; $this->showModal = true; $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        // Combine social fields into single string
        $socialParts = [];
        if (!empty($data['instagram'])) {
            // Extract username from URL if full URL provided
            $url = $data['instagram'];
            $path = parse_url($url, PHP_URL_PATH);
            $username = trim($path, '/');
            $socialParts[] = 'ig: ' . $username;
        }
        if (!empty($data['facebook'])) {
            $socialParts[] = 'fb: ' . $data['facebook'];
        }
        $data['redesSociales'] = $socialParts ? implode(' | ', $socialParts) : null;
        $data['latitud']  = $data['latitud'] ? (float)$data['latitud'] : null;
        $data['longitud'] = $data['longitud'] ? (float)$data['longitud'] : null;
        foreach (['telefono','paginaWeb','mail','periodoApertura','imagen'] as $f)
            $data[$f] = $data[$f] ?: null;
        // Remove temporary fields
        unset($data['facebook'], $data['instagram']);

        if ($this->isEditing) {
            Alojamiento::findOrFail($this->editingId)->update($data);
            $this->toast('Alojamiento actualizado.');
        } else {
            Alojamiento::create($data);
            $this->toast('Alojamiento creado.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; $this->confirmName = $nombre; $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) Alojamiento::findOrFail($this->confirmId)->delete();
        $this->toast('Alojamiento eliminado.');
        $this->showConfirm = false; $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; $this->toastType = $type; $this->dispatch('show-toast');
    }

    public function render()
    {
        $alojamientos = Alojamiento::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('tipo', 'like', '%' . $this->search . '%')
            ->orderBy('nombre')
            ->paginate(12);

        $tipos = Tipo::orderBy('tipo')->pluck('tipo');

        return view('livewire.admin.alojamientos-manager', compact('alojamientos', 'tipos'));
    }
}
