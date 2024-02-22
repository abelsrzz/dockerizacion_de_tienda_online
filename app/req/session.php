<?php
//Función que establece los datos de sesión necesarios para utilizar todas las funciones de la aplicación
if (isset($_SESSION["usuario"]) && $_SESSION["id_usuario"]){
    $sessionUsername = $_SESSION['usuario'];
    $sessionId = $_SESSION['id_usuario'];
}else{
    //Si la sesión no estaba iniciada la inicializa para evitar problemas con los datos de sesión
	ob_start();
	session_start();
};
