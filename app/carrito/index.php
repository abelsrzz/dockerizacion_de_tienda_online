<?php
//Llamada a la sesión, base de datos y cabecera
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
            //Se comprueba si la sesión está inciiada
            if (!isset($_SESSION['usuario'])) {
                //Si la sesión no está iniciada se muestra un botón para que el usuario pueda iniciar sesión
                echo "
                    <article class='pago'>
                        <a class='boton' href='/login/index.php'>Inicia sesión para poder añadir productos ala cesta</a>
                    </article>
                ";
            } else {
                //Se buscan los productos dentro de la cesta del usuario
                $sql = "SELECT p.id, p.nombre, p.imagen, p.precio  FROM producto p JOIN productos_en_cesta_usuario c ON p.id=c.id_producto  WHERE c.id_usuario LIKE ".$_SESSION['id_usuario'].";";
                $productos = mysqli_query($c, $sql);

                //Se inicializa la variable del todal de precio de la cesta
                $total_cesta = 0;

                //Se recorren los datos obtenidos
                while ($fila_productos = mysqli_fetch_row($productos)) {
                    list($idProducto, $nombreProducto, $imagenProducto, $precioProducto) = $fila_productos;

                    //Se añade el precio al total de la cesta
                    $total_cesta += $precioProducto;

                    //Se llama a la función céntimos
                    include_once '../functions/centimos.php';
                    $precioProducto = quitar_centimos($precioProducto);

                    //Se muestran los productos que están en la cesta del usuario
                    echo "
                        <div class='producto-cesta'>
                        <a href='/producto/?id=$idProducto' class='card cardCesta'>
                        <div class='img-container'>
                            <img src='$imagenProducto'>
                        </div>
                        <h1 class='nombre-producto'>$nombreProducto " . $precioProducto . "€</h1>
                        </a>
                        <a class='boton' href='./deleteFromBasket.php?id=$idProducto'>Eliminar</a>
                        </div>
                    ";
                }

                //Se llama a la función céntimos
                include_once '../functions/centimos.php';
                $total_cesta = quitar_centimos($total_cesta);


                //Se muestra el precio total de la cesta
                echo "
                    </section>
                    <article class='pago'>
                        <h1 class='total-productos'>Total: $total_cesta €</h1>
                        <a class='boton' href='/error/unavaliable/index.php'>Pagar</a>
                    </article>  
                ";

            }
        ?>
    <div class="blob-container">
        <span class="blob" />
    </div>
    </main>
    <?php
        include '../components/footer.php';
    ?>
</body>

</html>