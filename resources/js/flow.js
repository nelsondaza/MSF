/**
 * Created by nelson.daza on 30/03/2015.
 */
'use strict';

// Tal vez console no exista
// Esta forma de escribirlo hace que mi editor resalte de forma correcta
if( typeof(window.console) == 'undefined' || !window.console )
	window.console = window.console || {};

// La función log es la más importante
console.log = console.log || function(){};
// La función debug es la que más uso (aunque para el ejemplo no aplique)
console.debug = console.debug || console.log;

// Las función group, groupCollapsed y groupEnd ayudan a ordenar pero pueden no existir
console.groupCollapsed = console.groupCollapsed || function(){};
console.group = console.group || function(){};
console.groupEnd = console.groupEnd || function(){};


/**
 * La función shape será la que me permita agrupar, colorear y mostrar plegado
 *
 * @param args      Object  Argumentos pasados a la función "nativa"
 * @param scope     string  Nombre del grupo
 * @param color     string  Hexadecimal (RGB) -> '#FFFFFF'
 * @param collapsed bool    Si el grupo se colapsa o se expande
 */
console.shape = function( args, scope, color, collapsed ){

	if( window.msc_production )
		return;

	// Contador de elementos a mostrar
	var start = 0;
	// Si no hay color se usa gris
	color = color || '#999';

	// args debe ser un objeto "arguments" o un Array que es lo más cercano
	if( typeof( args ) != 'object' )
		args = [args];

	// Si solo que quiere mostrar un elemento no tiene sentido agruparlo,
	// solo se muestra como un log con color
	if( args.length == 1 && typeof( args[start] ) != 'object') {
		// Si scope existe se muestra como una llave
		if( scope )
			console.log('%c' + scope + ': ' + args[start], 'color: ' + color);
		else
			console.log('%c' + args[start], 'color: ' + color);
		return;
	}

	if( scope ) {
		// Si scope existe y el primer elemento a mostrar
		// no es un objeto, se puede agregar como texto,
		// solo para que se vea mejor
		var append = ( typeof( args[start] ) != 'object' ? ' ' + args[start++] : '' );

		if( collapsed )
			console.groupCollapsed('%c' + scope + ':' + append, 'color: ' + color);
		else
			console.group('%c' + scope + ':' + append, 'color: ' + color);
	}

	for( ;start < args.length; start ++ ) {
		// Mostrando los elementos restanter con la
		// forma normal de log
		console.log( args[start] );
	}

	// Cerrando el grupo (Solo si es necesario)
	if( scope )
		console.groupEnd( );

};

console.app = function(){
	console.shape(arguments, 'APP', '#6F4395', true);
};
console.service = function(){
	console.shape(arguments, 'SRV', '#6F9995', true);
};
console.controller = function(){
	console.shape(arguments, 'CTR', '#9F99FF', true);
};
console.user = function(){
	console.shape(arguments, 'USR', '#6F99FF', true);
};
console.show = function(){
	console.shape(arguments, 'SHW', '#FF4345', true);
};

var app = angular.module('flow',['ngAnimate', 'msc.controllers', 'msc.services', 'msc.directives']);
console.app('APP msc INIT');

app.run(['$rootScope','$location','AuthService', function ( $rootScope, $location, AuthService ) {
	console.app('RUN');
	AuthService.init();
}]);

