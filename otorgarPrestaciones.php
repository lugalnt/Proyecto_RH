<?php

require_once("conn.php");
require_once("ESTADOsepuedeprestacion.php");
require_once("documentosPrestaciones.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idPrestacion = $_POST['idPrestacion'];
    $tipoPrestacion = $_POST['tipoPrestacion'];

    if (strpos($tipoPrestacion, 'Plazo') === 0) {
        

        if (strpos($tipoPrestacion, 'Embarazo') !== false || strpos($tipoPrestacion, 'Incapacidad') !== false || strpos($tipoPrestacion, 'Permiso por duelo') !== false) {

            $queryFechas = $conn->prepare("SELECT Fecha_Inicio, Fecha_Final FROM prestacion_plazos WHERE Id_Prestacion = ?");
            $queryFechas->bind_param("i", $idPrestacion);
            $queryFechas->execute();
            $resultFechas = $queryFechas->get_result();
            $rowFechas = $resultFechas->fetch_assoc();
            $queryFechas->close();

            $fechaInicial = $rowFechas['Fecha_Inicio'];
            $fechaFinal = $rowFechas['Fecha_Final'];

            // Calcular los días hábiles entre las fechas
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
            $queryCD->close();
        
            if ($rowCD['Dias'] >= $dias) {
                // Actualizar los días disponibles del empleado
                $queryUD = $conn->prepare("UPDATE empleado SET Dias = Dias - ? WHERE Numero_Empleado = ?");
                $queryUD->bind_param("ii", $dias, $_SESSION['Numero_Empleado']);
                $queryUD->execute();
                $queryUD->close();
            } else {
                // Alertar al usuario si no tiene suficientes días disponibles
                echo "<script>alert('No tiene suficientes días disponibles para esa solicitud. Debio haber pedido un dia entre que se otrogaba la prestacion'); window.location.href='SOLICITUDprestacionplazo.php';</script>";
                exit();
            }
        }
    } //Aqui termina el quitar dias de plazo


    if (strpos($tipoPrestacion, 'Plazo:') === 0) {
        $tipoPrestacionBuscar = 'Otro';
    } elseif (strpos($tipoPrestacion, 'Dia:') === 0) {
        $tipoPrestacionBuscar = 'Otro';
    } else {
        $tipoPrestacionBuscar = explode(': ', $tipoPrestacion)[1];
    }

    echo '<script>
    if (!confirm("Alto!, para esta prestacion se requiere de los siguientes documentos: ' . queDocumentos($tipoPrestacionBuscar) . ' asegurarse de que esten presentes")) {
        window.location.href = "solicitudesprestaciones.php";
    } else {
        ' . 
        'var xhr = new XMLHttpRequest();
        xhr.open("POST", window.location.href, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("confirm=true&idPrestacion=' . $idPrestacion . '&tipoPrestacion=' . $tipoPrestacion . '");
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert("Prestación otorgada");
                window.location.href = "solicitudesprestaciones.php";
            }
        };
        ' . 
    '}
    </script>';

    if (isset($_POST['confirm']) && $_POST['confirm'] === 'true') {
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
    }
}
?>