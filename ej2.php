<?php
session_start();

// Creamos array de números si no está
if (!isset($_SESSION['numeros'])) {
    $_SESSION['numeros'] = [10, 20, 30];
}

$media = null;
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Si se pulsa el botón de modificar
    if (isset($_POST['modificar'])) {
        $posicion = intval($_POST['posicion']);
        $nuevo_valor = intval($_POST['nuevo_valor']);

        if (array_key_exists($posicion, $_SESSION['numeros'])) {
            $_SESSION['numeros'][$posicion] = $nuevo_valor;
            $mensaje = "Se ha modificado la posición $posicion con el valor $nuevo_valor.";
        }
    }

    // Si se pulsa el botón de calcular media
    if (isset($_POST['calcular_media'])) {
        $media = array_sum($_SESSION['numeros']) / count($_SESSION['numeros']);
        $mensaje = "Media calculada correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Array</title>
</head>
<body>

    <h1>Modificar valores del Array</h1>

    <!-- Mostramos el array actual -->
    <p>Array actual: <?= implode(", ", $_SESSION['numeros']) ?></p>

    <!-- Mensaje visual -->
    <?php if (!empty($mensaje)): ?>
        <p style="color: green;"><?= $mensaje ?></p>
    <?php endif; ?>

    <!-- Formulario para modificar -->
    <form method="post">
        <label>Selecciona una posición:</label>
        <select name="posicion">
            <?php foreach ($_SESSION['numeros'] as $indice => $valor): ?>
                <option value="<?= $indice ?>">Posición <?= $indice ?> (<?= $valor ?>)</option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Nuevo valor:</label>
        <input type="number" name="nuevo_valor" required>
        <br><br>

        <button type="submit" name="modificar">Modificar</button>
    </form>

    <!-- Botón para calcular la media -->
    <form method="post">
        <button type="submit" name="calcular_media">Calcular Media</button>
    </form>

    <!-- Mostrar la media si se ha calculado -->
    <?php if ($media !== null): ?>
        <h2>Media: <?= number_format($media, 2) ?></h2>
    <?php endif; ?>

</body>
</html>
