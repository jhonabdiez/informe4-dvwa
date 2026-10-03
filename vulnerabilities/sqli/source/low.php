<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
	// Get input
	$id = $_REQUEST[ 'id' ];

	// Defensa en profundidad: el user_id solo debe ser numerico
	if( !ctype_digit( (string)$id ) ) {
		$html .= "<pre>ID invalido. Debe ser numerico.</pre>";
	} else {
		switch ($_DVWA['SQLI_DB']) {
			case MYSQL:
				// Consulta preparada: la estructura SQL se fija antes de recibir el dato
				$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT first_name, last_name FROM users WHERE user_id = ?");
				mysqli_stmt_bind_param($stmt, "i", $id);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);

				// Get results
				while( $row = mysqli_fetch_assoc( $result ) ) {
					// Get values
					$first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
					$last  = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');

					// Feedback for end user
					$html .= "<pre>ID: " . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . "<br />First name: {$first}<br />Surname: {$last}</pre>";
				}

				mysqli_stmt_close($stmt);
				mysqli_close($GLOBALS["___mysqli_ston"]);
				break;
			case SQLITE:
				global $sqlite_db_connection;

				// Consulta preparada tambien para SQLite
				$stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = :id");
				$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
				try {
					$results = $stmt->execute();
				} catch (Exception $e) {
					echo 'Caught exception';
					exit();
				}

				if ($results) {
					while ($row = $results->fetchArray()) {
						// Get values
						$first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
						$last  = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');

						// Feedback for end user
						$html .= "<pre>ID: " . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . "<br />First name: {$first}<br />Surname: {$last}</pre>";
					}
				}
				break;
		}
	}
}

?>
