<?php
namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function generarFactura(Pedido $pedido)
    {
        
        $pedido->load('cliente');

        // asegúrate de que la vista use $pedido->getDetallesArray()
        $pdf = Pdf::loadView('pedidos.factura', [
            'pedido'   => $pedido,
            'detalles' => $pedido->getDetallesArray(),
        ]);

        return $pdf->download('factura_' . $pedido->id . '.pdf');
    }
}
