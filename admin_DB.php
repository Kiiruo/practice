<?php
include 'db/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_id = isset($_POST['route_id']) ? (int)$_POST['route_id'] : null;
    $country_name = $conn->real_escape_string($_POST['country_name']);
    $route_name = $conn->real_escape_string($_POST['route_name']);
    $price_per_passenger = (float)$_POST['price_per_passenger'];

    // Инициализация переменной для имени файла изображения
    $imageFileName = null;

    // Обработка загруженного изображения
    if (isset($_FILES['route_image']) && $_FILES['route_image']['error'] === UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($_FILES['route_image']['name'], PATHINFO_EXTENSION));
        $imageFileName = strtolower(str_replace(' ', '_', $country_name)) . '.' . $imageFileType;
        $targetDirectory = 'img/';
        
        // Переместить загруженный файл
        if (!move_uploaded_file($_FILES['route_image']['tmp_name'], $targetDirectory . $imageFileName)) {
            echo "Ошибка при загрузке изображения.";
            exit; // Завершаем выполнение, если не удалось загрузить изображение
        }
    }

    // Сначала проверяем, существует ли страна
    $country_sql = "SELECT id FROM countries WHERE name='$country_name'";
    $country_result = $conn->query($country_sql);

    if ($country_result) {
        if ($country_result->num_rows > 0) {
            // Страна существует, получаем её id
            $country_row = $country_result->fetch_assoc();
            $country_id = $country_row['id'];
        } else {
            // Если страны нет, добавляем её в таблицу countries
            $insert_country_sql = "INSERT INTO countries (name) VALUES ('$country_name')";
            if ($conn->query($insert_country_sql) === TRUE) {
                $country_id = $conn->insert_id; // Получаем id новой страны
            } else {
                echo "Ошибка добавления страны: " . $conn->error;
                exit;
            }
        }
    } else {
        echo "Ошибка выполнения запроса на выбор страны: " . $conn->error;
        exit;
    }

    // Вставка или обновление данных маршрута в таблицу routes
    $sql = "";
    if ($route_id) {
        // Обновление существующего маршрута
        if ($imageFileName) {
            // Если изображение загружено, обновляем вместе с изображением
            $sql = "UPDATE routes SET country_id='$country_id', name='$route_name', price_per_passenger='$price_per_passenger', route_image='$imageFileName' WHERE id=$route_id";
        } else {
            // Если изображение не загружено, не обновляем поле изображения
            $sql = "UPDATE routes SET country_id='$country_id', name='$route_name', price_per_passenger='$price_per_passenger' WHERE id=$route_id";
        }
    } else {
        // Создание нового маршрута
        $sql = "INSERT INTO routes (country_id, name, price_per_passenger, route_image) VALUES ('$country_id', '$route_name', '$price_per_passenger', '$imageFileName')";
    }

    if ($conn->query($sql) === TRUE) {
        echo $route_id ? "Маршрут успешно обновлён." : "Маршрут успешно добавлен.";
    } else {
        echo "Ошибка добавления или обновления маршрута: " . $conn->error;
    }
}

$conn->close();
?>
