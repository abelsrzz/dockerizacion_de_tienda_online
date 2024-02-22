<?php
//Se llama a la sesión y a la base de datos
require '../req/session.php';
require '../req/conection.php';

//Se comprueba el id el producto a elminiar
$idProducto = $_GET['id'];

//Si no hay producto se envía a error para evitar problemas de seguridad
if (!isset($idProducto)) {
    header("Location: /error/index.php");
    exit(1);
}

//Se elimina de la base de datos
$sql = "DELETE FROM productos_en_cesta_usuario WHERE id_producto LIKE $idProducto LIMIT 1;";
mysqli_query($c, $sql);

//Se redirige al carrito
header("Location: /carrito/index.php");
