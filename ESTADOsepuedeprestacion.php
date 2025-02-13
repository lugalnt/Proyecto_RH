<?php

//Si en los ultimos 4 meses ya se hizo una solicitud de prestacion, no se puede hacer otra, detener la operacion
//mandando un bool falso que detenga o no se w jaja muack



require_once("conn.php");

$fecha_actual = date('Y-m-d');
$fecha_limite = date('Y-m-d', strtotime('-4 months', strtotime($fecha_actual)));

$prestacionesPermitidas = [
    'Academico' => [
        'Exencion de inscripcion' => true,
        'Utiles' => true,
        'Tesis' => true
    ],
    'Apoyo Financiero' => [
        'Lentes' => true,
        'Gasto funerario' => true,
        'Guarderia' => true,
        'Aparato Ortopedico' => true,
        'Canastilla mama' => true
    ],
    'Dias' => [
        'Permiso Sindical' => true,
        'Nacimiento hijo' => true,
        'Otro' => true
    ]
];

$queryGAPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Fecha_Otorgamiento BETWEEN ? AND ?");
$queryGAPE->bind_param("iss", $numeroEmpleado, $fecha_limite, $fecha_actual);
$queryGAPE->execute();
$resultGAPE = $queryGAPE->get_result();

while ($rowGAPE = $resultGAPE->fetch_assoc()) {
    $rowGAPE = $resultGAPE->fetch_assoc();
    
    if ($rowGAPE['Tipo'] = "Academico") {
        $queryGAPAA = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
        $queryGAPAA->bind_param("i", $rowGAPE['Id_Prestacion']);
        $queryGAPAA->execute();
        $resultGAPAA = $queryGAPAA->get_result();
        $rowGAPAA = $resultGAPAA->fetch_assoc();

            if ($rowGAPAA['Tipo'] == "Exencion de inscripcion") {
                $prestacionesPermitidas['Academico']['Exencion de inscripcion'] = false;
            }

            if ($rowGAPAA['Tipo'] == "Utiles") {
                $prestacionesPermitidas['Academico']['Utiles'] = false;
            }

            if ($rowGAPAA['Tipo'] == "Tesis") {
                $prestacionesPermitidas['Academico']['Tesis'] = false;
            }
        }

        if ($rowGAPE['Tipo'] == "Apoyo Financiero") {
            $queryGAPAF = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
            $queryGAPAF->bind_param("i", $rowGAPE['Id_Prestacion']);
            $queryGAPAF->execute();
            $resultGAPAF = $queryGAPAF->get_result();
            $rowGAPAF = $resultGAPAF->fetch_assoc();

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
        }

        if ($rowGAPE['Tipo'] == "Dias") {
            $queryGAPAD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
            $queryGAPAD->bind_param("i", $rowGAPE['Id_Prestacion']);
            $queryGAPAD->execute();
            $resultGAPAD = $queryGAPAD->get_result();
            $rowGAPAD = $resultGAPAD->fetch_assoc();

            if ($rowGAPAD['Tipo'] == "Permiso Sindical") {
                $prestacionesPermitidas['Dias']['Permiso Sindical'] = false;
            }

            if ($rowGAPAD['Tipo'] == "Nacimiento hijo") {
                $prestacionesPermitidas['Dias']['Nacimiento hijo'] = false;
            }

            if ($rowGAPAD['Tipo'] == "Otro") {
                $prestacionesPermitidas['Dias']['Otro'] = false;
            }
        }


    }

    echo '<script>console.log("dale semillas a un granjero y te dará comida, dale una melodía a un músico, y te dará una canción, dale un enemigo a un soldado, y crearan nada, el solo destruye mata, me pregunto si Dios observa los eventos que se desarrollan cuando los hombres se revelan contra si mismos......tengo miedo de que al morir a dios le importen las cosas que hice, pero tengo más miedo que no le importe..que estará ahí sentado cortándose las uñas y con apenas la energía para poder juzgarme,por que entonces que...y que tal si a los hombres malos no se les juzga como se corresponden,la gente me dice que le tema al infierno pero hay un punto dónde has hecho cosas tan horribles que te preguntas si el diablo teconsiderará su amigo al llegar La verdad es... que ya deje de creer en algo... ");</script>';




?>