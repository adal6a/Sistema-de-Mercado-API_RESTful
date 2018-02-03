<?php

namespace App;

use App\Transaction;

class Buyer extends User
{
    /*Relaciones*/
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
