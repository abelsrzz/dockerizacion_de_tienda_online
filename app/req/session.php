<?php
if (isset($_SESSION["usuario"]) && $_SESSION["id_usuario"]){
    $sessionUsername = $_SESSION['usuario'];
    $sessionId = $_SESSION['id_usuario'];
}else{
	ob_start();
	session_start();
};
