<?php
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
            if (!isset($_SESSION['usuario'])) {
                echo "
                <article class='pago'>
                    <a class='boton' href='/login/index.php'>Inicia sesión para poder añadir productos ala cesta</a>
                </article>
                ";
            } else {
                $sql = "SELECT p.id, p.nombre, p.imagen, p.precio  FROM producto p JOIN productos_en_cesta_usuario c ON p.id=c.id_producto  WHERE c.id_usuario LIKE ".$_SESSION['id_usuario'].";";
                $productos = mysqli_query($c, $sql);

                $total_cesta = 0;
                while ($fila2 = mysqli_fetch_row($productos)) {
                    list($idProducto, $nombreProducto, $imagenProducto, $precioProducto) = $fila2;

                    $total_cesta += $precioProducto;

                    include_once '../functions/centimos.php';
                    $precioProducto = quitar_centimos($precioProducto);

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

                include_once '../functions/centimos.php';
                $total_cesta = quitar_centimos($total_cesta);


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