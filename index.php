<?php
include_once("error_handler.php");
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
    <style>
    body.dark-mode input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
    </style>
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
                <a href="index.php" class="active">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Menú</h3>
                </a>
                <a href="empleados.php">
                    <span class="material-icons-sharp">groups</span>
                    <h3>Empleados</h3>
                </a>
                <a href="solicitudesprestaciones.php">
                    <span class="material-icons-sharp">payments</span>
                    <h3>Prestaciones</h3>
                </a>
                <a href="convenioNuevo.php">
                    <span class="material-icons-sharp">article</span>
                    <h3>Convenios</h3>
                </a>
                <a href="RPPP.php">
                    <span class="material-icons-sharp">fact_check</span>
                    <h3>RPPP</h3>
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



<!-- FIN DE Preview de costos -->



            <!-- INICIO DE PRESTAMOS RECIENTES TABLA -->
            <div class="prestamos-recientes">

                        <!-- INICIO DE CIRCULOS -->
                        <div class="insights">
                <!-- INICIO DE PRESTACIONES -->
                <div class="prestaciones">
                    <span class="material-icons-sharp">analytics</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Prestamos Totales</h3>
                            <h1></h1>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle id="circuloTotal" cx='38' cy='38' r='36'></circle>
                            </svg>
                            <div class="number">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN DE PRESTACIONES -->

                <!-- INICIO DE GASTOS -->
                <div class="gastos">
                    <span class="material-icons-sharp">bar_chart</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Gastos Financieras</h3>
                            <h1></h1>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle id="circuloFinancieras" cx='38' cy='38' r='36'></circle>
                            </svg>
                            <div class="number">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN DE GASTOS -->

                <!-- INICIO DE INGRESOS -->
                <div class="ingresos">
                    <span class="material-icons-sharp">stacked_line_chart</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Gastos academicas</h3>
                            <h1></h1>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle id="circuloAcademicas" cx='38' cy='38' r='36'></circle>
                            </svg>
                            <div class="number">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- FIN DE INGRESOS -->
            </div>
            <br>
            <br>
    <div style="text-align: center;">
    <table style="margin: 0 auto;">
        <tr>
            <td>
                <form method="POST" action="">
                    <button>2 semanas</button>
                    <input type="hidden" name="2semanas" value="1">
                </form>
            </td>
            <td>
                <form method="POST" action="">
                    <button>4 meses</button>
                    <input type="hidden" name="4meses" value="1">
                </form>
            </td>
            <td>
                <form method="POST" action="">
                    <button>1 año</button>
                    <input type="hidden" name="1año" value="1">
                </form>
            </td>
            <td>
                <button onclick="window.location.href='costosDetallado.php'">Reporte más detallado</button>
            </td>
        </tr>
    </table>
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


