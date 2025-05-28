<?php
include_once("error_handler.php");
require_once("conn.php");
require_once("ESTADOempleados.php");
?>


<html>
<head>
    <link rel="stylesheet" href="./nuevostyle.css">
    <!-- SIMBOLOS QUE SE UTILIZARAN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"
    rel="stylesheet">
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: #f4f4f4;
    }
    .prestamos-recientes {
        background: #fff;
        padding: 32px 40px;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        min-width: 400px;
        max-width: 90vw;
    }
    table {
        margin: 0 auto;
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px 16px;
        text-align: left;
    }
    th {
        background: #f0f0f0;
    }
    .warning {
        color: #b8860b;
        font-weight: bold;
    }
    .success {
        color: #228B22;
        font-weight: bold;
    }
    body.dark-mode input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
    </style>
</head>
<body>
<main>
<div class="prestamos-recientes">
                <h2>Prestamos Recientes</h2>

                <table>
                    <thead>
                        <tr>
                    <th>Tipo</th>
                    <th>Empleado</th>
                    <th>Fecha Solicitada</th>
                    <th>Estado</th>        
                    </thead>
                    <tbody>
            <?php

                    $querySPR = $conn->prepare("SELECT * FROM prestacion ORDER BY Fecha_Solicitada DESC LIMIT 20");
                    $querySPR->execute();
                    $resultSPR = $querySPR->get_result();

                    while($rowSPR = $resultSPR->fetch_assoc())
                    {
                    
                      $queryCNE = $conn->prepare("SELECT Numero_Empleado FROM empleado_prestacion WHERE Id_Prestacion = ?");
                      $queryCNE->bind_param("i", $rowSPR['Id_Prestacion']);
                      $queryCNE->execute();
                      $resultCNE = $queryCNE->get_result();
                      $rowCNE = $resultCNE->fetch_assoc();
                    
                      $queryCNME = $conn->prepare("SELECT Nombre_Empleado FROM empleado WHERE Numero_Empleado = ?");
                      $queryCNME->bind_param("i", $rowCNE['Numero_Empleado']);
                      $queryCNME->execute();
                      $resultCNME = $queryCNME->get_result();
                      $rowCNME = $resultCNME->fetch_assoc();
                      $NombreEmpleado = $rowCNME['Nombre_Empleado'];
                    
                    
                    
                      echo "<div class='benefits-container'>";
                      echo "<td>".$rowSPR['Tipo']."</td>";
                      echo "<td>".$rowCNE['Numero_Empleado'].", ".htmlspecialchars($NombreEmpleado)."</td>";
                      echo "<td>FECHA: ".$rowSPR['Fecha_Solicitada']."</td>";

                        if (is_null($rowSPR['Fecha_Otorgada']))
                        {
                            echo "<td class=".'warning'.">En espera</td>";
                        }
                        else
                        {
                            echo "<td class=".'success'.">Concedido</td>";
                        }




                      echo "</tr>";
                    }

            ?>
                        
                    </tbody>
                    
                </table>

            </div>
        </main>
</body>
</html>