<?php
require_once("conn.php");
require_once("ESTADOsepuedeprestacion.php");
require_once("documentosPrestaciones.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si se confirma la prestación
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'true') {
        $idPrestacion = $_POST['idPrestacion'];

        // Actualizar la prestación como otorgada
        $queryOP = $conn->prepare("UPDATE prestacion SET Fecha_Otorgada = CURRENT_DATE WHERE Id_Prestacion = ?");
        $queryOP->bind_param("i", $idPrestacion);
        $queryOP->execute();
        $queryOP->close();

        $queryOPE = $conn->prepare("UPDATE empleado_prestacion SET Fecha_Otorgada = CURRENT_DATE WHERE Id_Prestacion = ?");
        $queryOPE->bind_param("i", $idPrestacion);
        $queryOPE->execute();
        $queryOPE->close();

        $queryCFP = $conn->prepare("SELECT * FROM familiar_prestacion WHERE Id_Prestacion = ?");
        $queryCFP->bind_param("i", $idPrestacion);
        $queryCFP->execute();
        $resultCFP = $queryCFP->get_result();

        if ($resultCFP->num_rows > 0) {
            $queryOPF = $conn->prepare("UPDATE familiar_prestacion SET Fecha_Otorgada = CURRENT_DATE WHERE Id_Prestacion = ?");
            $queryOPF->bind_param("i", $idPrestacion);
            $queryOPF->execute();
            $queryOPF->close();
        }

        // Redirigir de vuelta a la página de solicitudes
        echo '<script>
            alert("Prestación otorgada correctamente.");
            window.location.href = "solicitudesprestaciones.php";
        </script>';
        exit();
    }

    // Mostrar el PDF correspondiente
    $idPrestacion = $_POST['idPrestacion'];
    $tipoPrestacion = $_POST['tipoPrestacion'];

    if (strpos($tipoPrestacion, 'Plazo:') === 0 || strpos($tipoPrestacion, 'Dia:') === 0) {
        $tipoPrestacionBuscar = 'Otro';
    } else {
        $tipoPrestacionBuscar = explode(': ', $tipoPrestacion)[1];
    }

    $pdfPath = '';
    switch ($tipoPrestacionBuscar) {
        case 'Utiles':
            $pdfPath = "PDF Prestaciones/Utiles Escolares/Prestacion Utiles Escolares (Respuesta).pdf";
            break;
        case 'Exencion de inscripcion':
            $pdfPath = "PDF Prestaciones/Utiles Escolares/Prestacion Utiles Escolares (Respuesta).pdf";
            break;
        case 'Lentes':
            $pdfPath = "PDF Prestaciones/Lentes/Prestacion Lentes(Respuesta[Deposito]).pdf";
            break;
        case 'Guarderia':
            $pdfPath = "PDF Prestaciones/Guarderia y Canastilla/Prestacion guarderia y canastilla (Respuesta).pdf";
            break;
        case 'Aparato Ortopedico':
            $pdfPath = "PDF Prestaciones/Aparatos Ortopedicos/Prestacion Aparatos Ortopedicos (Respuesta).pdf";
            break;
        case 'Titulación':
            $pdfPath = "PDF Prestaciones/Titulacion/Prestacion Titulacion (Respuesta).pdf";
            break;
        default:
            $pdfPath = ""; // Ruta por defecto si no se encuentra el tipo
            break;
    }

    if (!empty($pdfPath)) {
        // Obtener los documentos requeridos
        $documentosRequeridos = queDocumentos($tipoPrestacionBuscar);

        // Mostrar el PDF y un formulario para confirmar
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmar Prestación</title>
            <script>
                function confirmarDocumentos() {
                    return confirm("Alto!, para esta prestación se requiere de los siguientes documentos: ' . $documentosRequeridos . '. ¿Está seguro de que están presentes?");
                }
            </script>
        </head>
        <body>
            <div style="text-align: center;">
                <embed src="' . htmlspecialchars($pdfPath) . '" type="application/pdf" width="80%" height="600px">
                <form method="POST" action="" onsubmit="return confirmarDocumentos();">
                    <input type="hidden" name="idPrestacion" value="' . htmlspecialchars($idPrestacion) . '">
                    <input type="hidden" name="confirm" value="true">
                    <button type="submit" style="margin-top: 20px; padding: 10px 20px; font-size: 16px;">Confirmar Prestación</button>
                </form>
                <a href="solicitudesprestaciones.php" style="display: block; margin-top: 10px; color: red; text-decoration: none;">Cancelar</a>
            </div>
        </body>
        </html>';
        exit();
    } else {
        echo '<script>alert("No se encontró un PDF para este tipo de prestación."); window.location.href = "solicitudesprestaciones.php";</script>';
        exit();
    }
}
?>