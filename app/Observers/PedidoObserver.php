<?php
namespace App\Observers;

use App\Models\Pedido;

class PedidoObserver
{
    public function saving(Pedido $pedido): void
    {
        $detalles = $pedido->detalles;

        $pedido->paga_cliente=$pedido->paga_cliente++;
        // 1️⃣ Decodificar si viene como string
        if (is_string($detalles)) {
            $detalles = json_decode($detalles, true);
        }

        // 2️⃣ Si sigue siendo string (JSON doble)
        if (is_string($detalles)) {
            $detalles = json_decode($detalles, true);
        }

        // 3️⃣ Garantizar array
        if (! is_array($detalles)) {
            $detalles = [];
        }

        // 4️⃣ Reasignar YA como array
        $pedido->detalles = $detalles;

        // 5️⃣ Calcular total
        $total = 0;
        foreach ($detalles as $item) {
            $total += ($item['precio'] ?? 0) * ($item['cantidad'] ?? 0);
        }

        if(!$pedido->domicilio){
            $pedido->nombre_recibe=NULL;
            $pedido->direccion=NULL;
            $pedido->telefono_whatsapp=NULL;
            $pedido->fecha_entrega=NULL;
            $pedido->costo_domicilio=NULL;
        }

        if ($pedido->domicilio && $pedido->costo_domicilio) {
            $total += $pedido->costo_domicilio;
        }

        $pedido->total = $total;
    }
/*
    public function updating(Pedido $pedido): void
    {
        \Log::info($pedido->getAttributes());
        $detalles = $pedido->getRawOriginal('detalles'); // devuelve el string JSON
        $detalles = json_decode($detalles, true);
        // Decodificar si viene como string
        if (is_string($detalles)) {
            $detalles = json_decode($detalles, true);
        }

        // Garantizar array
        if (! is_array($detalles)) {
            $detalles = [];
        }

        // Reasignar como array
        $pedido->detalles = $detalles;

        // Calcular total
        $total = 0;
        foreach ($detalles as $item) {
            $total += ($item['precio'] ?? 0) * ($item['cantidad'] ?? 0);
        }

        if ($pedido->domicilio && $pedido->costo_domicilio) {
            $total += $pedido->costo_domicilio;
        }

        $pedido->total = $total;
    }
*/
    /**
     * Handle the Pedido "created" event.
     */

    public function updating(Pedido $pedido)
{
   $abono = (int) request()->input('paga_cliente', 0);
$pedido->paga_cliente = (int) $pedido->getOriginal('paga_cliente') + $abono;
}
    public function created(Pedido $pedido): void
    {

    }
}
