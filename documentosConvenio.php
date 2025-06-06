<?php
require_once("conn.php");
session_start();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busqueda De Empleado</title>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="./styleSolicitudDePrestacionesxd.css">
    <link rel="stylesheet" href="./styleRegistrarFamiliares.css">   
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
                <a href="solicitudesprestaciones.php">
                <span class="material-icons-sharp">payments</span>
                    <h3>Prestaciones</h3>
                </a>
                <a href="convenioNuevo.php"  class="active">
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

  
            <main>
                                <h1>Registrar plantillas de convenios</h1>
                        <h2>Por favor selecciona el convenio</h2>
                        <form action="" method="post" id="registroConvenioForm" enctype="multipart/form-data">
                            <label for="nombre_familiar"><h5>Nombre del convenio</h5></label>
                            <select name="idConvenio" required>
                            <?php
                            $queryGC = $conn->prepare("SELECT id,nombre,tipoMayor FROM tiposprestacion ORDER BY tipoMayor");
                            $queryGC->execute();
                            $resultGC = $queryGC->get_result();
                            if ($resultGC->num_rows > 0) {
                                while ($row = $resultGC->fetch_assoc()) {
                                    echo '<option value="'.$row['id'].'">'.$row['nombre'].' || '.$row['tipoMayor'].'</option>';
                                }
                            } else {
                                echo '<option value="">No hay convenios disponibles</option>';
                            }
                            ?>
                            </select>
                            <label for="documento"><h5>Plantillas de solicitud del convenio</h5></label>
                            <br>
                            <input type="file" name="plantillaSolicitud[]" accept=".pdf" multiple required>
                            <br>
                            <label for="documento"><h5>Plantillas de respuesta del convenio</h5></label>
                            <br>
                            <input type="file" name="plantillaRespuesta[]" accept=".pdf" multiple required>
                            <br>
                            <button type="submit" name="registrarConvenio" class="btn">Registrar Convenio</button>
                        </form>
            </main>

            
        </div>
    </div>
    <script src="./index.js"></script>
</body>
</html>



<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $idConvenio = $_POST['idConvenio'];
    $plantillaSolicitud = $_FILES['plantillaSolicitud'];
    $plantillaRespuesta = $_FILES['plantillaRespuesta'];
    $errors = [];


    if (empty($plantillaSolicitud['name'][0])) {
        $errors[] = "Error al subir la plantilla de solicitud.";
    }
    if (empty($plantillaRespuesta['name'][0])) {
        $errors[] = "Error al subir la plantilla de respuesta.";
    }

    if (empty($errors)) {
        $queryGTM = $conn->prepare("SELECT nombre, tipoMayor FROM tiposprestacion WHERE id = ?");
        $queryGTM->bind_param("i", $idConvenio);
        $queryGTM->execute();
        $resultGTM = $queryGTM->get_result();
        if ($resultGTM->num_rows > 0) {
            $rowGTM = $resultGTM->fetch_assoc();
            $nombreConvenio = $rowGTM['nombre'];
            $tipoMayor = $rowGTM['tipoMayor'];

            $rutaSolicitud = 'DocumentosPrestaciones/Plantillas/'.$tipoMayor.'/'.$nombreConvenio.'/Solicitud/';
            $rutaRespuesta = 'DocumentosPrestaciones/Plantillas/'.$tipoMayor.'/'.$nombreConvenio.'/Respuesta/';
            if (!is_dir($rutaSolicitud)) mkdir($rutaSolicitud, 0777, true);
            if (!is_dir($rutaRespuesta)) mkdir($rutaRespuesta, 0777, true);

            // Guardar todas las plantillas de solicitud
            foreach ($plantillaSolicitud['name'] as $i => $nombreArchivo) {
                echo "<script>console.log('Subiendo archivo: ".htmlspecialchars($nombreArchivo)."');</script>";
                if ($plantillaSolicitud['error'][$i] === UPLOAD_ERR_OK) {
                    $rutaArchivoSolicitud = $rutaSolicitud . basename($nombreArchivo);
                    if (move_uploaded_file($plantillaSolicitud['tmp_name'][$i], $rutaArchivoSolicitud)) {
                        echo "<script>alert('Plantilla de solicitud registrada correctamente: ".htmlspecialchars($nombreArchivo)."');</script>";
                    } else {
                        echo "<script>alert('Error al mover la plantilla de solicitud: ".htmlspecialchars($nombreArchivo)."');</script>";
                    }
                }
            }
            // Guardar todas las plantillas de respuesta
            foreach ($plantillaRespuesta['name'] as $i => $nombreArchivo) {
                echo "<script>console.log('Subiendo archivo: ".htmlspecialchars($nombreArchivo)."');</script>";
                if ($plantillaRespuesta['error'][$i] === UPLOAD_ERR_OK) {
                    $rutaArchivoRespuesta = $rutaRespuesta . basename($nombreArchivo);
                    if (move_uploaded_file($plantillaRespuesta['tmp_name'][$i], $rutaArchivoRespuesta)) {
                        echo "<script>alert('Plantilla de respuesta registrada correctamente: ".htmlspecialchars($nombreArchivo)."');</script>";
                    } else {
                        echo "<script>alert('Error al mover la plantilla de respuesta: ".htmlspecialchars($nombreArchivo)."');</script>";
                    }
                }
            }
        } else {
            echo "<script>alert('Convenio no encontrado.');</script>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
}
?>