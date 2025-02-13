<?php

//Si en los ultimos 4 meses ya se hizo una solicitud de prestacion, no se puede hacer otra, detener la operacion
//mandando un bool falso que detenga o no se w jaja muack

require_once("conn.php");

$fecha_actual = date('Y-m-d');
$fecha_limite = date('Y-m-d', strtotime('-4 months', strtotime($fecha_actual)));

$queryGAPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Fecha_Otorgamiento BETWEEN ? AND ?");
$queryGAPE->bind_param("iss", $numeroEmpleado, $fecha_limite, $fecha_actual);
$queryGAPE->execute();
$resultGAPE = $queryGAPE->get_result();

while ($rowGAPE = $resultGAPE->fetch_assoc()) {
    $rowGAPE = $resultGAPE->fetch_assoc();
    
        



}


?>