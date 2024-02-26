<?php
//Se llama a la sesión si no está llamada y si lo está se establecen variables para sencillo uso de utilidades de sesión
if (isset($_SESSION["usuario"]) && $_SESSION["id_usuario"]) {
    $sessionUsername = $_SESSION['usuario'];
    $sessionId = $_SESSION['id_usuario'];
} else {
    ob_start();
    session_start();
}
;
