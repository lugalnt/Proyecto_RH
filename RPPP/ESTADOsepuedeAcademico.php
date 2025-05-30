
<?php
require_once("conn.php"); // Asegúrate de tener la conexión $conn

function normalizar($texto) {
    return strtolower(str_replace([' ', '_'], '', $texto));
}

/**
 * Verifica si se puede otorgar una prestación académica a un familiar de un empleado.
 * @param int $numeroEmpleado
 * @param int $idFamiliar
 * @param string $nombrePrestacion (ejemplo: "Exencion de inscripcion")
 * @return bool true si se puede otorgar, false si no
 */
function sePuedeOtorgarPrestacionAcademica($numeroEmpleado, $idFamiliar, $nombrePrestacion) {
    global $conn;

    // 1. Obtener el periodo de meses de la prestación
    $stmt = $conn->prepare("SELECT PeriodoMeses FROM tiposprestacion WHERE tipoMayor = 'Academico' AND nombre = ?");
    $stmt->bind_param("s", $nombrePrestacion);
    $stmt->execute();
    $stmt->bind_result($periodoMeses);
    if (!$stmt->fetch()) {
        $stmt->close();
        return false; // Prestación no encontrada
    }
    $stmt->close();

    if (!is_numeric($periodoMeses) || $periodoMeses <= 0) {
        return true; // Si no hay periodo, se puede otorgar siempre
    }

    // 2. Calcular la fecha límite
    $fecha_actual = date('Y-m-d');
    $fecha_limite = date('Y-m-d', strtotime("-{$periodoMeses} months", strtotime($fecha_actual)));

    // 3. Buscar si el familiar ya recibió esta prestación en el periodo
    $query = $conn->prepare(
        "SELECT PA.Id_Prestacion, EP.Fecha_Otorgada
         FROM prestacion_apoyoacademico AS PA
         INNER JOIN empleado_prestacion AS EP ON PA.Id_Prestacion = EP.Id_Prestacion
         WHERE PA.Numero_Empleado = ? AND PA.Id_Familiar = ? AND PA.Tipo = ? AND EP.Fecha_Otorgada IS NOT NULL
         AND EP.Fecha_Otorgada >= ? AND EP.Fecha_Otorgada <= ?"
    );
    $query->bind_param("iisss", $numeroEmpleado, $idFamiliar, $nombrePrestacion, $fecha_limite, $fecha_actual);
    $query->execute();
    $query->store_result();
    $puede = $query->num_rows === 0;
    $query->close();

    return $puede;
}
?>