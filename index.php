<?php

require_once("conn.php");
require_once("ESTADOempleados.php");
session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
}

if($_SESSION['Area'] != "RH")
{
  header('Location: indexEmpleado.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos Humanos</title>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="./nuevostyle.css">
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
                <a href="#" class="active">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Menú</h3>
                </a>
                <a href="empleados.php">
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
        <main>
            <h1>Menú</h1>



<!-- INICIO DE Preview de Costos -->
            <div class="date">
                <form method="POST" action="">
                <h2>Costos</h2>
                <label for="FechaInicio">Fecha de Inicio</label>
                <input type="date" name ="FechaInicio">
                <label for="FechaFin">Fecha de Fin</label>
                <input type="date" name ="FechaFin">
                <input type="hidden" name="Costos" value="1">
                <button type="submit">Revisar</button>
                </form>
            </div>
<!-- FIN DE Preview de costos -->



            <!-- INICIO DE CIRCULOS -->
            <div class="insights">
                <!-- INICIO DE PRESTACIONES -->
                <div class="prestaciones">
                    <span class="material-icons-sharp">analytics</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Prestamos Totales</h3>
                            <h1>$25,076</h1>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx='38' cy='38' r='36'></circle>
                            </svg>
                            <div class="number">
                                <p>81%</p>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Ultimo mes.</small>
                </div>
                <!-- FIN DE PRESTACIONES -->

                <!-- INICIO DE GASTOS -->
                <div class="gastos">
                    <span class="material-icons-sharp">bar_chart</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Gastos Totales</h3>
                            <h1>$12,056</h1>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx='38' cy='38' r='36'></circle>
                            </svg>
                            <div class="number">
                                <p>34%</p>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Ultimo mes.</small>
                </div>
                <!-- FIN DE GASTOS -->

                <!-- INICIO DE INGRESOS -->
                <div class="ingresos">
                    <span class="material-icons-sharp">stacked_line_chart</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Ingresos Totales</h3>
                            <h1>$21,236</h1>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx='38' cy='38' r='36'></circle>
                            </svg>
                            <div class="number">
                                <p>81%</p>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Ultimo mes.</small>
                </div>
            </div>
                <!-- FIN DE INGRESOS -->

            <!-- INICIO DE PRESTAMOS RECIENTES TABLA -->
            <div class="prestamos-recientes">
                <h2>Prestamos Recientes</h2>

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
        <!-- FIN DE PRESTAMOS RECIENTES TABLA -->

        <!-- PARTE DERECHA DE LA PANTALLA -->
        <div class="right">
            <!-- INICIO DEL TOP-->
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
            <!-- FIN DEL TOP-->

            <!-- INICIO DE ACTUALIZACIONES RECIENTES-->
            <div class="actualizaciones-recientes">
                <h2>Actualizaciones Recientes</h2>
                <div class="actualizaciones">
                    

                    <?php

                    $querySPR = $conn->prepare("SELECT * FROM prestacion ORDER BY Fecha_Solicitada DESC LIMIT 3");
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
                    
                      echo'<div class="actualizacion">
                        <div class="profile-photo">
                            <img src="./images/profile-2.jpg.jpeg">
                        </div>';  

                      echo'  <div class="message">
                            <p><b>'.htmlspecialchars($NombreEmpleado).'</b> ha registrado un nuevo prestamo de
                            '.htmlspecialchars($rowSPR['Tipo']).'.</p>
                            <small class="text-muted">El: '.htmlspecialchars($rowSPR['Fecha_Solicitada']).'</small>
                        </div> </div>';
                    }


                    ?>

                    
                    
                </div>
            </div>
            <!-- FIN DE ACTUALIZACIONES RECIENTES-->

            <!-- INICIO DE -->
            <div class="empleados-ausentes">
                <h2>Empleados Ausentes</h2>
                <!--
                <div class="empleado-ausente">
                    <div class="icon">
                        <span class="material-icons-sharp">person</span>
                    </div>
                    <div class="right">
                        <div class="info">
                            <h3>Nombre de Empleado</h3>
                            <small class="text-muted">Vacaciones</small>
                        </div>
                        <small class="danger"> Valido hasta el 1 de Julio del 2025</small>
                    </div>
                </div>
                <div class="empleado-ausente">
                    <div class="icon">
                        <span class="material-icons-sharp">person</span>
                    </div>
                    <div class="right">
                        <div class="info">
                            <h3>Nombre de Empleado</h3>
                            <small class="text-muted">Vacaciones</small>
                        </div>
                        <small class="danger"> Valido hasta el 1 de Julio del 2025</small>
                    </div>
                </div>
                -->
                <div class="empleado-ausente">
                    <div class="icon">
                        <span class="material-icons-sharp">person</span>
                    </div>

                    <?php
                    
                    $queryGED = $conn->prepare("SELECT * FROM empleado WHERE Estado = 'En Descanso'"); 
                    $queryGED->execute();
                    $resultGED = $queryGED->get_result();
                    
                    while ($rowGED = $resultGED->fetch_assoc())
                    {
                     
                       $queryGEP = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Fecha_Otorgada IS NOT NULL AND Tipo = 'Día'");
                       $queryGEP->bind_param("i", $rowGED['Numero_Empleado']);
                       $queryGEP->execute();
                       $resultGEP = $queryGEP->get_result();
                       $rowGEP = $resultGEP->fetch_assoc();
                       
                       $queryGPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                       $queryGPD->bind_param("i", $rowGEP['Id_Prestacion']);
                       $queryGPD->execute();
                       $resultGPD = $queryGPD->get_result();
                       $rowGPD = $resultGPD->fetch_assoc();

                       echo'

                          <div class="right">
                        <div class="info">
                            <h3>'.htmlspecialchars($rowGED['Nombre_Empleado']).'</h3>
                            <small class="text-muted">Motivo: '.htmlspecialchars($rowGPD['Motivo']).'</small>
                        </div>
                        <small class="danger"> Valido el: '.htmlspecialchars($rowGPD['Fecha_Solicitada']).'</small>
                    </div>
                       
                       
                       
                       ';



                    }


                    if ($resultGED->num_rows == 0)
                    {
                        echo '
                        <div class="right">
                        <div class="info">
                            <h3>No hay empleados ausentes</h3>
                            <small class="text-muted">Por ahora...</small>
                        </div>
                    </div>"';
                    }
                 

                    ?>

                </div>
                <div class="añadir-empleado">
                    <div onclick="window.location.href='adminPage.php';" style="cursor: pointer;">
                        <span class="material-icons-sharp">add</span>
                        <h3>Añadir Empleado</h3>
                     </div>   
                </div>    
            </div>
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


    if(isset($_POST["Costos"]))
    {
      require_once("conn.php");
      require_once("preciosPrestaciones.php");
      $FechaInicio = $_POST['FechaInicio'];
      $FechaFin = $_POST['FechaFin'];

      $queryCostos = $conn->prepare("SELECT * FROM prestacion WHERE Fecha_Otorgada BETWEEN ? AND ?");
      $queryCostos->bind_param("ss", $FechaInicio, $FechaFin);
      $queryCostos->execute();
      $resultCostos = $queryCostos->get_result();

      while($rowCostos = $resultCostos->fetch_assoc())
      {
        
        if ($rowCostos['Tipo'] == "Financiera")
        {
          $queryCostosF = $conn->prepare("SELECT Tipo, COUNT(*) as count FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ? GROUP BY Tipo");
          $queryCostosF->bind_param("i",$rowCostos['Id_Prestacion']);
          $queryCostosF->execute();
          $resultCostosF = $queryCostosF->get_result();
          $rowCostosF = $resultCostosF->fetch_assoc();
          $CostosF = (int)obtenerPrecioPrestacion($rowCostosF['Tipo'])*(int)$rowCostosF['count'];
        }
        else if ($rowCostos['Tipo'] == "Academico")
        {
          $queryCostosA = $conn->prepare("SELECT Tipo, COUNT(*) as count FROM prestacion_apoyoacademico WHERE Id_Prestacion = ? GROUP BY Tipo");
          $queryCostosA->bind_param("i",$rowCostos['Id_Prestacion']);
          $queryCostosA->execute();
          $resultCostosA = $queryCostosA->get_result();
          $rowCostosA = $resultCostosA->fetch_assoc();
          $CostosA = (int)obtenerPrecioPrestacion($rowCostosA['Tipo'])*(int)$rowCostosA['count'];
        }

      }

      $costoTotal = $CostosF + $CostosA;
        echo "<script>alert('El costo total de las prestaciones otorgadas entre ".$FechaInicio." y ".$FechaFin." es de: $".$costoTotal."');</script>";
        echo "<script>alert('Prestaciones financieras: $".$CostosF." y Prestaciones Academicas: $".$CostosA."');</script>";
    }
}


?>