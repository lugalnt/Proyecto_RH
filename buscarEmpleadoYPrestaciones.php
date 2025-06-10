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
            <div class="filtros-container">
    <form action="" method="post">
        <div class="filtro-item">
            <input type="checkbox" id="check" name="check" value="on">
            <label for="check">Quiero filtrar</label>
        </div>
        <div class="filtro-item">
            <label for="prestacionFiltro"><h3>Filtros</h3></label>
            <select name="prestacionFiltro" id="prestacionFiltro" required onchange="mostrarSelectEspecifico()">
                <option value="todos">Todos</option>
                <option value="Academico">Académicas</option>
                <option value="Financiera">Financieras</option>
                <option value="Día">Día</option>
                <option value="Plazo">Plazo</option>
            </select>
        </div>
        <div class="filtro-item" id="filtro-academico" style="display:none;">
            <label for="especificoAcademico">Tipo de apoyo académico:</label>
            <select name="especifico" id="especificoAcademico">
                <?php
                $queryTiposAcademicos = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Academica'");
                $queryTiposAcademicos->execute();
                $resultTiposAcademicos = $queryTiposAcademicos->get_result();
                if ($resultTiposAcademicos->num_rows > 0) {
                    while ($row = $resultTiposAcademicos->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay tipos académicos disponibles</option>';
                }
                $queryTiposAcademicos->close();
                ?>
                <!-- Agrega más opciones si es necesario -->
            </select>
        </div>
        <div class="filtro-item" id="filtro-financiera" style="display:none;">
            <label for="especificoFinanciera">Tipo de apoyo financiero:</label>
            <select name="especifico" id="especificoFinanciera">
                <?php
                $queryTiposAcademicos = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Financiera'");
                $queryTiposAcademicos->execute();
                $resultTiposAcademicos = $queryTiposAcademicos->get_result();
                if ($resultTiposAcademicos->num_rows > 0) {
                    while ($row = $resultTiposAcademicos->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay tipos financieros disponibles</option>';
                }
                $queryTiposAcademicos->close();
                ?>
                <!-- Agrega más opciones si es necesario -->
            </select>
        </div>
        <div class="filtro-item" id="filtro-dia" style="display:none;">
            <label for="especificoDia">Motivo del día:</label>
            <select name="especifico" id="especificoDia">
                <?php
                $queryTiposAcademicos = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Dia'");
                $queryTiposAcademicos->execute();
                $resultTiposAcademicos = $queryTiposAcademicos->get_result();
                if ($resultTiposAcademicos->num_rows > 0) {
                    while ($row = $resultTiposAcademicos->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay tipos de dia</option>';
                }
                $queryTiposAcademicos->close();
                ?>
            </select>
        </div>
        <div class="filtro-item" id="filtro-plazo" style="display:none;">
            <label for="especificoPlazo">Tipo de plazo:</label>
            <select name="especifico" id="especificoPlazo">
                <?php
                $queryTiposAcademicos = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Plazo'");
                $queryTiposAcademicos->execute();
                $resultTiposAcademicos = $queryTiposAcademicos->get_result();
                if ($resultTiposAcademicos->num_rows > 0) {
                    while ($row = $resultTiposAcademicos->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['nombre']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                    }
                } else {
                    echo '<option value="">No hay tipos de plazo disponibles</option>';
                }
                $queryTiposAcademicos->close();
                ?>
            </select>
        </div>
        <div class="filtro-item">
            <button type="submit" class="btn btn-primary">Aplicar filtros</button>
        </div>
    </form>

    <script>
    function mostrarSelectEspecifico() {
        // Oculta todos los selects
        document.getElementById('filtro-academico').style.display = 'none';
        document.getElementById('filtro-financiera').style.display = 'none';
        document.getElementById('filtro-dia').style.display = 'none';
        document.getElementById('filtro-plazo').style.display = 'none';

        // Deselecciona todos los selects específicos
        document.getElementById('especificoAcademico').selectedIndex = -1;
        document.getElementById('especificoFinanciera').selectedIndex = -1;
        document.getElementById('especificoDia').selectedIndex = -1;
        document.getElementById('especificoPlazo').selectedIndex = -1;

        var filtro = document.getElementById('prestacionFiltro').value;
        if (filtro === 'Academico') {
            document.getElementById('filtro-academico').style.display = 'block';
            document.getElementById('especificoAcademico').selectedIndex = 0;
        } else if (filtro === 'Financiera') {
            document.getElementById('filtro-financiera').style.display = 'block';
            document.getElementById('especificoFinanciera').selectedIndex = 0;
        } else if (filtro === 'Día') {
            document.getElementById('filtro-dia').style.display = 'block';
            document.getElementById('especificoDia').selectedIndex = 0;
        } else if (filtro === 'Plazo') {
            document.getElementById('filtro-plazo').style.display = 'block';
            document.getElementById('especificoPlazo').selectedIndex = 0;
        }
    }
    </script>
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


    //FILTROS//

    ///////////


    $flitroPrestacion = $_POST['prestacionFiltro'];
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
                echo "<script>console.log('prestacionesPermitidas:', " . json_encode($prestacionesPermitidas) . ");</script>";
    
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
    
                echo '</div>';
                            require_once("ESTADOsepuedeAcademico.php");

            // Lista de prestaciones académicas familiares reclamables
            echo '<div class="prestaciones-familiares">';
            echo '<h3>Prestaciones académicas familiares que pueden reclamar</h3>';
            echo '<ul>';

            // Obtener familiares (excluyendo N/A)
            $queryFamiliares = $conn->prepare("
                SELECT fe.Id_Familiar, fe.Nombre_Familiar
                FROM empleado_familiar ef
                INNER JOIN familiar_empleado fe ON ef.Id_Familiar = fe.Id_Familiar
                WHERE ef.Numero_Empleado = ? AND fe.Id_Familiar != 0
            ");
            $queryFamiliares->bind_param("i", $numeroEmpleado);
            $queryFamiliares->execute();
            $resultFamiliares = $queryFamiliares->get_result();

            // Obtener tipos de prestaciones académicas
            $queryPrestaciones = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Academico'");
            $queryPrestaciones->execute();
            $resultPrestaciones = $queryPrestaciones->get_result();
            $prestacionesAcademicas = [];
            while ($row = $resultPrestaciones->fetch_assoc()) {
                $prestacionesAcademicas[] = $row['nombre'];
            }
            $queryPrestaciones->close();

            while ($fam = $resultFamiliares->fetch_assoc()) {
                $nombreFamiliar = $fam['Nombre_Familiar'];
                $idFamiliar = $fam['Id_Familiar'];
                foreach ($prestacionesAcademicas as $nombrePrestacion) {
                    if (sePuedeOtorgarPrestacionAcademica($numeroEmpleado, $idFamiliar, $nombrePrestacion)) {
                        echo '<li class="familiar-permitido">'
                            . htmlspecialchars($nombreFamiliar . ' puede solicitar: ' . $nombrePrestacion)
                            . '</li>';
                    }
                }
            }
            echo '</ul>';
            echo '</div>';
            } else {                
                // Cierre del contenedor flex
            }
            //DESMADRE LISTAS //////////////////////////////////////////////////////////////////////////////////////////////
            }

            $queryCIE = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ?");
            $queryCIE->bind_param("i", $numeroEmpleado);
            $queryCIE->execute();
            $resultCIE = $queryCIE->get_result();

            while ($rowCIE = $resultCIE->fetch_assoc()) {


                if ($_POST['check'] == "on") {
                    $flitroPrestacion = $_POST['prestacionFiltro'];
                    if ($flitroPrestacion == "Academico") {

                        if (!empty($_POST['especifico'])) {
                            $especifico = $_POST['especifico'];
                            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Academico' AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE ep.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                            $queryGPE->bind_param("is", $numeroEmpleado, $especifico);
                        } else {
                            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Academico' AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE ep.Id_Prestacion = pa.Id_Prestacion)");
                            $queryGPE->bind_param("i", $numeroEmpleado);
                        }

                    } elseif ($flitroPrestacion == "Financiera") {

                        if (!empty($_POST['especifico'])) {
                            $especifico = $_POST['especifico'];
                            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Financiera' AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE ep.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                            $queryGPE->bind_param("is", $numeroEmpleado, $especifico);
                        } else {
                            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Financiera' AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE ep.Id_Prestacion = pa.Id_Prestacion)");
                            $queryGPE->bind_param("i", $numeroEmpleado);
                        }

                    } elseif ($flitroPrestacion == "Día") {

                        if (!empty($_POST['especifico'])) {
                            $especifico = $_POST['especifico'];
                            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Día' AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE ep.Id_Prestacion = pa.Id_Prestacion AND pa.Motivo LIKE ?)");
                            $queryGPE->bind_param("is", $numeroEmpleado, $especifico);
                        } else {
                            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Día' AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE ep.Id_Prestacion = pa.Id_Prestacion)");
                            $queryGPE->bind_param("i", $numeroEmpleado);
                        }

                    } elseif ($flitroPrestacion == "Plazo") {

                        if (!empty($_POST['especifico'])) {
                            $especifico = $_POST['especifico'];
                            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep INNER JOIN prestacion_plazos pa ON ep.Id_Prestacion = pa.Id_Prestacion WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Plazo' AND pa.Tipo LIKE ?");
                            $queryGPE->bind_param("is", $numeroEmpleado, $especifico);
                        } else {
                            $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Plazo'");
                            $queryGPE->bind_param("i", $numeroEmpleado);
                        }

                    } else {
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Fecha_Otorgada IS NULL");
                        $queryGPE->bind_param("i", $numeroEmpleado);
                    }
                }
                else{
                    $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ?");
                    $queryGPE->bind_param("i", $numeroEmpleado);
                }
                $queryGPE->execute();
                $resultGPE = $queryGPE->get_result();

                while ($rowGPE = $resultGPE->fetch_assoc()) {
                    $idPrestacion = $rowGPE['Id_Prestacion'];


                if ($_POST['check'] == "on") {
                    $flitroPrestacion = $_POST['prestacionFiltro'];
                    if ($flitroPrestacion == "Academicas") {

                        if (!empty($_POST['especifico'])) {
                            $especifico = $_POST['especifico'];
                            $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Academico' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                            $queryCE->bind_param("s", $especifico);
                        } else {
                            $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Academico' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                        }

                    } elseif ($flitroPrestacion == "Financieras") {

                        if (!empty($_POST['especifico'])) {
                            $especifico = $_POST['especifico'];
                            $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Financiera' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                            $queryCE->bind_param("s", $especifico);
                        } else {
                            $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Financiera' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                        }

                    } elseif ($flitroPrestacion == "Dias") {

                        if (!empty($_POST['especifico'])) {
                            $especifico = $_POST['especifico'];
                            $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Día' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Motivo LIKE ?)");
                            $queryCE->bind_param("s", $especifico);
                        } else {
                            $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Día' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                        }

                    } elseif ($flitroPrestacion == "Plazos") {

                        if (!empty($_POST['especifico'])) {
                            $especifico = $_POST['especifico'];
                            $queryCE = $conn->prepare("SELECT * FROM prestacion p INNER JOIN prestacion_plazos pa ON p.Id_Prestacion = pa.Id_Prestacion WHERE p.Tipo = 'Plazo' AND p.Fecha_Otorgada IS NULL AND pa.Tipo LIKE ?");
                            $queryCE->bind_param("s", $especifico);
                        } else {
                            $queryCE = $conn->prepare("SELECT * FROM prestacion WHERE Tipo = 'Plazo' AND Fecha_Otorgada IS NULL");
                        }

                    } else {
                        $queryCE = $conn->prepare("SELECT * FROM prestacion WHERE Fecha_Otorgada IS NULL");
                    }
                } else {
                    $queryCE = $conn->prepare("SELECT * from prestacion WHERE Id_Prestacion = ?");
                    $queryCE->bind_param("i", $idPrestacion);
                }
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
                        $tipoMayor = "Academico";
                        $tipoEspecifico = $rowCPA['Tipo'];
                    }

                    if ($rowGPE['Tipo'] == "Financiera") {
                        $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
                        $queryCPA->bind_param("i", $idPrestacion);
                        $queryCPA->execute();
                        $resultCPA = $queryCPA->get_result();
                        $rowCPA = $resultCPA->fetch_assoc();

                        $tipo = "Apoyo financiero: " . $rowCPA['Tipo'];
                        $tipoMayor = "Financiera";
                        $tipoEspecifico = $rowCPA['Tipo'];
                    }

                    if ($rowGPE['Tipo'] == "Día") {
                        $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                        $queryCPD->bind_param("i", $idPrestacion);
                        $queryCPD->execute();
                        $resultCPD = $queryCPD->get_result();
                        $rowCPD = $resultCPD->fetch_assoc();

                        $tipo = "Día: " . $rowCPD['Motivo'];
                        $tipoMayor = "Día";
                        $tipoEspecifico = $rowCPA['Motivo'];
                    }

                    if ($rowGPE['Tipo'] == "Plazo") {
                        $queryCPP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
                        $queryCPP->bind_param("i", $idPrestacion);
                        $queryCPP->execute();
                        $resultCPP = $queryCPP->get_result();
                        $rowCPP = $resultCPP->fetch_assoc();

                        $tipo = "Plazo: " . $rowCPP['Tipo'];
                        $tipoMayor = "Plazo";
                        $tipoEspecifico = $rowCPA['Tipo'];
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
                        <td>' . htmlspecialchars($rowCE['Estado']) . '
                        <br>
                        <form action="mostrarDocumentosDe.php" method="POST">
                        <input type="hidden" name="tipoMayor" value="'.$tipoMayor.'">
                        <input type="hidden" name="tipo_prestacion" value="'.$tipoEspecifico.'"> 
                        <input type="hidden" name="prestacion_id" value="'.$idPrestacion.'">
                        <input type="hidden" name="numero_empleado" value="'.$numeroEmpleado.'">
                        <button type="submit"> Ver documentos de esta solicitud</button>
                        </form>
                        </td>
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
     else if (!empty($numero) && empty($nombre)) {
        $numeroEmpleado = $numero;
        echo "<script>console.log('numeroEmpleado:', " . json_encode($numeroEmpleado) . ");</script>";

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
                echo "<script>console.log('prestacionesPermitidas:', " . json_encode($prestacionesPermitidas) . ");</script>";
    
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
    
                echo '</div>';
                            require_once("ESTADOsepuedeAcademico.php");

            // Lista de prestaciones académicas familiares reclamables
            echo '<div class="prestaciones-familiares">';
            echo '<h3>Prestaciones académicas familiares que pueden reclamar</h3>';
            echo '<ul>';

            // Obtener familiares (excluyendo N/A)
            $queryFamiliares = $conn->prepare("
                SELECT fe.Id_Familiar, fe.Nombre_Familiar
                FROM empleado_familiar ef
                INNER JOIN familiar_empleado fe ON ef.Id_Familiar = fe.Id_Familiar
                WHERE ef.Numero_Empleado = ? AND fe.Id_Familiar != 0
            ");
            $queryFamiliares->bind_param("i", $numeroEmpleado);
            $queryFamiliares->execute();
            $resultFamiliares = $queryFamiliares->get_result();

            // Obtener tipos de prestaciones académicas
            $queryPrestaciones = $conn->prepare("SELECT nombre FROM tiposprestacion WHERE tipoMayor = 'Academico'");
            $queryPrestaciones->execute();
            $resultPrestaciones = $queryPrestaciones->get_result();
            $prestacionesAcademicas = [];
            while ($row = $resultPrestaciones->fetch_assoc()) {
                $prestacionesAcademicas[] = $row['nombre'];
            }
            $queryPrestaciones->close();

            while ($fam = $resultFamiliares->fetch_assoc()) {
                $nombreFamiliar = $fam['Nombre_Familiar'];
                $idFamiliar = $fam['Id_Familiar'];
                foreach ($prestacionesAcademicas as $nombrePrestacion) {
                    if (sePuedeOtorgarPrestacionAcademica($numeroEmpleado, $idFamiliar, $nombrePrestacion)) {
                        echo '<li class="familiar-permitido">'
                            . htmlspecialchars($nombreFamiliar . ' puede solicitar: ' . $nombrePrestacion)
                            . '</li>';
                    }
                }
            }
            echo '</ul>';
            echo '</div>';
            } else {                
                // Cierre del contenedor flex
            }
        

        while ($rowCIE = $resultCIE->fetch_assoc()) {
            if ($_POST['check'] == "on") {
                $flitroPrestacion = $_POST['prestacionFiltro'];
                if ($flitroPrestacion == "Academicas") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Academico' AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE ep.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                        $queryGPE->bind_param("is", $numeroEmpleado, $especifico);
                    } else {
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Academico' AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE ep.Id_Prestacion = pa.Id_Prestacion)");
                        $queryGPE->bind_param("i", $numeroEmpleado);
                    }

                } elseif ($flitroPrestacion == "Financieras") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Financiera' AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE ep.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                        $queryGPE->bind_param("is", $numeroEmpleado, $especifico);
                    } else {
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Financiera' AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE ep.Id_Prestacion = pa.Id_Prestacion)");
                        $queryGPE->bind_param("i", $numeroEmpleado);
                    }

                } elseif ($flitroPrestacion == "Dias") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Día' AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE ep.Id_Prestacion = pa.Id_Prestacion AND pa.Motivo LIKE ?)");
                        $queryGPE->bind_param("is", $numeroEmpleado, $especifico);
                    } else {
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Día' AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE ep.Id_Prestacion = pa.Id_Prestacion)");
                        $queryGPE->bind_param("i", $numeroEmpleado);
                    }

                } elseif ($flitroPrestacion == "Plazos") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep INNER JOIN prestacion_plazos pa ON ep.Id_Prestacion = pa.Id_Prestacion WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Plazo' AND pa.Tipo LIKE ?");
                        $queryGPE->bind_param("is", $numeroEmpleado, $especifico);
                    } else {
                        $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion ep WHERE ep.Numero_Empleado = ? AND ep.Tipo = 'Plazo'");
                        $queryGPE->bind_param("i", $numeroEmpleado);
                    }

                } else {
                    $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ? AND Fecha_Otorgada IS NULL");
                    $queryGPE->bind_param("i", $numeroEmpleado);
                }
            }
            else{
                $queryGPE = $conn->prepare("SELECT * FROM empleado_prestacion WHERE Numero_Empleado = ?");
                $queryGPE->bind_param("i", $numeroEmpleado);
            }
            $queryGPE->execute();
            $resultGPE = $queryGPE->get_result();

            while ($rowGPE = $resultGPE->fetch_assoc()) {
                $idPrestacion = $rowGPE['Id_Prestacion'];


            if ($_POST['check'] == "on") {
                $flitroPrestacion = $_POST['prestacionFiltro'];
                if ($flitroPrestacion == "Academicas") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Academico' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                        $queryCE->bind_param("s", $especifico);
                    } else {
                        $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Academico' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyoacademico pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                    }

                } elseif ($flitroPrestacion == "Financieras") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Financiera' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Tipo LIKE ?)");
                        $queryCE->bind_param("s", $especifico);
                    } else {
                        $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Financiera' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_apoyofinanciero pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                    }

                } elseif ($flitroPrestacion == "Dias") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Día' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE p.Id_Prestacion = pa.Id_Prestacion AND pa.Motivo LIKE ?)");
                        $queryCE->bind_param("s", $especifico);
                    } else {
                        $queryCE = $conn->prepare("SELECT * FROM prestacion p WHERE p.Tipo = 'Día' AND p.Fecha_Otorgada IS NULL AND EXISTS (SELECT 1 FROM prestacion_dias pa WHERE p.Id_Prestacion = pa.Id_Prestacion)");
                    }

                } elseif ($flitroPrestacion == "Plazos") {

                    if (!empty($_POST['especifico'])) {
                        $especifico = $_POST['especifico'];
                        $queryCE = $conn->prepare("SELECT * FROM prestacion p INNER JOIN prestacion_plazos pa ON p.Id_Prestacion = pa.Id_Prestacion WHERE p.Tipo = 'Plazo' AND p.Fecha_Otorgada IS NULL AND pa.Tipo LIKE ?");
                        $queryCE->bind_param("s", $especifico);
                    } else {
                        $queryCE = $conn->prepare("SELECT * FROM prestacion WHERE Tipo = 'Plazo' AND Fecha_Otorgada IS NULL");
                    }

                } else {
                    $queryCE = $conn->prepare("SELECT * FROM prestacion WHERE Fecha_Otorgada IS NULL");
                }
            } else {
                $queryCE = $conn->prepare("SELECT * from prestacion WHERE Id_Prestacion = ?");
                $queryCE->bind_param("i", $idPrestacion);
            }
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
                    $tipoMayor = "Academico";
                    $tipoEspecifico = $rowCPA['Tipo'];
                }

                if ($rowGPE['Tipo'] == "Financiera") {
                    $queryCPA = $conn->prepare("SELECT * FROM prestacion_apoyofinanciero WHERE Id_Prestacion = ?");
                    $queryCPA->bind_param("i", $idPrestacion);
                    $queryCPA->execute();
                    $resultCPA = $queryCPA->get_result();
                    $rowCPA = $resultCPA->fetch_assoc();

                    $tipo = "Apoyo financiero: " . $rowCPA['Tipo'];
                    $tipoMayor = "Financiera";
                    $tipoEspecifico = $rowCPA['Tipo'];
                }

                if ($rowGPE['Tipo'] == "Día") {
                    $queryCPD = $conn->prepare("SELECT * FROM prestacion_dias WHERE Id_Prestacion = ?");
                    $queryCPD->bind_param("i", $idPrestacion);
                    $queryCPD->execute();
                    $resultCPD = $queryCPD->get_result();
                    $rowCPD = $resultCPD->fetch_assoc();

                    $tipo = "Día: " . $rowCPD['Motivo'];
                    $tipoMayor = "Día";
                    $tipoEspecifico = $rowCPD['Motivo'];
                }

                if ($rowGPE['Tipo'] == "Plazo") {
                    $queryCPP = $conn->prepare("SELECT * FROM prestacion_plazos WHERE Id_Prestacion = ?");
                    $queryCPP->bind_param("i", $idPrestacion);
                    $queryCPP->execute();
                    $resultCPP = $queryCPP->get_result();
                    $rowCPP = $resultCPP->fetch_assoc();

                    $tipo = "Plazo: " . $rowCPP['Tipo'];
                    $tipoMayor = "Plazo";
                    $tipoEspecifico = $rowCPP['Tipo'];
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
        <td>' . htmlspecialchars($rowCE['Estado']) . '
        <br>
        <form action="mostrarDocumentosDe.php" method="POST">
        <input type="hidden" name="tipoMayor" value="'.$tipoMayor.'">
        <input type="hidden" name="tipo_prestacion" value="'.$tipoEspecifico.'"> 
        <input type="hidden" name="prestacion_id" value="'.$idPrestacion.'">
        <input type="hidden" name="numero_empleado" value="'.$numeroEmpleado.'">
        <button type="submit"> Ver documentos de esta solicitud</button>
        </form>       
        </td>
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

}
?>
<script src="./index.js"></script> 
</div>
</main>
</body>
</html>