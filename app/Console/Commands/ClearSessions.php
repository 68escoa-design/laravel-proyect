<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearSessions extends Command
{
    protected $signature = 'sessions:clear';
    protected $description = 'Limpiar todas las sesiones de la base de datos';

    public function handle()
    {
        DB::table('sessions')->delete();
        $this->info('Todas las sesiones han sido eliminadas.');
        return 0;
    }
}
