<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
require_once("conn.php");
if ($_SERVER["REQUEST_METHOD"]=="POST"){

    $numero = $_POST["Numero_Empleado"] ?? '';
    $pass = $_POST["Contraseña"] ?? '';
 
    $querychecar = $conn->prepare("SELECT * FROM empleado WHERE Numero_Empleado = ? and Contraseña_Empleado = ?");
    $querychecar->bind_param("ss", $numero, $pass);
    $querychecar->execute();
    $result = $querychecar->get_result();
    $row = $result->fetch_assoc();

    ////////////////////////////////////////////////////////////////
    if ($result->num_rows > 0) {
      echo json_encode(["success" => true, "message" => "Login exitoso"]);
      $_SESSION['Area'] = $row['Area'];
      $_SESSION['Numero_Empleado'] = $row['Numero_Empleado'];
      $_SESSION['Nombre_Empleado'] = $row['Nombre_Empleado'];
    } 
    else
    {
      echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
    }
}
?>