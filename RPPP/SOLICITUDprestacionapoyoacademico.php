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
    <link href="/Proyecto_RH/lightbox.min.css" rel="stylesheet">
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

<?php
if (isset($_GET['mostrar_familiares'])) {
    // Mostrar familiares registrados del empleado
    $numeroEmpleado = $numeroEmpleado;
    $queryFamiliares = $conn->prepare("SELECT f.Nombre_Familiar, f.Nivel_academico FROM familiar_empleado f INNER JOIN empleado_familiar e ON f.Id_Familiar = e.Id_Familiar WHERE e.Numero_Empleado = ?");
    $queryFamiliares->bind_param("i", $numeroEmpleado);
    $queryFamiliares->execute();
    $resultFamiliares = $queryFamiliares->get_result();

    echo '<div style="background:#fff; padding:15px; margin:10px 0; border-radius:8px;"><h4>Familiares registrados:</h4><ul>';
    while ($rowF = $resultFamiliares->fetch_assoc()) {
        echo '<li>' . htmlspecialchars($rowF['Nombre_Familiar']) . ' (' . htmlspecialchars($rowF['Nivel_academico']) . ')</li>';
    }
    echo '</ul></div>';

    $queryFamiliares->close();
}
?>

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


    <h1>Solicitud de Apoyo Académico</h1>
    <h2>Se recuerda que se necesita presentar el comprobante de inscripcion del beneficiado</h2>
    <br>
    <form action="" method="post">
        <input type="hidden" name="numeroEmpleado" value ="<?php echo htmlspecialchars($numeroEmpleado); ?>">
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
<script src="/Proyecto_RH/lightbox.min.js"></script>

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

if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['tipo']))
{
$numeroEmpleado = $_POST['numeroEmpleado'];
$nombre_familiar = $_POST['nombre_familiar'];
$nombre_institucion = $_POST['nombre_institucion'];
$tipoApoyo = $_POST['tipo'];




////////////////////

$queryCheckToday = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Tipo = 'Academico' AND DATE(Fecha_Solicitada) = CURDATE()");
$queryCheckToday->bind_param("i", $numeroEmpleado);
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
$queryChecarPF->bind_param("si", $nombre_familiar, $numeroEmpleado);
$queryChecarPF->execute();
$result = $queryChecarPF->get_result();
$row = $result->fetch_assoc();
$nivel_academico = $row['Nivel_academico'];
$queryChecarPF->close();

if ($row)
{
    require_once("ESTADOsepuedeAcademico.php");
    $puedeOtorgar = sePuedeOtorgarPrestacionAcademica($numeroEmpleado, $row['Id_Familiar'], $tipoApoyo);
    echo "<script>console.log('Puede otorgar: " . ($puedeOtorgar ? 'Sí' : 'No') . "');</script>";
    if (!$puedeOtorgar) {
    echo "<script>alert('No se puede otorgar esta prestación académica al familiar debido a que ya la recibió en el periodo correspondiente.');</script>";
    exit;
    }

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
    $queryInsertPE->bind_param("iis", $numeroEmpleado, $id_prestacion, $tipo);
    $queryInsertPE->execute();
    $queryInsertPE->close();

    $queryInsertPF = $conn->prepare("INSERT INTO familiar_prestacion (Id_Familiar, Id_Prestacion, Tipo) VALUES (?, ?, ?)");
    $queryInsertPF->bind_param("iis", $row['Id_Familiar'], $id_prestacion, $tipo);
    $queryInsertPF->execute();
    $queryInsertPF->close();
    
    $queryInsertPA = $conn->prepare("INSERT INTO prestacion_apoyoacademico (Id_Prestacion, Numero_Empleado, Id_Familiar, Nivel_Academico, Nombre_Institucion, Tipo) VALUES (?, ?, ?, ?, ?, ?)");
    $queryInsertPA->bind_param("iiisss", $id_prestacion, $numeroEmpleado, $row['Id_Familiar'], $nivel_academico, $nombre_institucion, $tipoApoyo);
    $queryInsertPA->execute();
    $queryInsertPA->close();

    switch ($tipoApoyo)
    {
        case "Utiles":
            echo '<script>mostrarPDF("/Proyecto_RH/PDF Prestaciones/Utiles Escolares/Prestacion Utiles Escolares (Solicitud).pdf")</script>';
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


