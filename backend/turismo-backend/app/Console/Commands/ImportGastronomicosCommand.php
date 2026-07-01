<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gastronomico;
use App\Models\TipoGastronomico;
use App\Models\Menu;
use Illuminate\Support\Facades\File;

class ImportGastronomicosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:gastronomicos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa datos de establecimientos gastronómicos desde un archivo CSV.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando importación de establecimientos gastronómicos...');

        $filePath = base_path('database/imports/gastronomia.csv');

        if (!File::exists($filePath)) {
            $this->error("El archivo CSV '{$filePath}' no se encontró en database/imports/.");
            return Command::FAILURE;
        }

        $file = File::get($filePath);
        $lines = preg_split('/\r\n|\r|\n/', $file);
        $header = str_getcsv(array_shift($lines));
        $header = array_map('trim', $header);

        $importedCount = 0;
        $skippedCount = 0;

        foreach ($lines as $lineNumber => $line) {

            if (empty(trim($line))) {
                continue;
            }

            $data = str_getcsv($line);

            if (count($data) < count($header)) {
                $data = array_pad($data, count($header), null);
            }

            if (count($data) > count($header)) {
                $data = array_slice($data, 0, count($header));
            }
            $row = array_combine($header, $data);

            try {

                $nombre = trim($row['nombre'] ?? '');
                $direccion = trim($row['direccion'] ?? '');
                $telefono = trim($row['telefono'] ?? '');
                $redesSociales = trim($row['redesSociales'] ?? '');
                $horario = trim($row['horario'] ?? '');
                $tiendaOnline = trim($row['tiendaOnline'] ?? '');
                $extras = trim($row['extras'] ?? '');
                $imagen = trim($row['imagen'] ?? '');
                $latitud = isset($row['latitud']) && is_numeric($row['latitud']) ? (float)$row['latitud'] : null;
                $longitud = isset($row['longitud']) && is_numeric($row['longitud']) ? (float)$row['longitud'] : null;
                $habilitado = filter_var($row['habilitado'] ?? true, FILTER_VALIDATE_BOOLEAN);

                if (empty($nombre)) {
                    $this->warn("Línea " . ($lineNumber + 2) . " omitida: nombre vacío.");
                    $skippedCount++;
                    continue;
                }

                $gastronomico = Gastronomico::create([
                    'nombre' => $nombre,
                    'direccion' => $direccion ?: null,
                    'telefono' => $telefono ?: null,
                    'redesSociales' => $redesSociales ?: null,
                    'horario' => $horario ?: null,
                    'tiendaOnline' => $tiendaOnline ?: null,
                    'extras' => $extras ?: null,
                    'imagen' => $imagen ?: null,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'habilitado' => $habilitado,
                ]);

                // =========================
                // TIPOS GASTRONÓMICOS
                // =========================

                if (!empty($row['tipo'])) {

                    $tiposNombres = explode('|', $row['tipo']);

                    foreach ($tiposNombres as $tipoNombre) {

                        $tipoNombre = trim($tipoNombre);

                        if (!empty($tipoNombre)) {

                            $tipoGastronomico = TipoGastronomico::firstOrCreate([
                                'tipo' => $tipoNombre
                            ]);

                            $gastronomico->tipos()->syncWithoutDetaching([
                                $tipoGastronomico->id
                            ]);
                        }
                    }
                }

                // =========================
                // MENÚS ESPECIALES
                // =========================

                if (!empty($row['menues_especiales'])) {

                    $menusNombres = explode('|', $row['menues_especiales']);

                    foreach ($menusNombres as $menuNombre) {

                        $menuNombre = trim($menuNombre);

                        if (!empty($menuNombre)) {

                            $menu = Menu::firstOrCreate([
                                'tipo' => $menuNombre
                            ]);

                            $gastronomico->menus()->syncWithoutDetaching([
                                $menu->id
                            ]);
                        }
                    }
                }

                $importedCount++;

            } catch (\Exception $e) {

                $this->error(
                    "Error en línea " . ($lineNumber + 2) .
                    ": " . $e->getMessage()
                );

                $skippedCount++;
            }
        }

        $this->info(
            "Importación finalizada. " .
            "{$importedCount} establecimientos importados, " .
            "{$skippedCount} líneas omitidas."
        );

        return Command::SUCCESS;
    }
}
