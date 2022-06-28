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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // obtendo os dados do BD
        $brands = Brand::all();

        // retornando os dados
        return $brands;
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
        $brand = Brand::create($request->all());

        // retornando o objeto criado
        return $brand;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        // retornando o objeto
        return $brand;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        // atualiza os dados no BD
        $brand->update($request->all());

        // retornando o objeto atualizado
        return $brand;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        // remove os dados no BD
        $brand->delete();

        // retornando mensagem de sucesso
        return ['msg' => 'Registro removido com sucesso!'];
    }
}
