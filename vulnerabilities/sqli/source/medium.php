<?php

if( isset( $_POST[ 'Submit' ] ) ) {
	// Get input
	$id = $_POST[ 'id' ];

	// Defensa en profundidad: el user_id solo debe ser numerico
	if( !ctype_digit( (string)$id ) ) {
		$html .= "<pre>ID invalido. Debe ser numerico.</pre>";
	} else {
		switch ($_DVWA['SQLI_DB']) {
			case MYSQL:
				// Consulta preparada: el valor viaja como parametro tipado
				$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT first_name, last_name FROM users WHERE user_id = ?");
				mysqli_stmt_bind_param($stmt, "i", $id);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);

				while( $row = mysqli_fetch_assoc( $result ) ) {
					$first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
					$last  = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');
					$html .= "<pre>ID: " . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . "<br />First name: {$first}<br />Surname: {$last}</pre>";
				}
				mysqli_stmt_close($stmt);
				break;
			case SQLITE:
				global $sqlite_db_connection;

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
						$first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
						$last  = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');
						$html .= "<pre>ID: " . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . "<br />First name: {$first}<br />Surname: {$last}</pre>";
					}
				}
				break;
		}
	}
}

// This is used later on in the index.php page
$query  = "SELECT COUNT(*) FROM users;";
$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );
$number_of_rows = mysqli_fetch_row( $result )[0];

mysqli_close($GLOBALS["___mysqli_ston"]);
?>
