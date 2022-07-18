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
            return response()->json(['msg' => "Email ou Password incorretos!"], 403);
        }
    }

    // ação responsável por expirar o token de autorização
    public function logout()
    {
        // expirando o token
        auth('api')->logout();

        // envia mensagem de sucesso
        return response()->json(['msg' => "Logout realizado com sucesso!"]);
    }

    // ação responsável por gerar um novo token de autorização
    public function refresh()
    {

        // gerando o token atualizado
        $token = auth('api')->refresh();

        // retornando o token atualizado
        return response()->json(['token' => $token]);
    }

    // ação responsável por obter dados do usuário autenticado
    public function me()
    {

        // retornando os dados do usuário autenticado
        return response()->json(auth()->user());
    }
}
