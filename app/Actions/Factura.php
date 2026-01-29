<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Log;
class Factura extends AbstractAction
{
    public function getTitle()
    {
        return 'Factura';
    }

    public function getIcon()
    {
        return 'voyager-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-success pull-right',
        ];
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'pedidos';
    }
   

    public function getDefaultRoute()
    {
        return route('pedidos.factura',  $this->data->id);
    }
}