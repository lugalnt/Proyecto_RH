<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Prestaciones</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Solicitudes de Prestaciones</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id Prestación</th>
                    <th>Empleado que la solicitó</th>
                    <th>Fecha solicitada</th>
                    <th>Tipo de prestación</th>
                    <th>Familiar (si aplica)</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>

<?php

require_once("conn.php");
session_start();


$querySP = $conn->prepare("SELECT * FROM prestacion WHERE Fecha_Otorgada IS NULL");  
$querySP->execute();
$resultadoSP = $querySP->get_result();
while($rowSP = $resultadoSP->fetch_assoc())
{
    $fechaSolicitud = $rowSP['Fecha_Solicitada'];
    $idPrestacion = $rowSP['Id_Prestacion'];

    $queryCNE = $conn->prepare("SELECT Numero_Empleado FROM empleado_prestacion WHERE Id_Prestacion = ?");
    $queryCNE->bind_param("i", $idPrestacion);
    $queryCNE->execute();
    $resultCNE = $queryCNE->get_result();
    $rowCNE = $resultCNE->fetch_assoc();

    $numeroEmpleado = $rowCNE['Numero_Empleado'];

    $queryCNME = $conn->prepare("SELECT Nombre_Empleado FROM empleado WHERE Numero_Empleado = ?");
    $queryCNME->bind_param("i", $numeroEmpleado);
    $queryCNME->execute();
    $resultCNME = $queryCNME->get_result();
    $rowCNME = $resultCNME->fetch_assoc();

    $nombreEmpleado = $rowCNME['Nombre_Empleado'];

    if ($rowSP['Tipo'] == "Academico")
    {
        $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
        $queryCPA->bind_param("i", $idPrestacion);
        $queryCPA->execute();
        $resultCPA = $queryCPA->get_result();
        $rowCPA = $resultCPA->fetch_assoc();

        $tipo = "Apoyo académico: ".$rowCPA['Tipo'];
    }

    if ($rowSP['Tipo'] == "Financiera")
    {
        $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
        $queryCPA->bind_param("i", $idPrestacion);
        $queryCPA->execute();
        $resultCPA = $queryCPA->get_result();
        $rowCPA = $resultCPA->fetch_assoc();

        $tipo = "Apoyo financiero: ".$rowCPA['Tipo'];
    }

    if ($rowSP['Tipo'] == "Día")
    {
        $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
        $queryCPD->bind_param("i", $idPrestacion);
        $queryCPD->execute();
        $resultCPD = $queryCPD->get_result();
        $rowCPD = $resultCPD->fetch_assoc();

        $tipo = "Día: ".$rowCPD['Motivo'];
    }

    $queryCFP = $conn->prepare("SELECT * FROM familiar_prestacion WHERE Id_Prestacion = ?");
    $queryCFP->bind_param("i", $idPrestacion);
    $queryCFP->execute();
    $resultCFP = $queryCFP->get_result();

    if ($resultCFP->num_rows > 0)
    {
        $rowCFP = $resultCFP->fetch_assoc();
        $idFamiliar = $rowCFP['Id_Familiar'];

        $queryCF = $conn->prepare("SELECT Nombre_Familiar FROM familiar_empleado WHERE Id_Familiar = ?");
        $queryCF->bind_param("i", $idFamiliar);
        $queryCF->execute();
        $resultCF = $queryCF->get_result();
        $rowCF = $resultCF->fetch_assoc();

        $nombreFamiliar = $rowCF['Nombre_Familiar'];
    }
    else
    {
        $nombreFamiliar = "N/A";
    }

    echo "<tr>";
    echo "<td>".$idPrestacion."</td>";
    echo "<td>".$numeroEmpleado.", ".htmlspecialchars($nombreEmpleado)."</td>";
    echo "<td>".$fechaSolicitud."</td>";
    echo "<td>".$tipo."</td>";
    echo "<td>".$nombreFamiliar."</td>";
    echo "<td>";
    echo "<form action='' method='post'>";
    echo "<input type='hidden' name='idPrestacion' value='".$idPrestacion."'>";
    echo "<button type='submit' class='btn btn-primary'>Otorgar prestación</button>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
}

?>

            </tbody>
        </table>
    </div>
</body>
</html>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{

    $idPrestacion = $_POST['idPrestacion'];   
 
    $queryOP = $conn->prepare("UPDATE prestacion SET Fecha_Otorgada = CURRENT_DATE WHERE Id_Prestacion = ?");
    $queryOP->bind_param("i", $idPrestacion);
    $queryOP->execute();
    $queryOP->close();

    $queryOPE = $conn->prepare("UPDATE empleado_prestacion SET Fecha_Otorgada = CURRENT_DATE WHERE Id_Prestacion = ?");
    $queryOPE->bind_param("i", $idPrestacion);
    $queryOPE->execute();
    $queryOPE->close();
    

    $queryCFP = $conn->prepare("SELECT * FROM familiar_prestacion WHERE Id_Prestacion = ?");
    $queryCFP->bind_param("i", $idPrestacion);
    $queryCFP->execute();
    $resultCFP = $queryCFP->get_result();

    if ($resultCFP->num_rows > 0)
    {
        $queryOPF = $conn->prepare("UPDATE familiar_prestacion SET Fecha_Otorgada = CURRENT_DATE WHERE Id_Prestacion = ?");
        $queryOPF->bind_param("i", $idPrestacion);
        $queryOPF->execute();
        $queryOPF->close();

    }

    echo '<script type="text/javascript">
    alert("Prestación otorgada");
    </script>';
    echo("<meta http-equiv='refresh' content='1'>");

 


}

?>