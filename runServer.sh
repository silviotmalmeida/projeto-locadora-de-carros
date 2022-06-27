#!/bin/bash

echo "Definindo permissoes da pasta de código-fonte..."
docker container exec locadora-de-carros-laravel chmod 777 -R /root
sleep 1

echo "Iniciando o servidor..."
docker container exec -it locadora-de-carros-laravel bash -c "cd /root/app/app_locadora_de_carros; php artisan serve --host=0.0.0.0 --port=8080;"
