<?php
session_start();
?>

<html>
<head>
<link href="lightbox.min.css" rel="stylesheet">
<link rel="stylesheet" href="./styleRegistrarFamiliares.css">
<!-- SIMBOLOS QUE SE UTILIZARAN -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
</head>

<body>

<main>
<a class="btn btn-primary rainbow-text" href="<?php echo isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '#'; ?>">Regresar</a>
<style>
.rainbow-text {
    background: linear-gradient(270deg, #ff005a, #fffd44, #00ffae, #00c3ff, #ff00ea, #ff005a);
    background-size: 1200% 1200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: rainbow-animate 3s linear infinite;
    font-weight: bold;
    display: inline-block;
}
@keyframes rainbow-animate {
    0% { background-position: 0% 50%; }
    100% { background-position: 100% 50%; }
}
</style>

<?php 

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
 
$numeroEmpleado = $_POST['numero_empleado'];
$tipoPrestacion = $_POST['tipo_prestacion'];
$tipoMayor = $_POST['tipoMayor'];
$idPrestacion = $_POST['prestacion_id'];

$relativeDir = "DocumentosPrestaciones/Solicitudes/$numeroEmpleado/$tipoMayor/$tipoPrestacion/$idPrestacion";
$dir = __DIR__ . '/' . $relativeDir;

if (is_dir($dir)) {
    $archivos = scandir($dir);
    $archivos = array_diff($archivos, array('.', '..')); 
    if (count($archivos) > 0) {
        echo '<h2>Documentos de la solicitud</h2>';
        echo '<table class="table">';
        echo '<thead><tr><th>Nombre del docuemnto</th><th>Acciones</th></tr></thead>';
        echo '<tbody>';
        foreach ($archivos as $archivo) {
            if (is_file($dir . '/' . $archivo)) {
                $archivoUrl = $relativeDir . '/' . rawurlencode($archivo);
                echo '<tr>';
                echo '<td> <a href="#" onclick="mostrarPDF(\'' . $archivoUrl . '\')">' . htmlspecialchars($archivo) . '</a></td>';
                echo '<td><a href="' . $archivoUrl . '" download class="btn btn-primary">Descargar</a></td>';
                echo '</tr>';
            }
        }
    }
    else {
        echo '<p>No hay documentos subidos para esta solicitud.</p>';
    }
}
else {
    echo '<p>La carpeta de documentos '.$relativeDir.' no existe.</p>';
}

} 
?>




</main>














<div id="pdfLightbox" class="lightbox" style="display: none;">
    <div class="lb-outerContainer">
      
        <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="100%">

        
        <a class="lb-close" onclick="cerrarPDF()">x</button>
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
</body>

</html>
