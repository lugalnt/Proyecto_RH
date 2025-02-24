<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Empleado y Prestaciones</title>
    <link rel="stylesheet" href="./nuevostyle.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
body {
    font-family: Arial, sans-serif;
}

form {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-top: 10px;
}

input[type="text"] {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

h3 {
    margin-top: 20px;
}

ul {
    list-style-type: none;
    padding: 0;
}

ul li {
    padding: 5px;
    margin-bottom: 5px;
    border-radius: 4px;
}

ul li.permitido {
    background-color: #d4edda;
    color: #155724;
}

ul li.no-permitido {
    background-color: #f8d7da;
    color: #721c24;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}
    </style>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
    const tipoPrestacionElements = document.querySelectorAll("[data-tipo-prestacion]");

    tipoPrestacionElements.forEach(element => {
        const tipo = element.getAttribute("data-tipo-prestacion");
        const diaSolicitadoColumn = element.querySelector(".dia-solicitado");
        const fechaInicioColumn = element.querySelector(".fecha-inicio");
        const fechaFinalColumn = element.querySelector(".fecha-final");

        if (tipo === "Día") {
            diaSolicitadoColumn.style.display = "table-cell";
            fechaInicioColumn.style.display = "none";
            fechaFinalColumn.style.display = "none";
        } else if (tipo === "Plazo") {
            diaSolicitadoColumn.style.display = "none";
            fechaInicioColumn.style.display = "table-cell";
            fechaFinalColumn.style.display = "table-cell";
        } else {
            diaSolicitadoColumn.style.display = "none";
            fechaInicioColumn.style.display = "none";
            fechaFinalColumn.style.display = "none";
        }
    });
});
    </script>
</head>
<body>
    <h2>Buscar Empleado y Prestaciones</h2>
    <form action="" method="post">
        <label for="nombre">Nombre del Empleado:</label>
        <input type="text" id="nombre" name="nombre">
        <br>
        <label for="numero">Numero del Empleado:</label>
        <input type="text" id="numero" name="numero">
        <br>
        <button type="submit">Buscar</button>
    </form>
    <main>
    <div class="prestamos-recientes">

<?php

