<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// dependência para permitir recurso de soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;

    // habilitando o soft delete
    use SoftDeletes;

    // definindo o nome da tabela no BD
    protected $table = 'clients';

    // definindo os atributos a serem informados na criação
    // e pesquisáveis pelos clientes
    protected $fillable = [
        'id',
        'name'
    ];

    // implementando o relacionamento 1-N com a model location
    // o hasMany é utilizado na entidade forte, ou seja, a tabela que não contém a chave estrangeira
    // o primeiro argumento deve ser model da tabela fraca
    // o segundo argumento deve ser a fk na tabela fraca
    // o terceiro argumento deve ser a pk na tabela forte
    public function locations()
    {
        return $this->hasMany('App\Models\Location', 'client_id', 'id');
    }

    // definição das validações de cada campo
    // recebe como arqumento o id do registro, se 
    // este argumento será usado na restrição unique em caso de update
    public function rules($id = null)
    {
        return [
            'name' => 'required|unique:clients,name,' . $id . '|min:3|max:30'
        ];
    }

    // customização das mensagens de erro
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute não pode ser vazio!',
            'name.unique' => 'O nome já está registrado no sistema',
            'name.min' => 'O campo name não pode ter menos de 3 caracteres!',
            'name.max' => 'O campo name não pode ter mais de 30 caracteres!'
        ];
    }
}
