<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// dependência para permitir recurso de soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory;

    // habilitando o soft delete
    use SoftDeletes;

    // definindo o nome da tabela no BD
    protected $table = 'types';

    // definindo os atributos a serem informados na criação
    // e pesquisáveis pelos clientes
    protected $fillable = [
        'id',
        'name',
        'image',
        'qtd_doors',
        'qtd_seats',
        'air_bag',
        'abs',
        'brand_id'
    ];

    // implementando o relacionamento N-1 com a model brand
    // o belongsTo é utilizado na entidade fraca, ou seja, a tabela que contém a chave estrangeira
    // o primeiro argumento deve ser model da tabela forte
    // o segundo argumento deve ser a fk na tabela fraca
    // o terceiro argumento deve ser a pk na tabela forte
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }

    // definição das validações de cada campo
    // recebe como arqumento o id do registro, se 
    // este argumento será usado na restrição unique em caso de update
    public function rules($id = null)
    {
        return [
            'name' => 'required|unique:types,name,' . $id . '|min:3|max:30',
            'image' => 'file|mimes:png|min:3|max:100',
            'qtd_doors' => 'required|integer|digits_between:1,5',
            'qtd_seats' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
            'brand_id' => 'required|integer|exists:brands,id',
        ];
    }

    // customização das mensagens de erro
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute não pode ser vazio!',
            'name.unique' => 'O nome já está registrado no sistema',
            'name.min' => 'O campo name não pode ter menos de 3 caracteres!',
            'name.max' => 'O campo name não pode ter mais de 30 caracteres!',
            'image.file' => 'O campo image precisa ser um arquivo!',
            'image.mimes' => 'O campo image precisa ser uma imagem do tipo png!',
            'image.min' => 'O campo image não pode ter menos de 3 caracteres!',
            'image.max' => 'O campo image não pode ter mais de 100 caracteres!',
            'integer' => 'O campo :attribute precisa ser um número inteiro!',
            'qtd_doors.digits_between' => 'A quantidade de portas precisa estar entre 1 e 5!',
            'qtd_seats.digits_between' => 'A quantidade de portas precisa estar entre 1 e 20!',
            'boolean' => 'O campo :attribute precisa ser um valor booleano!',
            'brand_id.exists' => 'Marca inválida!',
        ];
    }
}
