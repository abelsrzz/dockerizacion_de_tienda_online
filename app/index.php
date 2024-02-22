<?php
    require './req/session.php';
    require './req/conection.php';
    include './components/header.php' ;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/general.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="icon" type="image/svg+xml" href="./favicon.svg" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <title>Tienda Online</title>
</head>
<body>


    <main class="principal dotted">
        <div class="bienvenida">
            <?php
            if (!isset($_SESSION["usuario"])) {
                echo "<h1>¡Bienvenid@ a la mejor tienda!</h1>";
            } else {
                echo "<h1>¡Bienvenid@ a la mejor tienda <span class='azul'>".$_SESSION["usuario"]."</span>!</h1>";
            }
            ?>
            <h1>¡CON LAS MEJORES <a href="/categoria/?id=1">Ofertas</a>!</h1>

            <input type="text" name="buscar" id="buscar" value="" placeholder="Buscar..." maxlength="30"
                autocomplete="off" onkeyup="buscar_ahora();" />
            <div id="resultados">
            </div>
        </div>
        <?php
        $sql = "SELECT * FROM categoria";
        $categorias = mysqli_query($c, $sql);

        while ($fila = mysqli_fetch_row($categorias)) {
            list($idCategoria, $nombreCategoria) = $fila;
            $nombreCategoria = strtoupper($nombreCategoria);

            if ($nombreCategoria == "OFERTAS") {
                $icon = '<svg class="icono-ofertas" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><circle cx="9.5" cy="9.5" r=".5" fill="currentColor" /><circle cx="14.5" cy="14.5" r=".5" fill="currentColor" /><path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7a2.2 2.2 0 0 0 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1a2.2 2.2 0 0 0 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" /></svg>';
            } else {
                $icon = '';
            }
            echo "
                <section class='seccion'>
                <h1 class='titulo-seccion'>" . $icon . "$nombreCategoria <a class='boton' href='/categoria/?id=$idCategoria'>Ver más...</a></h1>
                <section class='productos'>
                ";
            $sql = "SELECT p.id, p.nombre, p.imagen, p.precio  FROM producto p JOIN categoria_producto c ON p.id=c.id_producto  WHERE c.id_categoria LIKE $idCategoria LIMIT 5;";
            $productos = mysqli_query($c, $sql);

            while ($fila2 = mysqli_fetch_row($productos)) {
                list($idProducto, $nombreProducto, $imagenProducto, $precioProducto) = $fila2;

                include_once './functions/centimos.php';
                $precioProducto = quitar_centimos($precioProducto);

                echo "
                    <a href='/producto/?id=$idProducto' class='card'>
                    <div class='img-container'>
                        <img src='$imagenProducto' alt='Foto del producto $nombreProducto'>
                    </div>
                    <h1 class='nombre-producto'>$nombreProducto <span class='precio'>" . $precioProducto . "</span></h1>
                    </a>
                    ";
            }
            echo "
                </section>
                </section>
                ";
        }
        ?>
    <div class="blob-container">
        <span class="blob" />
    </div>
    </main>
    <?php
        include './components/footer.php';
    ?>
    <script type="text/javascript">
        function buscar_ahora() {
            var inputText = $('#buscar').val();
            var result = document.getElementById("resultados")

            $.ajax({
                type: 'POST',
                url: '/req/buscador.php',
                data: { query: inputText },
                success: function (response) {
                    $('#resultados').html(response);
                }
            });

            if (!inputText) {
                result.style.display = "none"
            } else {
                result.style.display = "flex"
            }
        }

    </script>
</body>
<?php mysqli_close($c); ?>

</html>