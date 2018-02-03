<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Seller;
use App\Transaction;
use App\Category;

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

    /* Relaciones */
    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }


}
