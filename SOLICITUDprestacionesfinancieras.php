<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Apoyo Financiero</title>
</head>
<body>
    <h2>Solicitud de Apoyo Financiero</h2>
    <form action="" method="post">
        <label for="nombre_familiar">Nombre del Familiar:</label>
        <input type="text" id="nombre_familiar" name="nombre_familiar" required><br><br>

        <label for="tipo">Tipo de Prestación:</label>
        <select id="tipo" name="tipo">
            <option value="Guarderia">Guardería</option>
            <option value="Gastos funerarios">Gastos funerarios</option>
            <option value="Exencion de pago de inscripciones">Exención de pago de inscripciones</option>
        </select><br><br>

        <label for="tipo_pago">Tipo de Pago:</label>
        <select id="tipo_pago" name="tipo_pago">
            <option value="Deposito">Depósito</option>
            <option value="Reembolso">Reembolso</option>
        </select><br><br>


        <button type="submit">Enviar Solicitud</button>
    </form>
</body>
</html>

<?php
require_once("conn.php");
session_start();


if ($_SERVER["REQUEST_METHOD"]=="POST")
{
    $tipo_pago = $_POST['tipo_pago'];
    $nombre_familiar = "%".$_POST['nombre_familiar']."%";
    $tipo = $_POST['tipo'];
    
   $queryChecarPF = $conn->prepare("SELECT * FROM familiar_empleado f INNER JOIN empleado_familiar e ON f.Id_Familiar = e.Id_Familiar WHERE f.Nombre_Familiar like ? AND e.Numero_Empleado = ?");
    $queryChecarPF->bind_param("si", $nombre_familiar, $_SESSION['Numero_Empleado']);
    $queryChecarPF->execute();
    $result = $queryChecarPF->get_result();
    $row = $result->fetch_assoc();
    $queryChecarPF->close();
    
    if ($row)
    {
        $tipo = "Financiera";
        $queryInsertP = $conn->prepare("INSERT INTO prestacion (Tipo) VALUES (?)");
        $queryInsertP->bind_param("s", $tipo);
        $queryInsertP->execute();
        $id_prestacion = $conn->insert_id;
        $queryInsertP->close();
        
        $queryInsertPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo) VALUES (?, ?, ?)");
        $queryInsertPE->bind_param("iis", $_SESSION['Numero_Empleado'], $id_prestacion, $tipo);
        $queryInsertPE->execute();
        $queryInsertPE->close();

        $queryInsertPEE = $conn->prepare("INSERT INTO familiar_prestacion (Id_Familiar,Id_Prestacion,Tipo) VALUES (?, ?, ?)");
        $queryInsertPEE->bind_param("iis", $row['Id_Familiar'], $id_prestacion, $tipo);
        $queryInsertPEE->execute();
        $queryInsertPEE->close();


        if ($tipo_pago == "Deposito")
        {
            $deposito = 1;
            $reembolso = 0;
        }
        else
        {
            $deposito = 0;
            $reembolso = 1;
        }



        $queryInsertPF = $conn->prepare("INSERT INTO prestacion_apoyofinanciero (Id_Prestacion,Numero_Empleado,Id_Familiar,Tipo,Deposito,Reembolso) VALUES (?,?,?,?,?,?)");
        $queryInsertPF->bind_param("iiisii", $id_prestacion, $_SESSION['Numero_Empleado'], $row['Id_Familiar'], $tipo, $deposito, $reembolso);
        $queryInsertPF->execute();
        $queryInsertPF->close();
        echo "Solicitud enviada correctamente";
    }
    else
    {
        echo "Error, no se encontró el familiar";
    }

   




    
}

?>
