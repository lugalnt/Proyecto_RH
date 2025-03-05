<?php
// preciosPrestaciones.php

$preciosPrestaciones = array(
    "Guarderia" => 1500.00,
    "Lentes" => 2000.00,
    "Apoyo Funerario" => 5000.00,
    "Utiles" => 1200.00,
    "Exencion de inscripc" => 2000.00, //Esto es asi porque en la base de datos se queda sin espacio para nombre completo lol
    "Canastilla mama" => 1000.00,
    "Aparato Ortopedico" => 3000.00,
    "Tesis" => 2000.00,
    "Gastos funerarios" => 5000.00
);

function obtenerPrecioPrestacion($prestacion) {
    global $preciosPrestaciones;
    if (array_key_exists($prestacion, $preciosPrestaciones)) {
        return $preciosPrestaciones[$prestacion];
    } else {
        return "Prestación no encontrada.";
    }
}
?>