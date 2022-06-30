<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {

            // colunas padrão do laravel
            $table->id();
            $table->timestamps();

            // colunas personalizadas da tabela
            $table->dateTime('initial_date');
            $table->dateTime('final_date_estimated');
            $table->dateTime('final_date');
            $table->float('daily_value', 8, 2);
            $table->integer('initial_km');
            $table->integer('final_km');

            // configuração da chave estrangeira para a tabela clients (um para muitos):
            //// criação da coluna
            $table->unsignedBigInteger('client_id');
            //// adição da restrição de integridade referencial
            $table->foreign('client_id')->references('id')->on('clients');

            // configuração da chave estrangeira para a tabela cars (um para muitos):
            //// criação da coluna
            $table->unsignedBigInteger('car_id');
            //// adição da restrição de integridade referencial
            $table->foreign('car_id')->references('id')->on('cars');

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
        Schema::dropIfExists('locations');
    }
}
