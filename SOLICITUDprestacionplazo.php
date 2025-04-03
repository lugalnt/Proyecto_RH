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
    <link rel="stylesheet" href="./styleRegistrarFamiliares.css">
    <!-- SIMBOLOS QUE SE UTILIZARAN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <script>
        function toggleMotivoTextbox() {
            var dropdown = document.getElementById("razon");
            var textbox = document.getElementById("motivo");
            if (dropdown.value === "Otro") {
                textbox.style.display = "block";
            } else {
                textbox.style.display = "none";
                textbox.value = ""; // Clear the textbox value when not needed
            }
        }
    </script>
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
                <a href="SOLICITUDprestacionapoyoacademico.php">
                    <span class="material-icons-sharp">school</span>
                    <h3>Solicitud de prestacion: Apoyo academico</h3>
                </a>
                <a href="SOLICITUDprestaciondia.php">
                    <span class="material-icons-sharp">today</span>
                    <h3>Solicitar un dia</h3>
                </a>
                <a href="SOLICITUDprestacionplazo.php" class="active">
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

    <h1>Solicitar Un Plazo</h1>
    <h2>Por favor, complete el siguiente formulario para solicitar un plazo.</h2>
        
    <form action="" method="post">
        <label for="fecha_inicial"><h5>Fecha Inicial:</h5></label>
        <input type="date" id="fecha_inicial" name="fecha_inicial" required><br><br>

        <label for="fecha_final"><h5>Fecha Final:</h5></label>
        <input type="date" id="fecha_final" name="fecha_final" required><br><br>

        <label for="razon"><h5>Razón del Plazo:</h5></label>
        <select id="razon" name="motivo" onchange="toggleMotivoTextbox()" required>
        <?php
require_once("conn.php");

$queryCon = $conn->prepare("SELECT nombre FROM tiposprestacion Where tipoMayor = 'Plazo'");
$queryCon->execute();
$result = $queryCon->get_result();
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
}
$queryCon->close();


?>
        </select><br><br>

        <label for="razon_social"><h5>¿Es Por Una Razón Social?</h5></label>
        <center>
        <input type="checkbox" id="razon_social" name="razon_social" value="1"><br><br>
        </center>
        <input type="text" id="motivo" name="motivo" placeholder="Indique el motivo" style="display:none;"><br><br>
        
        <div class="button-container">
            <button id="submit" type="submit" value="Enviar Solicitud">Enviar Solicitud</button>
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


</body>
</html>

<?php

require_once("conn.php");
include_once("error_handler.php");
session_start();


if ($_SERVER["REQUEST_METHOD"]=="POST")
{

$fechaInicial = $_POST['fecha_inicial'];
$fechaFinal = $_POST['fecha_final'];
$motivo = $_POST['motivo'];
$quitarDias = $_POST['razon_social'] ?? 0;

if ($quitarDias) {
    $startDate = new DateTime($fechaInicial);
    $endDate = new DateTime($fechaFinal);
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

    $dias = 0;
    foreach ($period as $date) {
        if ($date->format('N') < 6) { 
            $dias++;
        }
    }

    $queryCD = $conn->prepare("SELECT Dias FROM empleado WHERE Numero_Empleado = ?");
    $queryCD->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryCD->execute();
    $resultCD = $queryCD->get_result();
    $rowCD = $resultCD->fetch_assoc();

    if ($rowCD['Dias'] >= $dias) {
        // Update the employee's available days

    } else {
        // Alert the user if they do not have enough available days
        echo "<script>alert('No tienes suficientes días disponibles para esta solicitud.'); window.location.href='SOLICITUDprestacionplazo.php';</script>";
        exit();
    }
}





//ARRIBA GUARDADO PARA QUE SE HAGA DE UNA FORMA U OTRA DESPUES DE QUE SE OTORGE LOL

$queryInsertarP = $conn->prepare("INSERT INTO prestacion (Tipo, Fecha_Solicitada) VALUES ('Plazo', CURRENT_DATE)");
$queryInsertarP->execute();
$Id_Prestacion = $conn->insert_id;
$queryInsertarP->close();

$queryInsertarPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo, Fecha_Solicitada) VALUES (?, ?, ?, CURRENT_DATE)");
$tipo = 'Plazo';
$queryInsertarPE->bind_param("iis", $_SESSION['Numero_Empleado'], $Id_Prestacion, $tipo);
$queryInsertarPE->execute();
$queryInsertarPE->close();

$queryInsertarPP = $conn->prepare("INSERT INTO prestacion_plazos (Id_Prestacion, Numero_Empleado, Fecha_Inicio, Fecha_Final, Tipo) VALUES (?, ?, ?, ?, ?)");
$queryInsertarPP->bind_param("issss", $Id_Prestacion, $_SESSION['Numero_Empleado'], $fechaInicial, $fechaFinal, $motivo);
$queryInsertarPP->execute();
$queryInsertarPP->close();

echo "<script>alert('Solicitud de prestación de plazo enviada correctamente. '); window.location.href='index.php';</script>";
exit();

}

?>