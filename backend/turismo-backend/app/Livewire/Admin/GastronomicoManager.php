<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Gastronomico;
use App\Models\TipoGastronomico;
use App\Models\Menu;

class GastronomicoManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public bool $isEditing   = false;

    public ?int $editingId = null;
    public string $nombre        = '';
    public string $direccion     = '';
    public string $telefono      = '';
    public string $redesSociales = '';
    public string $facebook = '';
    public string $instagram = '';
    public string $horario       = '';
    public string $tiendaOnline  = '';
    public string $extras        = '';
    public string $imagen        = '';
    public string $latitud       = '';
    public string $longitud      = '';
    public array  $tipo_ids      = [];
    public array  $menu_ids      = [];
    public bool   $habilitado    = false;

    public ?int $confirmId   = null;
    public string $confirmName = '';
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'nombre'       => 'required|string|max:255',
            'direccion'    => 'required|string|max:255',
            'telefono'     => 'nullable|string|max:50',
            'redesSociales'=> 'nullable|url|max:500',
            'facebook'       => 'nullable|url|max:500',
            'instagram'      => 'nullable|url|max:500',
            'horario'      => 'nullable|string|max:255',
            'tiendaOnline' => 'nullable|url|max:500',
            'extras'       => 'nullable|string',
            'imagen'       => 'nullable|url|max:500',
            'latitud'      => 'nullable|numeric',
            'longitud'     => 'nullable|numeric',
            'tipo_ids'     => 'required|array|min:1',
            'tipo_ids.*'   => 'exists:tipo_gastronomicos,id',
            'menu_ids'     => 'required|array|min:1',
            'menu_ids.*'   => 'exists:menus,id',
            'habilitado'   => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'tipo_ids.required' => 'El tipo gastronómico es obligatorio.',
            'tipo_ids.array'    => 'El formato de tipos no es válido.',
            'tipo_ids.min'      => 'Debe seleccionar al menos un tipo gastronómico.',
            'tipo_ids.*.exists' => 'El tipo seleccionado no existe en la base de datos.',
            'menu_ids.required' => 'El menú es obligatorio.',
            'menu_ids.min'      => 'Debe seleccionar al menos un menú.',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nombre','direccion','telefono','redesSociales','facebook','instagram','horario','tiendaOnline','extras','imagen','latitud','longitud','menu_ids','editingId','habilitado']);
        $this->tipo_ids = [null];
        $this->menu_ids = [];
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $g = Gastronomico::with('tipos','menus')->findOrFail($id);
        $this->editingId    = $g->id;
        $this->nombre       = $g->nombre;
        $this->direccion    = $g->direccion;
        $this->telefono     = $g->telefono ?? '';
        // Parse combined social field
        $social = $g->redesSociales ?? '';
        $this->facebook = '';
        $this->instagram = '';
        if ($social) {
            $parts = explode('|', $social);
            foreach ($parts as $part) {
                $part = trim($part);
                if (strpos(strtolower($part), 'fb:') === 0) {
                    $this->facebook = trim(substr($part, 3));
                } elseif (strpos(strtolower($part), 'ig:') === 0) {
                    $username = trim(substr($part, 3));
                    $this->instagram = $username ? 'https://www.instagram.com/' . $username . '/' : '';
                }
            }
        }
        $this->horario      = $g->horario ?? '';
        $this->tiendaOnline = $g->tiendaOnline ?? '';
        $this->extras       = $g->extras ?? '';
        $this->imagen       = $g->imagen ?? '';
        $this->latitud      = $g->latitud ?? '';
        $this->longitud     = $g->longitud ?? '';
        $this->tipo_ids     = $g->tipos->pluck('id')->map(fn($i)=>(string)$i)->toArray();
        $this->menu_ids     = $g->menus->pluck('id')->map(fn($i)=>(string)$i)->toArray();
        $this->habilitado   = (bool)$g->habilitado;
        $this->isEditing    = true; $this->showModal = true; $this->resetValidation();
    }

    public function maybeAddSelect(int $index): void
    {
        // If the current selection is not null and it's the last element, add a new empty select
        if (!empty($this->tipo_ids[$index]) && $index === array_key_last($this->tipo_ids)) {
            $this->tipo_ids[] = null;
        }
    }

    public function removeTipoSelect(int $index): void
    {
        // Remove the select at given index
        array_splice($this->tipo_ids, $index, 1);
        // Ensure at least one empty select remains
        if (empty($this->tipo_ids)) {
            $this->tipo_ids[] = null;
        }
    }

    public function save(): void
    {
        // 1. Limpiamos los nulos o strings vacíos ANTES de validar
        $this->tipo_ids = array_filter($this->tipo_ids ?? [], fn($i) => !is_null($i) && $i !== '');

        // 2. Ahora sí ejecutamos la validación con los datos limpios
        $data = $this->validate();
        
        // El array_filter de abajo ya no es necesario porque lo hicimos arriba, 
        // así que puedes dejarlo mapeado directamente:
        $tipo_ids = $this->tipo_ids;
        $menu_ids = $data['menu_ids'] ?? [];
        unset($data['tipo_ids'], $data['menu_ids']);

        $data['latitud']  = $data['latitud'] ? (float)$data['latitud'] : null;
        $data['longitud'] = $data['longitud'] ? (float)$data['longitud'] : null;
        // Combine social fields into single string
        $socialParts = [];
        if (!empty($data['instagram'])) {
            $url = $data['instagram'];
            $path = parse_url($url, PHP_URL_PATH);
            $username = trim($path, '/');
            $socialParts[] = 'ig: ' . $username;
        }
        if (!empty($data['facebook'])) {
            $socialParts[] = 'fb: ' . $data['facebook'];
        }
        $data['redesSociales'] = $socialParts ? implode(' | ', $socialParts) : null;
        unset($data['facebook'], $data['instagram']);
        foreach (['telefono','redesSociales','horario','tiendaOnline','extras','imagen'] as $f)
            $data[$f] = $data[$f] ?: null;

        if ($this->isEditing) {
            $g = Gastronomico::findOrFail($this->editingId);
            $g->update($data);
            $g->tipos()->sync($tipo_ids);
            $g->menus()->sync($menu_ids);
            $this->toast('Gastronómico actualizado.');
        } else {
            $g = Gastronomico::create($data);
            $g->tipos()->sync($tipo_ids);
            $g->menus()->sync($menu_ids);
            $this->toast('Gastronómico creado.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; $this->confirmName = $nombre; $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) {
            $g = Gastronomico::findOrFail($this->confirmId);
            $g->tipos()->detach(); $g->menus()->detach(); $g->delete();
        }
        $this->toast('Gastronómico eliminado.');
        $this->showConfirm = false; $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; $this->toastType = $type; $this->dispatch('show-toast');
    }

    public function render()
    {
        $gastronomicos = Gastronomico::with('tipos', 'menus')
            ->where(function($query) {
                // Buscamos por las propiedades directas del gastronómico
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('direccion', 'like', '%' . $this->search . '%')
                    
                    // Buscamos dentro de la relación Muchos a Muchos con "tipos"
                    ->orWhereHas('tipos', function($q) {
                        $q->where('tipo', 'like', '%' . $this->search . '%');
                    })
                    
                    // Buscamos dentro de la relación Muchos a Muchos con "menus"
                    ->orWhereHas('menus', function($q) {
                        $q->where('tipo', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('nombre')
            ->paginate(12);

        $tiposGastronomicos = TipoGastronomico::orderBy('tipo')->get();
        $menus = Menu::orderBy('tipo')->get();

        return view('livewire.admin.gastronomico-manager', compact('gastronomicos', 'tiposGastronomicos', 'menus'));
    }
}
