<?php
include_once("error_handler.php");
require_once("conn.php");

session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
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
    <title>Empleado</title>
    <!-- ASIGNACION DE CSS -->
    <link href="lightbox.min.css" rel="stylesheet">
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
                <a href="registrarfamiliares.php">
                    <span class="material-icons-sharp">people</span>
                    <h3>Registrar familiar para prestamo</h3>
                </a>
                <a href="SOLICITUDprestacionesfinancieras.php">
                    <span class="material-icons-sharp">payments</span>
                    <h3>Solicitud de prestacion: Apoyo financiero</h3>
                </a>
                <a href="SOLICITUDprestacionapoyoacademico.php" class="active">
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


    <h1>Solicitud de Apoyo Académico</h1>
    <h2>Se recuerda que se necesita presentar el comprobante de inscripcion del beneficiado</h2>
    <br>
    <form action="" method="post">
        <label for="nombre_familiar"><h5>Nombre del Familiar:</h5></label>
        <input type="text" id="nombre_familiar" name="nombre_familiar" placeholder="Nombre Del Familiar" required><br><br>

        <label for="nombre_institucion"><h5>Nombre de la Institución:</h5></label>
        <input type="text" id="nombre_institucion" name="nombre_institucion" placeholder="Nombre De la Institución"><br><br>

        <label for="tipo"><h5>Tipo de Apoyo:</h5></label>
        <select id="tipo" name="tipo">
<?php
require_once("conn.php");

$queryCon = $conn->prepare("SELECT nombre FROM tiposprestacion Where tipoMayor = 'Academico'");
$queryCon->execute();
$result = $queryCon->get_result();
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
}
$queryCon->close();


?>

        </select><br><br>

        <div class="button-container">
        <button type="submit">Enviar Solicitud</button>
        </div>
    </form>

    <div id="pdfLightbox" class="lightbox" style="display: none;">
        <div class="lb-outerContainer">
          
            <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="100%">
    
            
            <a href="SOLICITUDprestacionapoyoacademico.php" class="lb-close" onclick="cerrarPDF()">x</a>
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


document.getElementById('pdfViewer').src = '';
}

</script>

<?php

require_once("conn.php");
include_once("error_handler.php");

session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST")
{

$nombre_familiar = $_POST['nombre_familiar'];
$nombre_institucion = $_POST['nombre_institucion'];
$tipoApoyo = $_POST['tipo'];

require_once("ESTADOsepuedeprestacion.php");
$prestacionesPermitidas = verificarPrestaciones($_SESSION['Numero_Empleado']);

if (!$prestacionesPermitidas['Academico'][$tipoApoyo]) {
echo "<script>alert('No se puede solicitar este tipo de apoyo académico debido a que ya te lo otorgaron este cuatrimestre');</script>";
exit;
echo "<script>location.reload();</script>"; 
} 


////////////////////
$queryCheckToday = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Tipo = 'Academico' AND DATE(Fecha_Solicitada) = CURDATE()");
$queryCheckToday->bind_param("i", $_SESSION['Numero_Empleado']);
$queryCheckToday->execute();
$resultCheckToday = $queryCheckToday->get_result();

while($rowCheckToday = $resultCheckToday->fetch_assoc()) {
    $queryCheckTodayPlus = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ? AND Tipo = ?");
    $queryCheckTodayPlus->bind_param("is", $rowCheckToday['Id_Prestacion'], $tipoApoyo);
    $queryCheckTodayPlus->execute();
    $resultCheckTodayPlus = $queryCheckTodayPlus->get_result();
    $rowCheckTodayPlus = $resultCheckTodayPlus->fetch_assoc();

        if ($rowCheckTodayPlus) {
                echo "<script>alert('Ya has solicitado este tipo de apoyo Academico el día de hoy.');</script>";
                exit;
        }
}

////////////////////


$queryChecarPF = $conn->prepare("SELECT * FROM familiar_empleado f INNER JOIN empleado_familiar e ON f.Id_Familiar = e.Id_Familiar WHERE f.Nombre_Familiar like ? AND e.Numero_Empleado = ?");
$queryChecarPF->bind_param("si", $nombre_familiar, $_SESSION['Numero_Empleado']);
$queryChecarPF->execute();
$result = $queryChecarPF->get_result();
$row = $result->fetch_assoc();
$nivel_academico = $row['Nivel_academico'];
$queryChecarPF->close();

if ($row)
{

    if ($tipoApoyo == "Exencion de inscripcion" && $nivel_academico != "Universidad" && (strpos($nombre_institucion, "UTN") === false && strpos($nombre_institucion, "Universidad Tecnologica de Nogales") === false))
    {
        echo "No se puede solicitar exención de inscripción para esta institución y/o nivel académico del familiar no es Universidad"; 
    }
    else
    {
        
    

    $tipo = "Academico";
    $queryInsertP = $conn->prepare("INSERT INTO prestacion (Tipo) VALUES (?)");
    $queryInsertP->bind_param("s", $tipo);
    $queryInsertP->execute();
    $id_prestacion = $conn->insert_id;
    $queryInsertP->close();
    
    $queryInsertPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo) VALUES (?, ?, ?)");
    $queryInsertPE->bind_param("iis", $_SESSION['Numero_Empleado'], $id_prestacion, $tipo);
    $queryInsertPE->execute();
    $queryInsertPE->close();

    $queryInsertPF = $conn->prepare("INSERT INTO familiar_prestacion (Id_Familiar, Id_Prestacion, Tipo) VALUES (?, ?, ?)");
    $queryInsertPF->bind_param("iis", $row['Id_Familiar'], $id_prestacion, $tipo);
    $queryInsertPF->execute();
    $queryInsertPF->close();
    
    $queryInsertPA = $conn->prepare("INSERT INTO prestacion_apoyoacademico (Id_Prestacion, Numero_Empleado, Id_Familiar, Nivel_Academico, Nombre_Institucion, Tipo) VALUES (?, ?, ?, ?, ?, ?)");
    $queryInsertPA->bind_param("iiisss", $id_prestacion, $_SESSION['Numero_Empleado'], $row['Id_Familiar'], $nivel_academico, $nombre_institucion, $tipoApoyo);
    $queryInsertPA->execute();
    $queryInsertPA->close();

    switch ($tipoApoyo)
    {
        case "Utiles":
            echo '<script>mostrarPDF("PDF Prestaciones/Utiles Escolares/Prestacion Utiles Escolares (Solicitud).pdf")</script>';
            break;
        
        default:
            echo '<script>mostrarPDF("")</script>';
            break;

    }
    
    echo "Solicitud enviada correctamente";
    }
}
else
{
    echo "No se encontró el familiar";
}

}





?>

<script src="./index.js"></script>

<script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll("input[type='text']");

            inputs.forEach(input => {
                input.addEventListener("input", function() {
                    // Limitar a 40 caracteres
                    if (this.value.length > 40) {
                        this.value = this.value.slice(0, 40);
                    }

                    // Eliminar números y caracteres especiales
                    this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
                });
            });
        });
    </script>





</body>
</html>


