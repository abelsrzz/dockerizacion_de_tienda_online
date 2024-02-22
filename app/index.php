<?php
    // Llamamos a las funciones esenciales para el correcto funcionamiento de la página
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
            //Si la sesión tiene un nombre de usuario registrado saludamos al usuario en lugar de utilizar un saludo generico
            if (!isset($_SESSION["usuario"])) {
                echo "<h1>¡Bienvenid@ a la mejor tienda!</h1>";
            } else {
                echo "<h1>¡Bienvenid@ a la mejor tienda <span class='azul'>".$_SESSION["usuario"]."</span>!</h1>";
            }
            ?>
            <h1>¡CON LAS MEJORES <a href="/categoria/?id=1">Ofertas</a>!</h1>

            <!-- LLamada al script del buscador -->
            <input type="text" name="buscar" id="buscar" value="" placeholder="Buscar..." maxlength="30"
                autocomplete="off" onkeyup="buscar_ahora();" />
            <div id="resultados"></div>

        </div>
        <?php
            //Consulta para obtener categorías
            $sql = "SELECT * FROM categoria";
            $categorias = mysqli_query($c, $sql);

            //Fetch del resultado
            while ($fila = mysqli_fetch_row($categorias)) {
                list($idCategoria, $nombreCategoria) = $fila;
                $nombreCategoria = strtoupper($nombreCategoria);

                //Si la categoría es ofertas se establece un icono para destacar la categoría en el frontend
                if ($nombreCategoria == "OFERTAS") {
                    $icon = '<svg class="icono-ofertas" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><circle cx="9.5" cy="9.5" r=".5" fill="currentColor" /><circle cx="14.5" cy="14.5" r=".5" fill="currentColor" /><path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7a2.2 2.2 0 0 0 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1a2.2 2.2 0 0 0 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" /></svg>';
                } else {
                    $icon = '';
                }
                //Imprimimos la categoría en función a los resultados obtenidos
                echo "
                    <section class='seccion'>
                    <h1 class='titulo-seccion'>" . $icon . "$nombreCategoria <a class='boton' href='/categoria/?id=$idCategoria'>Ver más...</a></h1>
                    <section class='productos'>
                ";
                    //Consulta para obtener productos de la categoría mostrada
                    $sql = "SELECT p.id, p.nombre, p.imagen, p.precio  FROM producto p JOIN categoria_producto c ON p.id=c.id_producto  WHERE c.id_categoria LIKE $idCategoria LIMIT 5;";
                    $productos = mysqli_query($c, $sql);

                    //Se recorren los resultados de la consulta
                    while ($fila_productos = mysqli_fetch_row($productos)) {
                        list($idProducto, $nombreProducto, $imagenProducto, $precioProducto) = $fila_productos;

                        //LLamada a la función céntimos para que los céntimos del precio se muestren de forma correcta
                        include_once './functions/centimos.php';
                        $precioProducto = quitar_centimos($precioProducto);

                        //Se muestra el producto actual
                        echo "
                            <a href='/producto/?id=$idProducto' class='card'>
                            <div class='img-container'>
                                <img src='$imagenProducto' alt='Foto del producto $nombreProducto'>
                            </div>
                            <h1 class='nombre-producto'>$nombreProducto <span class='precio'>" . $precioProducto . "</span></h1>
                            </a>
                        ";
                    }
                //Cerramos las secciones
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
        // El texto introducido en el input del buscador se envía al fichero con el back-end de búsqueda y este nos contesta con los resultados
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