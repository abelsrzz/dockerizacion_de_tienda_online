# Tienda Online Dockerizada

###### Todos los productos de esta tienda provienen de <a href='neobyte.es'>neobyte.es</a>

### Dockerización

```yaml
version: '3.8'

services:
  nginx:
    build:
      context: ./nginx
    container_name: nginx
    restart: unless-stopped
    volumes:
      - ./app/:/var/www/
    depends_on:
      - php-fpm
      - database
    ports:
      - 8080:80
    networks:
      - php-stack

  php-fpm:
    build:
      context: ./php-fpm
    container_name: php-fpm
    restart: unless-stopped
    volumes:
      - ./app/:/var/www/
    depends_on:
      - database
    networks:
      - php-stack

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: phpmyadmin
    restart: unless-stopped
    environment:
      - PMA_ARBITRARY=1
      - TZ=Spain/Madrid
    ports:
      - 8081:80
    depends_on:
      - database
    networks:
      - php-stack

  database:
    build:
      context: ./mysql
    container_name: database
    restart: unless-stopped
    command:
      - '--local-infile=1'
      - '--secure-file-priv=/data'
    environment:
      - MYSQL_DATABASE=tienda
      - MYSQL_USER=myadmin
      - MYSQL_PASSWORD=abc123.
      - MYSQL_ROOT_PASSWORD=abc123.
    networks:
      - php-stack

networks:
  php-stack:
    name: php-stack 
```

#### Dockerfile php

```docker
FROM php:8.2-fpm
RUN rm -rf /var/www/html
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
```

#### Dockerfile nginx

```dockerfile
FROM nginx:latest
RUN rm -rf /var/www/html
ADD nginx.conf /etc/nginx/nginx.conf
ADD conf.d/default.conf /etc/nginx/conf.d/default.conf
```

#### Dockerfile mysql

```dockerfile
FROM mysql:latest
ADD databases/ /docker-entrypoint-initdb.d/
ADD scrapped_data/ /data 
```

### Funcionamiento 

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

### Base de datos

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

