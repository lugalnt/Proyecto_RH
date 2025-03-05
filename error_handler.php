<?php
try {
    session_start();  // Asegurar que la sesión está activa
} catch (Exception $e) {
    error_log("Error al iniciar la sesión: " . $e->getMessage());
}

// Función para manejar errores no fatales
function manejarError($nivel, $mensaje, $archivo, $linea) {
    // Solo capturar errores graves, ignorar warnings y notices
    $errores_graves = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR];

    if (!in_array($nivel, $errores_graves)) {
        return;
    }

    // Generar código de error único
    $codigoError = uniqid("ERR_");
    error_log("[$codigoError] Error grave: [$nivel] $mensaje en $archivo línea $linea");

    $_SESSION['error'] = [
        'codigo' => $codigoError,
        'mensaje' => "Ha ocurrido un error inesperado. Por favor, intenta nuevamente.",
        'archivo' => $archivo,
        'linea' => $linea
    ];

    header("Location: oops.php");
    exit();
}

// Registrar el manejador de errores
set_error_handler("manejarError");

// Capturar errores fatales al final de la ejecución
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        manejarError($error['type'], $error['message'], $error['file'], $error['line']);
    }
});
?>
