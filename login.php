<?php
session_start();
require_once("conn.php");
include_once("error_handler.php");
if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST["pass"]) && !empty($_POST["numero"])){

    $numero = $_POST["numero"];
    $pass = $_POST["pass"];
 
    $querychecar = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ? and Contraseña_Empleado = ?");
    $querychecar->bind_param("ss", $numero, $pass);
    $querychecar->execute();
    $result = $querychecar->get_result();
    $row = $result->fetch_assoc();

    ////////////////////////////////////////////////////////////////
    if ($result->num_rows <= 0) {
        echo '<script type="text/javascript">
                alert("Numero o Contraseña Incorrectos.");
                history.back();
              </script>';
         
    } 
    else
    {
      $_SESSION['Area'] = $row['Area'];
      $_SESSION['Numero_Empleado'] = $row['Numero_Empleado'];
      $_SESSION['Nombre_Empleado'] = $row['Nombre_Empleado'];
        echo '<script type="text/javascript">
        alert("Login Correcto");
        
      </script>';
      header('Location: index.php');
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