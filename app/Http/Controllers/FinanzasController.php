<?php
namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FinanzasController extends Controller
{

    private function calcularEstadisticas($start, $end)
    {
        $end = $end . ' 23:59:59';
        $pedidos = Pedido::whereBetween('created_at', [$start, $end])->get();

        $totalDomicilios = $pedidos->sum('costo_domicilio');
        $totalVentas     = $pedidos->sum('total')- $totalDomicilios;
        $numeroVentas    = $pedidos->count();

        return [
            'total_facturas'   => $totalVentas ,
            'total_domicilios' => $totalDomicilios,
            'ganancias_30'     => ($totalVentas ) * 0.30,
            'numero_ventas'    => $numeroVentas,
            'metodo'           => [
                'Efectivo' => [
                    'cantidad' => $pedidos->where('metodo_pago', 'Efectivo')->count(),
                ],
                'Transferencia' => [
                    'cantidad' => $pedidos->where('metodo_pago', 'Transferencia')->count(),
                ],
            ],
        ];
    }

    public function finanzas(Request $request)
{
    $estadistica = false;
$informacion_estadistica=NULL;
    if ($request->has(['start_date', 'end_date'])) {
        $estadistica = $this->calcularEstadisticas(
            $request->start_date,
            $request->end_date
        );

        $informacion_estadistica =
            "Estadísticas desde {$request->start_date} hasta {$request->end_date}";
    }  
    

    return view(
        "vendor.voyager.finanzas.index",
        compact("estadistica", "informacion_estadistica")
    );
}

public function finanzasPdf(Request $request)
{
    if (!$request->has(['start_date', 'end_date'])) {
        abort(404);
    }

    $estadistica = $this->calcularEstadisticas(
        $request->start_date,
        $request->end_date
    );
    $inicio=$request->start_date;
    $final=$request->end_date;

    return Pdf::loadView(
        'pedidos.pdf',
        compact('estadistica',"inicio","final")
    )->download('finanzas.pdf');
}

}
