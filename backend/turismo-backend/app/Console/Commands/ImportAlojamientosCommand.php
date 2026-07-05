<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alojamiento;
use App\Models\TipoAlojamiento;
use Illuminate\Support\Facades\File;

class ImportAlojamientosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:alojamientos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa datos de alojamientos desde un archivo CSV.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando importación de alojamientos...');

        $filePath = base_path('database/imports/alojamientos.csv');

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
            $paginaWeb = $row['web'] ?? null;
            $mail = $row['mail'] ?? null;
            $mascotas = (isset($row['mascotas']) && strtolower($row['mascotas']) === 'si');
            $periodoApertura = $row['periodoApertura'] ?? null;
            $tipoString = $row['tipo'] ?? null;
            $imagen = $row['imagen'] ?? null;
            $latitud = isset($row['latitud']) && is_numeric($row['latitud']) ? (float)$row['latitud'] : null;
            $longitud = isset($row['longitud']) && is_numeric($row['longitud']) ? (float)$row['longitud'] : null;
            $habilitado = filter_var($row['habilitado'] ?? true, FILTER_VALIDATE_BOOLEAN);

            try {
                $alojamiento = Alojamiento::create([
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'redesSociales' => $redesSociales,
                    'paginaWeb' => $paginaWeb,
                    'mail' => $mail,
                    'mascotas' => $mascotas,
                    'periodoApertura' => $periodoApertura,
                    'imagen' => $imagen,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'habilitado' => $habilitado,
                ]);

                // Relación tiposAlojamiento
                if ($tipoString) {
                    $tipoNames = array_map('trim', explode(',', $tipoString));
                    $tipoIds = [];
                    foreach ($tipoNames as $tipoName) {
                        $tipoAlojamiento = TipoAlojamiento::firstOrCreate(['tipo' => $tipoName]);
                        $tipoIds[] = $tipoAlojamiento->id;
                    }
                    $alojamiento->tiposAlojamiento()->attach($tipoIds);
                }

                $importedCount++;
            } catch (\Exception $e) {
                $this->error("Error al importar alojamiento de la línea: " . $line . " - " . $e->getMessage());
                $skippedCount++;
            }
        }

        $this->info("Importación finalizada. {$importedCount} alojamientos importados, {$skippedCount} líneas saltadas.");
        return Command::SUCCESS;
    }
}
