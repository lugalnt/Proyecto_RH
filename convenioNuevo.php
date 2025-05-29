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
            <div class="acciones-container">
    <form action="" method="post" class="accion-item">
        <input type="hidden" name="Registrar" value="Registrar">
        <button value="Registrar" type="submit" class="btn btn-primary">Registrar nuevo convenio</button>
    </form>
    <form action="" method="post" class="accion-item">
        <input type="hidden" name="Modificar" value="Modificar">
        <button value="Modificar" type="submit" class="btn btn-primary">Modificar convenio</button>
    </form>
    <form action="" method="post" class="accion-item">
        <input type="hidden" name="Eliminar" value="Eliminar">
        <button value="Eliminar" type="submit" class="btn btn-primary">Eliminar convenio</button>
    </form>
</div>
    


        <?php
if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["irRegistrar"]) && !isset($_POST["irActualizar"]) && !isset($_POST["iraBorrar"]))
{
                if(isset($_POST["Registrar"]))
                {
                unset($_POST["Registrar"]);


echo<<<HTML

                <h1>Registro de nuevo convenio</h1>
                        <h2>Por favor, llena los siguientes campos:</h2>
                        <form action="" method="post" id="registroConvenioForm">
                            <label for="nombre_familiar"><h5>Nombre del convenio</h5></label>
                            <input type="text" id="nombre_familiar" name="nombre_convenio" placeholder="Nombre del convenio aqui" required><br><br>

                            <label for="tipo"><h5>En que categoria cae?</h5></label>
                            <select id="tipo" name="tipoMayor" required>
                                <option value="Financiera">Financiera</option>
                                <option value="Academico">Academico</option>
                                <option value="Plazo">Plazo</option>
                                <option value="Dia">Dia</option>
                            </select><br><br>

                            <input type="number" name="costo" placeholder="Costo del convenio" required><br><br>
                            <label> Tiempo de periodo de carencia del convenio (En meses) </label>
                            <input type="number" name="tiempoCarencia"><br><br>
                            <label for="documentos"><h5>Lista de documentos requeridos</h5></label> <br>
                            <p>Por favor, ingrese los documentos separados por comas. Ejemplo: "Documento1, Documento2"</p>
                            <input 
                                type="text" 
                                id="documentos" 
                                name="documentos" 
                                value="Carta de solicitud FIRMADA"
                                pattern="^([a-zA-ZÁÉÍÓÚáéíóúñÑ0-9\s]+)(,\s*[a-zA-ZÁÉÍÓÚáéíóúñÑ0-9][a-zA-ZÁÉÍÓÚáéíóúñÑ0-9\s]*)*$"
                                required
                            ><br><br>
                            <input type="hidden" name="irRegistrar" value="irRegistrar"><br><br>
                            <div class="button-container">
                                <button type="submit" id="btnRegistrar" style="display:none;">Registrar</button>
                            </div>
                        </form>
                        <script>
                        const input = document.getElementById('documentos');
                        const btn = document.getElementById('btnRegistrar');
                        const pattern = new RegExp(input.getAttribute('pattern'));

                        function checkPattern() {
                            if (pattern.test(input.value)) {
                                btn.style.display = '';
                            } else {
                                btn.style.display = 'none';
                            }
                        }
                        input.addEventListener('input', checkPattern);
                        window.addEventListener('DOMContentLoaded', checkPattern);
                        </script>
                
                        <script src="./index.js"></script>
                
                        

                
HTML;
                    
                }
                if(isset($_POST["Modificar"]))
                {
                unset($_POST["Modificar"]);
                
echo<<<HTML

                <h1>Actualizar convenio convenio</h1>
                        <h2>Por favor, llena los siguientes campos:</h2>
                        <form action="" method="post">
                           <select name="convenioAactualizar" id="convenio" required>
HTML;
                        require_once("conn.php");
                        include_once("error_handler.php");
                
                        $querySIC = "SELECT * FROM tiposprestacion";
                        $resultSIC = mysqli_query($conn, $querySIC);
                        $rowsSIC = mysqli_num_rows($resultSIC);
                        if($rowsSIC > 0) {
                            while($rowSIC = mysqli_fetch_assoc($resultSIC)) {
                                $nombreConvenio = $rowSIC['nombre'];
                                $tipoConvenio = $rowSIC['tipoMayor'];
                                $costoConvenio = $rowSIC['precio'];
                                $idConvenio = $rowSIC['id'];
                                echo "<option value='" . htmlspecialchars($idConvenio, ENT_QUOTES, 'UTF-8') . "'>" . 
                                     htmlspecialchars($tipoConvenio, ENT_QUOTES, 'UTF-8') . " - " . 
                                     htmlspecialchars($nombreConvenio, ENT_QUOTES, 'UTF-8') . " - $" . 
                                     htmlspecialchars($costoConvenio, ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                        } else {
                            echo "<option value=''>No hay convenios disponibles</option>";
                        }
                
echo<<<HTML
                    </select>
                    
                    <label for="nombre_familiar"><h5>Nombre del convenio</h5></label>
                                <input type="text" id="nombre_familiar" name="nombre_convenio" placeholder="Nombre del convenio aqui" required><br><br>
                
                                <label for="tipo"><h5>En que categoria cae?</h5></label>
                                <select id="tipo" name="tipoMayor" required>
                                        <option value="Financiera">Financiera</option>
                                        <option value="Academico">Academico</option>
                                        <option value="Plazo">Plazo</option>
                                        <option value="Dia">Dia</option>
                                </select><br><br>
                                <input type ="number" name="costo" placeholder="Costo del convenio" required><br><br>
                                <label> Tiempo de periodo de carencia del convenio (En meses) </label>
                                <input type="number" name="tiempoCarencia"><br><br>
                                                            <label for="documentos"><h5>Lista de documentos requeridos</h5></label> <br>
                            <p>Por favor, ingrese los documentos separados por comas. Ejemplo: "Documento1, Documento2"</p>
                            <input 
                                type="text" 
                                id="documentos" 
                                name="documentos" 
                                value="Carta de solicitud FIRMADA"
                                pattern="^([a-zA-ZÁÉÍÓÚáéíóúñÑ0-9\s]+)(,\s*[a-zA-ZÁÉÍÓÚáéíóúñÑ0-9][a-zA-ZÁÉÍÓÚáéíóúñÑ0-9\s]*)*$"
                                required
                            ><br><br>
                            <input type ="hidden" name="irActualizar" value="irActualizar"><br><br>
                            <div class="button-container">
                                <button type="submit" id="btnModificar" style="display:none;">Modificar</button>
                            </div>
                        
                        </form>
                        <script>
                        const input = document.getElementById('documentos');
                        const btn = document.getElementById('btnModificar');
                        const pattern = new RegExp(input.getAttribute('pattern'));

                        function checkPattern() {
                            if (pattern.test(input.value)) {
                                btn.style.display = '';
                            } else {
                                btn.style.display = 'none';
                            }
                        }
                        input.addEventListener('input', checkPattern);
                        window.addEventListener('DOMContentLoaded', checkPattern);
                        </script>

                
                        <script src="./index.js"></script>
                
                        

                
HTML;      
                    
                }
                if(isset($_POST["Eliminar"]))
                {
                unset($_POST["Eliminar"]);

echo<<<HTML

                <h1>Eliminar convenio</h1>
                        <h2>Por favor, llena los siguientes campos:</h2>
                        <form action="" method="post">
                           <select name="convenioAborrar" id="convenio" required>
HTML;
                        require_once("conn.php");
                        include_once("error_handler.php");
                
                        $querySIC = "SELECT * FROM tiposprestacion";
                        $resultSIC = mysqli_query($conn, $querySIC);
                        $rowsSIC = mysqli_num_rows($resultSIC);
                        if($rowsSIC > 0) {
                            while($rowSIC = mysqli_fetch_assoc($resultSIC)) {
                                $nombreConvenio = $rowSIC['nombre'];
                                $tipoConvenio = $rowSIC['tipoMayor'];
                                $costoConvenio = $rowSIC['precio'];
                                $idConvenio = $rowSIC['id'];
                                echo "<option value='" . htmlspecialchars($idConvenio, ENT_QUOTES, 'UTF-8') . "'>" . 
                                     htmlspecialchars($tipoConvenio, ENT_QUOTES, 'UTF-8') . " - " . 
                                     htmlspecialchars($nombreConvenio, ENT_QUOTES, 'UTF-8') . " - $" . 
                                     htmlspecialchars($costoConvenio, ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                        } else {
                            echo "<option value=''>No hay convenios disponibles</option>";
                        }
                
echo<<<HTML
                    </select>
                    
                                <input type ="hidden" name="iraBorrar" value="iraBorrar"><br><br>
                                <div class="button-container">
                                <button type="submit">Eliminar</button>
                                </div>
                        
                        </form>
                
                        <script src="./index.js"></script>
                
                        
                
HTML;                


                }
             }





        ?>




<?php

if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["Modificar"]) && !isset($_POST["Eliminar"]) && !isset($_POST["Registrar"]))
{

    if(isset($_POST["irRegistrar"]))
    {

        unset($_POST["irRegistrar"]);

        require_once("conn.php");
        include_once("error_handler.php");

        $nombreConvenio = $_POST['nombre_convenio'];
        $tipoConvenio = $_POST['tipoMayor'];
        $costoConvenio = $_POST['costo'];
        $periodoCarencia = $_POST['tiempoCarencia'];
        $documentos = $_POST['documentos'];
        

        $queryCIC = "SELECT * FROM tiposprestacion WHERE nombre = '$nombreConvenio'";
        $resultCIC = mysqli_query($conn, $queryCIC);
        $rowsCIC = mysqli_num_rows($resultCIC);
        if($rowsCIC > 0) {
            echo "<script>alert('El convenio ya existe');</script>";
            echo "<script>window.location.href = 'convenioNuevo.php';</script>";
            exit();
        } 


        $queryIIC = $conn->prepare("INSERT INTO tiposprestacion (tipoMayor,nombre,precio,PeriodoMeses,documentos) VALUES (?, ?, ?, ?, ?)");
        $queryIIC->bind_param("ssiis", $tipoConvenio, $nombreConvenio, $costoConvenio, $periodoCarencia, $documentos);

        if($queryIIC->execute()) {
            echo "<script>alert('Convenio registrado exitosamente');</script>";
            echo "<script>window.location.href = 'convenioNuevo.php';</script>";
        } else {
            echo "<script>alert('Error al registrar el convenio');</script>";
            echo "<script>window.location.href = 'convenioNuevo.php';</script>";
        }

    }

    if(isset($_POST["irActualizar"]))
    {
        unset($_POST["irActualizar"]);

        require_once("conn.php");
        include_once("error_handler.php");

        $convenioAact = $_POST['convenioAactualizar'];
        $nombreConvenio = $_POST['nombre_convenio'];
        $tipoConvenio = $_POST['tipoMayor'];
        $costoConvenio = $_POST['costo'];
        $periodoCarencia = $_POST['tiempoCarencia'];
        $documentos = $_POST['documentos'];

        $queryUC = $conn->prepare("UPDATE tiposprestacion SET tipoMayor = ?, nombre = ?, precio = ?, PeriodoMeses = ?, documentos = ? WHERE id = ?");
        $queryUC->bind_param("ssiisi", $tipoConvenio, $nombreConvenio, $costoConvenio, $periodoCarencia, $documentos, $convenioAact);

        if($queryUC->execute()) {
            echo "<script>alert('Convenio actualizado exitosamente');</script>";
            echo "<script>window.location.href = 'convenioNuevo.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el convenio');</script>";
            echo "<script>window.location.href = 'convenioNuevo.php';</script>";
        }


    }

    if(isset($_POST["iraBorrar"]))
    {
        unset($_POST["iraBorrar"]);

        require_once("conn.php");
        include_once("error_handler.php");

        $convenioAact = $_POST['convenioAborrar'];


        $queryUC = $conn->prepare("DELETE FROM tiposprestacion WHERE id = ?");
        $queryUC->bind_param("i", $convenioAact);

        if($queryUC->execute()) {
            echo "<script>alert('Convenio eliminado exitosamente');</script>";
            echo "<script>window.location.href = 'convenioNuevo.php';</script>";
        } else {
            echo "<script>alert('Error al eliminar el convenio');</script>";
            echo "<script>window.location.href = 'convenioNuevo.php';</script>";
        }


    }



}






?>


        </div>
    </div>
    <script src="./index.js"></script>
</body>
</html>