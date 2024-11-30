<?php

$host = '192.168.199.13';
$user = 'learn';
$password = 'learn';
$database = 'learn_stavtsev364';

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);

    $insert_sql = "INSERT INTO products (name, category, price) VALUES ('$name', '$category', $price)";

    if (mysqli_query($conn, $insert_sql)) {
        echo "Товар успешно добавлен.";
    } else {
        echo "Ошибка: " . mysqli_error($conn);
    }
}


$category = isset($_POST['category']) ? $_POST['category'] : '';
$min_price = isset($_POST['min_price']) ? $_POST['min_price'] : '';
$max_price = isset($_POST['max_price']) ? $_POST['max_price'] : '';
$search_name = isset($_POST['search_name']) ? $_POST['search_name'] : '';


$sql = "SELECT * FROM products WHERE 1=1";

if ($category !== '') {
    $sql .= " AND category = '".mysqli_real_escape_string($conn, $category)."'";
}

if ($min_price !== '') {
    $sql .= " AND price >= ".floatval($min_price);
}

if ($max_price !== '') {
    $sql .= " AND price <= ".floatval($max_price);
}

if ($search_name !== '') {
    $sql .= " AND name LIKE '%".mysqli_real_escape_string($conn, $search_name)."%'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Фильтрация товаров</title>
</head>
<body>
    <h1>Добавление товара</h1>
    <form method="post" action="">
        <label for="name">Название:</label>
        <input type="text" name="name" id="name" required>
        
        <label for="category">Категория:</label>
        <select name="category" id="category" required>
            <option value="Электроника">Электроника</option>
            <option value="Одежда">Одежда</option>
            <option value="Мебель">Мебель</option>
        </select>
        
        <label for="price">Цена:</label>
        <input type="number" name="price" id="price" required>
        
        <button type="submit" name="add_product">Добавить товар</button>
    </form>

    <h1>Фильтр товаров</h1>

    <form method="post" action="">
        <label for="category">Категория:</label>
        <select name="category" id="category">
            <option value="">Все</option>
            <option value="Электроника" <?php if ($category === 'Электроника') echo 'selected'; ?>>Электроника</option>
            <option value="Одежда" <?php if ($category === 'Одежда') echo 'selected'; ?>>Одежда</option>
            <option value="Мебель" <?php if ($category === 'Мебель') echo 'selected'; ?>>Мебель</option>
        </select>
        
        <label for="min_price">Мин. цена:</label>
        <input type="number" name="min_price" id="min_price" value="<?php echo htmlspecialchars($min_price); ?>">
        
        <label for="max_price">Макс. цена:</label>
        <input type="number" name="max_price" id="max_price" value="<?php echo htmlspecialchars($max_price); ?>">
        
        <label for="search_name">Поиск по имени:</label>
        <input type="text" name="search_name" id="search_name" value="<?php echo htmlspecialchars($search_name); ?>">
        
        <button type="submit">Фильтровать</button>
    </form>

    <h2>Список товаров</h2>
    <ul>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>" . htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['category']) . " - " . htmlspecialchars($row['price']) . " ₽</li>";
            }
        } else {
            echo "<li>Товары не найдены.</li>";
        }
        ?>
    </ul>
</body>
</html>

<?php

mysqli_close($conn);
?>