<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Balneario;
use Illuminate\Support\Facades\File;

class ImportBalneariosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:balnearios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa datos de balnearios desde un archivo CSV.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando importación de balnearios...');

        $filePath = base_path('database/imports/balnearios.csv');

        if (!File::exists($filePath)) {
            $this->error("El archivo CSV '{$filePath}' no se encontró en database/imports/.");
            return Command::FAILURE;
        }

        $file = File::get($filePath);
        $lines = explode(PHP_EOL, $file);
        $header = str_getcsv(array_shift($lines));

        $header = array_map('trim', $header);

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

            $nombre = $row['nombre'];
            $direccion = $row['direccion'] ?? null;
            $telefono = $row['telefono'] ?? null;
            $redesSociales = $row['redesSociales'] ?? null;
            $servicios = $row['servicios'] ?? null;
            $mail = $row['mail'] ?? null;
            $accesibilidad = $row['accesibilidad'] ?? null;
            $fechaDesdeHasta = $row['fecha_desde_hasta'] ?? null;
            $imagen = $row['imagen'] ?? null;

            try {
                Balneario::create([
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'redesSociales' => $redesSociales,
                    'servicios' => $servicios,
                    'mail' => $mail,
                    'accesibilidad' => $accesibilidad,
                    'fecha_desde_hasta' => $fechaDesdeHasta,
                    'imagen' => $imagen,
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $this->error("Error al importar balneario de la línea: " . $line . " - " . $e->getMessage());
                $skippedCount++;
            }
        }

        $this->info("Importación finalizada. {$importedCount} balnearios importados, {$skippedCount} líneas saltadas.");
        return Command::SUCCESS;
    }
}
