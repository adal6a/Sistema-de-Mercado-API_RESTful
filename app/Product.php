<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PRODUCTO_DISPONIBLE = '1';
    const PRODUCTO_NO_DISPONIBLE = '0';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    /* Scopes */
    public function estaDisponible(){
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }


}
