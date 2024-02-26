# Tienda Online Dockerizada

###### Todos los productos de esta tienda provienen de <a href='neobyte.es'>neobyte.es</a>

![image-20240226113744930](.markdown_images/`README`/image-20240226113744930.png)



### Función para guardar el id del producto donde se encuentra el usuario cuando inicia sesión.

![image-20240226113841604](.markdown_images/`README`/image-20240226113841604.png)

`producto/index.php`

```php
//Si el usuario decide iniciar sesión se registra el producto en el que estaba para que no lo pierda cuando ya tenga la sesión iniciada
$boton = "<a class='boton' href='/login?prodID=$idProducto'>Inicia sesión para añadir a la cesta.</a>";
```

`login/index.php`

```php
//Si el usuario estaba en un producto cuando inicio sesión se redirige al producto
if (isset($_GET['prodID'])) {
header("Location: /producto/?id=" . $_GET['prodID']);
} else {
header("Location: /");
}
exit(0);
```

### Función para establecer los céntimos de forma correcta en el frontend

![image-20240226114241523](.markdown_images/`README`/image-20240226114241523.png)

`functions/centimos.php`

```php
<?php
//Función para mostrar correctamente los céntimos en el frontend
function quitar_centimos($cifra)
{
    $centimosCifra = substr(strrchr($cifra, "."), 1);

    $cifrasCentimos = strlen($centimosCifra);

    if ($cifrasCentimos == 1) {
        $cifra = $cifra . "0";
    }
    return $cifra;
}
```

### Cesta de productos

![image-20240226115729893](.markdown_images/`README`/image-20240226115729893.png)