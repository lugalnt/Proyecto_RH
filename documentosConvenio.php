<?php
require_once("conn.php");
session_start();

?>

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
                <a href="convenioNuevo.php"  class="active">
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
            </main>

            
        </div>
    </div>
    <script src="./index.js"></script>
</body>
</html>



<?php
?>