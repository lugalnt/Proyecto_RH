<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Empleado y Prestaciones</title>
</head>
<body>
    <h1>Buscar Empleado y Prestaciones</h1>
    <form action="buscarEmpleadoYPrestaciones.php" method="post">
        <label for="nombre">Nombre del Empleado:</label>
        <input type="text" id="nombre" name="nombre">
        <br>
        <label>Y/O</label>
        <br>
        <label for="numero">Número de Empleado:</label>
        <input type="text" id="numero" name="numero">
        <br>
        <button type="submit">Buscar</button>
    </form>
</body>
</html>

<?php

require_once("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nombre']) && !isset($_POST['numero'])) {
        $nombre = $_POST['nombre'];

        $queryNE = $conn->prepare("SELECT Numero_Empleado FROM empleado WHERE Nombre_Empleado = ?");
        $queryNE->bind_param("s", $nombre);
        $queryNE->execute();
        $resultNE = $queryNE->get_result();
        $rowNE = $resultNE->fetch_assoc();

        if ($rowNE == NULL) {
            echo "No se encontró el empleado";
        } else {
            $numeroEmpleado = $rowNE['Numero_Empleado'];

            $queryCIE = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ?");
            $queryCIE->bind_param("i", $numeroEmpleado);
            $queryCIE->execute();
            $resultCIE = $queryCIE->get_result();

            if ($rowCIE == NULL) {
                echo "No se encontró el empleado";
            } else {
                while ($rowNE = $resultCIE->fetch_assoc()) {
                    $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ?");
                    $queryGPE->bind_param("i", $numeroEmpleado);
                    $queryGPE->execute();
                    $resultGPE = $queryGPE->get_result();

                    $queryCE = $conn->prepare("SELECT Estado from prestacion WHERE Id_Prestacion = ?");
                    $queryCE->bind_param("i", $idPrestacion);
                    $queryCE->execute();
                    $resultCE = $queryCE->get_result();
                    $rowCE = $resultCE->fetch_assoc();


                    while ($rowGPE = $resultGPE->fetch_assoc()) {
                        $idPrestacion = $rowGPE['Id_Prestacion'];

                        if ($rowGPE['Tipo'] == "Academico") {
                            $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
                            $queryCPA->bind_param("i", $idPrestacion);
                            $queryCPA->execute();
                            $resultCPA = $queryCPA->get_result();
                            $rowCPA = $resultCPA->fetch_assoc();

                            $tipo = "Apoyo académico: " . $rowCPA['Tipo'];
                        }

                        if ($rowGPE['Tipo'] == "Financiera") {
                            $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
                            $queryCPA->bind_param("i", $idPrestacion);
                            $queryCPA->execute();
                            $resultCPA = $queryCPA->get_result();
                            $rowCPA = $resultCPA->fetch_assoc();

                            $tipo = "Apoyo financiero: " . $rowCPA['Tipo'];
                        }

                        if ($rowGPE['Tipo'] == "Día") {
                            $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                            $queryCPD->bind_param("i", $idPrestacion);
                            $queryCPD->execute();
                            $resultCPD = $queryCPD->get_result();
                            $rowCPD = $resultCPD->fetch_assoc();

                            $tipo = "Día: " . $rowCPD['Motivo'];
                        }

                        echo '

                        <table>
                            <tr>
                                <th>Nombre del Empleado</th>
                                <th>Fecha de Solicitud</th>
                                <th>Tipo de Prestación</th>
                                <th>Fecha de Otorgamiento</th>
                                <th>Estado</th>
                            </tr>
                            <tr>
                                <td>' . $rowCIE['Nombre_Empleado'] . '</td>
                                <td>' . $rowGPE['Fecha_Solicitada'] . '</td>
                                <td>' . $tipo . '</td>
                                <td>' . $rowSP['Fecha_Otorgada'] . '</td>
                                <td>' . $rowCE['Estado'] . '</td>
                            </tr>

                        </table>


                        ';

                    }
                }
            }
        }
    }

    if (!isset($_POST['nombre']) && isset($_POST['numero'])) 
    {

            $queryCIE = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ?");
            $queryCIE->bind_param("i", $numeroEmpleado);
            $queryCIE->execute();
            $resultCIE = $queryCIE->get_result();

            if ($rowCIE == NULL) {
                echo "No se encontró el empleado";
            } else {
                while ($rowNE = $resultCIE->fetch_assoc()) {
                    $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ?");
                    $queryGPE->bind_param("i", $numeroEmpleado);
                    $queryGPE->execute();
                    $resultGPE = $queryGPE->get_result();

                    $queryCE = $conn->prepare("SELECT Estado from prestacion WHERE Id_Prestacion = ?");
                    $queryCE->bind_param("i", $idPrestacion);
                    $queryCE->execute();
                    $resultCE = $queryCE->get_result();
                    $rowCE = $resultCE->fetch_assoc();


                    while ($rowGPE = $resultGPE->fetch_assoc()) {
                        $idPrestacion = $rowGPE['Id_Prestacion'];

                        if ($rowGPE['Tipo'] == "Academico") {
                            $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
                            $queryCPA->bind_param("i", $idPrestacion);
                            $queryCPA->execute();
                            $resultCPA = $queryCPA->get_result();
                            $rowCPA = $resultCPA->fetch_assoc();

                            $tipo = "Apoyo académico: " . $rowCPA['Tipo'];
                        }

                        if ($rowGPE['Tipo'] == "Financiera") {
                            $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
                            $queryCPA->bind_param("i", $idPrestacion);
                            $queryCPA->execute();
                            $resultCPA = $queryCPA->get_result();
                            $rowCPA = $resultCPA->fetch_assoc();

                            $tipo = "Apoyo financiero: " . $rowCPA['Tipo'];
                        }

                        if ($rowGPE['Tipo'] == "Día") {
                            $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                            $queryCPD->bind_param("i", $idPrestacion);
                            $queryCPD->execute();
                            $resultCPD = $queryCPD->get_result();
                            $rowCPD = $resultCPD->fetch_assoc();

                            $tipo = "Día: " . $rowCPD['Motivo'];
                        }

                        echo '

                        <table>
                            <tr>
                                <th>Nombre del Empleado</th>
                                <th>Fecha de Solicitud</th>
                                <th>Tipo de Prestación</th>
                                <th>Fecha de Otorgamiento</th>
                                <th>Estado</th>
                            </tr>
                            <tr>
                                <td>' . $rowCIE['Nombre_Empleado'] . '</td>
                                <td>' . $rowGPE['Fecha_Solicitada'] . '</td>
                                <td>' . $tipo . '</td>
                                <td>' . $rowSP['Fecha_Otorgada'] . '</td>
                                <td>' . $rowCE['Estado'] . '</td>
                            </tr>

                        </table>


                        ';

                    }
                }
            }


    }



}

?>
