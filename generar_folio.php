<?php
// Función para generar un folio único y guardarlo en la tabla `Folio`
function generarFolioUnico($conn) {
    $folioUnico = false;
    $folioGenerado = "";

    while (!$folioUnico) {
        // Generar folio
        $prefijo = "Inter";
        $numeros = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $letras = chr(mt_rand(65, 90)) . chr(mt_rand(65, 90));
        $simbolosPermitidos = ['!', '@', '#', '$', '%', '&', '*', '?'];
        $simbolos = $simbolosPermitidos[array_rand($simbolosPermitidos)] . $simbolosPermitidos[array_rand($simbolosPermitidos)];
        $folioGenerado = $prefijo . $numeros . $letras . $simbolos;

        // Verificar si el folio ya existe
        $query = "SELECT COUNT(*) AS total FROM Folio WHERE CodigoFolio = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $folioGenerado);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // Si el folio no existe, es único
        if ($row['total'] == 0) {
            $folioUnico = true;

            // Insertar el folio en la tabla Folio
            $insertQuery = "INSERT INTO Folio (CodigoFolio) VALUES (?)";
            $stmtInsert = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmtInsert, 's', $folioGenerado);
            mysqli_stmt_execute($stmtInsert);
            mysqli_stmt_close($stmtInsert);
        }

        mysqli_stmt_close($stmt);
    }

    // Obtener el ID del folio recién insertado
    $folioId = mysqli_insert_id($conn);
    return $folioId;
}
?>
