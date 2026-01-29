<?php
namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class FinanzasController extends Controller
{
    public function finanzas(Request $request)
    {
        $estadistica = false;

        // Verificar si hay fechas en la solicitud
        if ($request->has(['start_date', 'end_date'])) {

            $pedidos = Pedido::whereBetween('created_at', [
                $request->start_date,
                $request->end_date,
            ])->get();

            $totalDomicilios = $pedidos->sum('costo_domicilio');
            $totalVentas     = $pedidos->sum('total');
            $numeroVentas    = $pedidos->count();

            $estadistica = [
                'total_facturas'   => $totalVentas + $totalDomicilios,
                'total_domicilios' => $totalDomicilios,
                'ganancias_30'     => $totalVentas * 0.30,
                'numero_ventas'    => $numeroVentas,
                'metodo'           => [
                    'Efectivo'      => [
                        'cantidad' => $pedidos->where('metodo_pago', 'Efectivo')->count(),
                        //'total'    => $pedidos->where('metodo_pago', 'Efectivo')->sum('total'),
                    ],
                    'Transferencia' => [
                        'cantidad' => $pedidos->where('metodo_pago', 'Transferencia')->count(),
                        //'total'    => $pedidos->where('metodo_pago', 'Transferencia')->sum('total'),
                    ],
                ],
            ];

            $informacion_estadistica = "Estadísticas desde {$request->start_date} hasta {$request->end_date}";

            return view(
                "vendor.voyager.finanzas.index",
                compact("estadistica", "informacion_estadistica")
            );
        } else {
            return view("vendor.voyager.finanzas.index", compact("estadistica"));
            return response(["no data"]);
        }

    }

}
