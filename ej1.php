<?php
session_start();


if (!isset($_SESSION['worker'])) {
    $_SESSION['worker'] = "";
}

if (!isset($_SESSION['inventory'])) {
    $_SESSION['inventory'] = [
        'milk' => 10,
        'soft_drink' => 10
    ];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    if (!empty($_POST['worker'])) {
        $_SESSION['worker'] = htmlspecialchars($_POST['worker']);
    }

 
    if (!empty($_POST['quantity']) && !empty($_POST['product'])) {
        $product = $_POST['product'];
        $quantity = intval($_POST['quantity']);
        $action = $_POST['action'];

        if ($action == 'add') {
            $_SESSION['inventory'][$product] += $quantity;
        } elseif ($action == 'remove') {
            if ($_SESSION['inventory'][$product] >= $quantity) {
                $_SESSION['inventory'][$product] -= $quantity;
            } else {
                $_SESSION['inventory'][$product] = 0; 
            }
        } elseif ($action == 'reset') {
            $_SESSION['inventory'] = [
                'milk' => 10,
                'soft_drink' => 10
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket management</title>
</head>
<body>

    <h1>Supermarket management</h1>

    <form method="post">
        <label>Worker name: </label>
        <input type="text" name="worker" value="<?php $_SESSION['worker'] ?>" required>
        <br><br>

        <label>Choose product:</label>
        <select name="product">
            <option value="soft_drink">Soft Drink</option>
            <option value="milk">Milk</option>
        </select>
        <br><br>

        <label>Product quantity:</label>
        <input type="number" name="quantity" min="1" required>
        <br><br>

        <button type="submit" name="action" value="add">add</button>
        <button type="submit" name="action" value="remove">remove</button>
        <button type="submit" name="action" value="reset">reset</button>
    </form>

    <h2>Inventory:</h2>
    <p>worker: <?= $_SESSION['worker'] ?></p>
    <p>units milk: <?= $_SESSION['inventory']['milk'] ?></p>
    <p>units soft drink: <?= $_SESSION['inventory']['soft_drink'] ?></p>

</body>
</html>
