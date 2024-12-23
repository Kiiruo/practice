<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_id = isset($_POST['route_id']) ? (int)$_POST['route_id'] : null;

    if ($route_id) {
        // Сначала получаем идентификатор страны
        $sql = "SELECT country_id FROM routes WHERE id = $route_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $country_id = $row['country_id'];

            // Удаление маршрута
            $sql = "DELETE FROM routes WHERE id = $route_id";
            if ($conn->query($sql) === TRUE) {
                // Проверяем, есть ли другие маршруты для этой страны
                $checkSql = "SELECT COUNT(*) as count FROM routes WHERE country_id = $country_id";
                $checkResult = $conn->query($checkSql);
                $checkRow = $checkResult->fetch_assoc();

                // Если маршрутов больше нет, удаляем страну
                if ($checkRow['count'] == 0) {
                    $deleteCountrySql = "DELETE FROM countries WHERE id = $country_id";
                    if ($conn->query($deleteCountrySql) === TRUE) {
                        echo "Маршрут и страна успешно удалены.";
                    } else {
                        echo "Ошибка удаления страны: " . $conn->error;
                    }
                } else {
                    echo "Маршрут успешно удален, страна не была удалена, так как у нее есть другие маршруты.";
                }
            } else {
                echo "Ошибка удаления маршрута: " . $conn->error;
            }
        } else {
            echo "Маршрут не найден.";
        }
    } else {
        echo "Ошибка: ID маршрута не задан.";
    }
}

$conn->close();
?>
