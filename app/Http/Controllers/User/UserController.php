<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        return response()->json(['data' => $usuarios], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Reglas de validación
        /** TODO:
         * Crear FormRequest para Usuario en vez de crear las reglas acá
         */
        $reglas = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $reglas);

        $campos = $request->all();

        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificacionToken();
        $campos['admin'] = User::USUARIO_NO_ADMINISTRADOR;

        $usuario = User::create($campos);

        return response()->json(['data' => $usuario, 201]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::findOrFail($id);

        return response()->json(['data' => $usuario], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $usuario = User::findOrFail($id);

        $reglas = [
            'email' => 'email|unique:users,email,'. $usuario->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_NO_ADMINISTRADOR,
        ];

        $this->validate($request, $reglas);

        if($request->has('name')){
            $usuario->name = $request->name;
        }

        if($request->has('email') && ($usuario->email != $request->email)){
            $usuario->verified = User::USUARIO_NO_VERIFICADO;
            $usuario->verification_token = User::generarVerificacionToken();

            $usuario->email = $request->email;
        }

        if($request->has('password')){
            $usuario->password = bcrypt($request->password);
        }

        if($request->has('admin')){
            if(!$usuario->esVerificado()){
                return response()->json([
                    'error'=> 'Únicamente los usuarios verificados pueden cambiar su valor de administrador',
                    'code' => 409
                ],
                409);
            }

            $usuario->admin = $request->admin;
        }

        //Este método valida si realmente los datos cambian con respecto a los del usuario que lo ha solicitado
        if (!$usuario->isDirty()){
            return response()->json([
                'error'=> 'Se debe especificar al menos un valor diferente para actualizar',
                'code' => 422
            ],
            422);
        }

        $usuario->save();

        return response()->json(['data' => $usuario], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        $usuario->delete();

        return response()->json(['data' => $usuario], 200);
    }
}
