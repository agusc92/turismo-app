<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use Illuminate\Support\Facades\File;

class ImportContactosCommand extends Command
{
    protected $signature = 'import:contactos';
    protected $description = 'Importa los datos de contacto desde un archivo CSV.';

    public function handle()
    {
        $this->info('Iniciando importación de contactos...');

        $filePath = base_path('database/imports/contacts.csv');

        if (!File::exists($filePath)) {
            $this->error("El archivo CSV '{$filePath}' no se encontró en database/imports/.");
            return Command::FAILURE;
        }

        $file = File::get($filePath);
        $lines = explode(PHP_EOL, $file);
        $header = str_getcsv(array_shift($lines));
        $header = array_map('trim', $header);

        $importedCount = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $data = str_getcsv($line);
            if (count($header) !== count($data)) {
                continue;
            }

            $row = array_combine($header, $data);

            try {
                // Como es una tabla de configuración única, podemos usar updateOrCreate
                // buscando por ID 1 o simplemente vaciando y creando, o actualizando el registro global.
                Contact::updateOrCreate(
                    ['id' => 1], // O sin condición si querés que pise el registro global
                    [
                        'telefono' => $row['telefono'] ?? $row['Telefono'] ?? null,
                        'whatsapp' => $row['whatsapp'] ?? $row['Whatsapp'] ?? null,
                        'email' => $row['email'] ?? $row['Email'] ?? null,
                        'web' => $row['web'] ?? $row['Web'] ?? null,
                        'instagram' => $row['instagram'] ?? $row['Instagram'] ?? null,
                        'facebook' => $row['facebook'] ?? $row['Facebook'] ?? null,
                        'youtube' => $row['youtube'] ?? $row['Youtube'] ?? null,
                        'tiktok' => $row['tiktok'] ?? $row['Tiktok'] ?? null,
                        'direccion' => $row['direccion'] ?? $row['Direccion'] ?? null,
                    ]
                );
                $importedCount++;
            } catch (\Exception $e) {
                $this->error("Error al importar contacto: " . $e->getMessage());
            }
        }

        $this->info("Importación de contactos finalizada. {$importedCount} registros procesados.");
        return Command::SUCCESS;
    }
}
