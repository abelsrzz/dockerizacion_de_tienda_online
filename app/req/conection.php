<?php
//Datos de acceso a la base de datos
$DBSEERVER="database";
$DBUSER="myadmin";
$DBPASSWORD="abc123.";
$DBNAME="tienda";

//Intento de conexión, si este falla se redirige a la página de error
try {
    $c = mysqli_connect($DBSEERVER, $DBUSER, $DBPASSWORD, $DBNAME);
    header("Location /");
} catch (\Throwable $th) {
    header("Location: /error/index.php");
}
