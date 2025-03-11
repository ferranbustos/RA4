<?php
session_start();

// Inicializar array si no existe en la sesión
if (!isset($_SESSION['numeros'])) {
    $_SESSION['numeros'] = [10, 20, 30];
}

$media = null; // Variable para almacenar la media

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['posicion']) && isset($_POST['nuevo_valor'])) {
        $posicion = intval($_POST['posicion']);
        $nuevo_valor = intval($_POST['nuevo_valor']);

        // Validar que la posición existe en el array
        if (array_key_exists($posicion, $_SESSION['numeros'])) {
            $_SESSION['numeros'][$posicion] = $nuevo_valor;
        }
    }

    if (isset($_POST['calcular_media'])) {
        $media = array_sum($_SESSION['numeros']) / count($_SESSION['numeros']);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Array</title>
</head>
<body>

    <h1>Modificar valores del Array</h1>

    <p>Array actual: <?= implode(", ", $_SESSION['numeros']) ?></p>

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

        <button type="submit">Modificar</button>
    </form>

    <form method="post">
        <button type="submit" name="calcular_media">Calcular Media</button>
    </form>

    <?php if ($media !== null): ?>
        <h2>Media: <?= number_format($media, 2) ?></h2>
    <?php endif; ?>

</body>
</html>
