<?php
session_start();
include('config/conexion.php');

if (isset($_SESSION['usuario_id'])) {
    header("Location: tienda.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['bienvenida'] = true;
            header("Location: tienda.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adidas Store - Iniciar Sesión</title>
</head>
<body bgcolor="#FFF0F5" text="#4A4A4A">

    <!-- ENCABEZADO -->
    <table border="0" width="100%" cellpadding="15" cellspacing="0" bgcolor="#F8C8DC">
        <tr>
            <td align="center">
                <h1><font color="#6B4C57" face="Segoe UI, Arial">ADIDAS STORE</font></h1>
            </td>
        </tr>
    </table>

    <br><br>

    <!-- FORMULARIO DE INICIO DE SESIÓN -->
    <table border="2" align="center" cellpadding="15" cellspacing="0" bgcolor="#FFFFFF" bordercolor="#F3D1DC" width="450">
        <tr>
            <td>
                <h2 align="center"><font color="#D87093" face="Segoe UI, Arial">Iniciar Sesión</font></h2>
                <hr color="#F8C8DC" size="2">

                <?php if (!empty($error)) { echo "<p align='center'><font color='red'><b>$error</b></font></p>"; } ?>

                <form action="login.php" method="POST">
                    <p>
                        <label for="email"><font color="#705060" face="Segoe UI, Arial"><b>Correo Electrónico:</b></font></label><br>
                        <input type="email" id="email" name="email" placeholder="usuario@ejemplo.com" size="35" required autofocus>
                    </p>

                    <p>
                        <label for="password"><font color="#705060" face="Segoe UI, Arial"><b>Contraseña:</b></font></label><br>
                        <input type="password" id="password" name="password" placeholder="Tu contraseña" size="35" required>
                    </p>

                    <p align="center">
                        <input type="submit" value="Ingresar">
                        <input type="reset" value="Limpiar">
                    </p>
                </form>

                <hr color="#F8C8DC" size="1">

                <!-- HIPERVÍNCULO A REGISTRO -->
                <p align="center">
                    <font face="Segoe UI, Arial" size="2">¿No tienes una cuenta?</font><br><br>
                    <a href="registro.php" style="text-decoration: none;">
                        <font color="#B56576" face="Segoe UI, Arial" size="3"><b>Crear cuenta nueva</b></font>
                    </a>
                </p>
            </td>
        </tr>
    </table>

</body>
</html>