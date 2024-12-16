<?php
include 'connect.php'; // Подключение к базе данных
if (isset($_GET['country_id'])) {
    $country_id = (int)$_GET['country_id'];

    $stmt = mysqli_prepare($conn, "SELECT id, name, price_per_passenger FROM routes WHERE country_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $country_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $routes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $routes[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode(['routes' => $routes]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка обработки запроса.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Отсутствует параметр country_id.']);
}
