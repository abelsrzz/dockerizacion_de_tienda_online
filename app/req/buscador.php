<?php
//Llamada a la base de datos
require '../req/conection.php';

//Recopilación de los datos del front-end
$buscar = $_POST['query'] ?? '';
$buscar = strtolower($buscar);

//Consulta de los datos recibidos
$sql = "SELECT * FROM producto WHERE LOWER(nombre) LIKE '%$buscar%' OR LOWER(especificaciones) LIKE '%$buscar%' OR LOWER(marca) LIKE LOWER('%$buscar%')";
$busqueda = mysqli_query($c, $sql);

//Filtrado por si el resultado es nulo
if (mysqli_num_rows($busqueda) > 0) {
    echo "
        <section class='resultados'>
        <section class='productos'>
    ";
        //Se recorren los datos obtenidos y se escribe en el frontend
        while ($fila = mysqli_fetch_row($busqueda)) {
            list($idProducto, $nombreProducto, $imagenProducto, $precioProducto, $especificacionesProducto, $marcaProducto) = $fila;
            echo "
                <a href='/producto/?id=$idProducto' class='card'>
                <div class='img-container'>
                    <img src='$imagenProducto' alt='Foto del producto $nombreProducto' class='imagen-producto'>
                </div>
                <h1 class='nombre-producto' style='font-size: 12px;'>$nombreProducto <span class='precio'>" . $precioProducto . "</span></h1>
                </a>
            ";
        }
    echo "
        </section>
        </section>
    ";
} else {
    //Si la búsqueda no es exitosa se imprime un mensaje de error
    echo "<p class='contenido-resultado'>No hay coincidencias</p>";
}
