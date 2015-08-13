#!/bin/bash
# Actualización de sistema


clear

blue=`tput setaf 4`
reset=`tput sgr0`

echo
echo "${blue}Descargando actualización...${reset}"
git pull origin master


FILE=./db/msf_dump_`date +"%Y%m%d"`.sql
DBSERVER=127.0.0.1
DATABASE=
USER=
PASS=

echo
echo "${blue}BASE DE DATOS:${reset}"

	read -p '    Host [127.0.0.1]: ' DBSERVER
	if test -z "$DBSERVER"
	then
		DBSERVER=127.0.0.1
	fi

	read -p '    Usuario [root]  : ' USER
	if test -z "$USER"
	then
		USER=root
	fi

	stty -echo
	printf  '    Clave           : '
	read PASS
	stty echo
	printf "\n"

	read -p '    Base de Datos   : ' DATABASE

	echo
	echo "${blue}    Backup de base de datos...${reset}"
	mysqldump -h ${DBSERVER} -e -n --tables --triggers --add-drop-table=FALSE -u${USER} -p${PASS} ${DATABASE} > ${FILE}
	gzip -f $FILE
	rm -f $FILE
	ls -l -h ${FILE}.gz

	echo
	echo "${blue}    Actualización base de datos...${reset}"
	mysql -h ${DBSERVER} -u${USER} -p${PASS} ${DATABASE} < ./db/changes.sql


echo
echo "${blue}Restaurando configuración...${reset}"
cp ./config/index.php ./index.php
cp ./config/database.php ./application/config/database.php

echo
echo "${blue}Realizando limpieza...${reset}"
rm -rf ./.idea/
rm -rf ./db/msf_2015*
rm -rf ./resources/css/*.less

echo
echo
