<?php
require_once("conn.php");
//include_once("error_handler.php");
session_start();

if(!isset($_SESSION['Numero_Empleado'])) {
    header('Location: login.html');
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST["logout"]))
    {
    session_destroy();
    header('Location: login.html');
    exit();
    }
}
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busqueda De Empleado</title>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="./styleSolicitudDePrestacionesxd.css">
    <!-- SIMBOLOS QUE SE UTILIZARAN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
</head>
<body>

    <!-- BARRA LATERAL -->
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="./images/logo.png.png">
                    <h2>Recursos<span class="danger"> Humanos</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>
            <div class="sidebar">
                <a href="index.php">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Menú</h3>
                </a>
                <a href="empleados.php">
                    <span class="material-icons-sharp">groups</span>
                    <h3>Empleados</h3>
                </a>
                <a href="solicitudesprestaciones.php" class="active">
                <span class="material-icons-sharp">payments</span>
                    <h3>Prestaciones</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">date_range</span>
                    <h3>Descansos</h3>
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="material-icons-sharp">logout</span>
                    <h3>Cerrar Sesión</h3>
                </a>
                <form id="logout-form" action="" method="POST" style="display: none;">
                    <input type="hidden" name="logout" value="1">
                </form>
            </div>
        </aside>
    
        <!-- FIN DE BARRA LATERAL -->

        <!-- APARTADO DE CUENTA Y CAMBIO DE MODO CLARO/OSCURO -->
        <div class="contenido"> 
            <div class="top">
                <button id="menu-btn">
                    <span class="material-icons-sharp">menu</span>
                </button>
                <div class="theme-toggler">
                    <span class="material-icons-sharp active">light_mode</span>
                    <span class="material-icons-sharp">dark_mode</span>
                </div>
                <div class="profile">
                    <div class="info">
                        <?php
                        echo '<p>Hey, <b>'.htmlspecialchars($_SESSION['Nombre_Empleado']).'</b></p>
                            <small class="text-muted">'.htmlspecialchars($_SESSION['Area']).'</small>';
                        ?>
                    </div>
                    <div class="profile-photo">
                        <img src="./images/profile-1.jpg.jpeg">
                    </div>
                </div>
            </div> 
            <!-- FIN DE APARTADO DE CUENTA Y CAMBIO DE MODO CLARO/OSCURO -->

            <h1>Solicitudes de Prestaciones Recientes</h1>
            <div class="button-container">
                <button type="button" class="button" onclick="window.location.href='buscarEmpleadoYPrestaciones.php'">Buscar Empleado y Prestaciones</button>
            </div>

            <table>
            <form>

            <select>
                <option value="0"></option>
                <option value="1">Prestaciones Otorgadas</option>
                <option value="2">Prestaciones No Otorgadas</option>
                <option value="3">Prestaciones Pendientes</option>
            </select>


            </form>
            </table>

            <?php
            $querySP = $conn->prepare("SELECT * FROM prestacion WHERE Fecha_Otorgada IS NULL");  
            $querySP->execute();
            $resultadoSP = $querySP->get_result();
            while($rowSP = $resultadoSP->fetch_assoc()) {
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

                if ($rowSP['Tipo'] == "Academico") {
                    $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
                    $queryCPA->bind_param("i", $idPrestacion);
                    $queryCPA->execute();
                    $resultCPA = $queryCPA->get_result();
                    $rowCPA = $resultCPA->fetch_assoc();

                    $tipo = "Apoyo académico: ".$rowCPA['Tipo'];
                }

                if ($rowSP['Tipo'] == "Financiera") {
                    $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
                    $queryCPA->bind_param("i", $idPrestacion);
                    $queryCPA->execute();
                    $resultCPA = $queryCPA->get_result();
                    $rowCPA = $resultCPA->fetch_assoc();

                    $tipo = "Apoyo financiero: ".$rowCPA['Tipo'];
                }

                if ($rowSP['Tipo'] == "Día") {
                    $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                    $queryCPD->bind_param("i", $idPrestacion);
                    $queryCPD->execute();
                    $resultCPD = $queryCPD->get_result();
                    $rowCPD = $resultCPD->fetch_assoc();

                    $tipo = "Día: ".$rowCPD['Motivo'];
                }

                if($rowSP['Tipo'] == "Plazo") {
                    $queryCPP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
                    $queryCPP->bind_param("i", $idPrestacion);
                    $queryCPP->execute();
                    $resultCPP = $queryCPP->get_result();
                    $rowCPP = $resultCPP->fetch_assoc();

                    $tipo = "Plazo: ".$rowCPP['Tipo'];
                }

                $queryCFP = $conn->prepare("SELECT * FROM familiar_prestacion WHERE Id_Prestacion = ?");
                $queryCFP->bind_param("i", $idPrestacion);
                $queryCFP->execute();
                $resultCFP = $queryCFP->get_result();

                if ($resultCFP->num_rows > 0) {
                    $rowCFP = $resultCFP->fetch_assoc();
                    $idFamiliar = $rowCFP['Id_Familiar'];

                    $queryCF = $conn->prepare("SELECT Nombre_Familiar FROM familiar_empleado WHERE Id_Familiar = ?");
                    $queryCF->bind_param("i", $idFamiliar);
                    $queryCF->execute();
                    $resultCF = $queryCF->get_result();
                    $rowCF = $resultCF->fetch_assoc();

                    $nombreFamiliar = $rowCF['Nombre_Familiar'];
                } else {
                    $nombreFamiliar = "N/A";
                }

                // Aquí se imprime la tabla con la columna extra si es de tipo día
                if ($rowSP['Tipo'] == "Día") {
                    echo '
                        <main>
                        <div class="prestamos-recientes">
                        <table class="table table-bordered prestamos-recientes">
                            <thead>
                                <tr>
                                    <th>Id Prestación</th>
                                    <th>Empleado que la solicitó</th>
                                    <th>Fecha solicitada</th>
                                    <th>Tipo de prestación</th>
                                    <th>Fecha Pedida</th>
                                    <th>Familiar (si aplica)</th>
                                    <th>Fecha Otorgada</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>' . htmlspecialchars($idPrestacion) . '</td>
                                    <td>' . htmlspecialchars($numeroEmpleado) . ', ' . htmlspecialchars($nombreEmpleado) . '</td>
                                    <td>' . htmlspecialchars($fechaSolicitud) . '</td>
                                    <td>' . htmlspecialchars($tipo) . '</td>
                                    <td>' . htmlspecialchars($rowCPD['Fecha_Solicitada']) . '</td>
                                    <td>' . htmlspecialchars($nombreFamiliar) . '</td>
                                    <td>' . htmlspecialchars($rowSP['Fecha_Otorgada']) . '</td>
                                    <td>' . htmlspecialchars($rowSP['Estado']) . '</td>
                                    <td>
                                        <form action="otorgarPrestaciones.php" method="post">
                                            <input type="hidden" name="idPrestacion" value="' . htmlspecialchars($idPrestacion) . '">';
                                            echo "<input type='hidden' name='tipoPrestacion' value='".htmlspecialchars($tipo)."'>";        
                                   echo'         <button type="submit" class="btn btn-primary">Otorgar prestación</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </main> 
                    ';
                } 

                // Aquí se imprime la tabla con la columna extra si es de tipo plazo
                if ($rowSP['Tipo'] == "Plazo") {
                    echo '
                        <main>
                        <div class="prestamos-recientes">
                        <table class="table table-bordered prestamos-recientes">
                            <thead>
                                <tr>
                                    <th>Id Prestación</th>
                                    <th>Empleado que la solicitó</th>
                                    <th>Fecha solicitada</th>
                                    <th>Tipo de prestación</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Final</th>
                                    <th>Familiar (si aplica)</th>
                                    <th>Fecha Otorgada</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>' . htmlspecialchars($idPrestacion) . '</td>
                                    <td>' . htmlspecialchars($numeroEmpleado) . ', ' . htmlspecialchars($nombreEmpleado) . '</td>
                                    <td>' . htmlspecialchars($fechaSolicitud) . '</td>
                                    <td>' . htmlspecialchars($tipo) . '</td>
                                    <td>' . htmlspecialchars($rowCPP['Fecha_Inicio']) . '</td>
                                    <td>' . htmlspecialchars($rowCPP['Fecha_Final']) . '</td>
                                    <td>' . htmlspecialchars($nombreFamiliar) . '</td>
                                    <td>' . htmlspecialchars($rowSP['Fecha_Otorgada']) . '</td>
                                    <td>' . htmlspecialchars($rowSP['Estado']) . '</td>
                                    <td>
                                        <form action="otorgarPrestaciones.php" method="post">
                                            <input type="hidden" name="idPrestacion" value="' . htmlspecialchars($idPrestacion) . '">';
                                            echo "<input type='hidden' name='tipoPrestacion' value='".htmlspecialchars($tipo)."'>";        
                                   echo'         <button type="submit" class="btn btn-primary">Otorgar prestación</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </main>
                    ';      
            } 

            // Aquí se imprime la tabla de los otros tipos de prestaciones
            if ($rowSP['Tipo'] != "Día" && $rowSP['Tipo'] != "Plazo") {
                echo '
                <main> 
                <div class="prestamos-recientes">
                <table class="table table-bordered">
                <thead>
                    <tr>
                    <th>Id Prestación</th>
                    <th>Empleado que la solicitó</th>
                    <th>Fecha solicitada</th>
                    <th>Tipo de prestación</th>
                    <th>Familiar (si aplica)</th>
                    <th>Fecha Otorgada</th>
                    <th>Estado</th>
                    <th>Acción</th>
                    </tr>
                </thead>
                <tbody>';
                echo "<tr>";
                echo "<td>".htmlspecialchars($idPrestacion)."</td>";
                echo "<td>".htmlspecialchars($numeroEmpleado).", ".htmlspecialchars($nombreEmpleado)."</td>";
                echo "<td>".htmlspecialchars($fechaSolicitud)."</td>";
                echo "<td>".htmlspecialchars($tipo)."</td>";
                echo "<td>".htmlspecialchars($nombreFamiliar)."</td>";
                echo "<td>".htmlspecialchars($rowSP['Fecha_Otorgada'])."</td>";
                echo "<td>".htmlspecialchars($rowSP['Estado'])."</td>";
                echo "<td>";
                echo "<form action='otorgarPrestaciones.php' method='post'>";
                echo "<input type='hidden' name='idPrestacion' value='".htmlspecialchars($idPrestacion)."'>";
                echo "<input type='hidden' name='tipoPrestacion' value='".htmlspecialchars($tipo)."'>";
                echo "<button type='submit' class='btn btn-primary'>Otorgar prestación</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>
                </tbody>
                </table>
                </div>
                </main>";
                
            }
        }
        ?>

        </tbody>
        </table>

        <!--/// DESMADRE TABLA///////////////////////////////////////////////////////////////////////////////////// -->

    </div>
</body>
</html>


<script src="./index.js"></script>