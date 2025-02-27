<?php
require_once("conn.php");
session_start();

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que todos los campos estén presentes
    if (!isset($_POST['nombre_familiar'], $_POST['tipo'], $_POST['tipo_pago'])) {
        echo json_encode(["success" => false, "message" => "Faltan datos en la solicitud."]);
        exit;
    }

    // Recibir datos desde B4A
    $tipo_pago = $_POST['tipo_pago'];
    $nombre_familiar = "%" . $_POST['nombre_familiar'] . "%";
    $tipoPF = $_POST['tipo'];

    require_once("ESTADOsepuedeprestacion.php");
    $prestacionesPermitidas = verificarPrestaciones($_SESSION['Numero_Empleado']);

    if (!$prestacionesPermitidas['Financiera'][$tipoPF]) {
        echo json_encode(["success" => false, "message" => "Ya te otorgaron este apoyo financiero este cuatrimestre."]);
        exit;
    }

    // Verificar si el usuario ya solicitó apoyo financiero hoy
    $queryCheckToday = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Tipo = 'Financiera' AND DATE(Fecha_Solicitada) = CURDATE()");
    $queryCheckToday->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryCheckToday->execute();
    $resultCheckToday = $queryCheckToday->get_result();

    if ($resultCheckToday->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Ya solicitaste este apoyo financiero hoy."]);
        exit;
    }

    $queryCheckToday->close();

    // Verificar si el familiar existe en la base de datos
    $queryChecarPF = $conn->prepare("SELECT * FROM familiar_empleado f INNER JOIN empleado_familiar e ON f.Id_Familiar = e.Id_Familiar WHERE f.Nombre_Familiar LIKE ? AND e.Numero_Empleado = ?");
    $queryChecarPF->bind_param("si", $nombre_familiar, $_SESSION['Numero_Empleado']);
    $queryChecarPF->execute();
    $result = $queryChecarPF->get_result();
    $row = $result->fetch_assoc();
    $queryChecarPF->close();

    if (!$row) {
        echo json_encode(["success" => false, "message" => "No se encontró el familiar."]);
        exit;
    }

    // Insertar en la tabla prestacion
    $tipo = "Financiera";
    $queryInsertP = $conn->prepare("INSERT INTO prestacion (Tipo) VALUES (?)");
    $queryInsertP->bind_param("s", $tipo);
    $queryInsertP->execute();
    $id_prestacion = $conn->insert_id;
    $queryInsertP->close();

    // Insertar en empleado_prestacion
    $queryInsertPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo) VALUES (?, ?, ?)");
    $queryInsertPE->bind_param("iis", $_SESSION['Numero_Empleado'], $id_prestacion, $tipo);
    $queryInsertPE->execute();
    $queryInsertPE->close();

    // Insertar en familiar_prestacion
    $queryInsertPEE = $conn->prepare("INSERT INTO familiar_prestacion (Id_Familiar, Id_Prestacion, Tipo) VALUES (?, ?, ?)");
    $queryInsertPEE->bind_param("iis", $row['Id_Familiar'], $id_prestacion, $tipo);
    $queryInsertPEE->execute();
    $queryInsertPEE->close();

    // Determinar el tipo de pago
    $deposito = ($tipo_pago == "Deposito") ? 1 : 0;
    $reembolso = ($tipo_pago == "Reembolso") ? 1 : 0;

    // Insertar en prestacion_apoyofinanciero
    $queryInsertPF = $conn->prepare("INSERT INTO prestacion_apoyofinanciero (Id_Prestacion, Numero_Empleado, Id_Familiar, Tipo, Deposito, Reembolso) VALUES (?, ?, ?, ?, ?, ?)");
    $queryInsertPF->bind_param("iiisii", $id_prestacion, $_SESSION['Numero_Empleado'], $row['Id_Familiar'], $tipoPF, $deposito, $reembolso);
    $queryInsertPF->execute();
    $queryInsertPF->close();

    // Responder con éxito
    echo json_encode(["success" => true, "message" => "Solicitud enviada correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>
