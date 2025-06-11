<?php
session_start();
require_once("conn.php");
?>

<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleado</title>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="./styleRegistrarFamiliares.css">
    <!-- SIMBOLOS QUE SE UTILIZARAN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"
    rel="stylesheet">
</head>
<body>
    <!-- BARRA LATERAL -->
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                        <img src="./images/logo.png.png">
                        <h2>Empleado<span class="danger">
                            UTN</span> </h2>
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
                <a href="registrarfamiliares.php"  class="active">
                    <span class="material-icons-sharp">people</span>
                    <h3>Registrar familiar para prestamo</h3>
                </a>
                <a href="SOLICITUDprestacionesfinancieras.php">
                    <span class="material-icons-sharp">payments</span>
                    <h3>Solicitud de prestacion: Apoyo financiero</h3>
                </a>
                <a href="SOLICITUDprestacionapoyoacademico.php">
                    <span class="material-icons-sharp">school</span>
                    <h3>Solicitud de prestacion: Apoyo academico</h3>
                </a>
                <a href="SOLICITUDprestaciondia.php">
                    <span class="material-icons-sharp">today</span>
                    <h3>Solicitar un dia</h3>
                </a>
                <a href="SOLICITUDprestacionplazo.php">
                    <span class="material-icons-sharp">date_range</span>
                    <h3>Solicitar un plazo</h3>
                </a>
            </div>
        </aside>
    <!-- FIN DE BARRA LATERAL -->

    <!-- CONTENIDO PRINCIPAL -->
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

            <h1>Subida de documentos para tus solicitudes</h1>
