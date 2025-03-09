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
    <link rel="stylesheet" href="./stylebuscarEmpleadoYPrestaciones.css">
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
                <a href="empleados.php">
                    <span class="material-icons-sharp">groups</span>
                    <h3>Empleados</h3>
                </a>
                <a href="solicitudesprestaciones.php" class="active">
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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
    const tipoPrestacionElements = document.querySelectorAll("[data-tipo-prestacion]");

    tipoPrestacionElements.forEach(element => {
        const tipo = element.getAttribute("data-tipo-prestacion");
        const diaSolicitadoColumn = element.querySelector(".dia-solicitado");
        const fechaInicioColumn = element.querySelector(".fecha-inicio");
        const fechaFinalColumn = element.querySelector(".fecha-final");

        if (tipo === "Día") {
            diaSolicitadoColumn.style.display = "table-cell";
            fechaInicioColumn.style.display = "none";
            fechaFinalColumn.style.display = "none";
        } else if (tipo === "Plazo") {
            diaSolicitadoColumn.style.display = "none";
            fechaInicioColumn.style.display = "table-cell";
            fechaFinalColumn.style.display = "table-cell";
        } else {
            diaSolicitadoColumn.style.display = "none";
            fechaInicioColumn.style.display = "none";
            fechaFinalColumn.style.display = "none";
        }
    });
});
    </script>
</head>
<body>

        <div class="contenido">
        <h2>Buscar Empleado y Prestaciones</h2>
        <form action="" method="post">
            <input type="text" id="nombre" name="nombre" class="search-input" placeholder="Nombre Del Empleado..." />  
            <br>
            <input type="text" id="numero" name="numero" class="search-input" placeholder="Número Del Empleado..." />  
            <br>
            <div class="button-container">
            <button type="submit">Buscar</button>
            </div>
        </form>
        </div>

        
    <main>
    <div class="prestamos-recientes">

<?php

