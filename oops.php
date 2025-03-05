<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h1 {
            font-size: 2em;
            color: #e74c3c;
        }
        .container p {
            font-size: 1.2em;
        }
        .report {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Ups! Algo salió mal</h1>
        <p>Lo sentimos, ha ocurrido un error inesperado.</p>
        <div class="report">
            <p>Por favor, reporte este codigo de error al desarrollador:</p>
            <!-- Aquí se añadirá el mensaje de error -->

                            <?php
                session_start();
                $error = $_SESSION['error'] ?? null;
                session_destroy(); // Limpiar el error después de mostrarlo

                if ($error) {
                    $codigo = $error['codigo'];
                    $mensaje = htmlspecialchars($error['mensaje']);
                    $archivo = htmlspecialchars($error['archivo']);
                    $linea = $error['linea'];
                
                    echo "<p><strong>".$codigo."</strong></p>";
                    echo "<br>";
                    echo "<p><strong>Mensaje:</strong>".$mensaje."</p>";
                    echo "<br>";
                    if (strpos($archivo, 'conn') !== false) {
                        echo "<h1> Es posible que el servidor no está iniciado, contactar con RH</h1>";
                    } else {
                        echo "<p><strong>Archivo:</strong>".$archivo."</p>";
                    }
                    echo "<br>";
                    echo "<p><strong>Línea:</strong>".$linea."</p>";


                } else {
                    echo "<h1>¡Ups! Algo salió mal.</h1>";
                    echo "<p>No hay detalles disponibles sobre el error.</p>";
                }
                ?>


        </div>
        <a> <a href="javascript:history.back()">Regresar</a>
    </div>
    
</body>
</html>