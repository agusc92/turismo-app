<?php

namespace App\Livewire\Admin;

use App\Models\Imagen;
use Livewire\Component;

class ImagenesManager extends Component
{
    public string $actividades = '';
    public string $gastronomicos = '';
    public string $eventos = '';
    public string $alojamientos = '';
    public string $complejos = '';
    public string $balnearios = '';
    public string $toastMsg = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'actividades' => 'nullable|url|max:255',
            'gastronomicos' => 'nullable|url|max:255',
            'eventos' => 'nullable|url|max:255',
            'alojamientos' => 'nullable|url|max:255',
            'complejos' => 'nullable|url|max:255',
            'balnearios' => 'nullable|url|max:255',
        ];
    }

    public function mount(): void
    {
        $imagen = Imagen::first();

        if (!$imagen) {
            return;
        }

        foreach (['actividades', 'gastronomicos', 'eventos', 'alojamientos', 'complejos', 'balnearios'] as $field) {
            $this->{$field} = (string) ($imagen->{$field} ?? '');
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        foreach (array_keys($data) as $field) {
            $data[$field] = $data[$field] ?: null;
        }

        Imagen::updateOrCreate(
            ['id' => Imagen::value('id') ?? 1],
            $data
        );

        $this->toastMsg = 'Imágenes actualizadas.';
        $this->toastType = 'success';
    }

    public function render()
    {
        return view('livewire.admin.imagenes-manager');
    }
}
