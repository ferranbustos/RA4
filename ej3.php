<?php
session_start();

if (!isset($_SESSION['lista'])) {
    $_SESSION['lista'] = [];
}

$mensaje = "";
$total = 0;
$modo_editar = false;
$editar_indice = -1;

// Añadir producto
if (isset($_POST['añadir'])) {
    $nombre = $_POST['nombre'];
    $cantidad = intval($_POST['cantidad']);
    $precio = floatval($_POST['precio']);

    if ($nombre && $cantidad > 0 && $precio > 0) {
        $_SESSION['lista'][] = [
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'precio' => $precio,
            'total' => $cantidad * $precio
        ];
        $mensaje = "Producto añadido.";
    }
}

// Preparar edición
if (isset($_POST['editar'])) {
    $editar_indice = $_POST['indice'];
    $modo_editar = true;
}

// Guardar cambios al producto
if (isset($_POST['actualizar'])) {
    $indice = $_POST['indice'];
    $cantidad = intval($_POST['cantidad']);
    $precio = floatval($_POST['precio']);

    if (isset($_SESSION['lista'][$indice])) {
        $_SESSION['lista'][$indice]['cantidad'] = $cantidad;
        $_SESSION['lista'][$indice]['precio'] = $precio;
        $_SESSION['lista'][$indice]['total'] = $cantidad * $precio;
        $mensaje = "Producto actualizado.";
    }
}

// Borrar producto
if (isset($_POST['borrar'])) {
    $indice = $_POST['indice'];
    unset($_SESSION['lista'][$indice]);
    $_SESSION['lista'] = array_values($_SESSION['lista']);
    $mensaje = "Producto eliminado.";
}

// Calcular total
foreach ($_SESSION['lista'] as $item) {
    $total += $item['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de la Compra</title>
</head>
<body>

    <h1>Lista de la compra</h1>

    <?php if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

    <?php if (!$modo_editar): ?>
        <form method="post">
            <label>Producto:</label>
            <input type="text" name="nombre" required>
            <label>Cantidad:</label>
            <input type="number" name="cantidad" min="1" required>
            <label>Precio:</label>
            <input type="number" name="precio" step="0.01" min="0.01" required>
            <button type="submit" name="añadir">Añadir</button>
        </form>
    <?php else: ?>
        <form method="post">
            <input type="hidden" name="indice" value="<?= $editar_indice ?>">
            <label>Nueva cantidad:</label>
            <input type="number" name="cantidad" min="1" value="<?= $_SESSION['lista'][$editar_indice]['cantidad'] ?>" required>
            <label>Nuevo precio:</label>
            <input type="number" name="precio" step="0.01" value="<?= $_SESSION['lista'][$editar_indice]['precio'] ?>" required>
            <button type="submit" name="actualizar">Actualizar</button>
        </form>
    <?php endif; ?>

    <h2>Productos:</h2>
    <table border="1">
        <tr>
            <th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Total</th><th>Acciones</th>
        </tr>
        <?php foreach ($_SESSION['lista'] as $i => $prod): ?>
            <tr>
                <td><?= $prod['nombre'] ?></td>
                <td><?= $prod['cantidad'] ?></td>
                <td><?= number_format($prod['precio'], 2) ?> €</td>
                <td><?= number_format($prod['total'], 2) ?> €</td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="indice" value="<?= $i ?>">
                        <button type="submit" name="editar">Editar</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="indice" value="<?= $i ?>">
                        <button type="submit" name="borrar">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Total de la lista: <?= number_format($total, 2) ?> €</h3>

</body>
</html>
