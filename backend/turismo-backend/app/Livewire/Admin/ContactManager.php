<?php

namespace App\Livewire\Admin;

use App\Models\Contact;
use Livewire\Component;

class ContactManager extends Component
{
    public string $telefono = '';
    public string $whatsapp = '';
    public string $email = '';
    public string $web = '';
    public string $instagram = '';
    public string $facebook = '';
    public string $youtube = '';
    public string $tiktok = '';
    public string $direccion = '';
    public string $toastMsg = '';
    public string $toastType = 'success';

    protected function rules(): array
    {
        return [
            'telefono' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'web' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ];
    }

    public function mount(): void
    {
        $this->loadContact();
    }

    private function loadContact(): void
    {
        $contact = Contact::first();

        if (!$contact) {
            return;
        }

        $this->telefono = (string) ($contact->telefono ?? '');
        $this->whatsapp = (string) ($contact->whatsapp ?? '');
        $this->email = (string) ($contact->email ?? '');
        $this->web = (string) ($contact->web ?? '');
        $this->instagram = (string) ($contact->instagram ?? '');
        $this->facebook = (string) ($contact->facebook ?? '');
        $this->youtube = (string) ($contact->youtube ?? '');
        $this->tiktok = (string) ($contact->tiktok ?? '');
        $this->direccion = (string) ($contact->direccion ?? '');
    }

    public function save(): void
    {
        $this->validate();

        Contact::updateOrCreate(
            ['id' => Contact::value('id') ?? 1],
            [
                'telefono' => $this->telefono,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'web' => $this->web,
                'instagram' => $this->instagram,
                'facebook' => $this->facebook,
                'youtube' => $this->youtube,
                'tiktok' => $this->tiktok,
                'direccion' => $this->direccion,
            ]
        );

        $this->toastMsg = 'Datos de contacto actualizados.';
        $this->toastType = 'success';
    }

    public function render()
    {
        return view('livewire.admin.contact-manager');
    }
}
