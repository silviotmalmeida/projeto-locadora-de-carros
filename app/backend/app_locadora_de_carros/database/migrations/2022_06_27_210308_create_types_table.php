<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {

            // colunas padrão do laravel
            $table->id();
            $table->timestamps();

            // colunas personalizadas da tabela
            $table->string('name', 30)->unique();
            $table->string('image', 100)->nullable();
            $table->integer('qtd_doors');
            $table->integer('qtd_seats');
            $table->boolean('air_bag');
            $table->boolean('abs');

            // configuração da chave estrangeira para a tabela brands (um para muitos):
            //// criação da coluna
            $table->unsignedBigInteger('brand_id');
            //// adição da restrição de integridade referencial
            $table->foreign('brand_id')->references('id')->on('brands');

            // coluna para permitir o soft delete
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types');
    }
}
