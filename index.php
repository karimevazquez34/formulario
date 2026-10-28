<?php
session_start();
include('config/conexion.php');

$mensaje_bienvenida = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = $conexion->query("SELECT id FROM usuarios WHERE email = '$email'");
    if ($check_email && $check_email->num_rows > 0) {
        $error = "El correo ya está registrado.";
    } else {
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";

        if ($conexion->query($sql) === TRUE) {
            $mensaje_bienvenida = "¡Bienvenido/a " . htmlspecialchars($nombre) . "! Tu cuenta se ha registrado con éxito.";
        } else {
            $error = "Error al registrar: " . $conexion->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adidas Store - Registro</title>
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

    <br>

    <!-- MENSAJE DE BIENVENIDA CON HIPERVÍNCULO -->
    <?php if (!empty($mensaje_bienvenida)) { ?>
        <table border="1" align="center" cellpadding="12" cellspacing="0" bgcolor="#D4EDDA" bordercolor="#C3E6CB" width="450">
            <tr>
                <td align="center">
                    <font color="#155724" face="Segoe UI, Arial" size="4">
                        <b><?php echo $mensaje_bienvenida; ?></b>
                    </font>
                    <br><br>
                    <a href="login.php" style="text-decoration: none;">
                        <font color="#B56576" face="Segoe UI, Arial" size="3"><b>Iniciar Sesión Aquí</b></font>
                    </a>
                </td>
            </tr>
        </table>
        <br>
    <?php } ?>

    <!-- FORMULARIO DE REGISTRO CON HIPERVÍNCULO -->
    <table border="2" align="center" cellpadding="15" cellspacing="0" bgcolor="#FFFFFF" bordercolor="#F3D1DC" width="450">
        <tr>
            <td>
                <h2 align="center"><font color="#D87093" face="Segoe UI, Arial">Crear Cuenta</font></h2>
                <hr color="#F8C8DC" size="2">

                <?php if (!empty($error)) { echo "<p align='center'><font color='red'><b>$error</b></font></p>"; } ?>

                <form action="registro.php" method="POST">
                    <p>
                        <label for="nombre"><font color="#705060" face="Segoe UI, Arial"><b>Nombre Completo:</b></font></label><br>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej. Maria Pérez" size="35" maxlength="100" required autofocus>
                    </p>

                    <p>
                        <label for="email"><font color="#705060" face="Segoe UI, Arial"><b>Correo Electrónico:</b></font></label><br>
                        <input type="email" id="email" name="email" placeholder="usuario@ejemplo.com" size="35" maxlength="100" required>
                    </p>

                    <p>
                        <label for="password"><font color="#705060" face="Segoe UI, Arial"><b>Contraseña:</b></font></label><br>
                        <input type="password" id="password" name="password" minlength="6" maxlength="20" size="35" placeholder="Mínimo 6 caracteres" required>
                    </p>

                    <p align="center">
                        <input type="submit" value="Registrarme">
                        <input type="reset" value="Limpiar">
                    </p>
                </form>

                <hr color="#F8C8DC" size="1">

                <!-- HIPERVÍNCULO A LOGIN -->
                <p align="center">
                    <font face="Segoe UI, Arial" size="2">¿Ya tienes una cuenta?</font><br><br>
                    <a href="login.php" style="text-decoration: none;">
                        <font color="#B56576" face="Segoe UI, Arial" size="3"><b>Iniciar Sesión</b></font>
                    </a>
                </p>
            </td>
        </tr>
    </table>

</body>
</html>