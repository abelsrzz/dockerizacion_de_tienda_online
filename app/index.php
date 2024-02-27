<?php
//Llamada a las funcionalidades esenciales
require './req/session.php';
require './req/conection.php';
include './components/header.php';
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
            //Bienvenida personalizada para usuario registrados y con sesión iniciada
            if (!isset($_SESSION["usuario"])) {
                echo "<h1>¡Bienvenid@ a la mejor tienda!</h1>";
            } else {
                echo "<h1>¡Bienvenid@ a la mejor tienda <span class='azul'>" . $_SESSION["usuario"] . "</span>!</h1>";
            }
            ?>
            <h1>¡CON LAS MEJORES <a href="/categoria/?id=0">Ofertas</a>!</h1>

            <input type="text" name="buscar" id="buscar" value="" placeholder="Buscar..." maxlength="30"
                autocomplete="off" onkeyup="buscar_ahora();" />
            <div id="resultados">
            </div>
        </div>
        <section class="index-menu">
            <h1 class="nav-menu">
                <button class="boton" id="boton-mostrar-todas" onclick="show_all()">Mostrar todas las categorías</button>
            </h1>
            <article id="all-cats">
                <?php
                    //Selección de todas las categorías existentes
                    $sql = "SELECT * FROM categoria";
                    $categorias = mysqli_query($c, $sql);

                    //Se recorren los resultados
                    while ($fila = mysqli_fetch_row($categorias)) {
                        list($idCategoria, $nombreCategoria) = $fila;
                        $nombreCategoria = strtoupper($nombreCategoria);

                        echo "
                            <a class='categoria-todas' href='./categoria/?id=$idCategoria'>$nombreCategoria</a>
                        ";
                    }
                ?>
            </article>
        </section>
        <?php
        //Selección de todas las categorías existentes
        $sql = "SELECT * FROM categoria";
        $categorias = mysqli_query($c, $sql);

        //Se recorren los resultados
        while ($fila = mysqli_fetch_row($categorias)) {
            list($idCategoria, $nombreCategoria) = $fila;
            $nombreCategoria = strtoupper($nombreCategoria);

            //Icono para categoría de ofertas
            if ($nombreCategoria == "OFERTAS") {
                $icon = '<svg class="icono-ofertas" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><circle cx="9.5" cy="9.5" r=".5" fill="currentColor" /><circle cx="14.5" cy="14.5" r=".5" fill="currentColor" /><path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7a2.2 2.2 0 0 0 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1a2.2 2.2 0 0 0 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" /></svg>';
            } else {
                $icon = '';
            }

            //Se cuenta el tottal de productos de la categoría
            $sql = "SELECT COUNT(*)  FROM producto p JOIN categoria_producto c ON p.id=c.id_producto  WHERE c.id_categoria LIKE $idCategoria LIMIT 5;";
            $numero_de_productos_row = mysqli_query($c, $sql);
            $numero_de_productos = mysqli_fetch_row($numero_de_productos_row);
            list($numero_de_productos) = $numero_de_productos;

            //Se imprime la categoría actual
            echo "
                <section class='seccion '>
                <h1 class='titulo-seccion'>" . $icon . "$nombreCategoria <a class='boton' href='/categoria/?id=$idCategoria'>Ver $numero_de_productos productos</a></h1>
                <article class='productos-index'>
            ";

            //Se seleccionan los productos de la categoría actual
            $sql = "SELECT p.id, p.nombre, p.imagen, p.precio  FROM producto p JOIN categoria_producto c ON p.id=c.id_producto  WHERE c.id_categoria LIKE $idCategoria LIMIT 5;";
            $productos = mysqli_query($c, $sql);

            //Se recorren los resultados
            while ($filaProducto = mysqli_fetch_row($productos)) {
                list($idProducto, $nombreProducto, $imagenProducto, $precioProducto) = $filaProducto;

                //Se llama a la función céntimos para corregir la apariencia del precio en el frontend
                include_once './functions/centimos.php';
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
            //Se cierran las secciones actuales
            echo "
                </article>
                </section>
            ";
        }
        ?>
        <div class="blob-container">
            <span class="blob" />
        </div>
    </main>
    <script type="text/javascript">
        // Función que se activa al introducir texto dentro del buscador
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

        //Boton para mostrar todas las categorías
        function show_all() {
            const showButton = document.getElementById("boton-mostrar-todas")
            const showText = "Mostrar todas las categorías"
            const hideText = "Ocultar todas las categorías"

            const all_cats = document.getElementById("all-cats");

            all_cats.classList.toggle("shown");

            if(showButton.innerHTML === showText){
                showButton.innerHTML = hideText
            } else {
                showButton.innerHTML = showText
            }
        }
    </script>
</body>
<?php mysqli_close($c); ?>

</html>