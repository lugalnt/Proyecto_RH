<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Prestaciones Otorgadas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        label { margin-right: 10px; }
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Reporte de Prestaciones Otorgadas</h1>
    <form method="post" action="">
        <label for="fecha_inicio">Fecha Inicio:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>" required>
        <label for="fecha_fin">Fecha Fin:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>" required>
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
    $stmt_academico = $conn->prepare("SELECT Tipo, COUNT(*) AS cantidad FROM prestacion_apoyoacademico WHERE Id_Prestacion IN (SELECT Id_Prestacion FROM prestacion WHERE Estado = 'Otorgada' AND Fecha_Otorgada BETWEEN ? AND ? AND Tipo NOT IN ('Día', 'Plazo')) GROUP BY Tipo");
    $stmt_academico->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt_academico->execute();
    $resultados_academico = $stmt_academico->get_result();

    // Consulta para obtener las prestaciones de apoyo financiero
    $stmt_financiero = $conn->prepare("SELECT Tipo, COUNT(*) AS cantidad FROM prestacion_apoyofinanciero WHERE Id_Prestacion IN (SELECT Id_Prestacion FROM prestacion WHERE Estado = 'Otorgada' AND Fecha_Otorgada BETWEEN ? AND ? AND Tipo NOT IN ('Día', 'Plazo')) GROUP BY Tipo");
    $stmt_financiero->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt_financiero->execute();
    $resultados_financiero = $stmt_financiero->get_result();
}
?>   

<?php if (isset($resultados) || isset($resultados_academico) || isset($resultados_financiero)): ?>
    <table>
        <thead>
            <tr>
                <th>Tipo de Prestación</th>
                <th>Cantidad</th>
                <th>Costo Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultados->num_rows > 0): ?>
                <tr>
                    <th colspan="3">Generales</th>
                </tr>
                <?php while ($fila = $resultados->fetch_assoc()): ?>
                    <?php
                    $tipo = $fila['Tipo'];
                    $cantidad = $fila['cantidad'];
                    $costo_unitario = obtenerPrecioPrestacion($tipo);
                    $costo_total = is_numeric($costo_unitario) ? $costo_unitario * $cantidad : $costo_unitario;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo); ?></td>
                        <td><?php echo htmlspecialchars($cantidad); ?></td>
                        <td><?php echo htmlspecialchars($costo_total); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php if ($resultados_academico->num_rows > 0): ?>
                <tr>
                    <th colspan="3">Académicas</th>
                </tr>
                <?php while ($fila = $resultados_academico->fetch_assoc()): ?>
                    <?php
                    $tipo = $fila['Tipo'];
                    $cantidad = $fila['cantidad'];
                    $costo_unitario = obtenerPrecioPrestacion($tipo);
                    $costo_total = is_numeric($costo_unitario) ? $costo_unitario * $cantidad : $costo_unitario;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo); ?></td>
                        <td><?php echo htmlspecialchars($cantidad); ?></td>
                        <td><?php echo htmlspecialchars($costo_total); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php if ($resultados_financiero->num_rows > 0): ?>
                <tr>
                    <th colspan="3">Financieras</th>
                </tr>
                <?php while ($fila = $resultados_financiero->fetch_assoc()): ?>
                    <?php
                    $tipo = $fila['Tipo'];
                    $cantidad = $fila['cantidad'];
                    $costo_unitario = obtenerPrecioPrestacion($tipo);
                    $costo_total = is_numeric($costo_unitario) ? $costo_unitario * $cantidad : $costo_unitario;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo); ?></td>
                        <td><?php echo htmlspecialchars($cantidad); ?></td>
                        <td><?php echo htmlspecialchars($costo_total); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No se encontraron resultados para el rango de fechas seleccionado.</p>
<?php endif; ?>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
</body>
</html>