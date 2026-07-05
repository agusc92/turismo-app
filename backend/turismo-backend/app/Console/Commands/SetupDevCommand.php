<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class SetupDevCommand extends Command
{
    protected $signature = 'setup:dev';
    protected $description = 'Prepara el entorno de desarrollo del backend para frontend: instala dependencias, migra, importa datos y genera docs.';

    public function handle()
    {
        $this->info('Iniciando setup del entorno de desarrollo...');

        // 1. Instalar dependencias de Composer
        $this->comment('Instalando dependencias de Composer...');
        $process = Process::fromShellCommandline('composer install');
        $process->setTimeout(300); // Aumentar el tiempo de espera a 300 segundos (5 minutos)
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if (!$process->isSuccessful()) {
            $this->error('Error al instalar dependencias de Composer.');
            // Mostrar el error de salida si no fue un timeout
            if ($process->isTimedOut()) {
                $this->error('El proceso de Composer excedió el tiempo de espera. Intenta aumentar el timeout o ejecutar "composer install" manualmente.');
            } else {
                $this->error($process->getErrorOutput());
            }
            return Command::FAILURE;
        }

        $this->info('Dependencias de Composer instaladas.');

        // **NUEVO PASO DE VERIFICACIÓN:**
        $this->comment('Verificando la carga de dependencias y autoloader...');
        $verifyProcess = Process::fromShellCommandline('php artisan about');
        $verifyProcess->run();

        if (!$verifyProcess->isSuccessful()) {
            $this->error('¡Error crítico! Las dependencias de Composer no se cargaron correctamente o el autoloader falló.');
            $this->error($verifyProcess->getErrorOutput()); // Muestra el error de 'php artisan about'
            return Command::FAILURE;
        }
        $this->info('Verificación de dependencias y autoloader exitosa.');
        // Fin del NUEVO PASO DE VERIFICACIÓN

        // 2. Limpiar y migrar la base de datos
        $this->comment('Limpiando y migrando la base de datos...');
        $this->call('migrate:fresh');
        $this->info('Base de datos migrada.');

        // 3. Importar todos los datos iniciales
        $this->comment('Importando datos iniciales...');
        $this->call('import:all');
        $this->info('Datos iniciales importados.');

        // 4. Generar documentación de la API
        $this->comment('Generando documentación de la API...');
        $this->call('l5-swagger:generate');
        $this->info('Documentación de la API generada.');

        $this->info('Setup del entorno de desarrollo completado exitosamente.');
        return Command::SUCCESS;
    }
}
