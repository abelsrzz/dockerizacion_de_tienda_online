<?php
//DB credentials
$DBSEERVER="database";
$DBUSER="myadmin";
$DBPASSWORD="abc123.";
$DBNAME="tienda";


//Check if DB is down
try {
    //DB conection
    $c = mysqli_connect($DBSEERVER, $DBUSER, $DBPASSWORD, $DBNAME);
} catch (\Throwable $th) {
    header("Location: /error/index.php");
}
