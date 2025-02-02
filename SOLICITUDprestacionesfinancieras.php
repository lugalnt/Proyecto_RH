<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Apoyo Financiero</title>
</head>
<body>
    <h2>Solicitud de Apoyo Financiero</h2>
    <form action="" method="post">
        <label for="nombre_familiar">Nombre del Familiar:</label>
        <input type="text" id="nombre_familiar" name="nombre_familiar" required><br><br>

        <label for="tipo">Tipo de Prestación:</label>
        <select id="tipo" name="tipo">
            <option value="Guarderia">Guardería</option>
            <option value="Gastos funerarios">Gastos funerarios</option>
            <option value="Exencion de pago de inscripciones">Exención de pago de inscripciones</option>
        </select><br><br>

        <button type="submit">Enviar Solicitud</button>
    </form>
</body>
</html>

<?php
require_once("conn.php");
session_start();


if ($_SERVER["REQUEST_METHOD"]=="POST")
{

    $nombre_familiar = $_POST['nombre_familiar'];
    $tipo = $_POST['tipo']
   

   




    
}

?>
