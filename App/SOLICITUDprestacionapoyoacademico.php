<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("conn.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar si los datos existen en $_POST
    if (!isset($_POST['nombre_familiar'], $_POST['nombre_institucion'], $_POST['tipo'])) {
        echo json_encode(["success" => false, "message" => "Faltan datos"]);
        exit;
    }

    $nombre_familiar = $_POST['nombre_familiar'];
    $nombre_institucion = $_POST['nombre_institucion'];
    $tipoApoyo = $_POST['tipo'];

    require_once("ESTADOsepuedeprestacion.php");
    $prestacionesPermitidas = verificarPrestaciones($_SESSION['Numero_Empleado']);

    if (!$prestacionesPermitidas['Academico'][$tipoApoyo]) {
        echo json_encode(["success" => false, "message" => "No se puede solicitar este tipo de apoyo académico."]);
        exit;
    }

    // Verificar si ya se hizo una solicitud hoy
    $queryCheckToday = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Tipo = 'Academico' AND DATE(Fecha_Solicitada) = CURDATE()");
    $queryCheckToday->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryCheckToday->execute();
    $resultCheckToday = $queryCheckToday->get_result();

    if ($resultCheckToday->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Ya solicitaste este apoyo hoy."]);
        exit;
    }

    $queryCheckToday->close();

    // Verificar si el familiar existe
    $queryChecarPF = $conn->prepare("SELECT * FROM familiar_empleado f INNER JOIN empleado_familiar e ON f.Id_Familiar = e.Id_Familiar WHERE f.Nombre_Familiar LIKE ? AND e.Numero_Empleado = ?");
    $queryChecarPF->bind_param("si", $nombre_familiar, $_SESSION['Numero_Empleado']);
    $queryChecarPF->execute();
    $result = $queryChecarPF->get_result();
    $row = $result->fetch_assoc();
    $queryChecarPF->close();

    if (!$row) {
        echo json_encode(["success" => false, "message" => "Familiar no encontrado"]);
        exit;
    }

    $nivel_academico = $row['Nivel_academico'];

    if ($tipoApoyo == "Exencion de inscripcion" && $nivel_academico != "Universidad" && (strpos($nombre_institucion, "UTN") === false && strpos($nombre_institucion, "Universidad Tecnologica de Nogales") === false)) {
        echo json_encode(["success" => false, "message" => "No se puede solicitar exención de inscripción para esta institución"]);
        exit;
    }

    // Insertar datos
    $tipo = "Academico";
    $queryInsertP = $conn->prepare("INSERT INTO prestacion (Tipo) VALUES (?)");
    $queryInsertP->bind_param("s", $tipo);
    $queryInsertP->execute();
    $id_prestacion = $conn->insert_id;
    $queryInsertP->close();

    $queryInsertPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo) VALUES (?, ?, ?)");
    $queryInsertPE->bind_param("iis", $_SESSION['Numero_Empleado'], $id_prestacion, $tipo);
    $queryInsertPE->execute();
    $queryInsertPE->close();

    $queryInsertPF = $conn->prepare("INSERT INTO familiar_prestacion (Id_Familiar, Id_Prestacion, Tipo) VALUES (?, ?, ?)");
    $queryInsertPF->bind_param("iis", $row['Id_Familiar'], $id_prestacion, $tipo);
    $queryInsertPF->execute();
    $queryInsertPF->close();

    $queryInsertPA = $conn->prepare("INSERT INTO prestacion_apoyoacademico (Id_Prestacion, Numero_Empleado, Id_Familiar, Nivel_Academico, Nombre_Institucion, Tipo) VALUES (?, ?, ?, ?, ?, ?)");
    $queryInsertPA->bind_param("iiisss", $id_prestacion, $_SESSION['Numero_Empleado'], $row['Id_Familiar'], $nivel_academico, $nombre_institucion, $tipoApoyo);
    $queryInsertPA->execute();
    $queryInsertPA->close();

    echo json_encode(["success" => true, "message" => "Solicitud enviada correctamente"]);

} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}
?>
