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

<body>
    <?php
    //Se llama a la base de datos y a la cabecera
    require '../req/conection.php';
    include '../components/header.php';

    if ($_POST) {
        //En caso de post se recopilan los datos del front-end
        $usuario_login = $_POST['username'];
        $user_password = $_POST['password'];

        //Se consultan los datos en la base de datos
        $sql = "SELECT id, username FROM usuario";
        $resultado = mysqli_query($c, $sql);

        //Se pone la contraseña hasheada a null para evitar errores
        $hash_password = NULL;
        while ($fila = mysqli_fetch_row($resultado)) {
            list($id_usuario, $nombre_usuario) = $fila;

            //Si el usuario existe en la base de datos se continua
            if ($usuario_login == $nombre_usuario) {

                //Se consulta la contraseña del usuario indicado
                $sql = "SELECT password FROM usuario WHERE id LIKE $id_usuario";
                $password_bd = mysqli_query($c, $sql);

                //Se hashea la contraseña ya que se almacena hasheada en la base de datos
                $hash_password = mysqli_fetch_row($password_bd);
                list($hash_password) = $hash_password;

                //Se comprueba si las contraseñas coinciden
                if (password_verify($user_password, $hash_password)) {
                    //En caso de exito se establecen las variables de sesión
                    $_SESSION['usuario'] = $_POST["username"] ?? "";
                    $_SESSION['id_usuario'] = $id_usuario;

                    //Si la variable prodID estaba establecida se redirige al usuario a la página del producto donde decidió iniciar sesión
                    if (isset($_GET['prodID'])) {
                        header("Location: /producto/?id=" . $_GET['prodID']);
                    } else {
                        header("Location: /");
                    }
                    exit(0);
                } else {
                    //En caso de fallo se indica un código de error
                    header("Location: /login/?err=2");
                    exit(1);
                }
            }
        }
        //En caso de fallo se indica un código de error
        header("Location: /login/?err=1");
        exit(1);
    }
    ?>
    <main class='login'>
        <form action="" method="POST" autocomplete="off">
            <?php
                //Se buscan los códigos de error indicados anteriormente y se muestra un mensaje informativo
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
                //Si la variable prodID existe y el usuario necesita crear una cuenta se guarda la variable para redirigir al usuario al producto en el que se encontraba
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