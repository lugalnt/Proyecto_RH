<?php
include_once("error_handler.php");
require_once("conn.php");

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
    <title>Empleado</title>
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="./styleRegistrarFamiliares.css">
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
                <a href="index.php">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Menú</h3>
                </a>
                <a href="registrarfamiliares.php"  class="active">
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

            <h1>Registro De Tus Familiares</h1>
<body>

<div role="region" tabindex="0">
            <main>
                <header>
                    <p id="description"><h2>Registra a un familiar para procesamiento en prestaciones necesarias.</h2></p>
                </header>
                <form id="survey-form" method="post" action="">
                    <label for="name" id="email-label"><h5>Nombre Del Familiar</h5></label>
                    <input type="text" name="nombre" id="Direccion" class="serach-input" placeholder="Nombre Del Familiar" required>

                    <label for="occupation"><h5>Nivel Academico</h5></label>
                    <select name="nivelacademico" id="dropdown">
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                        <option value="Preparatoria">Preparatoria</option>
                        <option value="Universidad">Universidad</option>
                        <option value="No-estudiante">No-Estudiante</option>
                    </select>

                    <label for="edad" id="number-label"><h5>Edad</h5></label>
                    <input type="number" name="edad" id="edad" min="1" max="99" placeholder="Introduce La Edad Del Familiar">

                    <label for="occupation"><h5>Relacion Con El Familiar</h5></label>
                    <select name="relacion" id="dropdown">
                        <option value="Esposo">Esposo/a</option>
                        <option value="Pareja">Pareja/a</option>
                        <option value="Hijo">Hijo/a</option>
                        <option value="Padre">Madre/Padre</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <div class="button-container">
                    <button id="submit" type="submit">Terminar registro</button>
                    </div>
                </form>
            </main>
        </div>
    <script src="./index.js"></script>
        <?php

        if ($_SERVER["REQUEST_METHOD"]=="POST")
        {

           $nombreFam = $_POST['nombre'];
           $nivelAcademicoFam = $_POST['nivelacademico'];
           $edadFam = $_POST['edad'];
           $relacionFam = $_POST['relacion'];
            
            
            $queryFamilia = $conn->prepare("INSERT into familiar_empleado (Nombre_Familiar,Nivel_academico, Edad) values(?,?,?)");
            $queryFamilia->bind_param("ssi", $nombreFam,$nivelAcademicoFam,$edadFam);

            if($queryFamilia->execute())
            {
                $numero_empleado = $_SESSION['Numero_Empleado'];

                $id_familiar = $queryFamilia->insert_id;

                $queryFamiliaEmpleado = $conn->prepare("INSERT INTO empleado_familiar (Numero_Empleado,Id_Familiar,Relacion) VALUES (?,?,?)");
                $queryFamiliaEmpleado->bind_param("iis", $numero_empleado,$id_familiar,$relacionFam);

                if($queryFamiliaEmpleado->execute())
                {
                    echo '<script type="text/javascript">
                    alert("Familiar registrado");
                  </script>'; 
                  echo("<meta http-equiv='refresh' content='1'>");
                }
                else
                {
                    echo '<script type="text/javascript">
                    alert("Problema");
                  </script>'; 
                }

            }
            else
            {
                echo '<script type="text/javascript">
                alert("Problema");
              </script>';
            }


        } 


        ?>




    </main>

                </tr> 
                    </form>
                    
</body>
</html>