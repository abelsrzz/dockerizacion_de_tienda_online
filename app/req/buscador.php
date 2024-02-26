<?php
require '../req/conection.php';


$buscar = $_POST['query'] ?? '';
$buscar = strtolower($buscar);

$sql = "SELECT * FROM producto WHERE LOWER(nombre) LIKE '%$buscar%' OR LOWER(especificaciones) LIKE '%$buscar%' OR LOWER(marca) LIKE '%$buscar%'";
$busqueda = mysqli_query($c, $sql);

if (mysqli_num_rows($busqueda) > 0) {

    echo "
    <section class='resultados'>
    <section class='productos'>
    ";
    while ($fila = mysqli_fetch_row($busqueda)) {
        list($idProducto, $nombreProducto, $imagenProducto, $precioProducto, $especificacionesProducto, $marcaProducto) = $fila;
        echo "
        <a href='/producto/?id=$idProducto' class='card producto-buscado'>
        <div class='img-container'>
            <img src='$imagenProducto'>
        </div>
        <h1 class='nombre-producto nombre-producto-buscado'>$nombreProducto <span class='precio'>" . $precioProducto . "</span></h1>
        </a>
        ";
    }
    echo "
    </section>
    </section>
    ";




} else {
    echo "<p class='contenido-resultado'>No hay coincidencias</p>";
}

