<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	$uploaded  = $_FILES[ 'uploaded' ];
	$allowed   = array( 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif' );
	$max_bytes = 100000;

	// Extension en minusculas
	$ext = strtolower( pathinfo( $uploaded[ 'name' ], PATHINFO_EXTENSION ) );

	// Tipo MIME real, leido del contenido del archivo (no del encabezado del cliente)
	$finfo = new finfo( FILEINFO_MIME_TYPE );
	$mime  = $finfo->file( $uploaded[ 'tmp_name' ] );

	// Validaciones: lista blanca de extension, MIME coherente, tamano e imagen real
	if( !array_key_exists( $ext, $allowed )
	    || $allowed[ $ext ] !== $mime
	    || $uploaded[ 'size' ] > $max_bytes
	    || getimagesize( $uploaded[ 'tmp_name' ] ) === false ) {
		$html .= '<pre>Your image was not uploaded. Archivo rechazado.</pre>';
	}
	else {
		// Nombre aleatorio generado por el servidor
		$new_name    = bin2hex( random_bytes( 16 ) ) . '.' . $ext;
		$target_path = DVWA_WEB_PAGE_TO_ROOT . 'hackable/uploads/' . $new_name;

		if( !move_uploaded_file( $uploaded[ 'tmp_name' ], $target_path ) ) {
			$html .= '<pre>Your image was not uploaded.</pre>';
		}
		else {
			$html .= "<pre>{$target_path} succesfully uploaded!</pre>";
		}
	}
}

?>
