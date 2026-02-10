<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    use HasFactory;

      protected $fillable = [
        'nombres',
        'telefono',
        'cedula',
    ];
    public function __toString()
    {
        return $this->cedula
            .' - '.$this->nombres
            .' ('.$this->telefono.')';
    }

        public function getCedulaNombreAttribute()
    {
        return $this->cedula . ' - ' . $this->nombres;
    }
}
