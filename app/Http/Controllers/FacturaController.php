<?php
namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Imagick;

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

    public function generarFacturaImagen(Pedido $pedido)
    {
        $pedido->load('cliente');

        $pdf = Pdf::loadView('pedidos.factura', [
            'pedido'   => $pedido,
            'detalles' => $pedido->getDetallesArray(),
        ]);

        // Guardar PDF temporal
        $pdfPath = storage_path("app/factura_{$pedido->id}.pdf");
        file_put_contents($pdfPath, $pdf->output());
        $imagick = new Imagick();
        $imagick->setResolution(200, 200);
        $imagick->readImage($pdfPath);

// 🔥 SOLUCIÓN FONDO NEGRO
        $imagick->setImageBackgroundColor('white');
        $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

        $imagick->setImageFormat('jpeg');
        $imagick->setImageCompressionQuality(90);

        $imagePath = storage_path("app/factura_{$pedido->id}.jpg");
        $imagick->writeImage($imagePath);

        $imagick->clear();
        $imagick->destroy();
        return response()->download($imagePath)->deleteFileAfterSend(true);
    }
    public function descargarEstadistica(Pedido $pedido)
    {

    }
}
