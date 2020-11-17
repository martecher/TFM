<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
      return response()->json([
        'status_code' => 200,
        'access_token' => $tokenResult,
        'token_type' => 'Bearer',
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

