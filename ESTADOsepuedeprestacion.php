<?php
function normalizar($texto) {
    return strtolower(str_replace([' ', '_'], '', $texto));
}

function buscarNombrePrestacion($tipoMayor, $nombreBuscado, $tiposPrestacion) {
    foreach ($tiposPrestacion as $row) {
        if (
            normalizar($row['tipoMayor']) == normalizar($tipoMayor) &&
            normalizar($row['nombre']) == normalizar($nombreBuscado)
        ) {
            return $row['nombre'];
        }
    }
    return null;
}

function verificarPrestaciones($numeroEmpleado) {
    global $conn;

    $fecha_actual = date('Y-m-d');
    $fecha_limite = date('Y-m-d', strtotime('-12 months', strtotime($fecha_actual)));

    // Obtener todos los tipos de prestaciones
    $tiposPrestacion = [];
    $queryTipos = $conn->query("SELECT tipoMayor, nombre FROM tiposprestacion");
    while ($row = $queryTipos->fetch_assoc()) {
        $tiposPrestacion[] = $row;
    }

    // Inicializar arreglo dinámico
    $prestacionesPermitidas = [];
    foreach ($tiposPrestacion as $row) {
        $tipoMayor = $row['tipoMayor'];
        $nombre = $row['nombre'];
        if (!isset($prestacionesPermitidas[$tipoMayor])) {
            $prestacionesPermitidas[$tipoMayor] = [];
        }
        $prestacionesPermitidas[$tipoMayor][$nombre] = true;
    }

    // Buscar prestaciones otorgadas y marcarlas como false
    $queryGAPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Fecha_Otorgada BETWEEN ? AND ?");
    $queryGAPE->bind_param("iss", $numeroEmpleado, $fecha_limite, $fecha_actual);
    $queryGAPE->execute();
    $resultGAPE = $queryGAPE->get_result();

    while ($rowGAPE = $resultGAPE->fetch_assoc()) {
        $tipo = $rowGAPE['Tipo'];
        $idPrestacion = $rowGAPE['Id_Prestacion'];

        // Según el tipo, busca el nombre específico en la tabla correspondiente
        if ($tipo == "Academico" || $tipo == "Academica") {
            $query = $conn->prepare("SELECT Tipo FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
            $query->bind_param("i", $idPrestacion);
        } elseif ($tipo == "Financiera") {
            $query = $conn->prepare("SELECT Tipo FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
            $query->bind_param("i", $idPrestacion);
        } elseif ($tipo == "Día" || $tipo == "Dia") {
            $query = $conn->prepare("SELECT Motivo as Tipo FROM prestacion_dias WHERE Id_Prestacion = ?");
            $query->bind_param("i", $idPrestacion);
        } elseif ($tipo == "Plazo") {
            $query = $conn->prepare("SELECT Tipo FROM prestacion_plazos WHERE Id_Prestacion = ?");
            $query->bind_param("i", $idPrestacion);
        } else {
            continue;
        }

        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        if ($row && !empty($row['Tipo'])) {
            // Buscar el nombre real en tiposprestacion
            $nombreReal = buscarNombrePrestacion($tipo, $row['Tipo'], $tiposPrestacion);
            if ($nombreReal && isset($prestacionesPermitidas[$tipo][$nombreReal])) {
                $prestacionesPermitidas[$tipo][$nombreReal] = false;
            }
        }
        $query->close();
    }

    return $prestacionesPermitidas;
}