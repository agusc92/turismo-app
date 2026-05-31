<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportAllDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta todos los comandos de importación de datos CSV.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando importación de TODOS los datos...');

        $this->call('import:complejos');
        $this->call('import:eventos');
        $this->call('import:balnearios');
        $this->call('import:alojamientos');
        $this->call('import:actividades');
        $this->call('import:gastronomicos');

        $this->info('Todos los comandos de importación han sido ejecutados.');
        return Command::SUCCESS;
    }
}
