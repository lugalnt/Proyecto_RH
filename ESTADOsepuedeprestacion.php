<?php
function normalizar($texto) {
    return strtolower(str_replace([' ', '_'], '', $texto));
}

function buscarNombrePrestacion($tipoMayor, $nombreBuscado, $tiposPrestacion) {
    error_log("buscarNombrePrestacion: tipoMayor=$tipoMayor, nombreBuscado=$nombreBuscado");
    foreach ($tiposPrestacion as $row) {
        error_log("Comparando con: tipoMayor={$row['tipoMayor']}, nombre={$row['nombre']}");
        if (
            normalizar($row['tipoMayor']) == normalizar($tipoMayor) &&
            normalizar($row['nombre']) == normalizar($nombreBuscado)
        ) {
            error_log("Nombre encontrado: " . $row['nombre']);
            return $row['nombre'];
        }
    }
    error_log("Nombre no encontrado");
    return null;
}

function buscarPeriodoMesesPrestacion($tipoMayor, $nombreBuscado, $tiposPrestacion) {
    error_log("buscarPeriodoMesesPrestacion: tipoMayor=$tipoMayor, nombreBuscado=$nombreBuscado");
    foreach ($tiposPrestacion as $row) {
        if (
            normalizar($row['tipoMayor']) == normalizar($tipoMayor) &&
            normalizar($row['nombre']) == normalizar($nombreBuscado)
        ) {
            error_log("PeriodoMeses encontrado: " . $row['PeriodoMeses']);
            return $row['PeriodoMeses'];
        }
    }
    error_log("PeriodoMeses no encontrado");
    return null;
}

function verificarPrestaciones($numeroEmpleado) {
    global $conn;
    error_log("verificarPrestaciones: numeroEmpleado=$numeroEmpleado");

    // Obtener todos los tipos de prestaciones
    $tiposPrestacion = [];
    $queryTipos = $conn->query("SELECT tipoMayor, nombre, PeriodoMeses FROM tiposprestacion WHERE tipoMayor != 'Academico'");
    if (!$queryTipos) {
        error_log("Error en queryTipos: " . $conn->error);
    }
    while ($row = $queryTipos->fetch_assoc()) {
        error_log("TipoPrestacion row: " . json_encode($row));
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
    error_log("prestacionesPermitidas inicial: " . json_encode($prestacionesPermitidas));

    // Buscar prestaciones otorgadas y marcarlas como false
    $queryGAPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Fecha_Otorgada is NOT NULL ");
    if (!$queryGAPE) {
        error_log("Error en queryGAPE: " . $conn->error);
    }
    $queryGAPE->bind_param("i", $numeroEmpleado);
    $queryGAPE->execute();
    $resultGAPE = $queryGAPE->get_result();

    while ($rowGAPE = $resultGAPE->fetch_assoc()) {
        error_log("empleado_prestacion row: " . json_encode($rowGAPE));
        $tipo = $rowGAPE['Tipo'];
        $idPrestacion = $rowGAPE['Id_Prestacion'];

        // Según el tipo, busca el nombre específico en la tabla correspondiente

        if ($tipo == "Financiera") {
            $query = $conn->prepare(
            "SELECT PF.Tipo, EP.Fecha_Otorgada 
             FROM prestacion_apoyofinanciero AS PF 
             INNER JOIN empleado_prestacion AS EP 
             ON PF.Id_Prestacion = EP.Id_Prestacion 
             WHERE PF.Id_Prestacion = ?"
            );
            $query->bind_param("i", $idPrestacion);
        } elseif ($tipo == "Día" || $tipo == "Dia") {
            $query = $conn->prepare(
            "SELECT PD.Motivo as Tipo, EP.Fecha_Otorgada 
             FROM prestacion_dias AS PD 
             INNER JOIN empleado_prestacion AS EP 
             ON PD.Id_Prestacion = EP.Id_Prestacion 
             WHERE PD.Id_Prestacion = ?"
            );
            $query->bind_param("i", $idPrestacion);
        } elseif ($tipo == "Plazo") {
            $query = $conn->prepare(
            "SELECT PP.Tipo, EP.Fecha_Otorgada 
             FROM prestacion_plazos AS PP 
             INNER JOIN empleado_prestacion AS EP 
             ON PP.Id_Prestacion = EP.Id_Prestacion 
             WHERE PP.Id_Prestacion = ?"
            );
            $query->bind_param("i", $idPrestacion);
        } else {
            error_log("Tipo desconocido: $tipo");
            continue;
        }

        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        error_log("Prestacion detalle: " . json_encode($row));
        if ($row && !empty($row['Tipo'])) {
            // Buscar el nombre real en tiposprestacion
            $nombreReal = buscarNombrePrestacion($tipo, $row['Tipo'], $tiposPrestacion);
            $meses = buscarPeriodoMesesPrestacion($tipo, $row['Tipo'], $tiposPrestacion);

            $fecha_otorgada = $row['Fecha_Otorgada'];
            $fecha_actual = date('Y-m-d');
            $meses_valor = is_numeric($meses) ? (int)$meses : 0;
            error_log("nombreReal=$nombreReal, meses=$meses, fecha_otorgada=$fecha_otorgada, fecha_actual=$fecha_actual");
            if ($meses_valor <= 0) {
                error_log("meses_valor <= 0, se omite");
                continue; 
            }
            $fecha_limite = date('Y-m-d', strtotime("-{$meses_valor} months", strtotime($fecha_actual)));
            error_log("fecha_limite=$fecha_limite");

            // Si la fecha otorgada está entre fecha_limite y fecha_actual, marcar como false
            if ($fecha_otorgada >= $fecha_limite && $fecha_otorgada <= $fecha_actual) {
                error_log("Marcando como false: tipo=$tipo, nombreReal=$nombreReal");
                if ($nombreReal && isset($prestacionesPermitidas[$tipo][$nombreReal])) {
                    $prestacionesPermitidas[$tipo][$nombreReal] = false;
                }
            }
        }
        $query->close();
    }

    error_log("prestacionesPermitidas final: " . json_encode($prestacionesPermitidas));
    return $prestacionesPermitidas;
}
