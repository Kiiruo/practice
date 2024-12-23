<?php
include 'db/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_id = isset($_POST['route_id']) ? (int)$_POST['route_id'] : null;
    $country_name = $conn->real_escape_string($_POST['country_name']);
    $route_name = $conn->real_escape_string($_POST['route_name']);
    $price_per_passenger = (float)$_POST['price_per_passenger'];

    // Обработка загруженного изображения
    $imageFileName = null; // Инициализируем переменную для имени файла

    if (isset($_FILES['route_image']) && $_FILES['route_image']['error'] === UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($_FILES['route_image']['name'], PATHINFO_EXTENSION));
        $imageFileName = strtolower(str_replace(' ', '_', $country_name)) . '.' . $imageFileType;
        $targetDirectory = 'img/';
        // Загрузка файла
        if (!move_uploaded_file($_FILES['route_image']['tmp_name'], to: $targetDirectory . $imageFileName)) {
            echo "Ошибка при загрузке изображения.";
            exit; // Завершаем выполнение, если не удалось загрузить изображение
        } else {
            echo "Изображение успешно загружено: $imageFileName<br>";
        }
    } else {
        echo "Ошибка: Укажите изображение для загрузки.";
        exit; // Завершаем выполнение, если изображение не загружено
    }

    // Сначала проверяем, существует ли страна
    $country_sql = "SELECT id FROM countries WHERE name='$country_name'";
    $country_result = $conn->query($country_sql);

    if ($country_result) {
        if ($country_result->num_rows > 0) {
            // Страна существует, получаем её id
            $country_row = $country_result->fetch_assoc();
            $country_id = $country_row['id'];
            echo "Страна найдена: $country_name (ID: $country_id)<br>";
        } else {
            // Если страны нет, добавляем её в таблицу countries
            $insert_country_sql = "INSERT INTO countries (name) VALUES ('$country_name')";
            
            if ($conn->query($insert_country_sql) === TRUE) {
                $country_id = $conn->insert_id; // Получаем id новой страны
                echo "Страна успешно добавлена: $country_name (ID: $country_id)<br>";
            } else {
                echo "Ошибка добавления страны: " . $conn->error;
                exit;
            }
        }
    } else {
        echo "Ошибка выполнения запроса на выбор страны: " . $conn->error;
        exit;
    }

    // Вставка данных маршрута в таблицу routes
    if ($route_id) {
        // Обновление существующего маршрута
        $sql = "UPDATE routes SET country_id='$country_id', name='$route_name', price_per_passenger='$price_per_passenger' WHERE id=$route_id";
    } else {
        // Создание нового маршрута
        $sql = "INSERT INTO routes (country_id, name, price_per_passenger) VALUES ('$country_id', '$route_name', '$price_per_passenger')";
    }

    if ($conn->query($sql) === TRUE) {
        echo $route_id ? "Маршрут успешно обновлён." : "Маршрут успешно добавлен.";
    } else {
        echo "Ошибка добавления или обновления маршрута: " . $conn->error;
    }
}

$conn->close();
?>
