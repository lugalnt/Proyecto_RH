<?php
try {
    session_start();  // Asegurar que la sesión está activa
} catch (Exception $e) {
    error_log("Error al iniciar la sesión: " . $e->getMessage());
}

// Función para manejar errores
function manejarError($nivel, $mensaje, $archivo, $linea) {
    // Definir los niveles de error graves
    $errores_graves = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR];

    // Ignorar errores no graves
    if (!in_array($nivel, $errores_graves)) {
        return;
    }

    // Generar un código único para el error
    $codigoError = uniqid("ERR_");
    error_log("[$codigoError] Error grave: [$nivel] $mensaje en $archivo línea $linea");

    // Guardar información del error en la sesión
    $_SESSION['error'] = [
        'codigo' => $codigoError,
        'mensaje' => $mensaje,
        'archivo' => $archivo,
        'linea' => $linea
    ];
    
    // Redirigir a una página de error
    header("Location: oops.php");
    exit();
}

// Configurar el manejador de errores
set_error_handler("manejarError");

?>
