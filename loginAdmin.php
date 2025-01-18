<?php
require_once("conn.php");
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST["pass"]) && !empty($_POST["numero"])){

    $numero = $_POST["numero"];
    $pass = $_POST["pass"];
    $_SESSION['nombreUser'] = $nombre;
    $querychecar = $conn->prepare("SELECT * FROM usuarios WHERE Numero_Empleado = ? and Contraseña_Empleado = ? and Area = 'Administrador de sistema' ");
    $querychecar->bind_param("ss", $numero, $pass);
    $querychecar->execute();
    $result = $querychecar->get_result();
    ////////////////////////////////////////////////////////////////
    if ($result->num_rows <= 0) {
        echo '<script type="text/javascript">
                alert("Numero o Contraseña Incorrectos.");
                history.back();
              </script>';
        header('Location: adminPage.php');
         
    } 
    else
    {
        echo '<script type="text/javascript">
        alert("Login Correcto");
        history.back();
      </script>';
      header('Location: adminPage.php');
 
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