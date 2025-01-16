<?php
require_once("conn.php");
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST"){

    $nombre = $_POST["nombre"];
    $pass = $_POST["pass"];
    $_SESSION['nombreUser'] = $nombre;
    $querychecar = $conn->prepare("SELECT * FROM usuarios WHERE Nombre_Empleado = ? and Contraseña_Empleado = ?");
    $querychecar->bind_param("ss", $nombre, $pass);
    $querychecar->execute();
    $result = $querychecar->get_result();
    ////////////////////////////////////////////////////////////////
    if ($result->num_rows <= 0) {
        echo '<script type="text/javascript">
                alert("Usuario o Contraseña Incorrectos.");
                history.back();
              </script>';
         
    } 
    else
    {
        echo '<script type="text/javascript">
        alert("Login Correcto");
        history.back();
      </script>';
 
    }
}

        ?>