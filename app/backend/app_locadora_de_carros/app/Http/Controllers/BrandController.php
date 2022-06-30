<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    // definição das validações de cada campo
    private $validationRules =
    [
        'name' => 'required|unique:brands|min:3|max:30',
        'image' => 'required|min:3|max:100'
    ];

    // customização das mensagens de erro
    private $validationMessages =
    [
        'required' => 'O campo não pode ser vazio!',
        'name.unique' => 'O nome já está registrado no sistema',
        'name.min' => 'O campo não pode ter menos de 3 caracteres!',
        'name.max' => 'O campo não pode ter mais de 30 caracteres!',
        'image.min' => 'O campo não pode ter menos de 3 caracteres!',
        'image.max' => 'O campo não pode ter mais de 100 caracteres!',
    ];

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
        $brands = $this->brand->all();

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
        $request->validate($this->validationRules, $this->validationMessages);

        // insere os dados no BD
        $brand = $this->brand->create($request->all());

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
        $brand = $this->brand->find($id);

        // se a consulta não retornar nenhum registro, envia mensagem de erro 404
        if($brand === null) return response()->json(['msg' => 'Recurso solicitado não existe.'], 404);

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
        if($brand === null) return response()->json(['msg' => 'Não foi possível realizar a atualização. Recurso solicitado não existe.'], 404);

        // atualiza os dados no BD
        $brand->update($request->all());

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
        if($brand === null) return response()->json(['msg' => 'Não foi possível realizar a exclusão. Recurso solicitado não existe.'], 404);

        // remove os dados no BD
        $brand->delete();

        // retornando mensagem de sucesso e o status 200
        return response()->json(['msg' => 'Registro excluído com sucesso!'], 200);
    }
}
