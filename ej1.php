<?php
session_start();

// Si es la primera vez que se entra, se guardan estos valores
if (!isset($_SESSION['trabajador'])) {
    $_SESSION['trabajador'] = "";
}

if (!isset($_SESSION['productos'])) {
    $_SESSION['productos'] = [
        "leche" => 10,
        "refresco" => 10
    ];
}

$mensaje = ""; // Para mostrar texto al usuario

// Cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Guardar el nombre del trabajador
    if (!empty($_POST["trabajador"])) {
        $_SESSION["trabajador"] = $_POST["trabajador"];
    }

    // Recoger producto, cantidad y acción
    $producto = $_POST["producto"];
    $cantidad = intval($_POST["cantidad"]);
    $accion = $_POST["accion"];

    if ($accion == "añadir") {
        $_SESSION["productos"][$producto] += $cantidad;
        $mensaje = "Se han añadido $cantidad unidades de $producto.";
    }

    if ($accion == "quitar") {
        if ($_SESSION["productos"][$producto] >= $cantidad) {
            $_SESSION["productos"][$producto] -= $cantidad;
            $mensaje = "Se han quitado $cantidad unidades de $producto.";
        } else {
            $mensaje = "Error: No hay suficientes unidades de $producto.";
        }
    }

    if ($accion == "reiniciar") {
        $_SESSION['productos'] = [
            "leche" => 10,
            "refresco" => 10
        ];
        $mensaje = "Inventario reiniciado.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inventario Supermercado</title>
</head>
<body>

    <h1>Gestión de inventario</h1>

    <form method="post">
        <label>Nombre del trabajador:</label>
        <input type="text" name="trabajador" value="<?= $_SESSION['trabajador'] ?>" required>
        <br><br>

        <label>Producto:</label>
        <select name="producto">
            <option value="leche">Leche</option>
            <option value="refresco">Refresco</option>
        </select>
        <br><br>

        <label>Cantidad:</label>
        <input type="number" name="cantidad" min="1" required>
        <br><br>

        <button type="submit" name="accion" value="añadir">Añadir</button>
        <button type="submit" name="accion" value="quitar">Quitar</button>
        <button type="submit" name="accion" value="reiniciar">Reiniciar</button>
    </form>

    <h2>Estado del inventario:</h2>
    <p>Trabajador: <?= $_SESSION["trabajador"] ?></p>
    <p>Leche: <?= $_SESSION["productos"]["leche"] ?> unidades</p>
    <p>Refresco: <?= $_SESSION["productos"]["refresco"] ?> unidades</p>

    <?php if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

</body>
</html>
