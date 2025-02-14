<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Prestación de Día</title>
    <script>
        function toggleOtroField() {
            var motivo = document.getElementById("motivo").value;
            var otroField = document.getElementById("otroMotivo");
            if (motivo === "Otro") {
                otroField.style.display = "block";
            } else {
                otroField.style.display = "none";
            }
        }
    </script>
     <link rel="stylesheet" href="stylesolicitudes.css">
</head>
<body>
    <form action="" method="post">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="diaExtra">Día extra:</label>
        <input type="checkbox" id="diaExtra" name="diaExtra" value="1"><br><br>

        <label for="motivo">Motivo:</label>
        <select id="motivo" name="motivo" onchange="toggleOtroField()" required>
            <option value="Permiso sindical">Permiso sindical</option>
            <option value="Nacimiento hijo">Nacimiento hijo</option>
            <option value="Otro">Otro</option>
        </select><br><br>

        <div id="otroMotivo" style="display: none;">
            <label for="otro">Especificar otro motivo:</label>
            <input type="text" id="otro" name="otro"><br><br>
        </div>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>

<?php

require_once("conn.php");
session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST")
{
    $fecha = $_POST['fecha'];
    $diaExtra = $_POST['diaExtra'] ?? 0;
    $motivo = $_POST['motivo'];
    $otro = $_POST['otro'] ?? null;

    $queryCheckDias = $conn->prepare("SELECT Dias, Dias_Extras FROM empleado WHERE Numero_Empleado = ?");
    $queryCheckDias->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryCheckDias->execute();
    $queryCheckDias->bind_result($dias, $diasExtras);
    $queryCheckDias->fetch();
    $queryCheckDias->close();

    if ($diaExtra) {
        if ($diasExtras <= 0) {
            echo '<script type="text/javascript">
            alert("No tienes suficientes días extras disponibles para solicitar esta prestación.");
            </script>';
            echo("<meta http-equiv='refresh' content='1'>");
            exit;
        }
    } else {
        if ($dias <= 0) {
            echo '<script type="text/javascript">
            alert("No tienes suficientes días disponibles para solicitar esta prestación.");
            </script>';
            echo("<meta http-equiv='refresh' content='1'>");
            exit;
        }
    }


//Comentado hasta tener especificaciones cuando suficicentes dias han sido otorgados
// require_once("ESTADOsepuedeprestacion.php");
// $prestacionesPermitidas = verificarPrestaciones($_SESSION['Numero_Empleado']);

// if (!$prestacionesPermitidas['Día'][$motivo]) {
// echo "<script>alert('No se puede solicitar este tipo de apoyo académico debido a que ya te lo otorgaron este cuatrimestre');</script>";
// exit;
// echo "<script>location.reload();</script>"; 
// } 

    $queryCheckFecha = $conn->prepare("SELECT COUNT(*) FROM prestacion_dias WHERE Numero_Empleado = ? AND Fecha_Solicitada = ?");
    $queryCheckFecha->bind_param("is", $_SESSION['Numero_Empleado'], $fecha);
    $queryCheckFecha->execute();
    $queryCheckFecha->bind_result($count);
    $queryCheckFecha->fetch();
    $queryCheckFecha->close();

    if ($count > 0) {
        echo '<script type="text/javascript">
        alert("Ya existe una solicitud de prestación para la fecha seleccionada.");
        </script>';
        echo("<meta http-equiv='refresh' content='1'>");
    }
    else{




    $queryInsertarP = $conn->prepare("INSERT INTO prestacion (Tipo, Fecha_Solicitada) VALUES ('Día', CURRENT_DATE)");
    $queryInsertarP->execute();
    $Id_Prestacion = $conn->insert_id;
    $queryInsertarP->close();

    $queryInsertarPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo, Fecha_Solicitada) VALUES (?, ?, ?, CURRENT_DATE)");
    $tipo = 'Día';
    $queryInsertarPE->bind_param("iis", $_SESSION['Numero_Empleado'], $Id_Prestacion, $tipo);
    $queryInsertarPE->execute();
    $queryInsertarPE->close();


    $queryInsertarPF = $conn->prepare("INSERT INTO prestacion_dias (Id_Prestacion, Numero_Empleado, Fecha_Solicitada, Dia_Extra,  Motivo) VALUES (?, ?, ?, ?, ?)");
    $queryInsertarPF->bind_param("iisis", $Id_Prestacion, $_SESSION['Numero_Empleado'], $fecha, $diaExtra, $motivo);
    $queryInsertarPF->execute();
    $queryInsertarPF->close();


    if($diaExtra)
    {
        $queryUpdateED = $conn->prepare("UPDATE empleado SET Dias_Extras = Dias_Extras - 1 WHERE Numero_Empleado = ?");
        $queryUpdateED->bind_param("i", $_SESSION['Numero_Empleado']);
        $queryUpdateED->execute();
        $queryUpdateED->close();
    }
    else
    {
    $queryUpdateED = $conn->prepare("UPDATE empleado SET Dias = Dias - 1 WHERE Numero_Empleado = ?");
    $queryUpdateED->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryUpdateED->execute();
    $queryUpdateED->close();
    }

    if ($otro)
    {
        $queryInsertarPF = $conn->prepare("UPDATE prestacion_dias SET Motivo = ? WHERE Id_Prestacion = ?");
        $queryInsertarPF->bind_param("si", $otro, $Id_Prestacion);
        $queryInsertarPF->execute();
        $queryInsertarPF->close();
    }

    echo '<script type="text/javascript">
    alert("Solicitud de prestación de día enviada, a continuacion rellenar documento de solicitud de prestación");
    </script>';

    if($motivo == "Permiso sindical")
    {
        echo '<script type="text/javascript">
        window.location.href = "PermisoSindical.pdf";
        </script>';
    }else
    {
        echo '<script type="text/javascript">
        alert("Prestación otorgada");
        </script>';
        echo("<meta http-equiv='refresh' content='1'>");
    }

    }
  
}






?>