<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// dependência para permitir recurso de soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;

    // habilitando o soft delete
    use SoftDeletes;

    // definindo o nome da tabela no BD
    protected $table = 'locations';

    // definindo os atributos a serem informados
    protected $fillable = [
        'id',
        'initial_date',
        'final_date_estimated',
        'final_date',
        'daily_value',
        'initial_km',
        'final_km',
        'client_id',
        'car_id'
    ];

    // implementando o relacionamento N-1 com a model client
    // o belongsTo é utilizado na entidade fraca, ou seja, a tabela que contém a chave estrangeira
    // o primeiro argumento deve ser model da tabela forte
    // o segundo argumento deve ser a fk na tabela fraca
    // o terceiro argumento deve ser a pk na tabela forte
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }

    // implementando o relacionamento N-1 com a model car
    // o belongsTo é utilizado na entidade fraca, ou seja, a tabela que contém a chave estrangeira
    // o primeiro argumento deve ser model da tabela forte
    // o segundo argumento deve ser a fk na tabela fraca
    // o terceiro argumento deve ser a pk na tabela forte
    public function car()
    {
        return $this->belongsTo('App\Models\Car', 'car_id', 'id');
    }

    // definição das validações de cada campo
    public function rules()
    {
        return [
            'initial_date' => 'required|date',
            'final_date_estimated' => 'required|date',
            'final_date' => 'required|date',
            'daily_value' => 'required|numeric',
            'initial_km' => 'required|integer',
            'final_km' => 'required|integer',
            'client_id' => 'required|integer|exists:clients,id',
            'car_id' => 'required|integer|exists:cars,id'
        ];
    }

    // customização das mensagens de erro
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute não pode ser vazio!',
            'date' => 'O campo :attribute precisa ser uma data!',
            'numeric' => 'O campo :attribute precisa ser um número!',
            'integer' => 'O campo :attribute precisa ser um número inteiro!',
            'client_id.exists' => 'Cliente inválido!',
            'car_id.exists' => 'Carro inválido!'
        ];
    }
}
