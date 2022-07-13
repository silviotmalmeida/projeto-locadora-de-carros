<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// importando as models
use App\Models\Car;
use App\Models\Type;
use App\Models\Location;

// biblioteca customizada para validação das requisições
use App\Utils\RequestsCustomValidation;

class CarController extends Controller
{

    // foi adicionado o construct para permitir a injeção do model
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct(Car $car)
    {
        // injetando a model ao controler
        $this->car = $car;
    }

    /**
     * Display a listing of the resource.
     * Exemplo de consulta:
     * ...api/car?atr_car=atr1,atr2,...&atr_location=atr1,atr2,...&atr_type=atr1,atr2,...&filter=atr1:op1:val1;atr2:op2:val2;...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // se existir o atributo atr_type na requisição
        if ($request->has('atr_type')) {

            // obtendo erros em nomes de atributos, se existirem
            $bad_attr = RequestsCustomValidation::getErrorOnAttributeName(new Type, $request->atr_type);

            // se existir nome de atributo errado
            if ($bad_attr !== false) {

                // envia mensagem de erro 404
                return response()->json(['msg' => "Atributo '$bad_attr' não disponível na tabela types. (Disponíveis: " .
                    implode(',', (new Type)->getFillable()) . ")"], 404);
            }

            // obtendo os parâmetros a partir da requisição
            // foi incluído o id para permitir o relacionamento
            $atr_type = 'type:id,' . $request->atr_type;
        }
        // senão
        else {

            // considera todos os atributos pesquisáveis de type
            // foi incluído o id para permitir o relacionamento
            $atr_type = 'type:id,' . implode(',', (new Type)->getFillable());
        }
        // montando a consulta, filtrando os atributos de type
        $cars = $this->car->with($atr_type);

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
            // foi incluído o car_id para permitir o relacionamento
            $atr_location = 'locations:car_id,' . $request->atr_location;
        }
        // senão
        else {

            // considera todos os atributos pesquisáveis de location
            // foi incluído o car_id para permitir o relacionamento
            $atr_location = 'locations:car_id,' . implode(',', (new Location)->getFillable());
        }
        // montando a consulta, filtrando os atributos de location
        $cars = $cars->with($atr_location);

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
                    if (!in_array($atr, $this->car->getFillable())) {

                        // envia mensagem de erro 404
                        return response()->json(['msg' => "Atributo '$atr' não disponível na tabela cars. (Disponíveis: " .
                            implode(',', (new Car)->getFillable()) . ")"], 404);
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
                    $cars = $cars->where($atr, $operator, $value);
                }
            }
        }

        // se existir o atributo atr_car na requisição
        if ($request->has('atr_car')) {

            // obtendo erros em nomes de atributos, se existirem
            $bad_attr = RequestsCustomValidation::getErrorOnAttributeName(new Car, $request->atr_car);

            // se existir nome de atributo indisponível ou vazio
            if ($bad_attr !== false) {

                return response()->json(['msg' => "Atributo '$bad_attr' não disponível na tabela cars. (Disponíveis: " .
                    implode(',', (new Car)->getFillable()) . ")"], 404);
            }

            // obtendo os parâmetros
            // foi incluído o type_id para permitir o relacionamento
            $atr_car = 'type_id,' . $request->atr_car;
        }
        // senão
        else {

            // considera todos os atributos pesquisáveis de car
            $atr_car = implode(',', (new Car)->getFillable());
        }
        // montando a consulta, filtrando os atributos de car
        $cars = $cars->selectRaw($atr_car)->get();

        // retornando os dados e o status 200
        return response()->json($cars, 200);
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
        $request->validate($this->car->rules(), $this->car->feedback());

        // insere os dados no BD
        $car = $this->car->create([
            'plate' => $request->plate,
            'km' => $request->km,
            'available' => $request->available,
            'type_id' => $request->type_id
        ]);

        // retornando o objeto criado e o status 201
        return response()->json($car, 201);
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
        $car = $this->car->with('type')->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($car === null) return response()->json(['msg' => 'Recurso solicitado não existe.'], 404);

        // retornando o objeto e o status 200
        return response()->json($car, 200);
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
        $car = $this->car->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($car === null) return response()->json(['msg' => 'Não foi possível realizar a atualização. Recurso solicitado não existe.'], 404);

        // se a requisição for PATCH, somente alguns atributos serão validados
        if ($request->method() === 'PATCH') {

            // inicializando o array de validações customizado
            $validationRules = array();

            // iterando sobre todas as regras definidas no model
            foreach ($car->rules($id) as $key => $value) {

                // se o atributo da regra estiver presente no request, considera-o
                if (array_key_exists($key, $request->all())) {

                    $validationRules[$key] = $value;
                }
            }

            // validando os dados recebidos do formulário
            $request->validate($validationRules, $this->car->feedback());
        }
        // se for PUT, valida todos os atributos
        else {

            // validando os dados recebidos do formulário
            $request->validate($this->car->rules($id), $this->car->feedback());
        }

        // atualizando o objeto com os dados proveninetes da requisição
        $car->fill($request->all());

        // atualiza os dados no BD
        $car->save();

        // retornando o objeto atualizado e o status 200
        return response()->json($car, 200);
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
        $car = $this->car->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($car === null) return response()->json(['msg' => 'Não foi possível realizar a exclusão. Recurso solicitado não existe.'], 404);

        // remove os dados no BD
        $car->delete();

        // retornando mensagem de sucesso e o status 200
        return response()->json(['msg' => 'Registro excluído com sucesso!'], 200);
    }
}
