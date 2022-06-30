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
}
