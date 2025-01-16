<?php

require_once("conn.php");
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST"){

    $nombre = $_POST["nombre"];
    $pass = $_POST["pass"];

    $querychecar = $conn->prepare("SELECT * FROM usuarios WHERE Nombre_Empleado = ? and Contraseña_Empleado = ?");
    $querychecar->bind_param("ss", $nombre, $pass);
    $querychecar->execute();
    $result = $querychecar->get_result();
    ////////////////////////////////////////////////////////////////
    if ($result->num_rows > 0) {
        echo '<script type="text/javascript">
                alert("Ese usuario ya existe.");
                history.back();
              </script>';
         
    } 
    else
    {

        $query = $conn->prepare("INSERT INTO usuarios(Nombre_Empleado,Contraseña_Empleado) VALUES(?,?)");
        $query->bind_param("ss", $nombre, $pass);
        if ($query->execute()) {
            echo '<script type="text/javascript">
        alert("Registro Correcto");
        history.back();
      </script>';
        } else {
            echo "no: " . $query->error;
        }
    }
}




?>