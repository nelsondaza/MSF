#!/usr/bin/env bash
# Actualización de sistema

echo -e "\e[94mDescargando actualización..."
git pull origin master

echo -e "\e[36mActualización base de datos..."
mysql -u root -p gestor < ./db/changes.sql

echo -e "\e[94mRestaurando configuración..."
cp ./config/index.php ./index.php
cp ./config/database.php ./application/config/database.php

echo -e "\e[36mRealizando limpieza..."
rm -rf ./.idea/
rm -rf ./db/msf_2015*
rm -rf ./resources/css/*.less
