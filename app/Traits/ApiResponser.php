<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
    /*Estos métodos ahorran estar declarando las response de tipo json en cada controlador*/
    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code){
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /*Cuando toca retornar una colleción completa de la petición, por ejemplo: todos los usuarios*/
    protected function showAll(Collection $collection, $code = 200){
        return $this->successResponse(['data' => $collection], $code);
    }

    /*Cuando toca retornar solo una instancia de la petición, por ejemplo: un usuario que ha
    sido creado o modificado*/
    protected function showOne(Model $instance, $code = 200){
        return $this->successResponse(['data' => $instance], $code);
    }
}
