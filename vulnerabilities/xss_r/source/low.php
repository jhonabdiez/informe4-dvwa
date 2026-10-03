<?php

// Cabecera de proteccion del navegador habilitada (defensa en profundidad)
header ("X-XSS-Protection: 1; mode=block");

// Is there any input?
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
	// Codificacion de salida segun el contexto HTML
	$name = htmlspecialchars( $_GET[ 'name' ], ENT_QUOTES, 'UTF-8' );

	// Feedback for end user
	$html .= '<pre>Hello ' . $name . '</pre>';
}

?>
