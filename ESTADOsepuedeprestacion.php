<?php

require_once("conn.php");
include_once("error_handler.php");

function verificarPrestaciones($numeroEmpleado) {
    global $conn;

    $fecha_actual = date('Y-m-d');
    $fecha_limite = date('Y-m-d', strtotime('-12 months', strtotime($fecha_actual)));

    $prestacionesPermitidas = [
        'Academico' => [
            'Exencion de inscripcion' => true,
            'Utiles' => true,
            'Tesis' => true
        ],
        'Financiera' => [
            'Lentes' => true,
            'Gasto funerario' => true,
            'Guarderia' => true,
            'Aparato Ortopedico' => true,
            'Canastilla mama' => true,
            'Titulacion' => true
        ],
        'Dias' => [
            'Permiso Sindical' => true,
            'Nacimiento hijo' => true,
            'Otro' => true
        ],
        'Plazo' => [
            'Incapacidad' => true,
            'Embarazo' => true,
            'Permiso por Duelo' => true,
            'Otro' => true
        ]
    ];

    $queryGAPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Fecha_Otorgada BETWEEN ? AND ?");
    $queryGAPE->bind_param("iss", $numeroEmpleado, $fecha_limite, $fecha_actual);
    $queryGAPE->execute();
    $resultGAPE = $queryGAPE->get_result();

    while ($rowGAPE = $resultGAPE->fetch_assoc()) {
        if ($rowGAPE['Tipo'] == "Academico") {
            $queryGAPAA = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
            $queryGAPAA->bind_param("i", $rowGAPE['Id_Prestacion']);
            $queryGAPAA->execute();
            $resultGAPAA = $queryGAPAA->get_result();
            $rowGAPAA = $resultGAPAA->fetch_assoc();

            if ($rowGAPAA) {
                if ($rowGAPAA['Tipo'] == "Exencion de inscripc") { //Esto es asi porque en la base de datos se queda sin espacio para nombre completo lol
                    $prestacionesPermitidas['Academico']['Exencion de inscripcion'] = false;
                }

                if ($rowGAPAA['Tipo'] == "Utiles") {
                    $prestacionesPermitidas['Academico']['Utiles'] = false;
                }

                if ($rowGAPAA['Tipo'] == "Tesis") {
                    $prestacionesPermitidas['Academico']['Tesis'] = false;
                }
            }
        }

        if ($rowGAPE['Tipo'] == "Financiera") {
            $queryGAPAF = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
            $queryGAPAF->bind_param("i", $rowGAPE['Id_Prestacion']);
            $queryGAPAF->execute();
            $resultGAPAF = $queryGAPAF->get_result();
            $rowGAPAF = $resultGAPAF->fetch_assoc();

            if ($rowGAPAF) {
                if ($rowGAPAF['Tipo'] == "Lentes") {
                    $prestacionesPermitidas['Apoyo Financiero']['Lentes'] = false;
                }

                if ($rowGAPAF['Tipo'] == "Gasto funerario") {
                    $prestacionesPermitidas['Apoyo Financiero']['Gasto funerario'] = false;
                }

                if ($rowGAPAF['Tipo'] == "Guarderia") {
                    $prestacionesPermitidas['Apoyo Financiero']['Guarderia'] = false;
                }

                if ($rowGAPAF['Tipo'] == "Aparato Ortopedico") {
                    $prestacionesPermitidas['Apoyo Financiero']['Aparato Ortopedico'] = false;
                }

                if ($rowGAPAF['Tipo'] == "Canastilla mama") {
                    $prestacionesPermitidas['Apoyo Financiero']['Canastilla mama'] = false;
                }
                if ($rowGAPAF['Tipo'] == "Titulacion") {
                    $prestacionesPermitidas['Apoyo Financiero']['Titulacion'] = false;
                }
            }
        }

        if ($rowGAPE['Tipo'] == "Dias") {
            $queryGAPAD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
            $queryGAPAD->bind_param("i", $rowGAPE['Id_Prestacion']);
            $queryGAPAD->execute();
            $resultGAPAD = $queryGAPAD->get_result();
            $rowGAPAD = $resultGAPAD->fetch_assoc();

            //Cheqar aca que en los ultimos 4 meses no haya mas de dos pretsacion de dia dadas a empleados del misma
            //area que el empleado a checar y el mismo dia
            //esto se hace en el mismo archivo de la solicitud de prestacion


            if ($rowGAPAD) {
                if ($rowGAPAD['Motivo'] == "Permiso Sindical") {
                    $prestacionesPermitidas['Dias']['Permiso Sindical'] = false;
                }

                if ($rowGAPAD['Motivo'] == "Nacimiento hijo") {
                    $prestacionesPermitidas['Dias']['Nacimiento hijo'] = false;
                }

                if ($rowGAPAD['Motivo'] == "Otro") {
                    $prestacionesPermitidas['Dias']['Otro'] = false;
                }
            }
        }

        if ($rowGAPE['Tipo'] == "Plazo") {
            $queryGAPAP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
            $queryGAPAP->bind_param("i", $rowGAPE['Id_Prestacion']);
            $queryGAPAP->execute();
            $resultGAPAP = $queryGAPAP->get_result();
            $rowGAPAP = $resultGAPAP->fetch_assoc();

            if ($rowGAPAP) {
                if ($rowGAPAP['Tipo'] == "Incapacidad") {
                    $prestacionesPermitidas['Plazo']['Incapacidad'] = false;
                }

                if ($rowGAPAP['Tipo'] == "Embarazo") {
                    $prestacionesPermitidas['Plazo']['Embarazo'] = false;
                }

                if ($rowGAPAP['Tipo'] == "Permiso por Duelo") {
                    $prestacionesPermitidas['Plazo']['Permiso por Duelo'] = false;
                }

                if ($rowGAPAP['Tipo'] == "Otro") {
                    $prestacionesPermitidas['Plazo']['Otro'] = false;
                }
            }
        }
    }

    return $prestacionesPermitidas;
}

?>