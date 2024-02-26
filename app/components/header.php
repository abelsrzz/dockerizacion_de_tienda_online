<header class="cabecera">
    <div id="zona-menu">
        <button id="boton-menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="24" height="24"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 6l16 0" />
                <path d="M4 12l16 0" />
                <path d="M4 18l16 0" />
            </svg>
        </button>
        <ul id="menu">
            <li class="elemento-menu">
                <a target="_blank" href="https://github.com/abelsrzz/dockerizacion_de_tienda_online">Ver código</a>
            </li>
            <li class="elemento-menu">
                <a target="_blank" href="https://github.com/abelsrzz">A cerca del creador</a>
            </li>
            <li class="elemento-menu">
            <a target="_blank" href="https://github.com/abelsrzz/dockerizacion_de_tienda_online/issues">Informar de un error</a>
        </ul>
    </div>
    <ul class="enlaces-cabecera" id='logo-central'>
        <li><a href="/">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cpu" width="24" height="24"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 5m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z" />
                    <path d="M9 9h6v6h-6z" />
                    <path d="M3 10h2" />
                    <path d="M3 14h2" />
                    <path d="M10 3v2" />
                    <path d="M14 3v2" />
                    <path d="M21 10h-2" />
                    <path d="M21 14h-2" />
                    <path d="M14 21v-2" />
                    <path d="M10 21v-2" />
                </svg>
            </a></li>
    </ul>
    <ul class="enlaces-cabecera">
        <button onclick="location.href='/carrito/index.php';" id="carrito">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart" width="24"
                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M17 17h-11v-14h-2" />
                <path d="M6 5l14 1l-1 7h-13" />
            </svg>
        </button>
        <?php
        //Si el usuario tiene sesión iniciada aparece boton de cerrar sesión, si no, de iniciar sesión
        if (isset($_SESSION['usuario'])) {
            echo "<a href='/sesion/cerrarSesion.php'>Cerrar sesión</a>";
        } else {
            echo "<a href='/login/index.php'>Iniciar sesión</a>";
        }
        ?>

    </ul>
</header>