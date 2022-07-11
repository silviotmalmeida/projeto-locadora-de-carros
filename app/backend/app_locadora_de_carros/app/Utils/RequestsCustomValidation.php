<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;

class RequestsCustomValidation
{

    /**
     * Verifica a existência de nomes de atributos errados na requisição
     *
     * @param  Model $model
     * @param  String $listAttr
     * @return Mixed
     */
    public static function getErrorOnAttributeName(Model $model, $listAttr)
    {

        // obtendo a lista de atributos pesquisáveis na model
        $columnArray = $model->getFillable();

        // obtendo a lista de nomes recebidos na requisição
        $attributeNames = explode(',', $listAttr);

        // iterando sobre a lista de nomes
        foreach ($attributeNames as $attribute) {

            // se o atributo estiver vazio
            if ($attribute == '') return '';

            // se o nome não existir na lista de atributos pesquisáveis da model
            if (!in_array($attribute, $columnArray)) {

                // retorna o nome errado
                return $attribute;
            }
        }

        // retorna false
        return false;
    }
}
