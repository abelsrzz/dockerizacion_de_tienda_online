<?php
//Llamada a las funciones esenciales
require '../req/session.php';
require '../req/conection.php';
include '../components/header.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/reset.css">
    <link rel="stylesheet" href="/style/general.css">
    <link rel="stylesheet" href="/style/index.css">
    <link rel="icon" type="image/svg+xml" href="../favicon.svg" />
    <title>Tienda Online</title>
</head>

<body>

    <main class="pagina-categoria dotted">
        <?php

        //Se comprueba que esté establecido el id del producto y si no lo está se redirige a la raíz
        if (isset($_GET["id"])) {
            $idProducto = $_GET["id"];
        } else {
            header("Location: /");
        }

        //Se seleccionan los datos del producto actual en la base de datos
        $sql = "SELECT * FROM producto WHERE id LIKE $idProducto";
        $producto = mysqli_query($c, $sql);

        //Se recorren los datos obtenidos
        while ($fila = mysqli_fetch_row($producto)) {
            list($idProducto, $nombreProducto, $imagenProducto, $precioProducto, $especificacionesProducto, $marcaProducto) = $fila;

            include_once '../functions/centimos.php';
            $precioProducto = quitar_centimos($precioProducto);

            //Se comprueba si el usuario ha iniciado sesión para poder añadir productos al carrito
            if (!isset($_SESSION['usuario'])) {
                //Si el usuario decide iniciar sesión se registra el producto en el que estaba para que no lo pierda cuando ya tenga la sesión iniciada
                $boton = "<a class='boton' href='/login?prodID=$idProducto'>Inicia sesión para añadir a la cesta.</a>";
            } else {
                $boton = "<a class='boton' href='/carrito/addToBasket.php?id=$idProducto'>Añadir a la cesta</a>";
            }

            //Se imprime el producto
            echo "
            <article class='producto-compra'>
                <h1 class='nombre'>$nombreProducto</h1>
                <h2 class='marca'>Marca: $marcaProducto</h2>
                <div class='img-container-compra'>
                    <img src='$imagenProducto'>

                    
                </div>
                <div class='actions-container'>
                    <p class='especificaciones'>$especificacionesProducto</p>
                    <h3 class='precio'>$precioProducto</h3>
                    $boton
                </div>
            </article>
            ";
        }
        ?>
    </main>

    <div class="blob-container">
        <span class="blob" />
    </div>
</body>

</html>