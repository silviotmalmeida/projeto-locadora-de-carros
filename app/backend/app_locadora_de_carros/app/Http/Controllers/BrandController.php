<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

// dependência para gerenciamento do storage
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{

    // foi adicionado o construct para permitir a injeção do model
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Brand $brand)
    {
        // injetando a model ao controler
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // obtendo os dados do BD
        $brands = $this->brand->with('types')->get();

        // retornando os dados e o status 200
        return response()->json($brands, 200);
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
        $request->validate($this->brand->rules(), $this->brand->feedback());

        // se existir o atributo image na requisição
        if ($request->file('image')) {

            // obtendo os dados da imagem submetida na requisição
            $img = $request->file('image');

            // salvando o arquivo de imagem na pasta app/storage/app/public/images/brands
            // e obtendo o seu respectivo nome
            $img_urn = $img->store('images/brands', 'public');

            // insere os dados no BD
            $brand = $this->brand->create([
                'name' => $request->name,
                'image' => $img_urn
            ]);
        }
        // senão
        else {

            // insere os dados no BD
            $brand = $this->brand->create([
                'name' => $request->name
            ]);
        }

        // retornando o objeto criado e o status 201
        return response()->json($brand, 201);
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
        $brand = $this->brand->with('types')->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($brand === null) return response()->json(['msg' => 'Recurso solicitado não existe.'], 404);

        // retornando o objeto e o status 200
        return response()->json($brand, 200);
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
        $brand = $this->brand->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($brand === null) return response()->json(['msg' => 'Não foi possível realizar a atualização. Recurso solicitado não existe.'], 404);

        // se a requisição for PATCH, somente alguns atributos serão validados
        if ($request->method() === 'PATCH') {

            // inicializando o array de validações customizado
            $validationRules = array();

            // iterando sobre todas as regras definidas no model
            foreach ($brand->rules($id) as $key => $value) {

                // se o atributo da regra estiver presente no request, considera-o
                if (array_key_exists($key, $request->all())) {

                    $validationRules[$key] = $value;
                }
            }

            // validando os dados recebidos do formulário
            $request->validate($validationRules, $this->brand->feedback());
        }
        // se for PUT, valida todos os atributos
        else {

            // validando os dados recebidos do formulário
            $request->validate($this->brand->rules($id), $this->brand->feedback());
        }

        // se existir o atributo image na requisição
        if ($request->file('image')) {

            // apagando a imagem cadastrada anteriormente
            Storage::disk('public')->delete($brand->image);

            // obtendo os dados da nova imagem submetida na requisição
            $img = $request->file('image');

            // salvando o arquivo da nova imagem na pasta app/storage/app/public/images/brands
            // e obtendo o seu respectivo nome
            $img_urn = $img->store('images/brands', 'public');
        }
        // senão
        else {

            // preserva a imagem
            $img_urn = $brand->image;
        }

        // atualizando o objeto com os dados proveninetes da requisição
        $brand->fill($request->all());

        // ajustando o atributo image
        $brand->image = $img_urn;

        // atualiza os dados no BD
        $brand->save();

        // retornando o objeto atualizado e o status 200
        return response()->json($brand, 200);
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
        $brand = $this->brand->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if ($brand === null) return response()->json(['msg' => 'Não foi possível realizar a exclusão. Recurso solicitado não existe.'], 404);

        // apagando a imagem cadastrada
        // como o softdelete está ativo, na exclusão as imagens serão mantidas
        // Storage::disk('public')->delete($brand->image);

        // remove os dados no BD
        $brand->delete();

        // retornando mensagem de sucesso e o status 200
        return response()->json(['msg' => 'Registro excluído com sucesso!'], 200);
    }
}
