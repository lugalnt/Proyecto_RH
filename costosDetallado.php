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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos Humanos</title>  
    <!-- ASIGNACION DE CSS -->
    <link rel="stylesheet" href="./prestacionesdetalladas.css">
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
    <main>
    <!-- CONTENIDO PRINCIPAL -->   
    <h1>Reporte de Prestaciones Otorgadas</h1>
    <form method="post" action="">
    <br>
    <div class="fecha-group">
        <label for="fecha_inicio"><h2>Fecha Inicio:</h2></label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>" required>
    </div>
    <div class="fecha-group">
        <label for="fecha_fin"><h2>Fecha Fin:</h2></label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>" required>
    </div>
    <button type="submit">Consultar</button>
</form>
 
<?php

require_once("conn.php"); 
include_once("error_handler.php");
require_once("preciosPrestaciones.php");

$fecha_inicio = '';
$fecha_fin    = '';
$resultados   = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
    // Recibimos las fechas
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin    = $_POST['fecha_fin'];

    // Consulta para obtener las prestaciones generales, excluyendo "Día" y "Plazo"
    $stmt = $conn->prepare("SELECT Tipo, COUNT(*) AS cantidad FROM prestacion WHERE Estado = 'Otorgada' AND Fecha_Otorgada BETWEEN ? AND ? AND Tipo NOT IN ('Día', 'Plazo') GROUP BY Tipo");  
    $stmt->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $resultados = $stmt->get_result();

    // Consulta para obtener las prestaciones de apoyo académico
    $stmt_academico = $conn->prepare("SELECT Tipo, COUNT(*) AS cantidad FROM prestacion_apoyoacademico WHERE Id_Prestacion IN (SELECT Id_Prestacion FROM prestacion WHERE Estado = 'Otorgada' AND Fecha_Otorgada BETWEEN ? AND ? AND Tipo = 'Academico') GROUP BY Tipo");
    $stmt_academico->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt_academico->execute();
    $resultados_academico = $stmt_academico->get_result();

    // Consulta para obtener las prestaciones de apoyo financiero
    $stmt_financiero = $conn->prepare("SELECT Tipo, COUNT(*) AS cantidad FROM prestacion_apoyofinanciero WHERE Id_Prestacion IN (SELECT Id_Prestacion FROM prestacion WHERE Estado = 'Otorgada' AND Fecha_Otorgada BETWEEN ? AND ? AND Tipo = 'Financiera') GROUP BY Tipo");
    $stmt_financiero->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt_financiero->execute();
    $resultados_financiero = $stmt_financiero->get_result();
}
?>   

<?php if (isset($resultados) || isset($resultados_academico) || isset($resultados_financiero)): ?>
    <div class="table-container">
    <br>
    <br>
    <table>
    <thead>
        <tr>
            <th>Tipo de Prestación</th>
            <th>Cantidad</th>
            <th>Costo Unitario</th>
            <th>Costo Total</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($resultados_academico->num_rows > 0): ?>
    <tr>
        <th colspan="4">Académicas</th>
    </tr>
    <?php while ($fila = $resultados_academico->fetch_assoc()): ?>
        <?php
        $contA += $fila['cantidad']; // Suma la cantidad al contador
        $tipo = $fila['Tipo'];
        $cantidad = $fila['cantidad'];
        $costo_unitario = obtenerPrecioPrestacion($tipo);
        $costo_total = is_numeric($costo_unitario) ? $costo_unitario * $cantidad : $costo_unitario;
        $costo_totalA += $costo_total; // Suma el costo total al acumulador
        ?>
        <tr>
            <td><?php echo htmlspecialchars($tipo); ?></td>
            <td><?php echo htmlspecialchars($cantidad); ?></td>
            <td><?php echo htmlspecialchars($costo_unitario); ?></td>
            <td><?php echo htmlspecialchars($costo_total); ?></td>
        </tr>
    <?php endwhile; ?>
<?php endif; ?>

            <?php if ($resultados_financiero->num_rows > 0): ?>
                <tr>
                    <th colspan="4">Financieras</th>
                </tr>
                <?php while ($fila = $resultados_financiero->fetch_assoc()): ?>
                    <?php
                    $contF = $contF + $fila['cantidad'];
                    $tipo = $fila['Tipo'];
                    $cantidad = $fila['cantidad'];
                    $costo_unitario = obtenerPrecioPrestacion($tipo);
                    $costo_total = is_numeric($costo_unitario) ? $costo_unitario * $cantidad : $costo_unitario;
                    $costo_totalF = $costo_totalF + $costo_total;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo); ?></td>
                        <td><?php echo htmlspecialchars($cantidad); ?></td>
                        <td><?php echo htmlspecialchars($costo_unitario); ?></td>
                        <td><?php echo htmlspecialchars($costo_total); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>

            <tr>
                <th colspan="4">Totales</th>
            </tr>
            <tr>
                <td>Académicas</td>
                <td><?php echo htmlspecialchars($contA); ?></td>
                <td></td>
                <td><?php echo htmlspecialchars($costo_totalA); ?></td>
            </tr>
            <tr>
                <td>Financieras</td>
                <td><?php echo htmlspecialchars($contF); ?></td>
                <td></td>
                <td><?php echo htmlspecialchars($costo_totalF); ?></td>
            </tr>
            <tr>
                <td>Total en General</td>
                <td><?php echo htmlspecialchars($contF+$contA); ?></td>
                <td></td>
                <td><?php echo htmlspecialchars($costo_totalF+$costo_totalA); ?></td>
            </tr>
        </tbody>
    </table>
<?php else: ?>
    <p>No se encontraron resultados para el rango de fechas seleccionado.</p>
<?php endif; ?>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
</main>
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
</body>
<script src="./index.js"></script>
</html>