require_once("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // INFORMACION UTIL PARA ERICK
    //
    //*Porque se muestra 2 veces la tabla?, Porque hay dos metodos para buscar y mostrar los datos,
    //estos metodos son independientes, osea, las tablas actuan como si fueran dos tablas diferentes
    //duplica los estilos para estas tablas, ya que, aunque actuen que son diferentes, son iguales.
    //
    //*Que es todo ese JS? El JS es para mostrar las columnas de la tabla dependiendo del tipo de prestacion
    //que se esta mostrando, por ejemplo en el php, si la prestacion es de tipo "Día", se crea adicionalmente la columna de "Dia solicitado"
    //y si la prestacion es de tipo "Plazo", se crean las columnas de "Fecha de Inicio" y "Fecha de Finalización",
    //esto por si solo hace que las tabla se vea mal, por lo que el JS se encarga de cambiar el estilo dependiendo
    //de cada una para que se vea bien. Favor de adaptarlo al nuevo estilo o encontrar nueva solucion.
    //
    //*Con que me guio? Todo el desmadre de las tablas esta encerrado entre dos bloques de comentario asi:
    // //DESMADRE TABLA/////////////////////////////////////////////////////////////////////////////
    // //////////////////////////////////////////////////////////////////////DESMADRE TABLA/////////
    //
    //*Con que se muestran las prestaciones permitidas y denegadas? Son dos listas, una para negada y otra para permitida
    //estas listas se crean con un foreach que recorre un array asociativo que se crea en el archivo ESTADOsepuedeprestacion.php
    //Creo que seria solo un estilo de lista, pero no estoy seguro, favor de revisar.
    //Tambien debe estar entre bloques de comentario.






    $nombre = $_POST["nombre"];
    $numero = $_POST["numero"];


    if (!empty($nombre) && empty($numero)) {
        $queryNE = $conn->prepare("SELECT Numero_Empleado FROM empleado WHERE Nombre_Empleado = ?");
        $queryNE->bind_param("s", $nombre);
        $queryNE->execute();
        $resultNE = $queryNE->get_result();
        $rowNE = $resultNE->fetch_assoc();
    
        if ($rowNE == NULL) {
            echo "No se encontró el empleado o no ha solicitado prestaciones.";
        } else {
            $numeroEmpleado = $rowNE['Numero_Empleado'];
    
            require_once("ESTADOsepuedeprestacion.php");
    
            if ($numeroEmpleado) {
                $prestacionesPermitidas = verificarPrestaciones($numeroEmpleado);
    
                // Contenedor flex para las listas
                echo '<div class="prestaciones-container">';
                
                // Lista de prestaciones permitidas
                echo '<div class="prestaciones-permitidas">';
                echo '<h3>Prestaciones Permitidas</h3>';
                echo '<ul>';
                foreach ($prestacionesPermitidas as $categoria => $prestaciones) {
                    foreach ($prestaciones as $prestacion => $permitido) {
                        if ($permitido) {
                            echo '<li class="permitido">' . htmlspecialchars($categoria . ': ' . $prestacion) . '</li>';
                        }
                    }
                }
                echo '</ul>';
                echo '</div>';
    
                // Lista de prestaciones no permitidas
                echo '<div class="prestaciones-no-permitidas">';
                echo '<h3>Prestaciones No Permitidas</h3>';
                echo '<ul>';
                foreach ($prestacionesPermitidas as $categoria => $prestaciones) {
                    foreach ($prestaciones as $prestacion => $permitido) {
                        if (!$permitido) {
                            echo '<li class="no-permitido">' . htmlspecialchars($categoria . ': ' . $prestacion) . '</li>';
                        }
                    }
                }
                echo '</ul>';
                echo '</div>';
    
                echo '</div>'; // Cierre del contenedor flex
            }
            //DESMADRE LISTAS //////////////////////////////////////////////////////////////////////////////////////////////
            }

            $queryCIE = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ?");
            $queryCIE->bind_param("i", $numeroEmpleado);
            $queryCIE->execute();
            $resultCIE = $queryCIE->get_result();

            while ($rowCIE = $resultCIE->fetch_assoc()) {
                $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ?");
                $queryGPE->bind_param("i", $numeroEmpleado);
                $queryGPE->execute();
                $resultGPE = $queryGPE->get_result();

                while ($rowGPE = $resultGPE->fetch_assoc()) {
                    $idPrestacion = $rowGPE['Id_Prestacion'];

                    $queryCE = $conn->prepare("SELECT * from prestacion WHERE Id_Prestacion = ?");
                    $queryCE->bind_param("i", $idPrestacion);
                    $queryCE->execute();
                    $resultCE = $queryCE->get_result();
                    $rowCE = $resultCE->fetch_assoc();

                    if ($rowGPE['Tipo'] == "Academico") {
                        $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
                        $queryCPA->bind_param("i", $idPrestacion);
                        $queryCPA->execute();
                        $resultCPA = $queryCPA->get_result();
                        $rowCPA = $resultCPA->fetch_assoc();

                        $tipo = "Apoyo académico: " . $rowCPA['Tipo'];
                    }

                    if ($rowGPE['Tipo'] == "Financiera") {
                        $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
                        $queryCPA->bind_param("i", $idPrestacion);
                        $queryCPA->execute();
                        $resultCPA = $queryCPA->get_result();
                        $rowCPA = $resultCPA->fetch_assoc();

                        $tipo = "Apoyo financiero: " . $rowCPA['Tipo'];
                    }

                    if ($rowGPE['Tipo'] == "Día") {
                        $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                        $queryCPD->bind_param("i", $idPrestacion);
                        $queryCPD->execute();
                        $resultCPD = $queryCPD->get_result();
                        $rowCPD = $resultCPD->fetch_assoc();

                        $tipo = "Día: " . $rowCPD['Motivo'];
                    }

                    if ($rowGPE['Tipo'] == "Plazo") {
                        $queryCPP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
                        $queryCPP->bind_param("i", $idPrestacion);
                        $queryCPP->execute();
                        $resultCPP = $queryCPP->get_result();
                        $rowCPP = $resultCPP->fetch_assoc();

                        $tipo = "Plazo: " . $rowCPP['Tipo'];
                    }
//DESMADRE TABLA////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    echo '
                    <table style="margin-top: 20px;">  <!--ESTA ES LA TABLA, AVECES TIENE 1 COLUMNA O 2 COLUMNAS MAS, VER ABAJO-->
                    <thead>
                        <tr>
                            <th>Nombre del Empleado</th>';

                if ($rowGPE['Tipo'] == "Día") {
                    echo'        
                            <th>Dia solicitado</th>'; //ESTA COLUMNA SE MUESTRA CUANDO LA PRESTACION ES DE DIA
                }
                else if ($rowGPE['Tipo'] == "Plazo") {
                    echo'        
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Finalización</th>'; 
                            //ESTAS COLUMNAS SE MUESTRAN CUANDO LA PRESTACION ES DE PLAZO
                }            

                            //ESTAS SIEMPRE LAS MUESTRA
                echo'        
                        <th>Fecha de Solicitud</th>
                        <th>Tipo de Prestación</th>
                        <th>Fecha de Otorgamiento</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody data-tipo-prestacion="' . htmlspecialchars($rowGPE['Tipo']) . '">
            ';



                echo '
                    <tr>
                        <td>' . htmlspecialchars($rowCIE['Nombre_Empleado']) . '</td>';
                
                if ($rowGPE['Tipo'] == "Día") {
                    echo '<td class="dia-solicitado">' . htmlspecialchars($rowCPD['Fecha_Solicitada']) . '</td>';
                } 
                else if ($rowGPE['Tipo'] == "Plazo") {
                    echo '<td class="fecha-inicio">' . htmlspecialchars($rowCPP['Fecha_Inicio']) . '</td>';
                    echo '<td class="fecha-final">' . htmlspecialchars($rowCPP['Fecha_Final']) . '</td>';
                } 


                echo'   <td>' . htmlspecialchars($rowCE['Fecha_Solicitada']) . '</td>
                        <td>' . htmlspecialchars($tipo) . '</td>
                        <td>' . htmlspecialchars($rowCE['Fecha_Otorgada']) . '</td>
                        <td>' . htmlspecialchars($rowCE['Estado']) . '</td>
                ';

                if ($rowCE['Estado'] == "Pendiente") {
                echo'
                        <td>
                            <form action="otorgarPrestaciones.php" method="post">
                                <input type="hidden" name="idPrestacion" value="' . htmlspecialchars($idPrestacion) . '">
                                <input type="hidden" name="tipoPrestacion" value="'.htmlspecialchars($tipo).'"> 
                                <button type="submit" class="btn btn-primary">Otorgar Prestación</button>
                            </form>
                        </td>
                    </tr>
                ';
                }
                echo'</tbody>
                </table>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////DESMADRE TABLA/////////


                }
            }
        }
    } else if (!empty($numero) && empty($nombre)) {
        $numeroEmpleado = $numero;

        $queryCIE = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ?");
        $queryCIE->bind_param("i", $numeroEmpleado);
        $queryCIE->execute();
        $resultCIE = $queryCIE->get_result();

        if ($resultCIE->num_rows == 0) {
            echo "No se encontró el empleado o no ha solicitado prestaciones.";
            exit();
        }

        require_once("ESTADOsepuedeprestacion.php");

        if ($numeroEmpleado) {
            $prestacionesPermitidas = verificarPrestaciones($numeroEmpleado);

            echo '<h3>Prestaciones Permitidas</h3>';
            echo '<ul>';
            foreach ($prestacionesPermitidas as $categoria => $prestaciones) {
                foreach ($prestaciones as $prestacion => $permitido) {
                    if ($permitido) {
                        echo '<li class="permitido">' . htmlspecialchars($categoria . ': ' . $prestacion) . '</li>';
                    }
                }
            }
            echo '</ul>';

            echo '<h3>Prestaciones No Permitidas</h3>';
            echo '<ul>';
            foreach ($prestacionesPermitidas as $categoria => $prestaciones) {
                foreach ($prestaciones as $prestacion => $permitido) {
                    if (!$permitido) {
                        echo '<li class="no-permitido">' . htmlspecialchars($categoria . ': ' . $prestacion) . '</li>';
                    }
                }
            }
            echo '</ul>';
        }

        while ($rowCIE = $resultCIE->fetch_assoc()) {
            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ?");
            $queryGPE->bind_param("i", $numeroEmpleado);
            $queryGPE->execute();
            $resultGPE = $queryGPE->get_result();

            while ($rowGPE = $resultGPE->fetch_assoc()) {
                $idPrestacion = $rowGPE['Id_Prestacion'];

                $queryCE = $conn->prepare("SELECT * from prestacion WHERE Id_Prestacion = ?");
                $queryCE->bind_param("i", $idPrestacion);
                $queryCE->execute();
                $resultCE = $queryCE->get_result();
                $rowCE = $resultCE->fetch_assoc();

                if ($rowGPE['Tipo'] == "Academico") {
                    $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyoacademico WHERE Id_Prestacion = ?");
                    $queryCPA->bind_param("i", $idPrestacion);
                    $queryCPA->execute();
                    $resultCPA = $queryCPA->get_result();
                    $rowCPA = $resultCPA->fetch_assoc();

                    $tipo = "Apoyo académico: " . $rowCPA['Tipo'];
                }

                if ($rowGPE['Tipo'] == "Financiera") {
                    $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
                    $queryCPA->bind_param("i", $idPrestacion);
                    $queryCPA->execute();
                    $resultCPA = $queryCPA->get_result();
                    $rowCPA = $resultCPA->fetch_assoc();

                    $tipo = "Apoyo financiero: " . $rowCPA['Tipo'];
                }

                if ($rowGPE['Tipo'] == "Día") {
                    $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                    $queryCPD->bind_param("i", $idPrestacion);
                    $queryCPD->execute();
                    $resultCPD = $queryCPD->get_result();
                    $rowCPD = $resultCPD->fetch_assoc();

                    $tipo = "Día: " . $rowCPD['Motivo'];
                }

                if ($rowGPE['Tipo'] == "Plazo") {
                    $queryCPP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
                    $queryCPP->bind_param("i", $idPrestacion);
                    $queryCPP->execute();
                    $resultCPP = $queryCPP->get_result();
                    $rowCPP = $resultCPP->fetch_assoc();

                    $tipo = "Plazo: " . $rowCPP['Tipo'];
                }
//DESMADRE TABLA////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    echo '
    <table style="margin-top: 20px;"> <!--ESTA ES LA TABLA QUE MUESTRA LAS PRESTACIONES CUANDO SON DE DIA O DE PLAZO-->
    <thead>
        <tr>
            <th>Nombre del Empleado</th>';

    if ($rowGPE['Tipo'] == "Día") {
    echo'        
            <th>Dia solicitado</th>';
    }
    else if ($rowGPE['Tipo'] == "Plazo") {
    echo'        
            <th>Fecha de Inicio</th>
            <th>Fecha de Finalización</th>';
    }            


    echo'        
        <th>Fecha de Solicitud</th>
        <th>Tipo de Prestación</th>
        <th>Fecha de Otorgamiento</th>
        <th>Estado</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody data-tipo-prestacion="' . htmlspecialchars($rowGPE['Tipo']) . '">
    ';



    echo '
    <tr>
        <td>' . htmlspecialchars($rowCIE['Nombre_Empleado']) . '</td>';

    if ($rowGPE['Tipo'] == "Día") {
    echo '<td class="dia-solicitado">' . htmlspecialchars($rowCPD['Fecha_Solicitada']) . '</td>';
    } 
    else if ($rowGPE['Tipo'] == "Plazo") {
    echo '<td class="fecha-inicio">' . htmlspecialchars($rowCPP['Fecha_Inicio']) . '</td>';
    echo '<td class="fecha-final">' . htmlspecialchars($rowCPP['Fecha_Final']) . '</td>';
    } 


    echo'   <td>' . htmlspecialchars($rowCE['Fecha_Solicitada']) . '</td>
        <td>' . htmlspecialchars($tipo) . '</td>
        <td>' . htmlspecialchars($rowCE['Fecha_Otorgada']) . '</td>
        <td>' . htmlspecialchars($rowCE['Estado']) . '</td>
    ';

    if ($rowCE['Estado'] == "Pendiente") {
    echo'
        <td>
            <form action="otorgarPrestaciones.php" method="post">
                <input type="hidden" name="idPrestacion" value="' . htmlspecialchars($idPrestacion) . '">
                <input type="hidden" name="tipoPrestacion" value="'.htmlspecialchars($tipo).'">
                <button type="submit" class="btn btn-primary">Otorgar prestación</button>
            </form>
        </td>
    </tr>
    ';
    }
    echo'</tbody>
    </table>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////DESMADRE TABLA/////////
            }
        }
    } else {
        echo "Por favor, ingrese el nombre o el número del empleado.";
    }

    echo '
        </tbody>
        </table>
    ';


?>
<script src="./index.js"></script> 
</div>
</main>
</body>
</html>