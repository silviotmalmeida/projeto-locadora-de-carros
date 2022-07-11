<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// dependência para permitir recurso de soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory;

    // habilitando o soft delete
    use SoftDeletes;

    // definindo o nome da tabela no BD
    protected $table = 'cars';

    // definindo os atributos a serem informados na criação
    // e pesquisáveis pelos clientes
    protected $fillable = [
        'id',
        'plate',
        'km',
        'available',
        'type_id'
    ];

    // implementando o relacionamento N-1 com a model type
    // o belongsTo é utilizado na entidade fraca, ou seja, a tabela que contém a chave estrangeira
    // o primeiro argumento deve ser model da tabela forte
    // o segundo argumento deve ser a fk na tabela fraca
    // o terceiro argumento deve ser a pk na tabela forte
    public function type()
    {
        return $this->belongsTo('App\Models\Type', 'type_id', 'id');
    }

    // definição das validações de cada campo
    // recebe como arqumento o id do registro, se 
    // este argumento será usado na restrição unique em caso de update
    public function rules($id = null)
    {
        return [
            'plate' => 'required|unique:cars,plate,' . $id . '|min:3|max:10',
            'km' => 'required|integer',
            'avaiable' => 'required|boolean',
            'type_id' => 'required|integer|exists:types,id',
        ];
    }

    // customização das mensagens de erro
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute não pode ser vazio!',
            'plate.unique' => 'A placa já está registrada no sistema',
            'plate.min' => 'O campo placa não pode ter menos de 3 caracteres!',
            'plate.max' => 'O campo placa não pode ter mais de 10 caracteres!',
            'integer' => 'O campo :attribute precisa ser um número inteiro!',
            'boolean' => 'O campo :attribute precisa ser um valor booleano!',
            'type_id.exists' => 'Modelo inválido!',
        ];
    }
}
