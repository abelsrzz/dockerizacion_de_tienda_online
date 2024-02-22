<?php
    ob_start();
    session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/reset.css">
    <link rel="stylesheet" href="/style/general.css">
    <link rel="stylesheet" href="/style/index.css">
    <link rel="stylesheet" href="/style/login.css">
    <link rel="icon" type="image/svg+xml" href="../favicon.svg" />
    <title>Tienda Online</title>
</head>

<body>
    <?php
    require '../req/conection.php';
    include '../components/header.php';

    if ($_POST) {
        $usuario_login = $_POST['username'];
        $user_password = $_POST['password'];


        $sql = "SELECT id, username FROM usuario";
        $resultado = mysqli_query($c, $sql);

        $hash_password = NULL;
        while ($fila = mysqli_fetch_row($resultado)) {
            list($id_usuario, $nombre_usuario) = $fila;

            if ($usuario_login == $nombre_usuario) {

                $sql = "SELECT password FROM usuario WHERE id LIKE $id_usuario";
                $password_bd = mysqli_query($c, $sql);
                $hash_password = mysqli_fetch_row($password_bd);
                list($hash_password) = $hash_password;

                if (password_verify($user_password, $hash_password)) {
                    $_SESSION['usuario'] = $_POST["username"] ?? "";
                    $_SESSION['id_usuario'] = $id_usuario;

                    if (isset($_GET['prodID'])) {
                        header("Location: /producto/?id=" . $_GET['prodID']);
                    } else {
                        header("Location: /");
                    }
                    exit(0);
                } else {
                    header("Location: /login/?err=2");
                    exit(1);
                }
            }

        }
        header("Location: /login/?err=1");
        exit(1);

    }
    ?>
    <main class='login'>
        <form action="" method="POST" autocomplete="off">
            <?php
            if (isset($_GET['err'])) {
                if ($_GET['err'] == 1) {
                    echo "<p class='error'>El usuario no existe</p>";
                } elseif ($_GET['err'] == 2) {
                    echo "<p class='error'>Contraseña incorrecta</p>";
                }
            }
            ?>
            <label for="username">Nombre de usuario:</label>
            <input type="text" name='username'>

            <label for="password">Contraseña:</label>
            <input type="password" name='password' autocomplete="no">

            <input type="submit" class='boton'>

            <?php

            if (isset($_GET['prodID'])) {
                echo "<p>¿No tienes cuenta? <a href='/registrarse/index.php?prodID=" . $_GET['prodID'] . "'>Regístrate</a></p>";
            } else {
                echo "<p>¿No tienes cuenta? <a href='/registrarse/index.php'>Regístrate</a></p>";
            }
            ?>

        </form>
    </main>
</body>

</html>