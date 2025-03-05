<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Apoyo Académico</title>
    <link rel="stylesheet" href="stylesolicitudes.css">
</head>
<body>
    <h2>Solicitud de Apoyo Académico</h2>
    <form action="" method="post">
        <label for="nombre_familiar">Nombre del Familiar:</label>
        <input type="text" id="nombre_familiar" name="nombre_familiar" required><br><br>

        <label for="nombre_institucion">Nombre de la Institución:</label>
        <input type="text" id="nombre_institucion" name="nombre_institucion"><br><br>

        <label for="tipo">Tipo de Apoyo:</label>
        <select id="tipo" name="tipo">
            <option value="Utiles">Útiles</option>
            <option value="Exencion de inscripcion">Exención de inscripción</option>
        </select><br><br>

        <button type="submit">Enviar Solicitud</button>
    </form>

<?php

require_once("conn.php");
include_once("error_handler.php");

session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST")
{

$nombre_familiar = $_POST['nombre_familiar'];
$nombre_institucion = $_POST['nombre_institucion'];
$tipoApoyo = $_POST['tipo'];

require_once("ESTADOsepuedeprestacion.php");
$prestacionesPermitidas = verificarPrestaciones($_SESSION['Numero_Empleado']);

if (!$prestacionesPermitidas['Academico'][$tipoApoyo]) {
echo "<script>alert('No se puede solicitar este tipo de apoyo académico debido a que ya te lo otorgaron este cuatrimestre');</script>";
exit;
echo "<script>location.reload();</script>"; 
} 

$queryCheckToday = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Tipo = 'Academico' AND DATE(Fecha_Solicitada) = CURDATE()");
$queryCheckToday->bind_param("i", $_SESSION['Numero_Empleado']);
$queryCheckToday->execute();
$resultCheckToday = $queryCheckToday->get_result();

if ($resultCheckToday->num_rows > 0) {
    echo "<script>alert('Ya has solicitado este tipo de apoyo académico el día de hoy.');</script>";
    exit;
}

$queryCheckToday->close();


$queryChecarPF = $conn->prepare("SELECT * FROM familiar_empleado f INNER JOIN empleado_familiar e ON f.Id_Familiar = e.Id_Familiar WHERE f.Nombre_Familiar like ? AND e.Numero_Empleado = ?");
$queryChecarPF->bind_param("si", $nombre_familiar, $_SESSION['Numero_Empleado']);
$queryChecarPF->execute();
$result = $queryChecarPF->get_result();
$row = $result->fetch_assoc();
$nivel_academico = $row['Nivel_academico'];
$queryChecarPF->close();

if ($row)
{

    if ($tipoApoyo == "Exencion de inscripcion" && $nivel_academico != "Universidad" && (strpos($nombre_institucion, "UTN") === false && strpos($nombre_institucion, "Universidad Tecnologica de Nogales") === false))
    {
        echo "No se puede solicitar exención de inscripción para esta institución y/o nivel académico del familiar no es Universidad"; 
    }
    else
    {
        
    

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
    
    echo "Solicitud enviada correctamente";
    }
}
else
{
    echo "No se encontró el familiar";
}

}





?>


<script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll("input[type='text']");

            inputs.forEach(input => {
                input.addEventListener("input", function() {
                    // Limitar a 40 caracteres
                    if (this.value.length > 40) {
                        this.value = this.value.slice(0, 40);
                    }

                    // Eliminar números y caracteres especiales
                    this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
                });
            });
        });
    </script>


</body>
</html>


