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

    // definindo os atributos a serem informados
    protected $fillable = [
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
}