<body>
<main>
<div class="prestamos-recientes">
                <h2>Prestamos Recientes</h2>

                <table>
                    <thead>
                    <tr>
                    <th>Tipo</th>
                    <th>Fecha Solicitada</th>
                    <th>Especificaciones</th>
                    <th>Estado</th> 
                    <th>SUbir documentos</th>       
                    <th>Ver documentos</th>       
                    </thead>
                    <tbody>
        <?php
            $queryCPEE = $conn -> prepare("SELECT Id_Prestacion FROM empleado_prestacion WHERE Numero_Empleado = ?");
            $queryCPEE->bind_param("i", $_SESSION['Numero_Empleado']);
            $queryCPEE->execute();
            $resultCPEE = $queryCPEE->get_result();
            while($rowCPEE = $resultCPEE->fetch_assoc())
            {
                    $querySPR = $conn->prepare("SELECT * FROM prestacion WHERE Id_Prestacion = ? ORDER BY Fecha_Solicitada ");
                    $querySPR->bind_param("i", $rowCPEE['Id_Prestacion']);
                    $querySPR->execute();
                    $resultSPR = $querySPR->get_result();

                    while($rowSPR = $resultSPR->fetch_assoc())
                    {
                        switch ($rowSPR['Tipo'])
                        {
                            case "Academico":
                                $queryGN = $conn->prepare("SELECT PA.Tipo, PA.Id_Prestacion, F.Nombre_Familiar FROM prestacion_apoyoacademico
                                                           AS PA INNER JOIN familiar_empleado AS F ON PA.Id_Familiar = F.Id_Familiar
                                                           WHERE PA.Id_Prestacion = ?");
                                $queryGN->bind_param("i", $rowSPR['Id_Prestacion']);
                                $queryGN->execute();
                                $resultGN = $queryGN->get_result();
                                $rowGN = $resultGN->fetch_assoc();
                                $tipo = $rowGN['Tipo'];
                                $especificos = 'De tipo: '.$rowGN['Tipo'].' y el familiar: '.$rowGN['Nombre_Familiar'];
                            break;
                            
                            case "Financiera":
                                $queryGN = $conn->prepare("SELECT PF.Tipo, PF.Id_Prestacion, F.Nombre_Familiar, PF.Deposito FROM prestacion_apoyofinanciero
                                                           AS PF INNER JOIN familiar_empleado AS F ON PF.Id_Familiar = F.Id_Familiar
                                                           WHERE PF.Id_Prestacion = ?");
                                $queryGN->bind_param("i", $rowSPR['Id_Prestacion']);
                                $queryGN->execute();
                                $resultGN = $queryGN->get_result();
                                $rowGN = $resultGN->fetch_assoc();
                                $tipo = $rowGN['Tipo'];
                                if ($rowGN['Deposito'] == 1)
                                {
                                    $especificos = 'De tipo: '.$rowGN['Tipo'].' y el familiar: '.$rowGN['Nombre_Familiar'].' con deposito';
                                }
                                else
                                {
                                    $especificos = 'De tipo: '.$rowGN['Tipo'].' y el familiar: '.$rowGN['Nombre_Familiar'].' con reembolso';
                                }
                            break;

                            case "Día":
                                $queryGN = $conn->prepare("SELECT Motivo, Fecha_Solicitada, Dia_Extra FROM prestacion_dias
                                                           WHERE Id_Prestacion = ?");
                                $queryGN->bind_param("i", $rowSPR['Id_Prestacion']);
                                $queryGN->execute();
                                $resultGN = $queryGN->get_result();
                                $rowGN = $resultGN->fetch_assoc();
                                $tipo = $rowGN['Motivo'];
                                if ($rowGN['Dia_Extra'] == 1)
                                {
                                    $especificos = 'Pidiendo el dia '.htmlspecialchars($rowGN['Fecha_Solicitada']).' con motivo: '.$rowGN['Motivo'].' y usa un dia extra';
                                }
                                else
                                {
                                    $especificos = 'Pidiendo el dia '.htmlspecialchars($rowGN['Fecha_Solicitada']).' con motivo: '.$rowGN['Motivo'].'';
                                }
                            break;

                            case "Plazo":
                                $queryGN = $conn->prepare("SELECT Tipo, Fecha_Inicio, Fecha_Final FROM prestacion_plazos
                                                           WHERE Id_Prestacion = ?");
                                $queryGN->bind_param("i", $rowSPR['Id_Prestacion']);
                                $queryGN->execute();
                                $resultGN = $queryGN->get_result();
                                $rowGN = $resultGN->fetch_assoc();
                                $tipo = $rowGN['Tipo'];
                                $especificos = 'De tipo: '.$rowGN['Tipo'].' desde '.htmlspecialchars($rowGN['Fecha_Inicio']).' hasta '.htmlspecialchars($rowGN['Fecha_Final']);
                            break;

                            default:
                                $especificos = 'No hay detalles adicionales disponibles.';
                            break;

                            // ^ Esto deberia ser una funcion, pero ya lo hice asi.    
                        }
                      
                    
                      echo "<div class='benefits-container'>";
                      echo "<td>".$rowSPR['Tipo']."</td>";
                      echo "<td>FECHA: ".$rowSPR['Fecha_Solicitada']."</td>";
                      echo "<td>".$especificos."</td>";

                        if (is_null($rowSPR['Fecha_Otorgada']))
                        {
                            echo "<td class=".'warning'.">En espera</td>";
                        }
                        else
                        {
                            echo "<td class=".'success'.">Concedido</td>";
                        }
                      echo '
                        <td>
                        <form action="" method="POST">
                        <input type="hidden" name="mostrar_subirDocumentos" value="1">
                        <input type="hidden" name="fecha_solicitada" value="'.$rowSPR['Fecha_Solicitada'].'">
                        <input type="hidden" name="prestacion_id" value="'.$rowSPR['Id_Prestacion'].'">
                        <input type="hidden" name="tipoMayor" value="'.$rowSPR['Tipo'].'">
                        <input type="hidden" name="tipo_prestacion" value="'.$tipo.'"> 
                        <button type="submit"> Subir documentos de esta solicitud</button>
                        </form>
                        </td>
                      ';  

                      echo '
                        <td>
                        <form action="mostrarDocumentosDe.php" method="POST">
                        <input type="hidden" name="tipoMayor" value="'.$rowSPR['Tipo'].'">
                        <input type="hidden" name="tipo_prestacion" value="'.$tipo.'"> 
                        <input type="hidden" name="prestacion_id" value="'.$rowSPR['Id_Prestacion'].'">
                        <input type="hidden" name="numero_empleado" value="'.$_SESSION['Numero_Empleado'].'">
                        <button type="submit"> Ver documentos de esta solicitud</button>
                        </form>
                        </td>
                      ';
                      echo "</tr>";
                    }
            }                 
        ?>
                        
                    </tbody>
                    
                </table>

            </div>
        </main>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {

    if (isset($_POST['mostrar_subirDocumentos']))
    {
        unset($_POST['mostrar_subirDocumentos']);
        $fecha_solicitada = $_POST['fecha_solicitada'];
        $prestacion_id = $_POST['prestacion_id'];
        $tipo_prestacion = $_POST['tipo_prestacion'];
        $tipoMayor = $_POST['tipoMayor'];

        require_once("documentosPrestaciones.php");
        $documentosRequeridos = queDocumentos($tipo_prestacion);

        if ($documentosRequeridos !== "Prestación no encontrada o sin documentos definidos.") {
            echo "<div class='prestamos-recientes'>";
            echo "<h2>Documentos requeridos para la prestación de tipo: $tipo_prestacion</h2>";
            echo "<p>$documentosRequeridos</p>";
            echo "</div>";
        }
        else {
            echo "<h2>Error: $documentosRequeridos</h2>";
        }

        echo 
        '
            <form action="" method="POST" enctype="multipart/form-data">
                <h2>Subir documentos para la prestación</h2>
                <input type="hidden" name="subirDocumentos" value="1">
                <input type="hidden" name="numero_empleado" value="'.$_SESSION['Numero_Empleado'].'">
                <input type="hidden" name="prestacion_id" value="'.$prestacion_id.'">
                <input type="hidden" name="fecha_solicitada" value="'.$fecha_solicitada.'">
                <input type="hidden" name="tipo_prestacion" value="'.$tipo_prestacion.'">
                <input type="hidden" name="tipoMayor" value="'.$tipoMayor.'">
                <label for="documento">Selecciona los documentos a subir:</label>
                <input type ="file" name="documentosPrestacion[]" accept=".pdf" multiple required>
                <br>
                <button type="submit" class="btn">Subir Documentos</button>
            </form>
        ';

    }

    if (isset($_POST['subirDocumentos'])) 
    {
        unset($_POST['subirDocumentos']);
        $numero_empleado = $_POST['numero_empleado'];
        $prestacion_id = $_POST['prestacion_id'];
        $fecha_solicitada = $_POST['fecha_solicitada'];
        $tipo_prestacion = $_POST['tipo_prestacion'];
        $tipoMayor = $_POST['tipoMayor'];
        $documentosPrestacion = $_FILES['documentosPrestacion'];
        $errors = [];

        if (empty($documentosPrestacion['name'][0])) {
            $errors[] = "Error al subir los documentos de la prestación.";
        }

        if (empty($errors)) {
            $rutaDocumentos = "DocumentosPrestaciones/Solicitudes/".$numero_empleado."/"
            .$tipoMayor."/".$tipo_prestacion."/".$prestacion_id."/";
            if (!is_dir($rutaDocumentos)) {
                mkdir($rutaDocumentos, 0777, true);
            }

            foreach ($documentosPrestacion['name'] as $i => $nombreArchivo) 
            {
                if ($documentosPrestacion['error'][$i] === UPLOAD_ERR_OK) 
                {
                    $rutaArchivo = $rutaDocumentos . basename($nombreArchivo);
                    if (move_uploaded_file($documentosPrestacion['tmp_name'][$i], $rutaArchivo)) 
                    {
                        echo "<script>console.log('Archivo subido: ".htmlspecialchars($nombreArchivo)."');</script>";
                    } 
                    else 
                    {
                        $errors[] = "Error al mover el archivo: ".htmlspecialchars($nombreArchivo);
                    }
                } 
                else 
                {
                    $errors[] = "Error al subir el archivo: ".htmlspecialchars($nombreArchivo);
                }
            }    
        }

    }




}
    
?>

</body>
</html>