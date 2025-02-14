<?php

require_once("conn.php");
require_once("ESTADOempleados.php");
session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos Humanos</title>
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
                        <h2>Recursos<span class="danger">
                            Humanos</span> </h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>

            <div class="sidebar">
                <a href="#">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Menú</h3>
                </a>
                <a href="buscarEmpleadoYPrestaciones.php">
                    <span class="material-icons-sharp">groups</span>
                    <h3>Empleados</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">email</span>
                    <h3>Notificaciones</h3>
                    <span class="message-count">2</span>
                </a>
                <a href="solicitudesprestaciones.php">
                    <span class="material-icons-sharp">payments</span>
                    <h3>Prestaciones</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">date_range</span>
                    <h3>Descansos</h3>
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

            <div class="date">
                <input type="date">
            </div>

            <!-- INICIO DE PRESTAMOS RECIENTES TABLA -->
            <div class="prestamos-recientes">
                <h2>Tus Prestamos Recientes</h2>

                <table>
                    <thead>
                        <tr>
                    <th>Tipo</th>
                    <th>Empleado</th>
                    <th>Fecha Solicitada</th>
                    <th>Estado</th>        
                    </thead>
                    <tbody>
            <?php

                    $querySPR = $conn->prepare("SELECT * FROM prestacion ORDER BY Fecha_Solicitada DESC LIMIT 6");
                    $querySPR->execute();
                    $resultSPR = $querySPR->get_result();

                    while($rowSPR = $resultSPR->fetch_assoc())
                    {
                    
                      $queryCNE = $conn->prepare("SELECT Numero_Empleado FROM empleado_prestacion WHERE Id_Prestacion = ?");
                      $queryCNE->bind_param("i", $rowSPR['Id_Prestacion']);
                      $queryCNE->execute();
                      $resultCNE = $queryCNE->get_result();
                      $rowCNE = $resultCNE->fetch_assoc();
                    
                      $queryCNME = $conn->prepare("SELECT Nombre_Empleado FROM empleado WHERE Numero_Empleado = ?");
                      $queryCNME->bind_param("i", $rowCNE['Numero_Empleado']);
                      $queryCNME->execute();
                      $resultCNME = $queryCNME->get_result();
                      $rowCNME = $resultCNME->fetch_assoc();
                      $NombreEmpleado = $rowCNME['Nombre_Empleado'];
                    
                    
                    
                      echo "<div class='benefits-container'>";
                      echo "<td>".$rowSPR['Tipo']."</td>";
                      echo "<td>".$rowCNE['Numero_Empleado'].", ".htmlspecialchars($NombreEmpleado)."</td>";
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

                <a href="">Mostrar Todos</a>
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