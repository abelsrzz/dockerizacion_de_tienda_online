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
            $idCategoria = $_GET["id"];
        } else {
            header("Location: /error/index.php");
        }
        $sql = "SELECT * FROM categoria WHERE id LIKE $idCategoria";
        $categorias = mysqli_query($c, $sql);

        while ($fila = mysqli_fetch_row($categorias)) {
            list($idCategoria, $nombreCategoria) = $fila;
            $nombreCategoria = strtoupper($nombreCategoria);
            echo "
                <h1 class='titulo-seccion'>$nombreCategoria</h1>
                <section class='productos'>
                ";
            $sql = "SELECT p.id, p.nombre, p.imagen, p.precio  FROM producto p JOIN categoria_producto c ON p.id=c.id_producto  WHERE c.id_categoria LIKE $idCategoria;";
            $productos = mysqli_query($c, $sql);

            while ($filaProductos = mysqli_fetch_row($productos)) {
                list($idProducto, $nombreProducto, $imagenProducto, $precioProducto) = $filaProductos;

                include_once '../functions/centimos.php';
                $precioProducto = quitar_centimos($precioProducto);

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
            ";
        ?>
    </main>

    <div class="blob-container">
        <span class="blob" />
        <span class="blob" />
        <span class="blob" />
        <span class="blob" />
        <span class="blob" />
    </div>
</body>

</html>