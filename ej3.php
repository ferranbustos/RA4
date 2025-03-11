<?php
session_start();

// Inicializar la lista de la compra si no existe
if (!isset($_SESSION['lista_compra'])) {
    $_SESSION['lista_compra'] = [];
}

$total_compra = 0;

// Agregar un nuevo ítem a la lista
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['cantidad']) && isset($_POST['precio'])) {
        $nombre = htmlspecialchars($_POST['nombre']);
        $cantidad = intval($_POST['cantidad']);
        $precio = floatval($_POST['precio']);
        
        if (!empty($nombre) && $cantidad > 0 && $precio > 0) {
            $_SESSION['lista_compra'][] = [
                'nombre' => $nombre,
                'cantidad' => $cantidad,
                'precio' => $precio,
                'total' => $cantidad * $precio
            ];
        }
    }

    // Editar un ítem
    if (isset($_POST['editar_indice']) && isset($_POST['editar_cantidad']) && isset($_POST['editar_precio'])) {
        $indice = intval($_POST['editar_indice']);
        $nueva_cantidad = intval($_POST['editar_cantidad']);
        $nuevo_precio = floatval($_POST['editar_precio']);

        if (isset($_SESSION['lista_compra'][$indice])) {
            $_SESSION['lista_compra'][$indice]['cantidad'] = $nueva_cantidad;
            $_SESSION['lista_compra'][$indice]['precio'] = $nuevo_precio;
            $_SESSION['lista_compra'][$indice]['total'] = $nueva_cantidad * $nuevo_precio;
        }
    }

    // Eliminar un ítem
    if (isset($_POST['borrar_indice'])) {
        $indice = intval($_POST['borrar_indice']);
        if (isset($_SESSION['lista_compra'][$indice])) {
            unset($_SESSION['lista_compra'][$indice]);
            $_SESSION['lista_compra'] = array_values($_SESSION['lista_compra']); // Reindexar
        }
    }
}

// Calcular el total de la compra
foreach ($_SESSION['lista_compra'] as $item) {
    $total_compra += $item['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de la Compra</title>
</head>
<body>

    <h1>Lista de la Compra</h1>

    <form method="post">
        <label>Nombre del producto:</label>
        <input type="text" name="nombre" required>
        <br><br>

        <label>Cantidad:</label>
        <input type="number" name="cantidad" min="1" required>
        <br><br>

        <label>Precio por unidad:</label>
        <input type="number" step="0.01" name="precio" min="0.01" required>
        <br><br>

        <button type="submit">Añadir Producto</button>
    </form>

    <h2>Productos en la Lista</h2>

    <table border="1">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Coste Total</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($_SESSION['lista_compra'] as $indice => $item): ?>
        <tr>
            <td><?= $item['nombre'] ?></td>
            <td><?= $item['cantidad'] ?></td>
            <td><?= number_format($item['precio'], 2) ?>€</td>
            <td><?= number_format($item['total'], 2) ?>€</td>
            <td>
                <!-- Formulario para editar -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="editar_indice" value="<?= $indice ?>">
                    <input type="number" name="editar_cantidad" min="1" value="<?= $item['cantidad'] ?>" required>
                    <input type="number" step="0.01" name="editar_precio" min="0.01" value="<?= $item['precio'] ?>" required>
                    <button type="submit">Editar</button>
                </form>

                <!-- Formulario para eliminar -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="borrar_indice" value="<?= $indice ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Total de la compra: <?= number_format($total_compra, 2) ?>€</h3>

</body>
</html>
