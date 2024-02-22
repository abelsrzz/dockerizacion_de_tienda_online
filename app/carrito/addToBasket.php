<?php
//Se inicia la sesión
ob_start();
session_start();
require '../req/conection.php';
require '../req/session.php';

//Se establece la variable de id del producto a añadir
$idProducto = $_GET['id'];

//Se establecen variables de sesión para más comodo trabajo
$sessionUsername = $_SESSION['usuario'];
$sessionId = $_SESSION['id_usuario'];

//Se comprueba que el producto, el usuario y la conexión a la base de datos están establecidos
if(!isset($idProducto) || !isset($sessionUsername) || !$c){
    header("Location: /error/index.php");
    exit(1);
}
    //Se inserta en la base de datos
    $sql = "INSERT INTO productos_en_cesta_usuario VALUES($sessionId, $idProducto);";
    mysqli_query($c, $sql);

    //Se redirige al índice
    header("Location: /");
    exit(0);
