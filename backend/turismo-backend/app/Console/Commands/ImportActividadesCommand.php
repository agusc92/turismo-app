<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Actividad;
use App\Models\Tipo;
use Illuminate\Support\Facades\File;

class ImportActividadesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:actividades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa datos de actividades desde un archivo CSV.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando importación de actividades...');

        $filePath = base_path('database/imports/actividades.csv');

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

        foreach ($lines as $lineNumber => $line) {
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
            $descripcion = $row['descripcion'] ?? null;
            $redesSociales = $row['redesSociales'] ?? null;
            $web = $row['web'] ?? null;
            $mail = $row['mail'] ?? null;
            $telefono = $row['telefono'] ?? null;
            $imagen = $row['imagen'] ?? null;
            $diasYHorarios = $row['diasYHorarios'] ?? null;
            $latitud = isset($row['latitud']) && is_numeric($row['latitud']) ? (float)$row['latitud'] : null;
            $longitud = isset($row['longitud']) && is_numeric($row['longitud']) ? (float)$row['longitud'] : null;
            $habilitado = filter_var($row['habilitado'] ?? true, FILTER_VALIDATE_BOOLEAN);

            $tipoId = null;
            if (isset($row['tipo']) && !empty($row['tipo'])) {
                $tipoNombre = trim($row['tipo']);
                $tipo = Tipo::firstOrCreate(['tipo' => $tipoNombre]);
                $tipoId = $tipo->id;
            }

            try {
                Actividad::create([
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'descripcion' => $descripcion,
                    'redes_sociales' => $redesSociales,
                    'web' => $web,
                    'mail' => $mail,
                    'telefono' => $telefono,
                    'imagen' => $imagen,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'tipo_id' => $tipoId,
                    'dias_y_horarios' => $diasYHorarios,
                    'habilitado' => $habilitado,
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $this->error("Error al importar actividad de la línea: " . $line . " - " . $e->getMessage());
                $skippedCount++;
            }
        }

        $this->info("Importación finalizada. {$importedCount} actividades importadas, {$skippedCount} líneas saltadas.");
        return Command::SUCCESS;
    }
}
