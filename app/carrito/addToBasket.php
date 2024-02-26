<?php
//Iniciar sesión
ob_start();
session_start();
$idProducto = $_GET['id'];

//Se establecen variables para uso cómodo
$sessionUsername = $_SESSION['usuario'];
$sessionId = $_SESSION['id_usuario'];

//LLamada de sesión y conexión a la base de datos
require '../req/conection.php';
require '../req/session.php';

//Si no está establecido el id del producto o el usuario se redirige al índice
if (!isset($idProducto) || !isset($sessionUsername) || !$c) {
    header("Location: /");
    exit(1);
}

//Se añade a la base de datos
$sql = "INSERT INTO productos_en_cesta_usuario VALUES($sessionId, $idProducto);";
mysqli_query($c, $sql);

header("Location: /");
exit(0);

