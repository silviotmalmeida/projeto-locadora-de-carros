<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {

            // colunas padrão do laravel
            $table->id();
            $table->timestamps();

            // colunas personalizadas da tabela
            $table->string('plate', 10)->unique();
            $table->integer('km');
            $table->boolean('available');

            // configuração da chave estrangeira para a tabela types (um para muitos):
            //// criação da coluna
            $table->unsignedBigInteger('type_id');
            //// adição da restrição de integridade referencial
            $table->foreign('type_id')->references('id')->on('types');

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
        Schema::dropIfExists('cars');
    }
}
