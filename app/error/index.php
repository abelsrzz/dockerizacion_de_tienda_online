<?php
    //Llamada de la sesión y la base de datos
    require '../req/session.php';
    require '../req/conection.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/reset.css">
    <link rel="stylesheet" href="/style/error.css">
    <link rel="stylesheet" href="/style/general.css">
    <title>Error de Conexión</title>
</head>

<body>
    <main>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plug-connected-x" width="24"
                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M20 16l-4 4" />
                <path d="M7 12l5 5l-1.5 1.5a3.536 3.536 0 1 1 -5 -5l1.5 -1.5z" />
                <path d="M17 12l-5 -5l1.5 -1.5a3.536 3.536 0 1 1 5 5l-1.5 1.5z" />
                <path d="M3 21l2.5 -2.5" />
                <path d="M18.5 5.5l2.5 -2.5" />
                <path d="M10 11l-2 2" />
                <path d="M13 14l-2 2" />
                <path d="M16 16l4 4" />
            </svg>
        </div>
        <h1>Estamos experimentando problemas de conexión.</h1>
        <h2>Intentalo de nuevo más tarde.</h2>
        <h3>Lamentamos las molestias.</h3>
    </main>
</body>

</html>