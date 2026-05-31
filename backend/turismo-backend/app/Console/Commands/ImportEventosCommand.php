<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evento;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ImportEventosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:eventos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa datos de eventos desde un archivo CSV.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando importación de eventos...');

        $filePath = base_path('database/imports/eventos.csv');

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

            $row['direccion'] = $row['dirección'] ?? null;
            unset($row['dirección']);

            $fecha = null;
            if (isset($row['fecha']) && !empty($row['fecha'])) {
                try {
                    $fecha = Carbon::createFromFormat('d/m/Y', $row['fecha'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $this->warn("No se pudo parsear la fecha '{$row['fecha']}' en la línea: " . $line . " - " . $e->getMessage());
                }
            }

            try {
                Evento::create([
                    'nombre' => $row['nombre'],
                    'direccion' => $row['direccion'],
                    'descripcion' => $row['descripcion'] ?? null,
                    'fecha' => $fecha,
                    'lugar' => $row['lugar'] ?? null,
                    'imagen' => $row['imagen'] ?? null,
                    'destacado' => false,
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $this->error("Error al importar evento de la línea: " . $line . " - " . $e->getMessage());
                $skippedCount++;
            }
        }

        $this->info("Importación finalizada. {$importedCount} eventos importados, {$skippedCount} líneas saltadas.");
        return Command::SUCCESS;
    }
}
