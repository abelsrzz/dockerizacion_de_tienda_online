<?php
//Credenciales de base de datos
$DBSEERVER = "database";
$DBUSER = "myadmin";
$DBPASSWORD = "abc123.";
$DBNAME = "tienda";


//Se comprueba si la base de datos está activa
try {
    $c = mysqli_connect($DBSEERVER, $DBUSER, $DBPASSWORD, $DBNAME);
} catch (\Throwable $th) {
    header("Location: /error/index.php");
}
