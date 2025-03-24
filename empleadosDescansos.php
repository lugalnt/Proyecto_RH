<?php
// Include the database connection
include 'conn.php';

// Query to fetch employees who are currently on "En descanso" status along with their reasons
$query = "
    SELECT e.Numero_Empleado, e.Nombre_Empleado, 
           COALESCE(pd.Motivo, pp.Tipo, 'Sin motivo') AS Motivo
    FROM empleado e
    LEFT JOIN prestacion_dias pd ON e.Numero_Empleado = pd.Numero_Empleado 
        AND pd.Fecha_Solicitada = CURDATE()
    LEFT JOIN prestacion_plazos pp ON e.Numero_Empleado = pp.Numero_Empleado 
        AND CURDATE() BETWEEN pp.Fecha_Inicio AND pp.Fecha_Final
    WHERE e.Estado = 'En descanso'
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados en Descanso</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Empleados en Descanso</h1>
    <table>
        <thead>
            <tr>
                <th>Número de Empleado</th>
                <th>Nombre</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Numero_Empleado']); ?></td>
                        <td><?php echo htmlspecialchars($row['Nombre_Empleado']); ?></td>
                        <td><?php echo htmlspecialchars($row['Motivo']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No hay empleados en descanso.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
