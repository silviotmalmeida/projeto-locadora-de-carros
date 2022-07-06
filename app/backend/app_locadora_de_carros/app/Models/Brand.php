<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// dependência para permitir recurso de soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;

    // habilitando o soft delete
    use SoftDeletes;

    // definindo o nome da tabela no BD
    protected $table = 'brands';

    // definindo os atributos a serem informados
    protected $fillable = [
        'name',
        'image'
    ];

    // implementando o relacionamento 1-N com a model type
    // o hasMany é utilizado na entidade forte, ou seja, a tabela que não contém a chave estrangeira
    // o primeiro argumento deve ser model da tabela fraca
    // o segundo argumento deve ser a fk na tabela fraca
    // o terceiro argumento deve ser a pk na tabela forte
    public function types()
    {
        return $this->hasMany('App\Models\Type', 'brand_id', 'id');
    }

    // definição das validações de cada campo
    // recebe como arqumento o id do registro, se 
    // este argumento será usado na restrição unique em caso de update
    public function rules($id = null)
    {
        return [
            'name' => 'required|unique:brands,name,' . $id . '|min:3|max:30',
            'image' => 'file|mimes:png|min:3|max:100'
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
        ];
    }
}
