<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$target = $_REQUEST[ 'ip' ];

	// Validar que la entrada sea una direccion IP legitima
	if( filter_var( $target, FILTER_VALIDATE_IP ) === false ) {
		$html .= '<pre>Direccion IP invalida.</pre>';
	}
	else {
		// Escapar el argumento: neutraliza metacaracteres de shell (; && | etc.)
		$arg = escapeshellarg( $target );

		// Determine OS and execute the ping command.
		if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
			// Windows
			$cmd = shell_exec( 'ping  ' . $arg );
		}
		else {
			// *nix
			$cmd = shell_exec( 'ping  -c 4 ' . $arg );
		}

		// Feedback for the end user
		$html .= "<pre>{$cmd}</pre>";
	}
}

?>
