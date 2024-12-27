<?php
session_start();
include 'db/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Удаление маршрута
    if (isset($_POST['route_id'])) {
        $route_id = (int)$_POST['route_id'];
        $delete_route_sql = "DELETE FROM routes WHERE id = $route_id";
        if ($conn->query($delete_route_sql) === TRUE) {
            echo "Маршрут успешно удалён.";
        } else {
            echo "Ошибка при удалении маршрута: " . $conn->error;
        }
        exit; // Завершаем выполнение
    }

    if (isset($_POST['caller_id'])) {
        $caller_id = (int)$_POST['caller_id'];
        $delete_caller_sql = "DELETE FROM callers WHERE id = $caller_id";
        if ($conn->query($delete_caller_sql) === TRUE) {
            echo "Звонок успешно удалён.";
        } else {
            echo "Ошибка при удалении звонка: " . $conn->error;
        }
        exit; // Завершаем выполнение
    }

    if (isset($_POST['booking_id'])) {
        $booking_id = (int)$_POST['booking_id'];
        $delete_booking_sql = "DELETE FROM bookings WHERE id = $booking_id";
        if ($conn->query($delete_booking_sql) === TRUE) {
            echo "Бронирование успешно удалено.";
        } else {
            echo "Ошибка при удалении бронирования: " . $conn->error;
        }
        exit;
    }


    $route_id = isset($_POST['route_id']) ? (int)$_POST['route_id'] : null;
    $country_name = $conn->real_escape_string($_POST['country_name']);
    $route_name = $conn->real_escape_string($_POST['route_name']);
    $price_per_passenger = (float)$_POST['price_per_passenger'];


    $imageFileName = null;


    if (isset($_FILES['route_image']) && $_FILES['route_image']['error'] === UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($_FILES['route_image']['name'], PATHINFO_EXTENSION));
        $imageFileName = strtolower(str_replace(' ', '_', $country_name)) . '.' . $imageFileType;
        $targetDirectory = 'img/';
        
        if (!move_uploaded_file($_FILES['route_image']['tmp_name'], $targetDirectory . $imageFileName)) {
            echo "Ошибка при загрузке изображения.";
            exit;
        }
    }

    $country_sql = "SELECT id FROM countries WHERE name='$country_name'";
    $country_result = $conn->query($country_sql);

    if ($country_result) {
        if ($country_result->num_rows > 0) {
            $country_row = $country_result->fetch_assoc();
            $country_id = $country_row['id'];
        } else {
            $insert_country_sql = "INSERT INTO countries (name) VALUES ('$country_name')";
            if ($conn->query($insert_country_sql) === TRUE) {
                $country_id = $conn->insert_id;
            } else {
                echo "Ошибка добавления страны: " . $conn->error;
                exit;
            }
        }
    } else {
        echo "Ошибка выполнения запроса на выбор страны: " . $conn->error;
        exit;
    }

    $sql = "";
    if ($route_id) {
        if ($imageFileName) {
            $sql = "UPDATE routes SET country_id='$country_id', name='$route_name', price_per_passenger='$price_per_passenger' WHERE id=$route_id";
        } else {
            $sql = "UPDATE routes SET country_id='$country_id', name='$route_name', price_per_passenger='$price_per_passenger' WHERE id=$route_id";
        }
    } else {
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
