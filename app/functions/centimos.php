<?php
//Función para establecer correctamente los céntimos en el front-end
function quitar_centimos($cifra)
{
    //La función recibe una cifra la cual corta a través del punto y se añade un 0 si es necesario
    $centimosCifra = substr(strrchr($cifra, "."), 1);

    $cifrasCentimos = strlen($centimosCifra);

    if ($cifrasCentimos == 1) {
        $cifra = $cifra . "0";
    }
    return $cifra;
}