<?php
// Consulta empleados en descanso con sus ausencias activas (hoy)
$queryGED = $conn->prepare("
    SELECT e.Nombre_Empleado, ep.Tipo, 
        pd.Fecha_Solicitada, pd.Motivo, 
        pp.Fecha_Inicio, pp.Fecha_Final, pp.Tipo as MotivoPlazo
    FROM empleado e
    INNER JOIN empleado_prestacion ep ON e.Numero_Empleado = ep.Numero_Empleado
    LEFT JOIN prestacion_dias pd ON ep.Id_Prestacion = pd.Id_Prestacion AND ep.Tipo = 'Día'
    LEFT JOIN prestacion_plazos pp ON ep.Id_Prestacion = pp.Id_Prestacion AND ep.Tipo = 'Plazo'
    WHERE e.Estado = 'En descanso'
");
$queryGED->execute();
$resultGED = $queryGED->get_result();

$hayAusentes = false;
$hoy = date('Y-m-d');
while ($rowGED = $resultGED->fetch_assoc()) {
    if (
        $rowGED['Tipo'] === 'Día' &&
        !empty($rowGED['Fecha_Solicitada']) &&
        $rowGED['Fecha_Solicitada'] === $hoy
    ) {
        $hayAusentes = true;
        echo '
            <div class="empleado-ausente">
                <div class="icon">
                    <span class="material-icons-sharp">person</span>
                </div>
                <div class="right">
                    <div class="info">
                        <h3>' . htmlspecialchars($rowGED['Nombre_Empleado']) . '</h3>
                        <small class="text-muted">Motivo: <b>' . htmlspecialchars($rowGED['Motivo']) . '</b></small>
                    </div>
                    <small class="danger">Válido el: <b>' . htmlspecialchars($rowGED['Fecha_Solicitada']) . '</b></small>
                </div>
            </div>
        ';
    } elseif (
        $rowGED['Tipo'] === 'Plazo' &&
        !empty($rowGED['Fecha_Inicio']) && !empty($rowGED['Fecha_Final']) &&
        $hoy >= $rowGED['Fecha_Inicio'] && $hoy <= $rowGED['Fecha_Final']
    ) {
        $hayAusentes = true;
        echo '
            <div class="empleado-ausente">
                <div class="icon">
                    <span class="material-icons-sharp">person</span>
                </div>
                <div class="right">
                    <div class="info">
                        <h3>' . htmlspecialchars($rowGED['Nombre_Empleado']) . '</h3>
                        <small class="text-muted">Motivo: <b>' . htmlspecialchars($rowGED['MotivoPlazo']) . '</b></small>
                    </div>
                    <small class="danger">Válido desde: <b>' . htmlspecialchars($rowGED['Fecha_Inicio']) . '</b> hasta: <b>' . htmlspecialchars($rowGED['Fecha_Final']) . '</b></small>
                </div>
            </div>
        ';
    }
}
if (!$hayAusentes) {
    echo '
        <div class="empleado-ausente">
            <div class="icon">
                <span class="material-icons-sharp">person</span>
            </div>
            <div class="right">
                <div class="info">
                    <h3>No hay empleados ausentes</h3>
                    <small class="text-muted">Por ahora...</small>
                </div>
            </div>
        </div>';
}
?>

                </div>
                <div class="añadir-empleado">
                    <div onclick="window.location.href='adminPage.php';" style="cursor: pointer;">
                        <center>
                        <span class="material-icons-sharp">add</span>
                        <h3>Añadir Empleado</h3>
                        </center>
                     </div>   
                </div>    
            </div>
        </div> 
    </div>
    <!-- <script src="./prestaciones.js"></script> -->
    <script src="./index.js"></script>

     <script>
        function setCircleProgress(circle, percentage) {
  const radius = circle.r.baseVal.value;
  const circumference = 2 * Math.PI * radius;
  const offset = circumference - (percentage / 100) * circumference;
  
  circle.style.strokeDasharray = `${circumference}`;
  circle.style.strokeDashoffset = `${offset}`;
}
document.addEventListener('DOMContentLoaded', function() {
    const themeToggler = document.querySelector('.theme-toggler');
    const body = document.body;

    themeToggler.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
    });
});
     </script>               


</body>
</html>


