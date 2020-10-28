<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserController extends Controller
{
    /**
     * Registro de usuarios con token
     *
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 'denegated',
                'status' => 400,
                'message' => $validator->errors(),
            ], 400);
        }

        $user = new User($request->all());
        $user->email = $request->email;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->password = bcrypt($request->password);

        $token = JWTAuth::fromUser($user);

        $user->save();

        return response()->json([
            'code' => 'success',
            'status' => 201,
            'message' => 'Usuario registrado',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);
        $user = auth()->user();

        try {
            if (!$token) {
                return response()->json([
                    'code' => 'error',
                    'status' => 401,
                    'message' => 'Credenciales inválidas'
                ], 401);
            }
        } catch (JWTException $error) {
            return response()->json([
                'code' => $error,
                'status' => 500,
                'message' => 'No puede crearse el token'
            ], 500);
        }

        return response()->json([
            'code' => 'success',
            'status' => 201,
            'message' => 'Logueado correctamente',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function update(Request $request)
    {
        $user = User::query()->find($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user->email = $request->email;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->avatar = $request->avatar;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);

        $user->save();

        return response()->json([
            'code' => 'success',
            'status' => 201,
            'message' => 'usuario actualizado correctamente',
            'user' => $user
        ], 201);
    }

    public function getAuthenticatedUser()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'code' => 'denegated',
                    'status' => 404,
                    'message' => 'usuario no válido'
                ], 404);
            }
        } catch (TokenExpiredException $error) {
            return response()->json([
                'code' => 'denegated',
                'status' => $error->getStatusCode(),
                'message' => 'el token a expirado'
            ], $error->getStatusCode());
        } catch (TokenInvalidException $error) {
            return response()->json([
                'code' => 'denegated',
                'status' => $error->getStatusCode(),
                'message' => 'el token es inválido'
            ], $error->getStatusCode());
        } catch (JWTException $error) {
            return response()->json([
                'code' => 'denegated',
                'status' => $error->getStatusCode(),
                'message' => 'el token ausente'
            ], $error->getStatusCode());
        }

        return response()->json([
            'code' => 'denegated',
            'status' => 201,
            'message' => 'token actualizado'
        ], 201);
    }
}
