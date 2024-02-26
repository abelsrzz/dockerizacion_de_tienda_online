<?php
//Llamada de sesión para evitar errores
ob_start();
session_start();

//Cerrar sesión
$_SESSION = [];
session_unset();
session_destroy();
setcookie(session_name(), "", 0, "/");
header("Location: /");
ob_end_flush();
