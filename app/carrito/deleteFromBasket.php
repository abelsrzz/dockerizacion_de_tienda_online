<?php
require '../req/session.php';
require '../req/conection.php';

$idProducto = $_GET['id'];

if (!isset($idProducto)) {
    header("Location: /error/index.php");
    exit(1);
}

$sql = "DELETE FROM productos_en_cesta_usuario WHERE id_producto LIKE $idProducto LIMIT 1;";
mysqli_query($c, $sql);

header("Location: /carrito/index.php");