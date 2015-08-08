#!/usr/bin/env bash
# Actualización de sistema

ECHO Descargando actualización...
git pull origin master

ECHO Actualización base de datos...
mysql -u root -p gestor < ./db/changes.sql

ECHO Restaurando configuración...
cp ./config/index.php ./index.php
cp ./config/database.php ./application/config/database.php

ECHO Realizando limpieza...
rm -rf ./.idea/
rm -rf ./db/msf_2015*
rm -rf ./resources/css/*.less
