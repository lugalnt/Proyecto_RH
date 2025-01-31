<html>
<head>
    <link rel="stylesheet" href="styleLogin.css">
    <link rel="stylesheet" href="styleencuesta.css">
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

                <main>
        <header>
            <h1 id="title">Registro</h1>
            <p id="description">Registra a un empleado al sistema</p>
        </header>
        <form id="survey-form" method="post" action="adminPage.php">



            <div class="form-group">
                <label for="numero" id="email-label">Numero Empleado</label>
                <input type="text" name="numeroem" id="Direccion" placeholder="Numero de empleado" required>
            </div>
            <div class="form-group">
                <label for="name" id="email-label">Nombre del empleado</label>
                <input type="text" name="nombre" id="Direccion" placeholder="Nombre de empleado" required>
            </div>
            <div class="form-group">
                <label for="name" id="email-label">Fecha de ingreso</label>
                <input type="date" id="start" name="fecha" min="1950-12-31" max="2999-12-31" required />
            </div>
            <div class="form-group">
                <label for="occupation">Area laboral</label>
                <select name="area" id="dropdown">
                    <option value="Administrador">Administrador de sistema</option>
                    <option value="RH">Recursos Humanos</option>
                    <option value="Profesor" selected>Profesor</option>
                    <option value="Area de administracion">Area de administracion</option>
                  </select>
            </div>
            


            <div class="form-group">
                <label for="name" id="email-label">Direccion</label>
                <input type="text" name="direccion" id="Direccion" placeholder="Introduce tu Direccion" required>
            </div>
            <div class="form-group">
                <label for="numero" id="email-label">Numero</label>
                <input type="text" name="numero" id="numero" placeholder="Introduce Un numero de telefono" required>
            </div>
            <div class="form-group">
                <label for="numero" id="email-label">Titulo</label>
                <input type="text" name="titulo" id="titulo" placeholder="Introduce tu titulo" required>
            </div>
            <div class="form-group">
                <label for="edad" id="number-label">Edad</label>
                <input type="number" name="edad" id="edad" min="1" max="99" placeholder="Introduce tu edad">
            </div>
            


            <div class="form-group">
                <label for="occupation">Selecciona tu genero</label>
                <select name="genero" id="dropdown">
                    <option value="masc">Maculino</option>
                    <option value="fem">Femenino</option>
                </select>
            </div>

            <div class="form-group">
                <button id="submit" type="post">Terminar registro</button>
            </div>
        </form>
    </main>

                </tr> 
                    </form>

<?php

require_once("conn.php");

$queryGAU = $conn->prepare("SELECT * from empleado Order by Numero_Empleado Desc");
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

    $numeroem = $_POST["numeroem"];
    $nombre = $_POST["nombre"];
    $fecha_ing = $_POST["fecha"];
    $direccion = $_POST["direccion"];
    $numero = $_POST["numero"];
    $titulo = $_POST["titulo"];
    $edad = $_POST["edad"];
    $genero = $_POST["genero"];
    $area = $_POST["area"];
    

    $queryPR = $conn->prepare("INSERT INTO empleado (Numero_Empleado,Nombre_Empleado,Area,Fecha_Ingreso,Direccion,Telefono,Edad,Genero,Titulo) Values (?,?,?,?,?,?,?,?,?)");
    $queryPR->bind_param("ssssssiss",$numeroem,$nombre,$area,$fecha_ing,$direccion,$numero,$edad,$genero,$titulo);

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