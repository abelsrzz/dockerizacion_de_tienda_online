<?php
ob_start();
session_start();
$idProducto = $_GET['id'];

$sessionUsername = $_SESSION['usuario'];
$sessionId = $_SESSION['id_usuario'];


require '../req/conection.php';
require '../req/session.php';



if(!isset($idProducto) || !isset($sessionUsername) || !$c){
    header("Location: /error/index.php");
    exit(1);
}
    
    $sql = "INSERT INTO productos_en_cesta_usuario VALUES($sessionId, $idProducto);";


    mysqli_query($c, $sql);


    header("Location: /");
    exit(0);

