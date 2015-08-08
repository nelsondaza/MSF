#!/bin/bash
# Actualización de sistema

clear

blue=`tput setaf 4`
reset=`tput sgr0`

echo
echo "${blue}Descargando actualización...${reset}"
git pull origin master

echo
echo "${blue}Actualización base de datos...${reset}"
mysql -u root -p gestor < ./db/changes.sql

echo
echo "${blue}Restaurando configuración...${reset}"
cp ./config/index.php ./index.php
cp ./config/database.php ./application/config/database.php

echo
echo "${blue}Realizando limpieza...${reset}"
rm -rf ./.idea/
rm -rf ./db/msf_2015*
rm -rf ./resources/css/*.less
