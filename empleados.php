<?php
require_once("conn.php");
session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
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
    <div class="container">
        <h2>Empleados</h2>
        <form action="" method="post">
            <label for="nombre">Nombre del Empleado:</label>
            <input type="text" id="nombre" name="nombre">
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

        $query->execute();
        $result = $query->get_result();
    
        if ($result->num_rows > 0) {
            echo '<table class="table table-striped">';
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