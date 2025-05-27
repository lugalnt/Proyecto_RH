<?php
require_once("conn.php");
//include_once("error_handler.php");
session_start();

if(!isset($_SESSION['Numero_Empleado'])) {
    header('Location: login.html');
    exit();
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
    <title>Busqueda De Empleado</title>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="./styleSolicitudDePrestacionesxd.css">
    <link rel="stylesheet" href="./styleRegistrarFamiliares.css">   
    <!-- SIMBOLOS QUE SE UTILIZARAN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
</head>
<body>

    <!-- BARRA LATERAL -->
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="./images/logo.png.png">
                    <h2>Recursos<span class="danger"> Humanos</span></h2>
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
                <a href="RPPP.php" class="active">
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

        <!-- APARTADO DE CUENTA Y CAMBIO DE MODO CLARO/OSCURO -->
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
            <!-- FIN DE APARTADO DE CUENTA Y CAMBIO DE MODO CLARO/OSCURO -->

  
            <main>
            <div class="acciones-container">
            <h2>Registrar prestamo por profesor.</h2>




    <form id="formPrestacion" action="" method="post">

    <input type="hidden" name="" value="1">
     
    <select name="numeroEmpleado2" id="profesor">
        <?php
        $queryCP = $conn->prepare("SELECT * FROM empleado");
        $queryCP->execute(); 
        $resultCP = $queryCP->get_result();
        while($rowCP = $resultCP->fetch_assoc()){
            echo '<option value= "'.$rowCP['Numero_Empleado'].'">'.$rowCP['Nombre_Empleado'].' -- '.$rowCP['Area'].'</option>'; 
        }
        ?>
    </select>

  <label for="tipoPrestacion">Selecciona el tipo de prestación</label><br>
  <select name="tipoPrestacion" id="tipoPrestacion" onchange="showSelectedSelect()">
    <option value="">-- Selecciona un tipo --</option>
    <option value="prestacionFinanciera">Prestaciones Financieras</option>
    <option value="prestacionAcademica">Prestaciones Académicas</option>
    <option value="prestacionDia">Prestaciones de Día</option>
    <option value="prestacionPlazo">Prestaciones de Plazo</option>
  </select>
  <br><br>

  <div id="prestacionFinanciera" class="prestacion-select" style="display: none;">

  </div>

  <div id="prestacionAcademica" class="prestacion-select" style="display: none;">

  </div>

  <div id="prestacionDia" class="prestacion-select" style="display: none;">

  </div>

  <div id="prestacionPlazo" class="prestacion-select" style="display: none;">

  </div>

  <br>
  <button type="submit">Enviar</button>
</form>

<script>
  function showSelectedSelect() {
    const selects = ['prestacionFinanciera', 'prestacionAcademica', 'prestacionDia', 'prestacionPlazo'];
    const selectedType = document.getElementById('tipoPrestacion').value;
    const form = document.getElementById('formPrestacion');

    // Mapeo de tipoPrestacion → URL de destino
    const actionMap = {
      prestacionFinanciera: 'RPPP/SOLICITUDprestacionesfinancieras.php',
      prestacionAcademica: 'RPPP/SOLICITUDprestacionapoyoacademico.php',
      prestacionDia:        'RPPP/SOLICITUDprestaciondia.php',
      prestacionPlazo:      'RPPP/SOLICITUDprestacionplazo.php'
    };

    // Actualiza el action del form (o lo deja vacío si no hay selección)
    form.action = actionMap[selectedType] || '';

    // Muestra sólo el select correspondiente
    selects.forEach(id => {
      document.getElementById(id).style.display = (id === selectedType) ? 'block' : 'none';
    });
  }
</script>

  
    



        </div>
    </div>
    <script src="./index.js"></script>
</body>
</html>