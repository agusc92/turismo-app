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
    public string $horario       = '';
    public string $tiendaOnline  = '';
    public string $extras        = '';
    public string $imagen        = '';
    public string $latitud       = '';
    public string $longitud      = '';
    public array  $tipo_ids      = [];
    public array  $menu_ids      = [];

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
            'horario'      => 'nullable|string|max:255',
            'tiendaOnline' => 'nullable|url|max:500',
            'extras'       => 'nullable|string',
            'imagen'       => 'nullable|url|max:500',
            'latitud'      => 'nullable|numeric',
            'longitud'     => 'nullable|numeric',
            'tipo_ids'     => 'nullable|array',
            'tipo_ids.*'   => 'exists:tipo_gastronomicos,id',
            'menu_ids'     => 'nullable|array',
            'menu_ids.*'   => 'exists:menus,id',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nombre','direccion','telefono','redesSociales','horario','tiendaOnline','extras','imagen','latitud','longitud','tipo_ids','menu_ids','editingId']);
        $this->tipo_ids = []; $this->menu_ids = [];
        $this->isEditing = false; $this->showModal = true; $this->resetValidation();
    }

    public function openEdit(int $id): void
    {
        $g = Gastronomico::with('tipos','menus')->findOrFail($id);
        $this->editingId    = $g->id;
        $this->nombre       = $g->nombre;
        $this->direccion    = $g->direccion;
        $this->telefono     = $g->telefono ?? '';
        $this->redesSociales= $g->redesSociales ?? '';
        $this->horario      = $g->horario ?? '';
        $this->tiendaOnline = $g->tiendaOnline ?? '';
        $this->extras       = $g->extras ?? '';
        $this->imagen       = $g->imagen ?? '';
        $this->latitud      = $g->latitud ?? '';
        $this->longitud     = $g->longitud ?? '';
        $this->tipo_ids     = $g->tipos->pluck('id')->map(fn($i)=>(string)$i)->toArray();
        $this->menu_ids     = $g->menus->pluck('id')->map(fn($i)=>(string)$i)->toArray();
        $this->isEditing    = true; $this->showModal = true; $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();
        $tipo_ids = $data['tipo_ids'] ?? [];
        $menu_ids = $data['menu_ids'] ?? [];
        unset($data['tipo_ids'], $data['menu_ids']);

        $data['latitud']  = $data['latitud'] ? (float)$data['latitud'] : null;
        $data['longitud'] = $data['longitud'] ? (float)$data['longitud'] : null;
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
        $gastronomicos = Gastronomico::with('tipos','menus')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('nombre')
            ->paginate(12);

        $tiposGastronomicos = TipoGastronomico::orderBy('tipo')->get();
        $menus = Menu::orderBy('tipo')->get();

        return view('livewire.admin.gastronomico-manager', compact('gastronomicos', 'tiposGastronomicos', 'menus'));
    }
}