<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{

  require_once("conn.php");
  require_once("preciosPrestaciones.php");  

  if(isset($_POST["logout"]))
  {
  session_destroy();
  echo("<meta http-equiv='refresh' content='1'>");
  }

  //AQUI SE OBTIENEN LOS COSTOS DE LAS PRESTACIONES
  //CON LOS DATOS ESTO SON LOS QUE SE VAN A ACTUALIZAR LAS GRAFICAS

  function actualizarCostos($FechaInicio, $FechaFin)
  {
    require_once("conn.php");
    require_once("preciosPrestaciones.php");  

    global $conn; // Ensure $conn is accessible within the function

      $CostosF = 0;
      $CostosA = 0;
  
      // Consulta para obtener las prestaciones de apoyo académico
      $stmt_academico = $conn->prepare("SELECT Tipo, COUNT(*) AS cantidad FROM prestacion_apoyoacademico WHERE Id_Prestacion IN (SELECT Id_Prestacion FROM prestacion WHERE Estado = 'Otorgada' AND Fecha_Otorgada BETWEEN ? AND ? AND Tipo = 'Academico') GROUP BY Tipo");
      $stmt_academico->bind_param('ss', $FechaInicio, $FechaFin);
      $stmt_academico->execute();
      $resultados_academico = $stmt_academico->get_result();
  
      while ($fila = $resultados_academico->fetch_assoc()) {
          $cantidad = $fila['cantidad'];
          $costo_unitario = obtenerPrecioPrestacion($fila['Tipo']);
          $CostosA += is_numeric($costo_unitario) ? $costo_unitario * $cantidad : 0;
      }
  
      // Consulta para obtener las prestaciones de apoyo financiero
      $stmt_financiero = $conn->prepare("SELECT Tipo, COUNT(*) AS cantidad FROM prestacion_apoyofinanciero WHERE Id_Prestacion IN (SELECT Id_Prestacion FROM prestacion WHERE Estado = 'Otorgada' AND Fecha_Otorgada BETWEEN ? AND ? AND Tipo = 'Financiera') GROUP BY Tipo");
      $stmt_financiero->bind_param('ss', $FechaInicio, $FechaFin);
      $stmt_financiero->execute();
      $resultados_financiero = $stmt_financiero->get_result();
  
      while ($fila = $resultados_financiero->fetch_assoc()) {
          $cantidad = $fila['cantidad'];
          $costo_unitario = obtenerPrecioPrestacion($fila['Tipo']);
          $CostosF += is_numeric($costo_unitario) ? $costo_unitario * $cantidad : 0;
      }
  
      $costoTotal = $CostosF + $CostosA;
      $porcentajeF = round(($CostosF / $costoTotal) * 100);
      $porcentajeA = round(($CostosA / $costoTotal) * 100);
  
      echo'
      <script>
  
      const circuloTotal = document.querySelector("#circuloTotal");
      const circuloFinancieras = document.querySelector("#circuloFinancieras"); 
      const circuloAcademicas = document.querySelector("#circuloAcademicas");
      
      function setCircleProgress(circle, percentage) {
      const radius = circle.r.baseVal.value;
      const circumference = 2 * Math.PI * radius;
      const offset = circumference - (percentage / 100) * circumference;
    
      circle.style.strokeDasharray = `${circumference}`;
      circle.style.strokeDashoffset = `${offset}`;
      }
  
      setCircleProgress(circuloTotal, 100); // Ajusta el círculo al 100%
      setCircleProgress(circuloFinancieras, '.$porcentajeF.'); // Ajusta el círculo al porcentaje de financieras
      setCircleProgress(circuloAcademicas, '.$porcentajeA.'); // Ajusta el círculo al porcentaje de académicas
  
      document.querySelector(".prestaciones .number p").textContent = "100%";
      document.querySelector(".gastos .number p").textContent = "'.$porcentajeF.'%";
      document.querySelector(".ingresos .number p").textContent = "'.$porcentajeA.'%";
  
      document.querySelector(".prestaciones h1").textContent = "$'.$costoTotal.'";
      document.querySelector(".gastos h1").textContent = "$'.$CostosF.'";
      document.querySelector(".ingresos h1").textContent = "$'.$CostosA.'";
  
      </script>
      ';
      return;
  }

  if(isset($_POST["2semanas"]))
  {
    unset($_POST["4meses"]);
    unset($_POST["1año"]);
    actualizarCostos(date('Y-m-d', strtotime('-2 weeks')), date('Y-m-d'));
  }

  if(isset($_POST["4meses"]))
  {
    unset($_POST["2semanas"]);
    unset($_POST["1año"]);
    actualizarCostos(date('Y-m-d', strtotime('-4 months')), date('Y-m-d'));
  }

  if(isset($_POST["1año"]))
  {
    unset($_POST["4meses"]);
    unset($_POST["2semanas"]);
    actualizarCostos(date('Y-m-d', strtotime('-1 year')), date('Y-m-d'));
  }





}


?>