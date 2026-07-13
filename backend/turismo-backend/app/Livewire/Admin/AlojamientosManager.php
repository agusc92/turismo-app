<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoAlojamiento;
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
    public array $tipo_ids = [];
    public string $imagen         = '';
    public string $latitud        = '';
    public string $longitud       = '';
    public bool   $habilitado       = false;

    public ?int $confirmId   = null;
    public string $confirmName = '';
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'nombre'         => 'required|string|max:255',
            'direccion'      => 'required|string|max:255',
            'tipo_ids'       => 'required|array|min:1',
            'tipo_ids.*'     => 'exists:tipo_alojamientos,id',
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
            'habilitado'     => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nombre','direccion','telefono','facebook','instagram','paginaWeb','mail','mascotas','periodoApertura','tipo_ids','imagen','latitud','longitud','habilitado','editingId']);
        $this->tipo_ids = [null]; // Inicializa con un selector vacío
        $this->isEditing = false; 
        $this->showModal = true; 
        $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        // Cargamos el alojamiento incluyendo sus tipos
        $a = Alojamiento::with('tiposAlojamiento')->findOrFail($id);
        $this->editingId      = $a->id;
        $this->nombre         = $a->nombre;
        $this->direccion      = $a->direccion;
        $this->telefono       = $a->telefono ?? '';
        // Parse combined social field
        $social = $a->redesSociales ?? '';
        $this->facebook = '';
        $this->instagram = '';
        if ($social) {
                $parts = explode(' | ', $social);
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
        
        // Mapemaos los IDs de la relación pivot a nuestro array de strings
        $this->tipo_ids       = $a->tiposAlojamiento->pluck('id')->map(fn($i)=>(string)$i)->toArray();
        
        $this->imagen         = $a->imagen ?? '';
        $this->latitud        = $a->latitud ?? '';
        $this->longitud       = $a->longitud ?? '';
        $this->habilitado       = (bool)$a->habilitado;
        $this->isEditing      = true; $this->showModal = true; $this->resetValidation();
    }

    public function save(): void
    {
        // Limpiamos nulos o campos vacíos antes de la validación
        $this->tipo_ids = array_filter($this->tipo_ids ?? [], fn($i) => !is_null($i) && $i !== '');

        $data = $this->validate();
        
        // Aislamos los IDs y los quitamos del array que va directo al update/create del alojamiento
        $tipo_ids = $this->tipo_ids;
        unset($data['tipo_ids']);
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
            $a = Alojamiento::findOrFail($this->editingId);
            $a->update($data);
            $a->tiposAlojamiento()->sync($tipo_ids); // Sincroniza la tabla intermedia
            $this->toast('Alojamiento actualizado.');
        } else {
            $a = Alojamiento::create($data);
            $a->tiposAlojamiento()->sync($tipo_ids); // Sincroniza la tabla intermedia
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
        $alojamientos = Alojamiento::with('tiposAlojamiento')
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhereHas('tiposAlojamiento', function($q) {
                          $q->where('tipo', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('nombre')
            ->paginate(12);

        // Cambiamos el pluck() para traer la colección de modelos necesarios para el select del modal
        $tipos = TipoAlojamiento::orderBy('tipo')->get();

        return view('livewire.admin.alojamientos-manager', compact('alojamientos', 'tipos'));
    }

    public function maybeAddSelect(int $index): void
    {
        if (!empty($this->tipo_ids[$index]) && $index === array_key_last($this->tipo_ids)) {
            $this->tipo_ids[] = null;
        }
    }

    public function removeTipoSelect(int $index): void
    {
        array_splice($this->tipo_ids, $index, 1);
        if (empty($this->tipo_ids)) {
            $this->tipo_ids[] = null;
        }
    }
}
