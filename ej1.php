<?php 
session_start();

// Guardamos el nombre del trabajador si aún no está
if (!isset($_SESSION['worker'])) {
    $_SESSION['worker'] = "";
}

// Guardamos el inventario inicial si aún no está
if (!isset($_SESSION['inventory'])) {
    $_SESSION['inventory'] = [
        'milk' => 10,
        'soft_drink' => 10
    ];
}

$error = ""; // Variable para mostrar errores
$success = ""; // Mensaje de éxito

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['worker'])) {
        $_SESSION['worker'] = htmlspecialchars($_POST['worker']);
    }

    if (!empty($_POST['quantity']) && !empty($_POST['product']) && !empty($_POST['action'])) {
        $product = $_POST['product'];
        $quantity = intval($_POST['quantity']);
        $action = $_POST['action'];

        if ($action == 'add') {
            $_SESSION['inventory'][$product] += $quantity;
            $success = "Se han añadido $quantity unidades de $product.";
        } elseif ($action == 'remove') {
            if ($_SESSION['inventory'][$product] >= $quantity) {
                $_SESSION['inventory'][$product] -= $quantity;
                $success = "Se han quitado $quantity unidades de $product.";
            } else {
                $error = "ERROR: No puedes quitar más unidades de las que hay.";
            }
        } elseif ($action == 'reset') {
            $_SESSION['inventory'] = [
                'milk' => 10,
                'soft_drink' => 10
            ];
            $success = "Inventario reiniciado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de supermercado</title>
</head>
<body>

    <h1>Gestión de supermercado</h1>

    <!-- Formulario principal -->
    <form method="post">
        <label>Nombre del trabajador: </label>
        <input type="text" name="worker" value="<?= $_SESSION['worker'] ?>" required>
        <br><br>

        <label>Producto:</label>
        <select name="product">
            <option value="soft_drink">Refresco</option>
            <option value="milk">Leche</option>
        </select>
        <br><br>

        <label>Cantidad:</label>
        <input type="number" name="quantity" min="1" required>
        <br><br>

        <button type="submit" name="action" value="add">Añadir</button>
        <button type="submit" name="action" value="remove">Quitar</button>
        <button type="submit" name="action" value="reset">Reiniciar</button>
    </form>

    <hr>

    <!-- Mostramos mensajes visuales si hay -->
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php endif; ?>

    <!-- Mostramos el estado del inventario -->
    <h2>Inventario actual:</h2>
    <p>Trabajador: <strong><?= $_SESSION['worker'] ?></strong></p>
    <p>Leche: <?= $_SESSION['inventory']['milk'] ?> unidades</p>
    <p>Refrescos: <?= $_SESSION['inventory']['soft_drink'] ?> unidades</p>

</body>
</html>
