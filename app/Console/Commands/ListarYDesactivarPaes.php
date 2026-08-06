<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListarYDesactivarPaes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:desactivar-paes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista productos activos y desactiva PAES si existe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Productos activos en home:');
        $productos = \App\Models\Producto::where('activo', true)
            ->orderBy('orden')
            ->get(['id', 'nombre', 'slug', 'orden']);

        foreach($productos as $p) {
            $this->line("{$p->id} - {$p->nombre} (slug: {$p->slug}) orden: {$p->orden}");
        }

        // Desactivar PAES si existe
        $paes = \App\Models\Producto::where('slug', 'paes')->first();
        if ($paes) {
            $paes->update(['activo' => false]);
            $this->warn("\nPAES desactivado exitosamente.");
        } else {
            $this->info("\nNo se encontró producto PAES en la base de datos.");
        }

        return Command::SUCCESS;
    }
}
