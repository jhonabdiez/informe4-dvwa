<?php

if( isset( $_SESSION [ 'id' ] ) ) {
	// Get input
	$id = $_SESSION[ 'id' ];

	// Defensa en profundidad: el user_id solo debe ser numerico
	if( !ctype_digit( (string)$id ) ) {
		$html .= "<pre>ID invalido. Debe ser numerico.</pre>";
	} else {
		switch ($_DVWA['SQLI_DB']) {
			case MYSQL:
				// Consulta preparada con LIMIT fijo en la estructura
				$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT first_name, last_name FROM users WHERE user_id = ? LIMIT 1");
				mysqli_stmt_bind_param($stmt, "i", $id);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);

				while( $row = mysqli_fetch_assoc( $result ) ) {
					$first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
					$last  = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');
					$html .= "<pre>ID: " . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . "<br />First name: {$first}<br />Surname: {$last}</pre>";
				}

				((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
				break;
			case SQLITE:
				global $sqlite_db_connection;

				$stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = :id LIMIT 1");
				$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
				try {
					$results = $stmt->execute();
				} catch (Exception $e) {
					echo 'Caught exception';
					exit();
				}

				if ($results) {
					while ($row = $results->fetchArray()) {
						$first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
						$last  = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');
						$html .= "<pre>ID: " . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . "<br />First name: {$first}<br />Surname: {$last}</pre>";
					}
				}
				break;
		}
	}
}

?>
