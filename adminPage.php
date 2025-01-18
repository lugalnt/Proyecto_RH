<html>
<head>
    <link rel="stylesheet" href="styleLogin.css">
</head>

<body>

    <div role="region" tabindex="0">
        <center>
        <table>
            <caption><br></caption>
            <thead>
                <tr>
                    <th>Numero_Empleado<br></th>
                    <th>Nombre_Empleado</th>
                    <th>Area<br></th>
                </tr>
            </thead>

            <tbody>
                <tr>

                    <form method="post">
                        <td><input type="text" name="numero" placeholder="Numero de empleado">  </td>
                        <td><input type="text" name="nombre" placeholder="Nombre de empleado"></td>
                        <td>
                            <select name="area">
                                <option value="Administrador">Administrador de sistema</option>
                                <option value="RH">Recursos Humanos</option>
                                <option value="Profesor" selected>Profesor</option>
                                <option value="Area de administracion">Area de administracion</option>
                              </select>
                        </td>
                        <td><button>Pre-registrar usuario</button></td>

                </tr> 
                    </form>

<?php

require_once("conn.php");

$queryGAU = $conn->prepare("SELECT * from usuarios Order by Numero_Empleado Desc");
$queryGAU->execute();
$resultGAU = $queryGAU->get_result();

while ($rowGAU = $resultGAU->fetch_assoc())
{

    $Numero_Empleado = $rowGAU['Numero_Empleado'];
    $Nombre_Empleado = $rowGAU['Nombre_Empleado'];
    $Area = $rowGAU['Area'];


    echo' 
    <tr>

    <td> '.htmlspecialchars($Numero_Empleado).' </td>
    <td> '.htmlspecialchars($Nombre_Empleado).' </td>
    <td> '.htmlspecialchars($Area).' </td>

    </tr>
    ';

}

if ($_SERVER["REQUEST_METHOD"]=="POST") 

{

    $numero = $_POST["numero"];
    $nombre = $_POST["nombre"];
    $area = $_POST["area"];

    $queryPR = $conn->prepare("INSERT INTO usuarios (Numero_Empleado,Nombre_Empleado,Area) Values (?,?,?)");
    $queryPR->bind_param("sss",$numero,$nombre,$area);

    if($queryPR->execute())
    {
        echo '<script type="text/javascript">
                alert("Preregistro exitoso");
              </script>';
              header('Location: adminPage.php');
    }
    else
    {
        echo '<script type="text/javascript">
        alert("Problema");
      </script>';
    }
  


}



?>

</tbody>
        </table>
    </center>
        </div>

</body>



</html>