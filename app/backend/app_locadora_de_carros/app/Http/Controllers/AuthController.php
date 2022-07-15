<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    // ação responsável por autenticar o usuário e emitir o token de autorização
    public function login(Request $request)
    {
        // obtendo os dados de login e senha pelo request
        $data = $request->all('email', 'password');

        // return print_r($data);

        // autenticando o usuário e recebendo o token de autorização
        $token = auth('api')->attempt($data);

        // se a autenticação for bem sucedida
        if ($token) {

            // retorna o token de autorização
            return response()->json(['token' => $token]);
        }
        // senão
        else {

            // envia mensagem de erro 403
            return response()->json(['msg' => "Email ou Password incorretos"], 403);
        }
    }

    public function logout()
    {

        return 'logout';
    }


    public function refresh()
    {

        return 'refresh';
    }


    public function me()
    {

        return 'me';
    }
}
