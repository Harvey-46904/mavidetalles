<?php
namespace App\Observers;

use App\Models\Pedido;

class PedidoObserver
{
    public function saving(Pedido $pedido): void
    {
        $detalles = $pedido->detalles;

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
    public function created(Pedido $pedido): void
    {

    }
}
