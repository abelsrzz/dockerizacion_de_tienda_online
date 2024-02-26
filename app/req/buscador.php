<?php
//Se llama al a base de datos
require '../req/conection.php';

//Se recibe la búsqueda por post
$buscar = $_POST['query'] ?? '';
$buscar = strtolower($buscar);

//Se seleccionan las coincidencias en la base de datos
$sql = "SELECT * FROM producto WHERE LOWER(nombre) LIKE '%$buscar%' OR LOWER(especificaciones) LIKE '%$buscar%' OR LOWER(marca) LIKE '%$buscar%'";
$busqueda = mysqli_query($c, $sql);

if (mysqli_num_rows($busqueda) > 0) {

    //Se devuelven los resultados
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
    //Si no hay resultados se da un mensaje informativo
    echo "<p class='contenido-resultado'>No hay coincidencias</p>";
}

