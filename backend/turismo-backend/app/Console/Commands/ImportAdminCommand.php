<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ImportAdminCommand extends Command
{
    protected $signature = 'import:admin';
    protected $description = 'Importa admins desde un archivo CSV y los registra en la base de datos';

    public function handle()
    {
        $path = database_path('imports/admin.csv');

        if (!File::exists($path)) {
            $this->error("El archivo CSV no fue encontrado en: {$path}");
            return 1;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // Lee la cabecera (name, email, password, rol)

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            // Verificamos si el usuario ya existe por email para no duplicar
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'rol' => $data['rol'] ?? 'turista',
                ]
            );

            $count++;
        }

        fclose($file);

        $this->info("¡Se importaron {$count} usuarios correctamente!");
        return 0;
    }
}
