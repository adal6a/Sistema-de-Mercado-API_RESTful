<?php

namespace App;

/*It is not necessary due to the nature of the relationship*/
//use Illuminate\Database\Eloquent\Model;

use App\Product;

//class Seller extends Model
class Seller extends User
{
    /* Relaciones */
    public function products(){
        return $this->hasMany(Product::class);
    }
}
