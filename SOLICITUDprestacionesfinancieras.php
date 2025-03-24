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
                                <a href="SOLICITUDprestacionesfinancieras.php" class="active">
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

        <h1>Solicitud de Apoyo Financiero</h1>
        <h2>Por favor, llena los siguientes campos:</h2>
        <form action="" method="post">
                <label for="nombre_familiar"><h5>Nombre del Familiar:</h5></label>
                <input type="text" id="nombre_familiar" name="nombre_familiar" placeholder="Nombre Del Familiar"><br><br>

                <label for="tipo"><h5>Tipo de Prestación:</h5></label>
                <select id="tipo" name="tipo">
                        <option value="Guarderia">Guardería</option>
                        <option value="Gastos funerarios">Gastos funerarios</option>
                        <option value="Lentes">Lentes</option>
                        <option value="Titulacion">Titulación</option>
                        <option value="Aparato Ortopedico">Aparato Ortopedico</option>
                </select><br><br>

                <label for="tipo_pago"><h5>Tipo de Pago:</h5></label>
                <select id="tipo_pago" name="tipo_pago">
                        <option value="Deposito">Depósito</option>
                        <option value="Reembolso">Reembolso</option>
                </select><br><br>
                
                <div class="button-container">
                <button type="submit">Enviar Solicitud</button>
                </div>
        </form>

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

<div id="pdfLightbox" class="lightbox" style="display: none;">
        <div class="lb-outerContainer">
          
            <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="100%">
    
            
            <a href="SOLICITUDprestacionesfinancieras.php" class="lb-close" onclick="cerrarPDF()">x</a>
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


</body>
</html>

<?php
require_once("conn.php");
include_once("error_handler.php");
session_start();


