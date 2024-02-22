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
    ?>
    <?php
    if ($_POST) {
        $username = $_POST["username"] ?? "";
        $password_form = $_POST["password"] ?? "";
        $rpassword = $_POST["rpassword"] ?? "";
        $nombre = $_POST["nombre"] ?? "";
        $apellido1 = $_POST["apellido1"] ?? "";
        $apellido2 = $_POST["apellido2"] ?? "";
        $mail = $_POST["mail"] ?? "";


        $sql = "SELECT mail FROM usuario WHERE mail LIKE '$mail'";
        $mailRepetido = mysqli_query($c, $sql);

        while ($fila = mysqli_fetch_row($mailRepetido)) {
            list($checkMail) = $fila;
            if ($checkMail == $mail) {
                header("Location: /registrarse?err=1");
                exit(1);
            }
        }



        $sql = "SELECT username FROM usuario WHERE username LIKE '$username'";
        $usernameRepetido = mysqli_query($c, $sql);

        while ($fila = mysqli_fetch_row($usernameRepetido)) {
            list($checkUsername) = $fila;
            if ($checkUsername == $username) {
                header("Location: /registrarse?err=2");
                exit(1);
            }
        }



        if ($password_form == $rpassword) {
            $hash_password = password_hash($password_form, PASSWORD_DEFAULT);
            $consulta_insertar_usuario = "INSERT INTO usuario(username, password, nombre, apellido1, apellido2, mail) VALUES ('$username', '$hash_password', '$nombre', '$apellido1', '$apellido2', '$mail');";
            mysqli_query($c, $consulta_insertar_usuario);

            $_SESSION['usuario'] = $_POST["username"] ?? "";
            $consulta_id_sesion = "SELECT id FROM usuario WHERE username LIKE '" . $_SESSION['usuario'] . "'";
            $id_usuario_db = mysqli_query($c, $consulta_id_sesion);

            $id_usuario = mysqli_fetch_row($id_usuario_db);
            list($id_usuario) = $id_usuario;

            $_SESSION['id_usuario'] = $id_usuario;


            if (isset($_GET['prodID'])) {
                header("Location: /producto/?id=" . $_GET['prodID']);
            } else {
                header("Location: /");
            }
            exit(0);
        } else {
            header("Location: /registrarse?err=3");
            exit(1);
        }
    }
    ?>
    <main class='login'>
        <form action="" method="POST" autocomplete="off">
            <?php
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
</body>

</html>