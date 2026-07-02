<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Actividad;
use App\Models\Tipo;

class ActividadesManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal   = false;
    public bool $showConfirm = false;
    public bool $isEditing   = false;

    public ?int $editingId = null;
    public string $nombre        = '';
    public string $direccion     = '';
    public string $descripcion   = '';
    public string $redes_sociales = '';
    public string $facebook      = '';
    public string $instagram     = '';
    public string $web           = '';
    public string $mail          = '';
    public string $telefono      = '';
    public string $imagen        = '';
    public string $latitud       = '';
    public string $longitud      = '';
    public string $dias_y_horarios = '';
    public string $tipo_id       = '';

    public ?int $confirmId   = null;
    public string $confirmName = '';
    public string $toastMsg  = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'nombre'          => 'required|string|max:255',
            'direccion'       => 'required|string|max:255',
            'tipo_id'         => 'required|exists:tipos,id',
            'descripcion'     => 'nullable|string',
            'redes_sociales'  => 'nullable|url|max:500',
            'facebook'       => 'nullable|url|max:500',
            'instagram'      => 'nullable|url|max:500',
            'web'             => 'nullable|url|max:500',
            'mail'            => 'nullable|email|max:255',
            'telefono'        => 'nullable|string|max:50',
            'imagen'          => 'nullable|url|max:500',
            'latitud'         => 'nullable|numeric',
            'longitud'        => 'nullable|numeric',
            'dias_y_horarios' => 'nullable|string|max:255',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nombre','direccion','descripcion','redes_sociales','facebook','instagram','web','mail','telefono','imagen','latitud','longitud','dias_y_horarios','tipo_id','editingId']);
        $this->isEditing = false;
        $this->showModal = true;
        $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $a = Actividad::findOrFail($id);
        $this->editingId       = $a->id;
        $this->nombre          = $a->nombre;
        $this->direccion       = $a->direccion;
        $this->descripcion     = $a->descripcion ?? '';
        $social = $a->redes_sociales ?? '';
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
        $this->web             = $a->web ?? '';
        $this->mail            = $a->mail ?? '';
        $this->telefono        = $a->telefono ?? '';
        $this->imagen          = $a->imagen ?? '';
        $this->latitud         = $a->latitud ?? '';
        $this->longitud        = $a->longitud ?? '';
        $this->dias_y_horarios = $a->dias_y_horarios ?? '';
        $this->tipo_id         = $a->tipo_id ?? '';
        $this->isEditing       = true;
        $this->showModal       = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['latitud'] = $data['latitud'] ? (float)$data['latitud'] : null;
        $data['longitud'] = $data['longitud'] ? (float)$data['longitud'] : null;
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
        $data['redes_sociales'] = $socialParts ? implode(' | ', $socialParts) : null;
        unset($data['facebook'], $data['instagram']);
        foreach (['descripcion','redes_sociales','web','mail','telefono','imagen','dias_y_horarios'] as $f)
            $data[$f] = $data[$f] ?: null;

        if ($this->isEditing) {
            Actividad::findOrFail($this->editingId)->update($data);
            $this->toast('Actividad actualizada.');
        } else {
            Actividad::create($data);
            $this->toast('Actividad creada.');
        }
        $this->showModal = false;
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->confirmId = $id; $this->confirmName = $nombre; $this->showConfirm = true;
    }

    public function delete(): void
    {
        if ($this->confirmId) Actividad::findOrFail($this->confirmId)->delete();
        $this->toast('Actividad eliminada.');
        $this->showConfirm = false; $this->confirmId = null;
    }

    private function toast(string $msg, string $type = 'success'): void
    {
        $this->toastMsg = $msg; $this->toastType = $type; $this->dispatch('show-toast');
    }

    public function render()
    {
        $actividades = Actividad::with('tipo')
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('direccion', 'like', '%' . $this->search . '%')
                    
                    // AGREGAMOS LA BÚSQUEDA POR LA RELACIÓN "TIPO"
                    ->orWhereHas('tipo', function($q) {
                        $q->where('tipo', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('nombre')
            ->paginate(12);

        $tipos = Tipo::orderBy('tipo')->get();

        return view('livewire.admin.actividades-manager', compact('actividades', 'tipos'));
    }
}