if ($_SERVER["REQUEST_METHOD"]=="POST")
{
        $tipo_pago = $_POST['tipo_pago'];
        $nombre_familiar = !empty($_POST['nombre_familiar']) ? "%".$_POST['nombre_familiar']."%" : "%N/A%";
        $tipoPF = $_POST['tipo'];
 
require_once("ESTADOsepuedeprestacion.php");
$prestacionesPermitidas = verificarPrestaciones($_SESSION['Numero_Empleado']);

if (!$prestacionesPermitidas['Financiera'][$tipoPF]) {
echo "<script>alert('No se puede solicitar este tipo de apoyo financiero debido a que ya te lo otorgaron este cuatrimestre');</script>";
exit;
echo "<script>location.reload();</script>"; 
} 


/////////////////////////////////////CHECAR SI YA SE SOLICITO EL MISMO TIPO DE APOYO FINANCIERO EL MISMO DIA/////////////////////////////////////
$queryCheckToday = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Tipo = 'Financiera' AND DATE(Fecha_Solicitada) = CURDATE()");
$queryCheckToday->bind_param("i", $_SESSION['Numero_Empleado']);
$queryCheckToday->execute();
$resultCheckToday = $queryCheckToday->get_result();

while($rowCheckToday = $resultCheckToday->fetch_assoc()) {
    $queryCheckTodayPlus = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ? AND Tipo = ?");
    $queryCheckTodayPlus->bind_param("is", $rowCheckToday['Id_Prestacion'], $tipoPF);
    $queryCheckTodayPlus->execute();
    $resultCheckTodayPlus = $queryCheckTodayPlus->get_result();
    $rowCheckTodayPlus = $resultCheckTodayPlus->fetch_assoc();

        if ($rowCheckTodayPlus) {
                echo "<script>alert('Ya has solicitado este tipo de apoyo Financiero el día de hoy.');</script>";
                exit;
                $queryCheckTodayPlus->close();
        }
}
$queryCheckToday->close();

/////////////////////////////////////CHECAR SI YA SE SOLICITO EL MISMO TIPO DE APOYO FINANCIERO EL MISMO DIA/////////////////////////////////////









    if ($nombre_familiar !== "%N/A%") {
        $queryChecarPF = $conn->prepare("SELECT * FROM familiar_empleado f INNER JOIN empleado_familiar e ON f.Id_Familiar = e.Id_Familiar WHERE f.Nombre_Familiar like ? AND e.Numero_Empleado = ?");
        $queryChecarPF->bind_param("si", $nombre_familiar, $_SESSION['Numero_Empleado']);
        $queryChecarPF->execute();
        $result = $queryChecarPF->get_result();
        $row = $result->fetch_assoc();
        $id_familiar = $row['Id_Familiar'] ?? 0;
        $queryChecarPF->close();

        if (!$row) {
            echo "Error, no se encontró el familiar";
            exit;
        }
    } else {
        $id_familiar = 0;
    }

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

if ($nombre_familiar !== "%N/A%") {
        $queryInsertPEE = $conn->prepare("INSERT INTO familiar_prestacion (Id_Familiar,Id_Prestacion,Tipo) VALUES (?, ?, ?)");
        $queryInsertPEE->bind_param("iis", $row['Id_Familiar'], $id_prestacion, $tipo);
        $queryInsertPEE->execute();
        $queryInsertPEE->close();
} else {
        $id_familiar = 0;
        $queryInsertPEE = $conn->prepare("INSERT INTO familiar_prestacion (Id_Familiar,Id_Prestacion,Tipo) VALUES (?, ?, ?)");
        $queryInsertPEE->bind_param("iis", $id_familiar, $id_prestacion, $tipo);
        $queryInsertPEE->execute();
        $queryInsertPEE->close();
}

    if ($tipo_pago == "Deposito") {
        $deposito = 1;
        $reembolso = 0;
    } else {
        $deposito = 0;
        $reembolso = 1;
    }


$queryInsertPF = $conn->prepare("INSERT INTO prestacion_apoyofinanciero (Id_Prestacion,Numero_Empleado,Id_Familiar,Tipo,Deposito,Reembolso) VALUES (?,?,?,?,?,?)");
$queryInsertPF->bind_param("iiisii", $id_prestacion, $_SESSION['Numero_Empleado'], $id_familiar, $tipoPF, $deposito, $reembolso);
$queryInsertPF->execute();
$queryInsertPF->close();

if ($tipoPF == 'Guarderia') {
        echo '<script>mostrarPDF("PDF Prestaciones/Guarderia y Canastilla/Prestacion guarderia y canastilla (Solicitud).pdf")</script>';
} elseif ($tipoPF == 'Gastos funerarios') {
        echo '<script>mostrarPDF("PDF Prestaciones/Gastos Funerarios/Prestacion gastos funerarios (Solicitud).pdf")</script>';
} elseif ($tipoPF == 'Lentes' && $tipo_pago == 'Deposito') {
        echo '<script>mostrarPDF("PDF Prestaciones/Lentes/Prestacion Lentes(Solicitud[Deposito]).pdf")</script>';
} elseif ($tipoPF == 'Lentes' && $tipo_pago == 'Reembolso') {
        echo '<script>mostrarPDF("PDF Prestaciones/Lentes/Prestacion Lentes(Solicitud[Reembolso]).pdf")</script>';
} elseif ($tipoPF == 'Titulacion') {
        echo '<script>mostrarPDF("PDF Prestaciones/Titulacion/Prestacion titulacion (Solicitud).pdf")</script>';
} elseif ($tipoPF == 'Aparato Ortopedico') {
        echo '<script>mostrarPDF("PDF Prestaciones/Aparatos Ortopedicos/Prestacion Aparatos Ortopedicos (Solicitud).pdf")</script>';
}


echo "Solicitud enviada correctamente";

     


echo <<<HTML
<div id="pdfLightbox" class="lightbox" style="display: none;">
        <div class="lb-outerContainer">
                <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="100%">
                <a href="pruebaMPDF.html" class="lb-close" onclick="cerrarPDF()">x</a>
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
HTML;

        
}

?>
