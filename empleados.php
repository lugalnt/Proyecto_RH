<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Empleados</h2>
        <form action="" method="post"> <!--ESTE ES EL FORMULARIO QUE SE UTILIZA PARA BUSCAR EMPLEADOS-->
            <label for="nombre">Nombre del Empleado:</label>
            <input type="text" id="nombre" name="nombre">
            <br>
            <label for="numero">Número del Empleado:</label>
            <input type="text" id="numero" name="numero">
            <br>
            <button type="submit">Buscar</button>
            <button type="button" onclick="window.location.href='empleados.php'">Ver Todos</button>
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

        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            echo '<table class="table table-striped">'; //ESTA ES LA TABLA QUE MUESTRA LOS EMPLEADOS
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
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No se encontraron empleados.</p>';
        }

        $query->close();
        $conn->close();
        ?>
    </div>
</body>
</html>