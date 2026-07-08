<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupFullDevCommand extends Command
{
    protected $signature = 'setup:full-dev';
    protected $description = 'Prepara el entorno de desarrollo completo: ejecuta setup:dev y luego corre los tests.';

    public function handle()
    {
        $this->info('Iniciando setup completo del entorno de desarrollo...');

        // Ejecutar el comando setup:dev
        $this->comment('Ejecutando setup:dev (instala dependencias, migra, importa datos y genera docs)...');
        $setupDevExitCode = $this->call('setup:dev'); // Capturar el código de salida

        // Verificar si el comando setup:dev fue exitoso antes de continuar con los tests
        if ($setupDevExitCode !== Command::SUCCESS) {
            $this->error('El comando setup:dev falló. Deteniendo el setup completo.');
            return Command::FAILURE;
        }

        // Ejecutar los tests
        $this->comment('Ejecutando todos los tests...');
        $testExitCode = $this->call('test'); // Capturar el código de salida

        // Verificar si los tests fueron exitosos
        if ($testExitCode !== Command::SUCCESS) {
            $this->error('Los tests fallaron. El setup completo se realizó, pero hay fallos en los tests.');
            return Command::FAILURE; // O Command::SUCCESS si quieres que el comando principal sea exitoso aunque los tests fallen
        }

        $this->info('Setup completo del entorno de desarrollo finalizado exitosamente.');
        return Command::SUCCESS;
    }
}
