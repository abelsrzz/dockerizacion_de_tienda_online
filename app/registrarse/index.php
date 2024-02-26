<?php
//Se inicia la sesión
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

<body class='dotted'>
    <?php
    //Llamada a la base de datos y a la cabecera de la página
    require '../req/conection.php';
    include '../components/header.php';
    ?>
    <?php
    if ($_POST) {
        //En caso de POST se recopilan las credenciales
        $username = $_POST["username"] ?? "";
        $password_form = $_POST["password"] ?? "";
        $rpassword = $_POST["rpassword"] ?? "";
        $nombre = $_POST["nombre"] ?? "";
        $apellido1 = $_POST["apellido1"] ?? "";
        $apellido2 = $_POST["apellido2"] ?? "";
        $mail = $_POST["mail"] ?? "";

        //Se comprueba si existe alguna cuenta con el mismo correo
        $sql = "SELECT mail FROM usuario WHERE mail LIKE '$mail'";
        $mailRepetido = mysqli_query($c, $sql);

        while ($fila = mysqli_fetch_row($mailRepetido)) {
            list($checkMail) = $fila;

            //Si el mail ya existe se envía un código de error
            if ($checkMail == $mail) {
                header("Location: /registrarse?err=1");
                exit(1);
            }
        }

        //Se comprueba si ya existe este nombre de usuario
        $sql = "SELECT username FROM usuario WHERE username LIKE '$username'";
        $usernameRepetido = mysqli_query($c, $sql);

        while ($fila = mysqli_fetch_row($usernameRepetido)) {
            list($checkUsername) = $fila;

            //Si el usuario ya existe se envía un código de error
            if ($checkUsername == $username) {
                header("Location: /registrarse?err=2");
                exit(1);
            }
        }

        //Se comprueba que las contraseñas son iguales
        if ($password_form == $rpassword) {

            //Se hashea la contraseña para guardarla de forma segura
            $hash_password = password_hash($password_form, PASSWORD_DEFAULT);

            //Se insertan los datos de usuario
            $consulta_insertar_usuario = "INSERT INTO usuario(username, password, nombre, apellido1, apellido2, mail) VALUES ('$username', '$hash_password', '$nombre', '$apellido1', '$apellido2', '$mail');";
            mysqli_query($c, $consulta_insertar_usuario);

            //Se establecen las credenciales de sesión
            $_SESSION['usuario'] = $_POST["username"] ?? "";
            $consulta_id_sesion = "SELECT id FROM usuario WHERE username LIKE '" . $_SESSION['usuario'] . "'";
            $id_usuario_db = mysqli_query($c, $consulta_id_sesion);
            $id_usuario = mysqli_fetch_row($id_usuario_db);
            list($id_usuario) = $id_usuario;
            $_SESSION['id_usuario'] = $id_usuario;

            //Si un usuario estaba en un producto cuando decidió registrarse se reenvia a la página del producto
            if (isset($_GET['prodID'])) {
                header("Location: /producto/?id=" . $_GET['prodID']);
            } else {
                header("Location: /");
            }
            exit(0);
        } else {
            //Si las contraseñas no coinciden se envía un código de error
            header("Location: /registrarse?err=3");
            exit(1);
        }
    }
    ?>
    <main class='login'>
        <form action="" method="POST" autocomplete="off">
            <?php
            //Mensajes de informativos de error
            if (isset($_GET['err'])) {
                if ($_GET['err'] == 1) {
                    echo "<p class='error'>Ya existe una cuenta con este correo.</p>";
                } elseif ($_GET['err'] == 2) {
                    echo "<p class='error'>Ya existe una cuenta con este nombre de usuario.</p>";
                } elseif ($_GET['err'] == 3) {
                    echo "<p class='error'>La contraseña no coincide.</p>";
                }
            }
            ?>
            <label for="username">Nombre de usuario:</label>
            <input type="text" name='username' required>

            <label for="password">Contraseña:</label>
            <input type="password" name='password' required>
            <label for="rpassword">Repetir contraseña:</label>
            <input type="password" name='rpassword' required>

            <label for="nombre">Nombre:</label>
            <input type="text" name='nombre'>

            <label for="apellido1">Primer apellido:</label>
            <input type="text" name='apellido1'>

            <label for="apellido2">Segundo apellido:</label>
            <input type="text" name='apellido2'>

            <label for="mail">Email:</label>
            <input type="text" name='mail' required>

            <input type="submit" class='boton'>

            <p>¿Ya tienes cuenta? <a href='/login'>Inicia sesión.</a></p>
        </form>

    </main>
    <div class="blob-container">
        <span class="blob" />
    </div>
</body>

</html>