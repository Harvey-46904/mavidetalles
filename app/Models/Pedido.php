<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'pedido',
        'detalles',
        'precio',
        'cantidad',
        'unidad',
        'total',
        'fecha_compra',
        'domicilio',
        'nombre_recibe',
        'telefono_whatsapp',
        'direccion',
        'fecha_entrega',
        'costo_domicilio',
    ];

    protected $casts = [
        'detalles' => 'array',
    ];

    public function setCostoDomicilioAttribute($value)
    {
        $this->attributes['costo_domicilio'] = $value ?? 0;
    }

    // Opcional: Accessor para que al leerlo nunca sea null
    public function getCostoDomicilioAttribute($value)
    {
        return $value ?? 0;
    }
    public function cliente()
    {
        return $this->belongsTo(\App\Models\clientes::class, 'cliente_id');
    }

public function getDetallesAttribute($value)
{
    $data = is_string($value) ? json_decode($value, true) : $value;

    if (!is_array($data)) {
        return $value;
    }

    $html = '<div style="white-space: normal; line-height:1.4">';

    foreach ($data as $item) {
        $html .= "
            <div style='display:block'>
                <strong>{$item['detalle']}</strong><br>
                Cantidad : {$item['cantidad']}<br>
                  Precio : {$item['precio']}<br>
            </div>
            <hr style='margin:4px 0'>
        ";
    }

    $html .= '</div>';

    return $html;
}

public function getDetallesArray(): array
{
    // Tomar el valor REAL de la BD (sin accessor)
    $detalles = $this->getRawOriginal('detalles');

    // Si viene como string
    if (is_string($detalles)) {
        $detalles = json_decode($detalles, true);
    }

    // JSON doble
    if (is_string($detalles)) {
        $detalles = json_decode($detalles, true);
    }

    return is_array($detalles) ? $detalles : [];
}

}
