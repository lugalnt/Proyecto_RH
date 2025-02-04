<?php
require_once("conn.php");
session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
}

if($_SESSION['Area'] != "RH")
{
  header('Location: indexEMP.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Recursos Humanos</h2>
        </div>
        <h2>Revisar solicitudes de prestaciones</h2>
        <br><br>
        <a class="ccbtn btn-blue btn-rounded" href="solicitudesprestacion.php"> Revisar solicitudes</a>
    </div>
    <div class="main">
        <div class="header">



        <div class="search-box">
            <form method="post">
            <input type="text" name="Empleado" placeholder="Escriba para buscar...">
            <button type="submit" name="buscarEmpleado"></button>
            </form>
   
                <div class="search-icon">
                <i class="fas fa-search"></i>
                </div>
                <div class="cancel-icon">
                <i class="fas fa-times"></i>
                </div>
                <div class="search-data">';
                </div></div>
             <?php



                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["buscarEmpleado"])) 
                {
                
                
                $nombre = "%".$_POST["Empleado"]."%";
                

                $stmt = $conn->prepare("SELECT * FROM empleado WHERE Nombre_Empleado like ?");
                $stmt->bind_param("s", $nombre);
                $stmt->execute();
                $resultado = $stmt->get_result();
            
                if ($resultado->num_rows > 0) {
                    echo "<table border='1'>";
                    echo "<tr>
                            <th>Número Empleado</th>
                            <th>Nombre</th>
                            <th>Contraseña</th>
                            <th>Área</th>
                            <th>Edad</th>
                            <th>Género</th>
                            <th>Título</th>
                            <th>Fecha Ingreso</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Discapacidad</th>
                            <th>Estado</th>
                            <th>Días</th>
                            <th>Días Extras</th>
                          </tr>";
            
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>
                                <td>{$fila['Numero_Empleado']}</td>
                                <td>{$fila['Nombre_Empleado']}</td>
                                <td>{$fila['Contraseña_Empleado']}</td>
                                <td>{$fila['Area']}</td>
                                <td>{$fila['Edad']}</td>
                                <td>{$fila['Genero']}</td>
                                <td>{$fila['Titulo']}</td>
                                <td>{$fila['Fecha_Ingreso']}</td>
                                <td>{$fila['Direccion']}</td>
                                <td>{$fila['Telefono']}</td>
                                <td>{$fila['Discapacidad']}</td>
                                <td>{$fila['Estado']}</td>
                                <td>{$fila['Dias']}</td>
                                <td>{$fila['Dias_Extras']}</td>
                              </tr>";
                    }
                    echo "</table>";
                } 
                else {
                    echo "No se encontró el empleado.";
                }
                $stmt->close();
                }
          
               
            ?>
           
            <div class="right">
                <div class="bell">
                    <div class="bell-top"></div>
                    <div class="bell-bot"></div>
                    <div class="bell-notification">0</div>
                  </div>
                  <div class="add-notification">
                    <button onclick="addNotification()">Add Notification</button>
                  </div>
                <?php
                echo'<span class="profile">'.htmlspecialchars($_SESSION['Nombre_Empleado']).'</span>';
                
                echo'<form method="post"> <button type="submit" name="logout">Log out</button> </form>';
                ?>
            </div>
        </div> 
        <div class="content">
            <div class="calendar-section">
                <h3>Mi Calendario</h3>
                <iframe src="https://calendar.google.com/calendar/embed?src=your_calendar_id%40group.calendar.google.com&ctz=America%2FMexico_City"></iframe>
                <a class="ccbtn btn-blue btn-rounded" href="#1">Solicitar Ausencia</a>
            </div>

            <div class="absent-employees">
                <h3>Empleados Ausentes</h3>
                <ul>
                    <li>Buscar en tabla empleado los que esten con estado ausente, relacionar a buscar los dias para sacar rango y mostrar aqui</li>
                    <li>Nombre Empleado: Vacaciones - Válido hasta 1 Julio</li>
                    <li>Nombre Empleado: Vacaciones - Válido hasta 1 Julio</li>
                </ul>
            </div>

            <div class="events">
                <h3>Eventos</h3>
                <p>Lunes, 3 de Febrero: Día de la Constitución</p>
            </div>

            <div class="latest-benefits">
                <h3>Últimas Solicitud de prestaciones</h3>

                  <?php


                  $querySPR = $conn->prepare("SELECT * FROM prestacion ORDER BY Fecha_Solicitada DESC LIMIT 3");
                  $querySPR->execute();
                  $resultSPR = $querySPR->get_result();

                  while($rowSPR = $resultSPR->fetch_assoc())
                  {

                    $queryCNE = $conn->prepare("SELECT Numero_Empleado FROM empleado_prestacion WHERE Id_Prestacion = ?");
                    $queryCNE->bind_param("i", $rowSPR['Id_Prestacion']);
                    $queryCNE->execute();
                    $resultCNE = $queryCNE->get_result();
                    $rowCNE = $resultCNE->fetch_assoc();

                    $queryCNME = $conn->prepare("SELECT Nombre_Empleado FROM empleado WHERE Numero_Empleado = ?");
                    $queryCNME->bind_param("i", $rowCNE['Numero_Empleado']);
                    $queryCNME->execute();
                    $resultCNME = $queryCNME->get_result();
                    $rowCNME = $resultCNME->fetch_assoc();
                    $NombreEmpleado = $rowCNME['Nombre_Empleado'];



                    echo "<div class='benefits-container'>";
                    echo "<p>".$rowSPR['Tipo']."</p>";
                    echo "<p>".$rowCNE['Numero_Empleado'].", ".htmlspecialchars($NombreEmpleado)."</p>";
                    echo "<p>FECHA: ".$rowSPR['Fecha_Solicitada']."</p>";
                    echo "</div>";
                  }

                  ?>
            </div>
        </div>
    </div>


    <script>
        const searchBox = document.querySelector(".search-box");
        const searchBtn = document.querySelector(".search-icon");
        const cancelBtn = document.querySelector(".cancel-icon");
        const searchInput = document.querySelector("input");
        const searchData = document.querySelector(".search-data");
        searchBtn.onclick =()=>{
          searchBox.classList.add("active");
          searchBtn.classList.add("active");
          searchInput.classList.add("active");
          cancelBtn.classList.add("active");
          searchInput.focus();
          if(searchInput.value != ""){
            var values = searchInput.value;
            searchData.classList.remove("active");
            searchData.innerHTML = "You just typed " + "<span style='font-weight: 500;'>" + values + "</span>";
          }else{
            searchData.textContent = "";
          }
        }
        cancelBtn.onclick =()=>{
          searchBox.classList.remove("active");
          searchBtn.classList.remove("active");
          searchInput.classList.remove("active");
          cancelBtn.classList.remove("active");
          searchData.classList.toggle("active");
          searchInput.value = "";
        }
      </script>
      <script src="addNotification.js" defer></script>
</body>
</html>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  if(isset($_POST["logout"]))
  {
  session_destroy();
  echo("<meta http-equiv='refresh' content='1'>");
  }


}


?>