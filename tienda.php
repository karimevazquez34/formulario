<?php
session_start();
include('config/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : 'calzado';
$producto_seleccionado  = isset($_GET['producto']) ? $_GET['producto'] : '';
$nombre_usuario         = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';

// Se comprueba si acaba de iniciar sesión para mostrar el mensaje superior
$mostrar_bienvenida = false;
if (isset($_SESSION['bienvenida'])) {
    $mostrar_bienvenida = true;
    unset($_SESSION['bienvenida']);
}

// PROCESAR PEDIDO EN LA BASE DE DATOS SQL
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $tipo_form  = $_POST['tipo_form'];
    $producto   = $_POST['producto'];
    $talla      = $_POST['talla'];
    $cantidad   = $_POST['cantidad'];
    $direccion  = $_POST['direccion'];

    $categoria = ($tipo_form == 'calzado') ? 'Calzado' : 'Ropa';

    $sql = "INSERT INTO pedidos (usuario_id, categoria, producto, talla, cantidad, direccion) 
            VALUES ('$usuario_id', '$categoria', '$producto', '$talla', '$cantidad', '$direccion')";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "¡Tu pedido de <b>$producto</b> ($categoria) se ha registrado con éxito!";
        $producto_seleccionado = '';
    } else {
        $error = "Error al procesar el pedido: " . $conexion->error;
    }
}

// CATÁLOGO DE CALZADO CON IMÁGENES LOCALES
$calzado_items = [
    ["nombre" => "Adidas Samba OG Pink", "img" => "adidas.webp"],
    ["nombre" => "Adidas Gazelle Bold Mint/Pink", "img" => "gazelle.jpg"],
    ["nombre" => "Adidas Ultraboost 1.0 All White", "img" => "ultraboost.avif"],
    ["nombre" => "Adidas Campus 00s Pink", "img" => "campus.jpg"],
    ["nombre" => "Adidas Forum Low White", "img" => "forum.avif"],
    ["nombre" => "Adidas Superstar XLG Black", "img" => "superstar.avif"],
    ["nombre" => "Adidas Stan Smith Valentine Heart", "img" => "stansmith.jpg"],
    ["nombre" => "Adidas Adilette Aqua Slides Pink", "img" => "aquaslides.jpg"]
];

