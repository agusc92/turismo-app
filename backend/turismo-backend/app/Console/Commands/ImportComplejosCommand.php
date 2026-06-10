<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Complejo;
use Illuminate\Support\Facades\File;

class ImportComplejosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:complejos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa datos de complejos desde un archivo CSV.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando importación de complejos...');
        $filePath = base_path('database/imports/complejos.csv');

        if (!File::exists($filePath)) {
            $this->error("El archivo CSV '{$filePath}' no se encontró en database/imports/.");
            return Command::FAILURE;
        }

        $file = File::get($filePath);
        $lines = explode(PHP_EOL, $file);
        $header = str_getcsv(array_shift($lines));
        $importedCount = 0;
        $skippedCount = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $data = str_getcsv($line);
            if (count($header) !== count($data)) {
                $this->warn("Saltando línea debido a un número inconsistente de columnas: " . $line);
                $skippedCount++;
                continue;
            }

            $row = array_combine($header, $data);

            $latitud = isset($row['latitud']) && is_numeric($row['latitud']) ? (float)$row['latitud'] : null;
            $longitud = isset($row['longitud']) && is_numeric($row['longitud']) ? (float)$row['longitud'] : null;

            try {
                Complejo::create([
                    'nombre' => $row['nombre'],
                    'direccion' => $row['direccion'],
                    'mail' => $row['mail'] ?? null,
                    'redesSociales' => $row['redesSociales'] ?? null,
                    'telefono' => $row['telefono'] ?? null,
                    'servicio' => $row['servicio'] ?? null,
                    'adicional' => $row['adicional'] ?? null,
                    'imagen' => $row['imagen'] ?? null,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $this->error("Error al importar complejo de la línea: " . $line . " - " . $e->getMessage());
                $skippedCount++;
            }
        }

        $this->info("Importación finalizada. {$importedCount} complejos importados, {$skippedCount} líneas saltadas.");
        return Command::SUCCESS;
    }
}
