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
    <title>Solicitud prestacion dia</title>

    <script>
        function toggleOtroField() {
            var motivo = document.getElementById("motivo").value;
            var otroField = document.getElementById("otroMotivo");
            if (motivo === "Otro") {
                otroField.style.display = "block";
            } else {
                otroField.style.display = "none";
            }
        }
    </script>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="stylesolicitudes.css">
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
                <a href="registrarfamiliares.php" >
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
                <a href="SOLICITUDprestaciondia.php"  class="active">
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
    <form action="" method="post">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="diaExtra">Día extra:</label>
        <input type="checkbox" id="diaExtra" name="diaExtra" value="1"><br><br>

        <label for="motivo">Motivo:</label>
        <select id="motivo" name="motivo" onchange="toggleOtroField()" required>
            <option value="Permiso sindical">Permiso sindical</option>
            <option value="Nacimiento hijo">Nacimiento hijo</option>
            <option value="Otro">Otro</option>
        </select><br><br>

        <div id="otroMotivo" style="display: none;">
            <label for="otro">Especificar otro motivo:</label>
            <input type="text" id="otro" name="otro"><br><br>
        </div>

        <input type="submit" value="Enviar">
    </form>

    
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
    $fecha = $_POST['fecha'];
    $diaExtra = $_POST['diaExtra'] ?? 0;
    $motivo = $_POST['motivo'];
    $otro = $_POST['otro'] ?? null;

    $queryCheckDias = $conn->prepare("SELECT Dias, Dias_Extras FROM empleado WHERE Numero_Empleado = ?");
    $queryCheckDias->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryCheckDias->execute();
    $queryCheckDias->bind_result($dias, $diasExtras);
    $queryCheckDias->fetch();
    $queryCheckDias->close();

    if ($diaExtra) {
        if ($diasExtras <= 0) {
            echo '<script type="text/javascript">
            alert("No tienes suficientes días extras disponibles para solicitar esta prestación.");
            </script>';
            echo("<meta http-equiv='refresh' content='1'>");
            exit;
        }
    } else {
        if ($dias <= 0) {
            echo '<script type="text/javascript">
            alert("No tienes suficientes días disponibles para solicitar esta prestación.");
            </script>';
            echo("<meta http-equiv='refresh' content='1'>");
            exit;
        }
    }

    $numeroEmpleado = $_SESSION['Numero_Empleado'];
    $queryCount = $conn->prepare("SELECT COUNT(*) as count FROM prestacion_dias pd INNER JOIN empleado_prestacion ep ON pd.Id_Prestacion = ep.Id_Prestacion INNER JOIN empleado e ON ep.Numero_Empleado = e.Numero_Empleado INNER JOIN prestacion p ON pd.Id_Prestacion = p.Id_Prestacion WHERE pd.Motivo = ? AND pd.Fecha_Solicitada = ? AND p.Estado = 'Otorgada' AND e.Area = (SELECT Area FROM empleado WHERE Numero_Empleado = ?)");
    $queryCount->bind_param("ssi",$motivo, $fecha, $numeroEmpleado);
    $queryCount->execute();
    $resultCount = $queryCount->get_result();
    $rowCount = $resultCount->fetch_assoc();

    if ($rowCount['count'] >= 2) {
        echo '<script type="text/javascript">
        alert("Lo sentimos, hay demasiados empleados de tu área que se le han otorgado esta prestación para la fecha seleccionada.");
        </script>';
        echo("<meta http-equiv='refresh' content='1'>");
        exit;
    }

//Comentado hasta tener especificaciones cuando suficicentes dias han sido otorgados
// require_once("ESTADOsepuedeprestacion.php");
// $prestacionesPermitidas = verificarPrestaciones($_SESSION['Numero_Empleado']);

// if (!$prestacionesPermitidas['Día'][$motivo]) {
// echo "<script>alert('No se puede solicitar este tipo de apoyo académico debido a que ya te lo otorgaron este cuatrimestre');</script>";
// exit;
// echo "<script>location.reload();</script>"; 
// } 

    $queryCheckFecha = $conn->prepare("SELECT COUNT(*) FROM prestacion_dias WHERE Numero_Empleado = ? AND Fecha_Solicitada = ?");
    $queryCheckFecha->bind_param("is", $_SESSION['Numero_Empleado'], $fecha);
    $queryCheckFecha->execute();
    $queryCheckFecha->bind_result($count);
    $queryCheckFecha->fetch();
    $queryCheckFecha->close();

    if ($count > 0) {
        echo '<script type="text/javascript">
        alert("Ya existe una solicitud de prestación para la fecha seleccionada.");
        </script>';
        echo("<meta http-equiv='refresh' content='1'>");
    }
    else{




    $queryInsertarP = $conn->prepare("INSERT INTO prestacion (Tipo, Fecha_Solicitada) VALUES ('Día', CURRENT_DATE)");
    $queryInsertarP->execute();
    $Id_Prestacion = $conn->insert_id;
    $queryInsertarP->close();

    $queryInsertarPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo, Fecha_Solicitada) VALUES (?, ?, ?, CURRENT_DATE)");
    $tipo = 'Día';
    $queryInsertarPE->bind_param("iis", $_SESSION['Numero_Empleado'], $Id_Prestacion, $tipo);
    $queryInsertarPE->execute();
    $queryInsertarPE->close();


    $queryInsertarPF = $conn->prepare("INSERT INTO prestacion_dias (Id_Prestacion, Numero_Empleado, Fecha_Solicitada, Dia_Extra,  Motivo) VALUES (?, ?, ?, ?, ?)");
    $queryInsertarPF->bind_param("iisis", $Id_Prestacion, $_SESSION['Numero_Empleado'], $fecha, $diaExtra, $motivo);
    $queryInsertarPF->execute();
    $queryInsertarPF->close();


    if($diaExtra)
    {
        $queryUpdateED = $conn->prepare("UPDATE empleado SET Dias_Extras = Dias_Extras - 1 WHERE Numero_Empleado = ?");
        $queryUpdateED->bind_param("i", $_SESSION['Numero_Empleado']);
        $queryUpdateED->execute();
        $queryUpdateED->close();
    }
    else
    {
    $queryUpdateED = $conn->prepare("UPDATE empleado SET Dias = Dias - 1 WHERE Numero_Empleado = ?");
    $queryUpdateED->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryUpdateED->execute();
    $queryUpdateED->close();
    }

    if ($otro)
    {
        $queryInsertarPF = $conn->prepare("UPDATE prestacion_dias SET Motivo = ? WHERE Id_Prestacion = ?");
        $queryInsertarPF->bind_param("si", $otro, $Id_Prestacion);
        $queryInsertarPF->execute();
        $queryInsertarPF->close();
    }

    echo '<script type="text/javascript">
    alert("Solicitud de prestación de día enviada, a continuacion rellenar documento de solicitud de prestación");
    </script>';

    if($motivo == "Permiso sindical")
    {
        echo '<script type="text/javascript">
        window.location.href = "PermisoSindical.pdf";
        </script>';
    }else
    {
        echo '<script type="text/javascript">
        alert("Prestación otorgada");
        </script>';
        echo("<meta http-equiv='refresh' content='1'>");
    }

    }
  
}






?>