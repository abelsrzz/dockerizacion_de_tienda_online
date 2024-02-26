<?php
//Llamada de funciones esenciales
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
    <link rel="stylesheet" href="/style/carrito.css">
    <link rel="icon" type="image/svg+xml" href="../favicon.svg" />
    <title>Tienda Online</title>
</head>

<body>
    <main class='carrito pagina-categoria dotted'>
        <h1 class='titulo-seccion'>Carrito</h1>
        <section class='productos'>
            <?php
            //Si la sesión no está iniciada se pide al usuario que inicie sesión
            if (!isset($_SESSION['usuario'])) {
                echo "
                <article class='pago'>
                    <a class='boton' href='/login/index.php'>Inicia sesión para poder añadir productos ala cesta</a>
                </article>
                ";
            } else {
                //Si la sesión está iniciada se recopilan los datos del carrito
                $sql = "SELECT p.id, p.nombre, p.imagen, p.precio  FROM producto p JOIN productos_en_cesta_usuario c ON p.id=c.id_producto  WHERE c.id_usuario LIKE " . $_SESSION['id_usuario'] . ";";
                $productos = mysqli_query($c, $sql);

                //Se inicializa la variable de precio total de la cesta
                $total_cesta = 0;

                //Se recorren los datos obtenidos de la consulta
                while ($filaProducto = mysqli_fetch_row($productos)) {
                    list($idProducto, $nombreProducto, $imagenProducto, $precioProducto) = $filaProducto;

                    //Se añade el precio del producto actual al total de la cesta
                    $total_cesta += $precioProducto;

                    //Llamada a la función céntimos para mostrar los céntimos correctamente en el frontend
                    include_once '../functions/centimos.php';
                    $precioProducto = quitar_centimos($precioProducto);

                    //Se imprime el producto actual
                    echo "
                    <div class='producto-cesta'>
                    <a href='/producto/?id=$idProducto' class='card cardCesta'>
                    <div class='img-container'>
                        <img src='$imagenProducto'>
                    </div>
                    <h1 class='nombre-producto'>$nombreProducto <span class='precio'>" . $precioProducto . "</span></h1>
                    </a>
                    <a class='boton' href='./deleteFromBasket.php?id=$idProducto'>Eliminar</a>
                    </div>
                    ";
                }

                //Se llama a la función céntimos para mostrar correctamente los céntimos en el frontend
                include_once '../functions/centimos.php';
                $total_cesta = quitar_centimos($total_cesta);


                //Se imprime el precio total
                echo "
                </section>
                <article class='pago'>
                    <h1 class='total-productos'>Total: $total_cesta €</h1>
                    <a class='boton' href='/error/unavaliable/index.php'>Pagar</a>
                </article>
                ";
            }
            ?>
    </main>
</body>

</html>