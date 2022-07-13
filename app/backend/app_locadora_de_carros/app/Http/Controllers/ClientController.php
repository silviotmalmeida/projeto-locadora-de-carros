<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// importando as models
use App\Models\Client;
use App\Models\Location;

// biblioteca customizada para validação das requisições
use App\Utils\RequestsCustomValidation;

class ClientController extends Controller
{

    // foi adicionado o construct para permitir a injeção do model
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct(Client $client)
    {
        // injetando a model ao controler
        $this->client = $client;
    }

    /**
     * Display a listing of the resource.
     * Exemplo de consulta:
     * ...api/client?atr_client=atr1,atr2,...&atr_location=atr1,atr2,...&filter=atr1:op1:val1;atr2:op2:val2;...
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // se existir o atributo atr_location na requisição
        if ($request->has('atr_location')) {

            // obtendo erros em nomes de atributos, se existirem
            $bad_attr = RequestsCustomValidation::getErrorOnAttributeName(new Location, $request->atr_location);

            // se existir nome de atributo errado
            if ($bad_attr !== false) {

                // envia mensagem de erro 404
                return response()->json(['msg' => "Atributo '$bad_attr' não disponível na tabela locations. (Disponíveis: " .
                    implode(',', (new Location)->getFillable()) . ")"], 404);
            }

            // obtendo os parâmetros a partir da requisição
            // foi incluído o client_id para permitir o relacionamento
            $atr_location = 'locations:client_id,' . $request->atr_location;
        }
        // senão
        else {

            // considera todos os atributos pesquisáveis de location
            // foi incluído o client_id para permitir o relacionamento
            $atr_location = 'locations:client_id,' . implode(',', (new Location)->getFillable());
        }
        // montando a consulta, filtrando os atributos de location
        $clients = $this->client->with($atr_location);

        // se existir o atributo filter na requisição
        if ($request->has('filter') and $request->filter != '') {

            // obtendo os parâmetros dos filtros
            $array_filters = explode(';', $request->filter);

            // iterando sobre cada filtro
            foreach ($array_filters as $filter) {

                // obtendo os parâmetros do filtro
                $array_filter = explode(':', $filter);

                // se existirem exatamente três parâmetros por filtro
                if (count($array_filter) == 3) {

                    // obtendo os fatores
                    $atr = $array_filter[0];
                    $operator = strtolower($array_filter[1]);
                    $value = $array_filter[2];

                    // se o atributo não for disponível
                    if (!in_array($atr, $this->client->getFillable())) {

                        // envia mensagem de erro 404
                        return response()->json(['msg' => "Atributo '$atr' não disponível na tabela clients. (Disponíveis: " .
                            implode(',', (new Client)->getFillable()) . ")"], 404);
                    }

                    // se o operador não for válido
                    if (!in_array($operator, ['=', '>', '<', '<>', '>=', '<=', 'like'])) {

                        // envia mensagem de erro 404
                        return response()->json(['msg' => "Operador '$operator' incorreto. (Possibilidades: =, >, <, <>, >=, <=, like)"], 404);
                    }

                    if ($value == '') {

                        // envia mensagem de erro 404
                        return response()->json(['msg' => "Valor da filtragem não pode ser vazio."], 404);
                    }

                    // montando a consulta, aplicando o filtro
                    $clients = $clients->where($atr, $operator, $value);
                }
            }
        }

        // se existir o atributo atr_client na requisição
        if ($request->has('atr_client')) {

            // obtendo erros em nomes de atributos, se existirem
            $bad_attr = RequestsCustomValidation::getErrorOnAttributeName(new Client, $request->atr_client);

            // se existir nome de atributo indisponível ou vazio
            if ($bad_attr !== false) {

                return response()->json(['msg' => "Atributo '$bad_attr' não disponível na tabela clients. (Disponíveis: " .
                    implode(',', (new Client)->getFillable()) . ")"], 404);
            }

            // obtendo os parâmetros
            // foi incluído o id para permitir o relacionamento
            $atr_client = 'id,' . $request->atr_client;
        }
        // senão
        else {

            // considera todos os atributos pesquisáveis de client
            $atr_client = implode(',', (new Client)->getFillable());
        }
        // montando a consulta, filtrando os atributos de client
        $clients = $clients->selectRaw($atr_client)->get();

        // retornando os dados e o status 200
        return response()->json($clients, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validando os dados recebidos do formulário
        $request->validate($this->client->rules(), $this->client->feedback());

        // insere os dados no BD
        $client = $this->client->create([
            'name' => $request->name
        ]);

        // retornando o objeto criado e o status 201
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // consultando os dados no BD
        $client = $this->client->with('locations')->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($client === null) return response()->json(['msg' => 'Recurso solicitado não existe.'], 404);

        // retornando o objeto e o status 200
        return response()->json($client, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // consultando os dados no BD
        $client = $this->client->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($client === null) return response()->json(['msg' => 'Não foi possível realizar a atualização. Recurso solicitado não existe.'], 404);

        // se a requisição for PATCH, somente alguns atributos serão validados
        if ($request->method() === 'PATCH') {

            // inicializando o array de validações customizado
            $validationRules = array();

            // iterando sobre todas as regras definidas no model
            foreach ($client->rules($id) as $key => $value) {

                // se o atributo da regra estiver presente no request, considera-o
                if (array_key_exists($key, $request->all())) {

                    $validationRules[$key] = $value;
                }
            }

            // validando os dados recebidos do formulário
            $request->validate($validationRules, $this->client->feedback());
        }
        // se for PUT, valida todos os atributos
        else {

            // validando os dados recebidos do formulário
            $request->validate($this->client->rules($id), $this->client->feedback());
        }

        // atualizando o objeto com os dados proveninetes da requisição
        $client->fill($request->all());

        // atualiza os dados no BD
        $client->save();

        // retornando o objeto atualizado e o status 200
        return response()->json($client, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // consultando os dados no BD
        $client = $this->client->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($client === null) return response()->json(['msg' => 'Não foi possível realizar a exclusão. Recurso solicitado não existe.'], 404);

        // remove os dados no BD
        $client->delete();

        // retornando mensagem de sucesso e o status 200
        return response()->json(['msg' => 'Registro excluído com sucesso!'], 200);
    }
}
