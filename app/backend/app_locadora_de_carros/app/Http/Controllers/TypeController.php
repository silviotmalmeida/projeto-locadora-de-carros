<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

// dependência para gerenciamento do storage
use Illuminate\Support\Facades\Storage;

class TypeController extends Controller
{

    // foi adicionado o construct para permitir a injeção do model
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Type $type)
    {
        // injetando a model ao controler
        $this->type = $type;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // obtendo os dados do BD
        $types = $this->type->all();

        // retornando os dados e o status 200
        return response()->json($types, 200);
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
        $request->validate($this->type->rules(), $this->type->feedback());

        // obtendo os dados da imagem submetida na requisição
        $img = $request->file('image');

        // salvando o arquivo de imagem na pasta app/storage/app/public/images/types
        // e obtendo o seu respectivo nome
        $img_urn = $img->store('images/types', 'public');

        // insere os dados no BD
        $type = $this->type->create([
            'name' => $request->name,
            'image' => $img_urn,
            'qtd_doors' => $request->qtd_doors,
            'qtd_seats' => $request->qtd_seats,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
            'brand_id' => $request->brand_id
        ]);

        // retornando o objeto criado e o status 201
        return response()->json($type, 201);
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
        $type = $this->type->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($type === null) return response()->json(['msg' => 'Recurso solicitado não existe.'], 404);

        // retornando o objeto e o status 200
        return response()->json($type, 200);
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
        $type = $this->type->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($type === null) return response()->json(['msg' => 'Não foi possível realizar a atualização. Recurso solicitado não existe.'], 404);

        // se a requisição for PATCH, somente alguns atributos serão validados
        if ($request->method() === 'PATCH') {

            // inicializando o array de validações customizado
            $validationRules = array();

            // iterando sobre todas as regras definidas no model
            foreach ($type->rules($id) as $key => $value) {

                // se o atributo da regra estiver presente no request, considera-o
                if (array_key_exists($key, $request->all())) {

                    $validationRules[$key] = $value;
                }
            }

            // validando os dados recebidos do formulário
            $request->validate($validationRules, $this->type->feedback());
        }
        // se for PUT, valida todos os atributos
        else {

            // validando os dados recebidos do formulário
            $request->validate($this->type->rules($id), $this->type->feedback());
        }

        // se existir o atributo image na requisição
        if ($request->file('image')) {

            // apagando a imagem cadastrada anteriormente
            Storage::disk('public')->delete($type->image);

            // obtendo os dados da nova imagem submetida na requisição
            $img = $request->file('image');

            // salvando o arquivo da nova imagem na pasta app/storage/app/public/images/types
            // e obtendo o seu respectivo nome
            $img_urn = $img->store('images/types', 'public');

            // atualizando o atributo do objeto para posterior update
            $type->image = $img_urn;
        }

        // se existir o atributo name na requisição
        if ($request->name) {

            // atualizando o atributo do objeto para posterior update
            $type->name = $request->name;
        }

        // se existir o atributo qtd_doors na requisição
        if ($request->qtd_doors) {

            // atualizando o atributo do objeto para posterior update
            $type->qtd_doors = $request->qtd_doors;
        }

        // se existir o atributo qtd_seats na requisição
        if ($request->qtd_seats) {

            // atualizando o atributo do objeto para posterior update
            $type->qtd_seats = $request->qtd_seats;
        }

        // se existir o atributo air_bag na requisição
        if ($request->air_bag) {

            // atualizando o atributo do objeto para posterior update
            $type->air_bag = $request->air_bag;
        }

        // se existir o atributo abs na requisição
        if ($request->abs) {

            // atualizando o atributo do objeto para posterior update
            $type->abs = $request->abs;
        }

        // se existir o atributo brand_id na requisição
        if ($request->brand_id) {

            // atualizando o atributo do objeto para posterior update
            $type->brand_id = $request->brand_id;
        }

        // atualiza os dados no BD
        $type->update();

        // retornando o objeto atualizado e o status 200
        return response()->json($type, 200);
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
        $type = $this->type->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($type === null) return response()->json(['msg' => 'Não foi possível realizar a exclusão. Recurso solicitado não existe.'], 404);

        // apagando a imagem cadastrada
        Storage::disk('public')->delete($type->image);

        // remove os dados no BD
        $type->delete();

        // retornando mensagem de sucesso e o status 200
        return response()->json(['msg' => 'Registro excluído com sucesso!'], 200);
    }
}
