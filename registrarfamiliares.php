<?php
require_once("conn.php");
session_start();
?>

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
                    <th>Nombre de tu familiar<br></th>
                    <th>Relacion</th>
                    <th>Nivel academico<br></th>
                </tr>
            </thead>

            <tbody>
                <tr>

                <main>
        <header>
            <h1 id="title">Registro de tus familiares</h1>
            <p id="description">Registra a un familiar para procesamiento en prestaciones necesarias.</p>
        </header>
        <form id="survey-form" method="post" action="">




            <div class="form-group">
                <label for="name" id="email-label">Nombre de tu familiar</label>
                <input type="text" name="nombre" id="Direccion" placeholder="Nombre de empleado" required>
            </div>
            <div class="form-group">
                <label for="occupation">Nivel academico</label>
                <select name="nivelacademico" id="dropdown">
                    <option value="Primaria">Primaria</option>
                    <option value="Secundaria">Secundaria</option>
                    <option value="Preparatoria">Preparatoria</option>
                    <option value="Universidad">Universidad</option>
                    <option value="No-estudiante">No-estudiante</option>
                  </select>
            </div>
            
            <div class="form-group">
                <label for="edad" id="number-label">Edad</label>
                <input type="number" name="edad" id="edad" min="1" max="99" placeholder="Introduce tu edad">
            </div>
            
            <div class="form-group">
                <label for="occupation">Relacion con tu familiar</label>
                <select name="relacion" id="dropdown">
                    <option value="Esposo">Esposo/a</option>
                    <option value="Pareja">Pareja/a</option>
                    <option value="Hijo">Hijo/a</option>
                    <option value="Padre">Madre/Padre</option>
                </select>
            </div>

            <div class="form-group">
                <button id="submit" type="post">Terminar registro</button>
            </div>
        </form>

        <?php

        if ($_SERVER["REQUEST_METHOD"]=="POST")
        {

           $nombreFam = $_POST['nombre'];
           $nivelAcademicoFam = $_POST['nivelacademico'];
           $edadFam = $_POST['edad'];
           $relacionFam = $_POST['relacion'];
            
            
            $queryFamilia = $conn->prepare("INSERT into familiar_empleado (Nombre_Familiar,Nivel_academico, Edad, Relacion) values(?,?,?,?)");
            $queryFamilia->bind_param("ssis", $nombreFam,$nivelAcademicoFam,$edadFam,$relacionFam);

            if($queryFamilia->execute())
            {
                $numero_empleado = $_SESSION['Numero_Empleado'];

                $id_familiar = $queryFamilia->insert_id;

                $queryFamiliaEmpleado = $conn->prepare("INSERT INTO empleado_familiar (Numero_Empleado,Id_Familiar,Relacion) VALUES (?,?,?)");
                $queryFamiliaEmpleado->bind_param("iis", $numero_empleado,$id_familiar,$relacionFam);

                if($queryFamiliaEmpleado->execute())
                {
                    echo '<script type="text/javascript">
                    alert("Familiar registrado");
                  </script>'; 
                  echo("<meta http-equiv='refresh' content='1'>");
                }
                else
                {
                    echo '<script type="text/javascript">
                    alert("Problema");
                  </script>'; 
                }

            }
            else
            {
                echo '<script type="text/javascript">
                alert("Problema");
              </script>';
            }


        } 


        ?>




    </main>

                </tr> 
                    </form>
</body>
</html>