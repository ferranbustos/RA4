<?php
session_start();

// Si el array no existe, lo creamos con 3 números
if (!isset($_SESSION['numeros'])) {
    $_SESSION['numeros'] = [10, 20, 30];
}

$mensaje = "";
$media = null;

// Si se pulsa modificar
if (isset($_POST['modificar'])) {
    $posicion = intval($_POST['posicion']);
    $nuevo = intval($_POST['nuevo_valor']);

    if (isset($_SESSION['numeros'][$posicion])) {
        $_SESSION['numeros'][$posicion] = $nuevo;
        $mensaje = "Valor de la posición $posicion cambiado a $nuevo.";
    }
}

// Si se pulsa calcular media
if (isset($_POST['media'])) {
    $media = array_sum($_SESSION['numeros']) / count($_SESSION['numeros']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modificar Array</title>
</head>
<body>

    <h1>Modificar valores de un array</h1>

    <p>Valores actuales: <?= implode(", ", $_SESSION['numeros']) ?></p>

    <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>

    <form method="post">
        <label>Posición a cambiar:</label>
        <select name="posicion">
            <?php foreach ($_SESSION['numeros'] as $i => $num): ?>
                <option value="<?= $i ?>">Posición <?= $i ?> (<?= $num ?>)</option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Nuevo valor:</label>
        <input type="number" name="nuevo_valor" required>
        <br><br>

        <button type="submit" name="modificar">Modificar</button>
        <button type="submit" name="media">Calcular media</button>
    </form>

    <?php if ($media !== null): ?>
        <h2>Media: <?= number_format($media, 2) ?></h2>
    <?php endif; ?>

</body>
</html>

