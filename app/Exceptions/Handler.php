<?php

namespace App\Exceptions;

use Exception;

use App\Traits\ApiResponser;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthorizationException;

use Illuminate\Validation\ValidationException;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{

    //Trait
    Use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        /*Acá es donde se obtienen los tipos de excepciones*/
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception instanceof ModelNotFoundException){
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$modelo} con el id especificado", 404);
        }

        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse("No tienes permiso para ejecutar esta acción", 403);
        }

        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse("No se encontró la URL especificada", 404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse("El método especificado en la petición no es válido", 405);
        }

        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if(config('app.debug')){
            //Usar esto en desarrollo
            return parent::render($request, $exception);
        }else{
            //Usar esto en producción
            return $this->errorResponse("Falla inesperada. Intenta más tarde.", 505);
        }


    }

    /*Este método se utiliza para retornar error de autenticación*/
    public function unauthenticated($request, AuthenticationException $exception){
        return $this->errorResponse("No autenticado", 401);
    }

    /*Este método se utiliza para retornar la lista de errores de validación al hacer un store o update*/
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        return response()->json([
            'error' => $e->errors(),
            'code' => 422
        ], 422);

        /*NO ENTIENDO POR QUÉ NINGUNA DE ESTAS ASIGNACIONES NO ES POSIBLE*/
        //$errors = $e->errors();
        //$errors = $e->getMessages();

        /*Acá se hace uso del trait ApiResponser*/
        //$this->errorResponse($errors, 422);
    }
}
