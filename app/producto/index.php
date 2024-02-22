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
    <link rel="icon" type="image/svg+xml" href="../favicon.svg" />
    <title>Tienda Online</title>
</head>

<body>
    <main class="pagina-categoria dotted">
        <?php
        if (isset($_GET["id"])) {
            $idProducto = $_GET["id"];
        } else {
            header("Location: /error/index.php");
        }
        $sql = "SELECT * FROM producto WHERE id LIKE $idProducto";
        $producto = mysqli_query($c, $sql);

        while ($fila = mysqli_fetch_row($producto)) {
            list($idProducto, $nombreProducto, $imagenProducto, $precioProducto, $especificacionesProducto, $marcaProducto) = $fila;
            
            include_once '../functions/centimos.php';
            $precioProducto = quitar_centimos($precioProducto);

            if(!isset($_SESSION['usuario'])){
                $boton = "<a class='boton' href='/login/?prodID=$idProducto'>Inicia sesión para añadir a la cesta.</a>";
            }
            else{
                $boton= "<a class='boton' href='/carrito/addToBasket.php?id=$idProducto'>Añadir a la cesta</a>";
            }

            echo "
            <article class='producto-compra'>
                <h1 class='nombre'>$nombreProducto</h1>
                <h2 class='marca'>Marca: $marcaProducto</h2>
                <div class='img-container-compra'>
                    <img src='$imagenProducto' alt='Foto del producto $nombreProducto'>
                </div>
                <div class='actions-container'>";
                if($especificacionesProducto == ""){
                    echo "<p class='especificaciones'>Actualmente no hay una descripción disponible sobre el producto $nombreProducto</p>";
                }else{
                    echo "
                        <h2 class='descripcion-producto'>Descripción del producto: </h2>
                        <p class='especificaciones'>$especificacionesProducto</p>
                    ";
                }
                    echo "<h3 class='precio'>$precioProducto</h3>
                    $boton
                </div>
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