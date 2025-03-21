<?php
require_once("conn.php");
session_start();

if(!isset($_SESSION['Numero_Empleado']))
{
  header('Location: login.html');
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
            <h2>Empleado</h2>
        </div>
        <h2>Prestaciones Académicas</h2>
    <ul>
        <li>Exención de inscripción</li>
        <li>Ayuda útiles</li>
    </ul>
    <br><br>
    <a class="ccbtn btn-blue btn-rounded" href="SOLICITUDprestacionapoyoacademico.php">Solicitar prestación académica</a>
    <br><br>
    <h2>Prestaciones Financieras</h2>
    <ul>
        <li>Guardería</li>
        <li>Gastos funerarios</li>
        <li>Útiles</li>
        <li>Lentes</li>
    </ul>
    <br><br>
    <a class="ccbtn btn-blue btn-rounded" href="SOLICITUDprestacionesfinancieras.php">Solicitar prestación financiera</a>
    <br><br>
    <h2>Prestaciones de Día</h2>
    <ul>
        <li>Permiso sindical</li>
        <li>Nacimiento de hijo</li>
        <li>Matrimonio</li>
    </ul>
    <br><br>
    <a class="ccbtn btn-blue btn-rounded" href="SOLICITUDprestaciondia.php">Solicitar prestación de día</a>
    </div>
    <div class="main">
        <div class="header">
            
       
          <div class="right">
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

      
            <div class="events">
                <h3>Eventos</h3>
                <p>Lunes, 3 de Febrero: Día de la Constitución</p>
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