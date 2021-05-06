<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeAsignacionTarea;

class AuthController extends Controller 
{
  public function login(Request $request)
  {
 
    try {
      $request->validate([
        'email' => 'email|required',
        'password' => 'required'
      ]);
       $credentials = request(['email', 'password']);
       if (!Auth::attempt($credentials)) {
        return response()->json([
          'status_code' => 500,
          'message' => 'Unauthorized'
        ]);
      }
      $user = Usuario::where('email', $request->email)->first();
      if ( ! Hash::check($request->password, $user->password, [])) {
         throw new \Exception('Error usuario y contraseña');
      }



      $user->tokens()->delete();
      $tokenResult = $user->createToken('usuario')->plainTextToken;

      Mail::to('miguelangel.artecheruiz@gmail.com')->
      queue(new MensajeAsignacionTarea);


      return response()->json([
        'status_code' => 200,
        'token_acceso' => $tokenResult,
        'nombreUsuario' => $user->nombre . ' '.$user->apellido1. ' '.$user->apellido2 ,
        'nombre' => $user->nombre,
        'apellido1' => $user->apellido1,
        'apellido2' => $user->apellido2,
        'fechaNacimiento' => $user->fechaNacimiento,
        'exento' => $user->exento,
        'bolsaHora' => $user->bolsaHora,
        'reputacion' => $user->reputacion,
        'administrador' => $user->administrador,
        'email' => $user->email,
        'usuario_id' => $user->usuario_id,
        'numeroVotaciones' => $user->numeroVotaciones,
        'sobreMi' => $user->sobreMi,
        'habilidades' => $user->habilidades,

        'tipo_token' => 'Bearer',
      ]);
    } catch (Exception $error) {
      return response()->json([
        'status_code' => 500,
        'message' => 'Error in Login',
        'error' => $error,
      ]);
    }
  }
}