require_once("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $numero = $_POST["numero"];


    if (!empty($nombre) && empty($numero)) {
        $queryNE = $conn->prepare("SELECT Numero_Empleado FROM empleado WHERE Nombre_Empleado = ?");
        $queryNE->bind_param("s", $nombre);
        $queryNE->execute();
        $resultNE = $queryNE->get_result();
        $rowNE = $resultNE->fetch_assoc();

        if ($rowNE == NULL) {
            echo "No se encontró el empleado o no ha solicitado prestaciones.";
        } else {
            $numeroEmpleado = $rowNE['Numero_Empleado'];

            require_once("ESTADOsepuedeprestacion.php");

            if ($numeroEmpleado) {
                $prestacionesPermitidas = verificarPrestaciones($numeroEmpleado);

                echo '<h3>Prestaciones Permitidas</h3>';
                echo '<ul>';
                foreach ($prestacionesPermitidas as $categoria => $prestaciones) {
                    foreach ($prestaciones as $prestacion => $permitido) {
                        if ($permitido) {
                            echo '<li class="permitido">' . htmlspecialchars($categoria . ': ' . $prestacion) . '</li>';
                        }
                    }
                }
                echo '</ul>';

                echo '<h3>Prestaciones No Permitidas</h3>';
                echo '<ul>';
                foreach ($prestacionesPermitidas as $categoria => $prestaciones) {
                    foreach ($prestaciones as $prestacion => $permitido) {
                        if (!$permitido) {
                            echo '<li class="no-permitido">' . htmlspecialchars($categoria . ': ' . $prestacion) . '</li>';
                        }
                    }
                }
                echo '</ul>';
            }

            $queryCIE = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ?");
            $queryCIE->bind_param("i", $numeroEmpleado);
            $queryCIE->execute();
            $resultCIE = $queryCIE->get_result();

            while ($rowCIE = $resultCIE->fetch_assoc()) {
                $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ?");
                $queryGPE->bind_param("i", $numeroEmpleado);
                $queryGPE->execute();
                $resultGPE = $queryGPE->get_result();

                while ($rowGPE = $resultGPE->fetch_assoc()) {
                    $idPrestacion = $rowGPE['Id_Prestacion'];

                    $queryCE = $conn->prepare("SELECT * from prestacion WHERE Id_Prestacion = ?");
                    $queryCE->bind_param("i", $idPrestacion);
                    $queryCE->execute();
                    $resultCE = $queryCE->get_result();
                    $rowCE = $resultCE->fetch_assoc();

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

                    if ($rowGPE['Tipo'] == "Plazo") {
                        $queryCPP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
                        $queryCPP->bind_param("i", $idPrestacion);
                        $queryCPP->execute();
                        $resultCPP = $queryCPP->get_result();
                        $rowCPP = $resultCPP->fetch_assoc();

                        $tipo = "Plazo: " . $rowCPP['Tipo'];
                    }
//DESMADRE TABLA////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    echo '
                    <table>
                    <thead>
                        <tr>
                            <th>Nombre del Empleado</th>';

                if ($rowGPE['Tipo'] == "Día") {
                    echo'        
                            <th>Dia solicitado</th>';
                }
                else if ($rowGPE['Tipo'] == "Plazo") {
                    echo'        
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Finalización</th>';
                }            


                echo'        
                        <th>Fecha de Solicitud</th>
                        <th>Tipo de Prestación</th>
                        <th>Fecha de Otorgamiento</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody data-tipo-prestacion="' . htmlspecialchars($rowGPE['Tipo']) . '">
            ';



                echo '
                    <tr>
                        <td>' . htmlspecialchars($rowCIE['Nombre_Empleado']) . '</td>';
                
                if ($rowGPE['Tipo'] == "Día") {
                    echo '<td class="dia-solicitado">' . htmlspecialchars($rowCPD['Fecha_Solicitada']) . '</td>';
                } 
                else if ($rowGPE['Tipo'] == "Plazo") {
                    echo '<td class="fecha-inicio">' . htmlspecialchars($rowCPP['Fecha_Inicio']) . '</td>';
                    echo '<td class="fecha-final">' . htmlspecialchars($rowCPP['Fecha_Final']) . '</td>';
                } 


                echo'   <td>' . htmlspecialchars($rowCE['Fecha_Solicitada']) . '</td>
                        <td>' . htmlspecialchars($tipo) . '</td>
                        <td>' . htmlspecialchars($rowCE['Fecha_Otorgada']) . '</td>
                        <td>' . htmlspecialchars($rowCE['Estado']) . '</td>
                ';

                if ($rowCE['Estado'] == "Pendiente") {
                echo'
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="idPrestacion" value="' . htmlspecialchars($idPrestacion) . '">
                                <button type="submit" class="btn btn-primary">Otorgar prestación</button>
                            </form>
                        </td>
                    </tr>
                ';
                }
                echo'</tbody>
                </table>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////DESMADRE TABLA/////////


                }
            }
        }
    } else if (!empty($numero) && empty($nombre)) {
        $numeroEmpleado = $numero;

        $queryCIE = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ?");
        $queryCIE->bind_param("i", $numeroEmpleado);
        $queryCIE->execute();
        $resultCIE = $queryCIE->get_result();

        if ($resultCIE->num_rows == 0) {
            echo "No se encontró el empleado o no ha solicitado prestaciones.";
            exit();
        }

        require_once("ESTADOsepuedeprestacion.php");

        if ($numeroEmpleado) {
            $prestacionesPermitidas = verificarPrestaciones($numeroEmpleado);

            echo '<h3>Prestaciones Permitidas</h3>';
            echo '<ul>';
            foreach ($prestacionesPermitidas as $categoria => $prestaciones) {
                foreach ($prestaciones as $prestacion => $permitido) {
                    if ($permitido) {
                        echo '<li class="permitido">' . htmlspecialchars($categoria . ': ' . $prestacion) . '</li>';
                    }
                }
            }
            echo '</ul>';

            echo '<h3>Prestaciones No Permitidas</h3>';
            echo '<ul>';
            foreach ($prestacionesPermitidas as $categoria => $prestaciones) {
                foreach ($prestaciones as $prestacion => $permitido) {
                    if (!$permitido) {
                        echo '<li class="no-permitido">' . htmlspecialchars($categoria . ': ' . $prestacion) . '</li>';
                    }
                }
            }
            echo '</ul>';
        }

        while ($rowCIE = $resultCIE->fetch_assoc()) {
            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ?");
            $queryGPE->bind_param("i", $numeroEmpleado);
            $queryGPE->execute();
            $resultGPE = $queryGPE->get_result();

            while ($rowGPE = $resultGPE->fetch_assoc()) {
                $idPrestacion = $rowGPE['Id_Prestacion'];

                $queryCE = $conn->prepare("SELECT * from prestacion WHERE Id_Prestacion = ?");
                $queryCE->bind_param("i", $idPrestacion);
                $queryCE->execute();
                $resultCE = $queryCE->get_result();
                $rowCE = $resultCE->fetch_assoc();

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

                if ($rowGPE['Tipo'] == "Plazo") {
                    $queryCPP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
                    $queryCPP->bind_param("i", $idPrestacion);
                    $queryCPP->execute();
                    $resultCPP = $queryCPP->get_result();
                    $rowCPP = $resultCPP->fetch_assoc();

                    $tipo = "Plazo: " . $rowCPP['Tipo'];
                }
//DESMADRE TABLA////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    echo '
    <table>
    <thead>
        <tr>
            <th>Nombre del Empleado</th>';

    if ($rowGPE['Tipo'] == "Día") {
    echo'        
            <th>Dia solicitado</th>';
    }
    else if ($rowGPE['Tipo'] == "Plazo") {
    echo'        
            <th>Fecha de Inicio</th>
            <th>Fecha de Finalización</th>';
    }            


    echo'        
        <th>Fecha de Solicitud</th>
        <th>Tipo de Prestación</th>
        <th>Fecha de Otorgamiento</th>
        <th>Estado</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody data-tipo-prestacion="' . htmlspecialchars($rowGPE['Tipo']) . '">
    ';



    echo '
    <tr>
        <td>' . htmlspecialchars($rowCIE['Nombre_Empleado']) . '</td>';

    if ($rowGPE['Tipo'] == "Día") {
    echo '<td class="dia-solicitado">' . htmlspecialchars($rowCPD['Fecha_Solicitada']) . '</td>';
    } 
    else if ($rowGPE['Tipo'] == "Plazo") {
    echo '<td class="fecha-inicio">' . htmlspecialchars($rowCPP['Fecha_Inicio']) . '</td>';
    echo '<td class="fecha-final">' . htmlspecialchars($rowCPP['Fecha_Final']) . '</td>';
    } 


    echo'   <td>' . htmlspecialchars($rowCE['Fecha_Solicitada']) . '</td>
        <td>' . htmlspecialchars($tipo) . '</td>
        <td>' . htmlspecialchars($rowCE['Fecha_Otorgada']) . '</td>
        <td>' . htmlspecialchars($rowCE['Estado']) . '</td>
    ';

    if ($rowCE['Estado'] == "Pendiente") {
    echo'
        <td>
            <form action="" method="post">
                <input type="hidden" name="idPrestacion" value="' . htmlspecialchars($idPrestacion) . '">
                <button type="submit" class="btn btn-primary">Otorgar prestación</button>
            </form>
        </td>
    </tr>
    ';
    }
    echo'</tbody>
    </table>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////DESMADRE TABLA/////////
            }
        }
    } else {
        echo "Por favor, ingrese el nombre o el número del empleado.";
    }

    echo '
        </tbody>
        </table>
    ';
}

?>
</div>
</main>
</body>
</html>