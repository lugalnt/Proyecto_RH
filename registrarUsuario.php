<?php

require_once("conn.php");
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST["nombre"]) && !empty($_POST["pass"]) && !empty($_POST["numero"]))
{

    $numero = $_POST["numero"];
    $nombre = $_POST["nombre"];
    $pass = $_POST["pass"];

    $querychecar = $conn->prepare("SELECT * FROM usuarios WHERE Numero_Empleado = ? AND Nombre_Empleado = ?");
    $querychecar->bind_param("ss", $numero, $nombre);
    $querychecar->execute();
    $result = $querychecar->get_result();
    ////////////////////////////////////////////////////////////////
    if ($result->num_rows == 0) {
        echo '<script type="text/javascript">
                alert("No existe dentro del sistema, contacte a la institucion para aclaraciones.");
                history.back();
              </script>';
         
    } 
    else
    {
      
 
    

    $querychecar = $conn->prepare("SELECT * FROM usuarios WHERE Numero_Empleado = ? and Contraseña_Empleado = ?");
    $querychecar->bind_param("ss", $numero, $pass);
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

        $query = $conn->prepare("UPDATE usuarios SET Nombre_Empleado = ?, Contraseña_Empleado = ? WHERE Numero_Empleado = ? ");
        $query->bind_param("sss", $nombre, $pass, $numero);
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
}
else
{
    echo '<script type="text/javascript">
    alert("Favor de completar los campos");
    history.back();
  </script>';
}




?>