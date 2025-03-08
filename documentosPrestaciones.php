<?php
// preciosPrestaciones.php

$documentosPrestaciones = array(
    "Utiles" => "Carta de solicitud FIRMADA, Constancia de estudio de el beneficiado",
    "Exencion de inscripc" => "Carta de solicitud FIRMADA, Constancia de estudio de el beneficiado",
    "Lentes" => "Carta de solicitud FIRMADA",
    "Guarderia" => "Carta de solicitud FIRMADA",
    "Aparato Ortopedico" => "Carta de solicitud FIRMADA",
    "Canastilla mama" => "Carta de solicitud FIRMADA",
    "Tesis" => "Carta de solicitud FIRMADA",
    "Gastos funerarios" => "Carta de solicitud FIRMADA",
    "Aparato ortopedico" => "Carta de solicitud FIRMADA",
    "Permiso sindical" => "Carta de solicitud FIRMADA",
    "Nacimiento hijo" => "Carta de solicitud FIRMADA",
    "Otro" => "Carta de solicitud FIRMADA",
    "Embarazo" => "Carta de solicitud FIRMADA",
    "Permiso por Duelo" => "Carta de solicitud FIRMADA",
    "Incapacidad" => "Carta de solicitud FIRMADA"
);

function queDocumentos($prestacion) {
    global $documentosPrestaciones;
    if (array_key_exists($prestacion, $documentosPrestaciones)) {
        return $documentosPrestaciones[$prestacion];
    } else {
        return "Prestación no encontrada.";
    }
}
?>