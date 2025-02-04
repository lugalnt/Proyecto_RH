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

    $queryInsertarP = $conn->prepare("INSERT INTO prestacion (Tipo, Fecha_Solicitada) VALUES ('Día', CURRENT_DATE)");
    $queryInsertarP->execute();
    $Id_Prestacion = $conn->insert_id;
    $queryInsertarP->close();

    $queryInsertarPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo, Fecha_Solicitada) VALUES (?, ?, ?, ?)");
    $queryInsertarPE->bind_param("iiss", $_SESSION['Numero_Empleado'], $Id_Prestacion, 'Día', CURRENT_DATE);
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
        window.location.href = "PermisoSindical.php";
        </script>';
    }else
    {
        echo '<script type="text/javascript">
        alert("Prestación otorgada");
        </script>';
        echo("<meta http-equiv='refresh' content='1'>");
    }


  
}






?>