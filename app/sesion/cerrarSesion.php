<?php
//Llamada de sesión por si esta no había sido inicializada antes
ob_start();
session_start();

//Vaciado de los datos de sesión
$_SESSION=[];
session_unset();
session_destroy();
setcookie(session_name(), "", 0, "/");
header("Location: /");
ob_end_flush();
