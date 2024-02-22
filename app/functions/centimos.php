<?php
function quitar_centimos($cifra)
{
    $centimosCifra = substr(strrchr($cifra, "."), 1);

    $cifrasCentimos = strlen($centimosCifra);

    if ($cifrasCentimos == 1) {
        $cifra = $cifra . "0";
    }
    return $cifra;
}
