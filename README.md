# Tienda Online Dockerizada

###### Todos los productos de esta tienda provienen de <a target="_blank" href='https://neobyte.es'>neobyte.es</a>

![image-20240226113744930](.markdown_images/`README`/image-20240226113744930.png)

## Los datos mostrados en la página web

Todos los productos de la página web han sido recopilado de la página oficial de <a target="_blank" href='https://neobyte.es'>neobyte.es</a> mediante un script en python.

El script recorre la página principal en búsqueda de las diferentes secciones, una vez localizada una sección hace un barrido completo de todos los productos que aparecen en el landing page de la sección.

`app/mysql/scrapped_data/scrapper.py`

```python
from requests_html import HTMLSession
import re

session = HTMLSession()
url = "https://www.neobyte.es/"
r = session.get(url)
full_page_scrap = r.html.xpath("//div/ul/li/a/@href")

prohibited_links=["content", "empresas", "quiero-mi-promo", "contactanos"]
categorys = []

def clear_files():
    product_data_file = open("./product_data.csv", "w")
    category_data_file = open("./category_data.csv", "w")
    product_category_data_file = open("./product_category_data.csv", "w")
    
    product_data_file.write("")
    category_data_file.write("0;' OFERTAS'")
    product_category_data_file.write("")
    


print("Buscando categorias")
for category_link in full_page_scrap:
    prohibited_detect=0
    if "www.neobyte.es" in category_link:
        for prohibited in prohibited_links:
            if prohibited in category_link:
                prohibited_detect = 1
                break
        if prohibited_detect == 0:    
            categorys.append(category_link)

category_id=0
product_id=0
clear_files()
for category_url in categorys:
    match = re.search(r'[^\/]+$', str(category_url))
    match = match.group(0)
    match = match.split("-")
    match = match[:-1]
    category_name=""
    for palabra in match:
        category_name = category_name +" "+ palabra
    print(category_name)

    category_id+=1

    url = category_url
    r = session.get(url)

    print("Buscando productos de categoria " + category_name)
    scrap = r.html.xpath("//article/div/a/@href")
    
    
    
    category_data_file = open("./category_data.csv", "a")
    category_data = str(category_id)+";'"+str(category_name)+"'\n"
    category_data_file.write(category_data)
    
    for product_url in scrap:
        url = product_url
        r = session.get(url)

        image = r.html.xpath("//div/div/div/img[@itemprop='image'][1]/@content")
        if type(image) is list:
            product_image = image[0]
        else:
            product_image = image
        
        nombre_elementos = r.html.xpath("//h1[@itemprop='name']/span")
        for nombre in nombre_elementos:
            product_name = nombre.text
        
        descripciones_elementos = r.html.xpath("//div[@itemprop='description']/p")
        for descripcion in descripciones_elementos:
            product_description = descripcion.text
        
        precios_elementos = r.html.xpath("//span[@itemprop='price']/@content")
        for precio in precios_elementos:
            product_price = precio
            
        marcas_elementos = r.html.xpath("//div[@itemprop='brand']/meta/@content")
        for marca in marcas_elementos:
            product_brand = marca
    
        print("Producto: " + str(nombre.text))
        product_data_file = open("./product_data.csv", "a")
        product_category_data_file = open("./product_category_data.csv", "a")

        product_data = str(product_id)+";"+"'"+str(product_name)+"'"+";"+"'"+str(product_image)+"'"+";"+str(product_price)+";"+"'"+str(product_description)+"'"+";"+"'"+str(product_brand)+"'\n"
        product_data = product_data.replace('"', '')
        product_data_file.write(product_data)
        
        product_category_data = str(category_id)+";"+str(product_id)+"\n"
        product_category_data_file.write(product_category_data)
        
        product_id+=1
```

Una vez recopilados los datos de la web estos se guardan en 3 archivos csv para posteriormente importarlos en la base de datos.

## Funcionalidades principales de la página

### Función para guardar el id del producto donde se encuentra el usuario cuando inicia sesión.

Si un usuario decide iniciar sesión mientras este se encuentra en una página de producto se guardará el ID del producto en una variable con el método GET.

![image-20240226113841604](.markdown_images/`README`/image-20240226113841604.png)

`producto/index.php`

```php
//Si el usuario decide iniciar sesión se registra el producto en el que estaba para que no lo pierda cuando ya tenga la sesión iniciada
$boton = "<a class='boton' href='/login?prodID=$idProducto'>Inicia sesión para añadir a la cesta.</a>";
```

Posteriormente se recopila en la página de login/registro y una vez iniciada la sesión se devuelve a la página del producto.

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

He creado una función para que el precio de los productos no se muestre como `999.0€` y se muestre como `999.00€`

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

La cesta permite eliminar productos de una forma sencilla, con un solo click.

### Buscador en tiempo real con JQuery

He implementado un buscador en tiempo real utilizando JQuery y back-end php.

El código JQuery recopila el texto introducido en el buscador y lo remite al back-end en cada pulsación de tecla.

```html
<input type="text" ... placeholder="Buscar..." maxlength="30" autocomplete="off" onkeyup="buscar_ahora();" />
```

#### JQuery:

```javascript
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
```

#### Back-end del buscador en php:

```php
<?php
//Se llama al a base de datos
require '../req/conection.php';

//Se recibe la búsqueda por post
$buscar = $_POST['query'] ?? '';
$buscar = strtolower($buscar);

//Protección contra XSS
$buscar = htmlspecialchars($buscar, ENT_QUOTES, 'UTF-8');


//Se seleccionan las coincidencias en la base de datos
$sql = "SELECT * FROM producto WHERE LOWER(nombre) LIKE '%$buscar%' OR LOWER(especificaciones) LIKE '%$buscar%' OR LOWER(marca) LIKE '%$buscar%'";
$busqueda = mysqli_query($c, $sql);

if (mysqli_num_rows($busqueda) > 0) {

    //Se devuelven los resultados
    echo "
    <section class='productos-wrapper small-text'>
    <section class='productos'>
    ";
    while ($fila = mysqli_fetch_row($busqueda)) {
        list($idProducto, $nombreProducto, $imagenProducto, $precioProducto, $especificacionesProducto, $marcaProducto) = $fila;

        //Se llama a la función céntimos para corregir la apariencia del precio en el frontend
        include_once '../functions/centimos.php';
        $precioProducto = quitar_centimos($precioProducto);

        //Imprimir producto
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
```

### Base de datos

La base de datos almacena categorías, productos, la relación entre una categoría y un producto para que así los productos puedan tener varias categorías, usuarios y el contenido de la cesta de un usuario.

```sql
CREATE TABLE categoria(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL
);
CREATE TABLE producto(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(500) NOT NULL,
    imagen VARCHAR(500) NOT NULL,
    precio FLOAT NOT NULL,
    especificaciones TEXT,
    marca VARCHAR(200) NOT NULL,
    id_categoria INT NOT NULL
);
CREATE TABLE categoria_producto(
	id_categoria INT,
    id_producto INT,
    PRIMARY KEY (id_categoria, id_producto)
);

CREATE TABLE usuario(
	id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(200) NOT NULL,
    nombre VARCHAR(50),
    apellido1 VARCHAR(50),
    apellido2 VARCHAR(50),
    mail VARCHAR(200) NOT NULL
);

CREATE TABLE productos_en_cesta_usuario(
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL
);
```

