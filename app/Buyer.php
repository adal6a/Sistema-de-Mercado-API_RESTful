<?php

namespace App;
/*It is not necessary due to the nature of the relationship*/
//use Illuminate\Database\Eloquent\Model;

use App\Transaction;

//class Buyer extends Model
class Buyer extends User
{
    /*Relaciones*/
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
