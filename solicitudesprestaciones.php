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
    <link href="lightbox.min.css" rel="stylesheet">
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
                <a href="convenioNuevo.php">
                    <span class="material-icons-sharp">article</span>
                    <h3>Convenios</h3>
                </a>
                <a href="RPPP.php">
                    <span class="material-icons-sharp">fact_check</span>
                    <h3>RPPP</h3>
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
            <br>
            <div class="filtros-container">
    <form action="" method="post">
        <input type="hidden" name="aplicarFiltros" value="1">
        <div class="filtro-item">
            <label for="prestacionFiltro"><h3>Filtros</h3></label>
            <select name="prestacionFiltro" id="prestacionFiltro" required onchange="mostrarSelectEspecifico()">
                <option value="todos">Todos</option>
                <option value="Academico">Académicas</option>
                <option value="Financiera">Financieras</option>
                <option value="Día">Día</option>
                <option value="Plazo">Plazo</option>
            </select>
        </div>
        <div class="filtro-item" id="filtro-academico" style="display:none;">
            <label for="especificoAcademico">Tipo de apoyo académico:</label>
            <select name="especifico" id="especificoAcademico">
                <?php
                $queryTiposAcademicos = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Academica'");
                $queryTiposAcademicos->execute();
                $resultTiposAcademicos = $queryTiposAcademicos->get_result();
                if ($resultTiposAcademicos->num_rows > 0) {
                    while ($row = $resultTiposAcademicos->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay tipos académicos disponibles</option>';
                }
                $queryTiposAcademicos->close();
                ?>
                <!-- Agrega más opciones si es necesario -->
            </select>
        </div>
        <div class="filtro-item" id="filtro-financiera" style="display:none;">
            <label for="especificoFinanciera">Tipo de apoyo financiero:</label>
            <select name="especifico" id="especificoFinanciera">
                <?php
                $queryTiposAcademicos = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Financiera'");
                $queryTiposAcademicos->execute();
                $resultTiposAcademicos = $queryTiposAcademicos->get_result();
                if ($resultTiposAcademicos->num_rows > 0) {
                    while ($row = $resultTiposAcademicos->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay tipos financieros disponibles</option>';
                }
                $queryTiposAcademicos->close();
                ?>
                <!-- Agrega más opciones si es necesario -->
            </select>
        </div>
        <div class="filtro-item" id="filtro-dia" style="display:none;">
            <label for="especificoDia">Motivo del día:</label>
            <select name="especifico" id="especificoDia">
                <?php
                $queryTiposAcademicos = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Dia'");
                $queryTiposAcademicos->execute();
                $resultTiposAcademicos = $queryTiposAcademicos->get_result();
                if ($resultTiposAcademicos->num_rows > 0) {
                    while ($row = $resultTiposAcademicos->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay tipos de dia</option>';
                }
                $queryTiposAcademicos->close();
                ?>
            </select>
        </div>
        <div class="filtro-item" id="filtro-plazo" style="display:none;">
            <label for="especificoPlazo">Tipo de plazo:</label>
            <select name="especifico" id="especificoPlazo">
                <?php
                $queryTiposAcademicos = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Plazo'");
                $queryTiposAcademicos->execute();
                $resultTiposAcademicos = $queryTiposAcademicos->get_result();
                if ($resultTiposAcademicos->num_rows > 0) {
                    while ($row = $resultTiposAcademicos->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay tipos de plazo disponibles</option>';
                }
                $queryTiposAcademicos->close();
                ?>
            </select>
        </div>
        <div class="filtro-item">
            <button type="submit" class="btn btn-primary">Aplicar filtros</button>
        </div>
    </form>
<button class="button" onclick="mostrarPDF('prestacionesRecientes.php')">Ver 20 ultimas prestaciones</a>
    <script>
    function mostrarSelectEspecifico() {
        // Oculta todos los selects
        document.getElementById('filtro-academico').style.display = 'none';
        document.getElementById('filtro-financiera').style.display = 'none';
        document.getElementById('filtro-dia').style.display = 'none';
        document.getElementById('filtro-plazo').style.display = 'none';

        // Deselecciona todos los selects específicos
        document.getElementById('especificoAcademico').selectedIndex = -1;
        document.getElementById('especificoFinanciera').selectedIndex = -1;
        document.getElementById('especificoDia').selectedIndex = -1;
        document.getElementById('especificoPlazo').selectedIndex = -1;

        var filtro = document.getElementById('prestacionFiltro').value;
        if (filtro === 'Academico') {
            document.getElementById('filtro-academico').style.display = 'block';
            document.getElementById('especificoAcademico').selectedIndex = 0;
        } else if (filtro === 'Financiera') {
            document.getElementById('filtro-financiera').style.display = 'block';
            document.getElementById('especificoFinanciera').selectedIndex = 0;
        } else if (filtro === 'Día') {
            document.getElementById('filtro-dia').style.display = 'block';
            document.getElementById('especificoDia').selectedIndex = 0;
        } else if (filtro === 'Plazo') {
            document.getElementById('filtro-plazo').style.display = 'block';
            document.getElementById('especificoPlazo').selectedIndex = 0;
        }
    }
    </script>
</div>
            <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aplicarFiltros'])) {
                $filtroPrestacion = $_POST['prestacionFiltro'];
                if ($filtroPrestacion == "Academico") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        echo "<script>console.log('Especifico: $especifico');</script>";
                        $querySP = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Academico' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                        $querySP->bind_param("s", $especifico);
                    }
                    else {
                        $querySP = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Academico' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                    }

                } elseif ($filtroPrestacion == "Financiera") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $querySP = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Financiera' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                        $querySP->bind_param("s", $especifico);
                    }
                    else {
                        $querySP = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Financiera' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                    }

                } elseif ($filtroPrestacion == "Día") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $querySP = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Día' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Motivo LIKE ?)");
                        $querySP->bind_param("s", $especifico);
                    }
                    else {
                        $querySP = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Día' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                    }



                } elseif ($filtroPrestacion == "Plazo") {


                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $querySP = $conn->prepare("SELECT * FROM prestacion p INNER JOIN prestacion_plazos pa ON p.Id_Prestacion = pa.Id_Prestacion WHERE p.Tipo = 'Plazo' AND p.Fecha_Otorgada IS NULL AND pa.Tipo LIKE ?");
                        $querySP->bind_param("s", $especifico);
                    }
                    else {
                        $querySP = $conn->prepare("SELECT * FROM prestacion WHERE Tipo = 'Plazo' AND Fecha_Otorgada IS NULL");
                    }
                    
                } else {
                    $querySP = $conn->prepare("SELECT * FROM prestacion WHERE Fecha_Otorgada IS NULL");
                }
            } else {
                $querySP = $conn->prepare("SELECT * FROM prestacion WHERE Fecha_Otorgada IS NULL");
            }
            $querySP->execute();
            $resultadoSP = $querySP->get_result();
            while ($rowSP = $resultadoSP->fetch_assoc()) {
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
                    $tipoMayor = "Academico";
                    $tipoEspecifico = $rowCPA['Tipo'];
                }

                if ($rowSP['Tipo'] == "Financiera") {
                    $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
                    $queryCPA->bind_param("i", $idPrestacion);
                    $queryCPA->execute();
                    $resultCPA = $queryCPA->get_result();
                    $rowCPA = $resultCPA->fetch_assoc();

                    $tipo = "Apoyo financiero: ".$rowCPA['Tipo'];
                    $tipoMayor = "Financiera";
                    $tipoEspecifico = $rowCPA['Tipo'];
                }

                if ($rowSP['Tipo'] == "Día") {
                    $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                    $queryCPD->bind_param("i", $idPrestacion);
                    $queryCPD->execute();
                    $resultCPD = $queryCPD->get_result();
                    $rowCPD = $resultCPD->fetch_assoc();

                    $tipo = "Día: ".$rowCPD['Motivo'];
                    $tipoMayor = "Día";
                    $tipoEspecifico = $rowCPD['Motivo'];
                }

                if($rowSP['Tipo'] == "Plazo") {
                    $queryCPP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
                    $queryCPP->bind_param("i", $idPrestacion);
                    $queryCPP->execute();
                    $resultCPP = $queryCPP->get_result();
                    $rowCPP = $resultCPP->fetch_assoc();

                    $tipo = "Plazo: ".$rowCPP['Tipo'];
                    $tipoMayor = "Plazo";
                    $tipoEspecifico = $rowCPP['Tipo'];
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
                                   echo'         <button type="submit" class="btn btn-primary">Otorgar Prestación</button>
                                        </form>
                                        <br>
                                        <form action="mostrarDocumentosDe.php" method="POST">
                                        <input type="hidden" name="tipoMayor" value="'.$tipoMayor.'">
                                        <input type="hidden" name="tipo_prestacion" value="'.$tipoEspecifico.'"> 
                                        <input type="hidden" name="prestacion_id" value="'.$idPrestacion.'">
                                        <input type="hidden" name="numero_empleado" value="'.$numeroEmpleado.'">
                                        <button type="submit"> Ver documentos de esta solicitud</button>
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
                                   echo'         <button type="submit" class="btn btn-primary">Otorgar Prestación</button>
                                        </form>
                                                                                <br>
                                        <form action="mostrarDocumentosDe.php" method="POST">
                                        <input type="hidden" name="tipoMayor" value="'.$tipoMayor.'">
                                        <input type="hidden" name="tipo_prestacion" value="'.$tipoEspecifico.'"> 
                                        <input type="hidden" name="prestacion_id" value="'.$idPrestacion.'">
                                        <input type="hidden" name="numero_empleado" value="'.$numeroEmpleado.'">
                                        <button type="submit"> Ver documentos de esta solicitud</button>
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
                echo "<button type='submit' class='btn btn-primary'>Otorgar Prestación</button>";
                echo "</form>";
                echo '
                    <br>
                    <form action="mostrarDocumentosDe.php" method="POST">
                    <input type="hidden" name="tipoMayor" value="'.$tipoMayor.'">
                    <input type="hidden" name="tipo_prestacion" value="'.$tipoEspecifico.'"> 
                    <input type="hidden" name="prestacion_id" value="'.$idPrestacion.'">
                    <input type="hidden" name="numero_empleado" value="'.$numeroEmpleado.'">
                    <button type="submit"> Ver documentos de esta solicitud</button>
                    </form>
                     ';
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


    <div id="pdfLightbox" class="lightbox" style="display: none;">
        <div class="lb-outerContainer">
          
            <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="100%">
    
            
            <a href="" class="lb-close" onclick="cerrarPDF()">x</a>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>

<script>
    function mostrarPDF(rutaPDF) {

document.getElementById('pdfViewer').src = rutaPDF;


document.getElementById('pdfLightbox').style.display = 'flex';
}

function cerrarPDF() {

document.getElementById('pdfLightbox').style.display = 'none';


document.getElementById('pdfViewer').src = '/ProyectoRH/solicitudesprestaciones.php'; 
}

</script>



</body>
</html>


<script src="./index.js"></script>