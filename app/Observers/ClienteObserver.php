<?php

namespace App\Observers;

use App\Models\clientes;

class ClienteObserver
{
    /**
     * Handle the clientes "created" event.
     */
    public function created(clientes $clientes): void
    {
        //
    }

    /**
     * Handle the clientes "updated" event.
     */
    public function updated(clientes $clientes): void
    {
        //
    }

    /**
     * Handle the clientes "deleted" event.
     */
    public function deleted(clientes $clientes): void
    {
        //
    }

    /**
     * Handle the clientes "restored" event.
     */
    public function restored(clientes $clientes): void
    {
        //
    }

    /**
     * Handle the clientes "force deleted" event.
     */
    public function forceDeleted(clientes $clientes): void
    {
        //
    }

     public function saving(clientes $cliente)
    {
        $cliente->display_name = $cliente->cedula . ' - ' . $cliente->nombres;
    }
}
