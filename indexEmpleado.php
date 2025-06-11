<?php
include_once("error_handler.php");
require_once("conn.php");

session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
}

//This shit so dumb bro
if(isset($_SESSION['Numero_Empleado'])){
$numeroEmpleado = $_SESSION['Numero_Empleado'];

// Check if the employee is already related to the familiar with Id_Familiar 0
$queryCheckRelation = $conn->prepare("SELECT * FROM empleado_familiar WHERE Numero_Empleado = ? AND Id_Familiar = 0");
$queryCheckRelation->bind_param("i", $numeroEmpleado);
$queryCheckRelation->execute();
$resultCheckRelation = $queryCheckRelation->get_result();

if ($resultCheckRelation->num_rows == 0) {
    // If not related, create the relation
    $queryInsertRelation = $conn->prepare("INSERT INTO empleado_familiar (Numero_Empleado, Id_Familiar, Relacion) VALUES (?, 0, 'Esposa')");
    $queryInsertRelation->bind_param("i", $numeroEmpleado);
    $queryInsertRelation->execute();
}
}
//









?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleado</title>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="./styleEmpleado.css">
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
                <a href="index.php" class="active">
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
        <main>
            <h1>Menú</h1>

            <!-- INICIO DE PRESTAMOS RECIENTES TABLA -->
            <div class="prestamos-recientes">
                <h2>Tus Prestamos Recientes</h2>

                <table>
                    <thead>
                        <tr>
                    <th>Tipo</th>
                    <th>Fecha Solicitada</th>
                    <th>Estado</th>        
                    </thead>
                    <tbody>
            <?php

                    $querySPR = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? ORDER BY Fecha_Solicitada DESC LIMIT 6");
                    $querySPR->bind_param("i", $_SESSION['Numero_Empleado']);
                    $querySPR->execute();
                    $resultSPR = $querySPR->get_result();

                    while($rowSPR = $resultSPR->fetch_assoc())
                    {
                      $NombreEmpleado = htmlspecialchars($_SESSION['Nombre_Empleado']);
                    
                    
                      echo "<div class='benefits-container'>";
                      echo "<td>".$rowSPR['Tipo']."</td>";
                      echo "<td>FECHA: ".$rowSPR['Fecha_Solicitada']."</td>";

                        if (is_null($rowSPR['Fecha_Otorgada']))
                        {
                            echo "<td class=".'warning'.">En espera</td>";
                        }
                        else
                        {
                            echo "<td class=".'success'.">Concedido</td>";
                        }

                      echo "</tr>";
                    }
            ?>

                    </tbody>
                    
                </table>

                <a href="documentosEmpleado.php">Manejar documentos de tus prestaciones</a>
            </div>
        </main>
    </div>

</div>
    <!-- <script src="./prestaciones.js"></script> -->
    <script src="./index.js"></script>
</body>
</html>


<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  if(isset($_POST["logout"]))
  {
  session_destroy();
  echo("<meta http-equiv='refresh' content='1'>");
  }


}


?>