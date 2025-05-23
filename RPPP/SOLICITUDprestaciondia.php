<?php
include_once("error_handler.php");
require_once("conn.php");

session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
}

if($_SERVER["REQUEST_METHOD"] == "POST")
if($_SERVER["REQUEST_METHOD"] == "POST")
{

$numeroEmpleado = $_POST['numeroEmpleado2'];
echo "<script>console.log('Numero de empleado: $numeroEmpleado');</script>";


    // Mostrar familiares registrados del empleado
    $queryFamiliares = $conn->prepare("SELECT f.Nombre_Familiar, f.Nivel_academico FROM familiar_empleado f INNER JOIN empleado_familiar e ON f.Id_Familiar = e.Id_Familiar WHERE e.Numero_Empleado = ?");
    $queryFamiliares->bind_param("i", $numeroEmpleado);
    $queryFamiliares->execute();
    $resultFamiliares = $queryFamiliares->get_result();

    // Recoger los familiares en un array para pasarlos a JS
    $familiares = [];
    while ($rowF = $resultFamiliares->fetch_assoc()) {
        $familiares[] = [
            'Nombre_Familiar' => $rowF['Nombre_Familiar'],
            'Nivel_academico' => $rowF['Nivel_academico']
        ];
    }
    // Pasar el array a JS usando json_encode
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var familiares = " . json_encode($familiares) . ";
            if (familiares.length > 0) {
                var div = document.createElement('div');
                div.style.background = '#fff';
                div.style.padding = '15px';
                div.style.margin = '10px 0';
                div.style.borderRadius = '8px';
                var html = '<h4>Familiares registrados:</h4><ul>';
                familiares.forEach(function(f) {
                    html += '<li>' + 
                        (f.Nombre_Familiar ? f.Nombre_Familiar.replace(/</g, '&lt;').replace(/>/g, '&gt;') : '') + 
                        ' (' + 
                        (f.Nivel_academico ? f.Nivel_academico.replace(/</g, '&lt;').replace(/>/g, '&gt;') : '') + 
                        ')</li>';
                });
                html += '</ul>';
                div.innerHTML = html;
                var contenido = document.querySelector('.sidebar');
                if (contenido) contenido.appendChild(div);
            }
        });
    </script>";

    $queryFamiliares->close();



}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleado</title>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="/Proyecto_RH/styleRegistrarFamiliares.css">
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
                        <img src="/Proyecto_RH/images/logo.png.png">
                        <h2>Empleado<span class="danger">
                            UTN</span> </h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>

            <div class="sidebar">
                <a href="/Proyecto_RH/RPPP.php">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Regresar</h3>
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
                        <img src="/Proyecto_RH/images/profile-1.jpg.jpeg">
                    </div>
                </div>
        </div> 
    
    <h1>Solicitar un día</h1>
    <h2>Por favor, complete el siguiente formulario para solicitar un día.</h2>

    <form action="" method="post">
        <label for="fecha"><h5>Fecha:</h5></label>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="diaExtra"><h5>Día extra:</h5></label>
        <center>
        <input type="checkbox" id="diaExtra" name="diaExtra" value="1"><br><br>
        </center>

        <label for="motivo"><h5>Motivo:</h5></label>
        <select id="motivo" name="motivo" onchange="toggleOtroField()" required>
        <?php
require_once("conn.php");

$queryCon = $conn->prepare("SELECT nombre FROM tiposprestacion Where tipoMayor = 'Dia'");
$queryCon->execute();
$result = $queryCon->get_result();
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
}
$queryCon->close();


?>
        </select><br><br>

        <div id="otroMotivo" style="display: none;">
            <label for="otro">Especificar otro motivo:</label>
            <input type="text" id="otro" name="otro"><br><br>
        </div>

        <div class="button-container">
            <button id="submit" type="submit" value="Enviar">Enviar</button>
        </div>
    </form>

    <script src="/Proyecto_RH/index.js"></script>
    
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

if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['motivo']))
{
    $fecha = $_POST['fecha'];
    $diaExtra = $_POST['diaExtra'] ?? 0;
    $motivo = $_POST['motivo'];
    $otro = $_POST['otro'] ?? null;

    $queryCheckDias = $conn->prepare("SELECT Dias, Dias_Extras FROM empleado WHERE Numero_Empleado = ?");
    $queryCheckDias->bind_param("i", $numeroEmpleado);
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

    $numeroEmpleado = $numeroEmpleado;
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
// $prestacionesPermitidas = verificarPrestaciones($numeroEmpleado);

// if (!$prestacionesPermitidas['Día'][$motivo]) {
// echo "<script>alert('No se puede solicitar este tipo de apoyo académico debido a que ya te lo otorgaron este cuatrimestre');</script>";
// exit;
// echo "<script>location.reload();</script>"; 
// } 

    $queryCheckFecha = $conn->prepare("SELECT COUNT(*) FROM prestacion_dias WHERE Numero_Empleado = ? AND Fecha_Solicitada = ?");
    $queryCheckFecha->bind_param("is", $numeroEmpleado, $fecha);
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
    $queryInsertarPE->bind_param("iis", $numeroEmpleado, $Id_Prestacion, $tipo);
    $queryInsertarPE->execute();
    $queryInsertarPE->close();


    $queryInsertarPF = $conn->prepare("INSERT INTO prestacion_dias (Id_Prestacion, Numero_Empleado, Fecha_Solicitada, Dia_Extra,  Motivo) VALUES (?, ?, ?, ?, ?)");
    $queryInsertarPF->bind_param("iisis", $Id_Prestacion, $numeroEmpleado, $fecha, $diaExtra, $motivo);
    $queryInsertarPF->execute();
    $queryInsertarPF->close();


    if($diaExtra)
    {
        $queryUpdateED = $conn->prepare("UPDATE empleado SET Dias_Extras = Dias_Extras - 1 WHERE Numero_Empleado = ?");
        $queryUpdateED->bind_param("i", $numeroEmpleado);
        $queryUpdateED->execute();
        $queryUpdateED->close();
    }
    else
    {
    $queryUpdateED = $conn->prepare("UPDATE empleado SET Dias = Dias - 1 WHERE Numero_Empleado = ?");
    $queryUpdateED->bind_param("i", $numeroEmpleado);
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