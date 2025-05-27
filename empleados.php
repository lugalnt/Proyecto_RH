<?php
require_once("conn.php");
include_once("error_handler.php");
session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
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
                <a href="index.php">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Menú</h3>
                </a>
                <a href="empleados.php" class="active">
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

    <!-- BUSQUEDA DE EMPLEADOS -->
    <div class="contenido"> 
        <h1>Empleados</h1>
        <br> 
        <form action="" method="post">
            <input type="text" id="nombre" name="nombre" class="search-input" placeholder="Nombre Del Empleado..." />
            <br>
            <br>
            <input type="text" id="numero" name="numero" class="search-input" placeholder="Número Del Empleado..." />   
            <br>
            <br>
            <div class="button-container">
            <button type="submit"><h2>Buscar</h2></button>
            <br>
            <br>
            <button type="button" onclick="window.location.href='empleados.php'"><h2>Ver Todos</h2></button>
            <br>
            </div>
        </form>
           

        <?php
        require_once("conn.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $numero = $_POST["numero"];


            if (!empty($nombre)) {
                $query = $conn->prepare("SELECT * FROM empleado WHERE Nombre_Empleado LIKE ?");
                $nombre = "%" . $nombre . "%";
                $query->bind_param("s", $nombre);
            } else if (!empty($numero)) {
                $query = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ?");
                $query->bind_param("i", $numero);
            } else {
                $query = $conn->prepare("SELECT * FROM empleado");
            }
        } else {
            $query = $conn->prepare("SELECT * FROM empleado");
        }

        if (isset($_POST["CambiarEstado"]))
        {
            $numeroEmpleado = $_POST["Numero_Empleado"];
            $estado = $_POST["Estado"];

            $queryUEE = $conn->prepare("UPDATE empleado SET Estado = ? WHERE Numero_Empleado = ? ");
            $queryUEE->bind_param("si",$estado,$numeroEmpleado);
            if($queryUEE->execute())
            {
                echo'<script>alert("Se ha cambiado el estado")</script>';
                echo("<meta http-equiv='refresh' content='1'>");
            }
            $queryUEE->close();
            
        }


        $query->execute();
        $result = $query->get_result();
    
        if ($result->num_rows > 0) {
            echo '<main>';
            echo '<div class="prestamos-recientes">';
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Número de Empleado</th>';
            echo '<th>Nombre</th>';
            echo '<th>Contraseña</th>';
            echo '<th>Área</th>';
            echo '<th>Edad</th>';
            echo '<th>Género</th>';
            echo '<th>Título</th>';
            echo '<th>Fecha de Ingreso</th>';
            echo '<th>Dirección</th>';
            echo '<th>Teléfono</th>';
            echo '<th>Discapacidad</th>';
            echo '<th>Estado</th>';
            echo '<th>Días Extras</th>';
            echo '<th>Días</th>';
            echo '<th>Actualizar Estado</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['Numero_Empleado']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Nombre_Empleado']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Contraseña_Empleado']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Area']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Edad']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Genero']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Titulo']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Fecha_Ingreso']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Direccion']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Telefono']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Discapacidad']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Estado']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Dias_Extras']) . '</td>';
                echo '<td>' . htmlspecialchars($row['Dias']) . '</td>';
                echo '<td>';
                echo '<form action="" method="post">';
                echo '<input type="hidden" name="Numero_Empleado" value="' . htmlspecialchars($row['Numero_Empleado']) . '">';
                echo '<input type="hidden" name="CambiarEstado" value="1">';
                echo '<div class="button.act-container">';
                echo '<select name="Estado">';
                echo '<option value="Activo">Activo</option>';
                echo '<option value="En descanso">En descanso</option>';
                echo '<option value="Incumplimiento">Incumplimiento</option>';
                echo '</select>';
                echo '<button type="submit" id="mas_chico" class="act"><span class="material-icons-sharp">
                restart_alt
                </span></button>';
                echo '</div>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</main>';
        } else {
            echo '<p>No se encontraron empleados.</p>';
        }

        $query->close();
        $conn->close();
        ?>
    <!-- FIN DE BUSQUEDA DE EMPLEADOS -->
    <script src="./index.js"></script> 
    <script>
        const themeToggler = document.querySelector('.theme-toggler');
        const body = document.querySelector('body');

        themeToggler.addEventListener('click', () => {
            body.classList.toggle('dark-theme');
        });
    </script> 
</body>
</html>