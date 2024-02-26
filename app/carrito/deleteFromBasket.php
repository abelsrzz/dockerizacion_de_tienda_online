<?php
//llamada de sesión y base de datos
require '../req/session.php';
require '../req/conection.php';

$idProducto = $_GET['id'];

//Redirección si él código de producto no está establecido
if (!isset($idProducto)) {
    header("Location: /error/index.php");
    exit(1);
}

//Borrado de la base de datos
$sql = "DELETE FROM productos_en_cesta_usuario WHERE id_producto LIKE $idProducto LIMIT 1;";
mysqli_query($c, $sql);

header("Location: /carrito/index.php");