// CATÁLOGO DE ROPA CON TUS NUEVAS IMÁGENES LOCALES
$ropa_items = [
    ["nombre" => "Chamarra Track Jacket Azul", "img" => "azul.webp"],
    ["nombre" => "Playera Oversized Trefoil", "img" => "camisa.jpg"],
    ["nombre" => "Conjunto Deportivo Top & Pants Navy", "img" => "cd.jpg"],
    ["nombre" => "Conjunto Deportivo Completo Azul", "img" => "conjunto.webp"],
    ["nombre" => "Chamarra & Pants Guinda Vintage", "img" => "guinda.webp"],
    ["nombre" => "Chamarra Track Jacket Negra", "img" => "negra.avif"],
    ["nombre" => "Conjunto Tracksuit Rojo Classic", "img" => "rojo.jpg"],
    ["nombre" => "Sudadera Cozy Fleece Azul Pasteles", "img" => "sudaderaazul.avif"]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adidas Store - Tienda Pastel</title>
</head>
<body bgcolor="#FFF0F5" text="#4A4A4A">

    <!-- BARRA ENCABEZADO -->
    <table border="0" width="100%" cellpadding="15" cellspacing="0" bgcolor="#F8C8DC">
        <tr>
            <td align="left">
                <h1><font color="#6B4C57" face="Segoe UI, Arial">ADIDAS STORE</font></h1>
            </td>
            <td align="right">
                <font color="#705060" face="Segoe UI, Arial">
                    Hola, <b><?php echo htmlspecialchars($nombre_usuario); ?></b> | 
                    <a href="logout.php" style="text-decoration: none;"><font color="#B56576"><b>Cerrar Sesión</b></font></a>
                </font>
            </td>
        </tr>
    </table>

    <br>

    <!-- MENSAJE DE BIENVENIDA SUPERIOR -->
    <?php if ($mostrar_bienvenida) { ?>
        <table border="1" align="center" cellpadding="12" cellspacing="0" bgcolor="#F8C8DC" bordercolor="#F3D1DC" width="600">
            <tr>
                <td align="center">
                    <font color="#6B4C57" face="Segoe UI, Arial" size="4">
                        <b>¡Bienvenido/a de nuevo, <?php echo htmlspecialchars($nombre_usuario); ?>!</b>
                    </font>
                </td>
            </tr>
        </table>
        <br>
    <?php } ?>

    <!-- MENSAJE DE CONFIRMACIÓN DE PEDIDO -->
    <?php if (isset($mensaje)) { ?>
        <table border="1" align="center" cellpadding="10" cellspacing="0" bgcolor="#D4EDDA" bordercolor="#C3E6CB" width="600">
            <tr>
                <td align="center">
                    <font color="#155724" face="Segoe UI, Arial"><?php echo $mensaje; ?></font>
                </td>
            </tr>
        </table>
        <br>
    <?php } ?>

    <!-- MENÚ DE NAVEGACIÓN ENTRE CATEGORÍAS -->
    <table border="0" align="center" cellpadding="0" cellspacing="5" width="600">
        <tr>
            <td align="center" bgcolor="<?php echo ($categoria_seleccionada == 'calzado') ? '#F8C8DC' : '#FFFFFF'; ?>" width="50%">
                <a href="tienda.php?categoria=calzado" style="text-decoration: none;">
                    <font color="#6B4C57" face="Segoe UI, Arial"><b>VER CALZADO</b></font>
                </a>
            </td>
            <td align="center" bgcolor="<?php echo ($categoria_seleccionada == 'ropa') ? '#F8C8DC' : '#FFFFFF'; ?>" width="50%">
                <a href="tienda.php?categoria=ropa" style="text-decoration: none;">
                    <font color="#6B4C57" face="Segoe UI, Arial"><b>VER ROPA</b></font>
                </a>
            </td>
        </tr>
    </table>

    <br>

    <!-- CATÁLOGO VISUAL CON HIPERVÍNCULO ÚNICAMENTE EN LAS IMÁGENES -->
    <h2 align="center"><font color="#D87093" face="Segoe UI, Arial">Catálogo de <?php echo ucfirst($categoria_seleccionada); ?></font></h2>

    <table border="0" align="center" cellpadding="8" cellspacing="8">
        <?php 
        $items = ($categoria_seleccionada == 'calzado') ? $calzado_items : $ropa_items;
        $contador = 0;
        
        foreach ($items as $item) {
            if ($contador % 4 == 0) {
                echo "<tr>";
            }
            $link_compra = "tienda.php?categoria=" . $categoria_seleccionada . "&producto=" . urlencode($item['nombre']) . "#formulario";
        ?>
            <td align="center">
                <table border="2" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" bordercolor="#F3D1DC" width="200">
                    <tr>
                        <td align="center">
                            <!-- ÚNICAMENTE LA IMAGEN COMO HIPERVÍNCULO HREF -->
                            <a href="<?php echo $link_compra; ?>">
                                <img src="<?php echo $item['img']; ?>" width="170" height="150" border="0" alt="<?php echo $item['nombre']; ?>">
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        <?php 
            $contador++;
            if ($contador % 4 == 0) {
                echo "</tr>";
            }
        } 
        ?>
    </table>

    <br><hr color="#F8C8DC" size="2" width="85%"><br>

    <!-- FORMULARIO DE COMPRA -->
    <?php if (!empty($producto_seleccionado)) { ?>
        <a name="formulario"></a>
        <table border="2" align="center" cellpadding="15" cellspacing="0" bgcolor="#FFFFFF" bordercolor="#F3D1DC" width="500">
            <tr>
                <td>
                    <h3 align="center"><font color="#D87093" face="Segoe UI, Arial">Formulario de Pedido</font></h3>
                    <p align="center"><font color="#705060" face="Segoe UI, Arial">Producto seleccionado: <b><?php echo htmlspecialchars($producto_seleccionado); ?></b></font></p>
                    <hr color="#F8C8DC" size="1">

                    <form action="tienda.php" method="POST">
                        <input type="hidden" name="tipo_form" value="<?php echo $categoria_seleccionada; ?>">
                        <input type="hidden" name="producto" value="<?php echo htmlspecialchars($producto_seleccionado); ?>">

                        <p>
                            <label><font color="#705060" face="Segoe UI, Arial"><b>Talla:</b></font></label><br>
                            <select name="talla" required>
                                <option value="" disabled selected>-- Selecciona Talla --</option>
                                <?php if ($categoria_seleccionada == 'calzado') { ?>
                                    <option value="22.5 MX">22.5 MX</option>
                                    <option value="23 MX">23 MX</option>
                                    <option value="23.5 MX">23.5 MX</option>
                                    <option value="24 MX">24 MX</option>
                                    <option value="24.5 MX">24.5 MX</option>
                                    <option value="25 MX">25 MX</option>
                                    <option value="25.5 MX">25.5 MX</option>
                                    <option value="26 MX">26 MX</option>
                                <?php } else { ?>
                                    <option value="XS">Extra Chica (XS)</option>
                                    <option value="S">Chica (S)</option>
                                    <option value="M">Mediana (M)</option>
                                    <option value="L">Grande (L)</option>
                                    <option value="XL">Extra Grande (XL)</option>
                                <?php } ?>
                            </select>
                        </p>

                        <p>
                            <label><font color="#705060" face="Segoe UI, Arial"><b>Cantidad:</b></font></label><br>
                            <input type="number" name="cantidad" value="1" min="1" max="10" required>
                        </p>

                        <p>
                            <label><font color="#705060" face="Segoe UI, Arial"><b>Dirección de Envío:</b></font></label><br>
                            <textarea name="direccion" rows="3" cols="35" placeholder="Calle, número, colonia y C.P." minlength="10" required></textarea>
                        </p>

                        <p align="center">
                            <input type="submit" value="Confirmar y Comprar">
                        </p>
                    </form>
                </td>
            </tr>
        </table>
    <?php } ?>

</body>
</html>