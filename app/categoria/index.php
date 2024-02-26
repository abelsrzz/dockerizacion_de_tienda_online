<?php
//Llamada a las funciones de sesión y base de datos
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
        //Se recopila el id de la categoría a imprimir
        if (isset($_GET["id"])) {
            $idCategoria = $_GET["id"];
        } else {
            header("Location: /error/index.php");
        }

        //Se seleccionan los datos de la categoría
        $sql = "SELECT * FROM categoria WHERE id LIKE $idCategoria";
        $categorias = mysqli_query($c, $sql);

        //Se recorren los datos obtenidos
        while ($fila = mysqli_fetch_row($categorias)) {
            list($idCategoria, $nombreCategoria) = $fila;
            $nombreCategoria = strtoupper($nombreCategoria);

            //Se imprime el nombre de la categoría
            echo "
                <h1 class='titulo-seccion'>$nombreCategoria</h1>
                <section class='productos-wrapper'>
                <section class='productos'>
                ";

            //Se seleccionan los productos de la categoría actual
            $sql = "SELECT p.id, p.nombre, p.imagen, p.precio  FROM producto p JOIN categoria_producto c ON p.id=c.id_producto  WHERE c.id_categoria LIKE $idCategoria;";
            $productos = mysqli_query($c, $sql);

            //Se recorren los datos obtenidso
            while ($filaProducto = mysqli_fetch_row($productos)) {
                list($idProducto, $nombreProducto, $imagenProducto, $precioProducto) = $filaProducto;

                //Llamada de la funcion centimos para que se muestren de forma correcta en el frontend
                include_once '../functions/centimos.php';
                $precioProducto = quitar_centimos($precioProducto);

                //Se imprime el producto actual
                echo "
                    <a href='/producto/?id=$idProducto' class='card'>
                    <div class='img-container'>
                        <img src='$imagenProducto'>
                    </div>
                    <h1 class='nombre-producto'>$nombreProducto <span class='precio'>" . $precioProducto . "</span></h1>
                    </a>
                ";
            }
        }
        echo "
            </section>
            </section>
            ";
        ?>
    </main>

    <div class="blob-container">
        <span class="blob" />
    </div>
</body>

</html>