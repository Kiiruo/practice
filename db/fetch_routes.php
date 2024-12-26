<?php
include 'connect.php';

$sql = "SELECT r.id, r.name, r.price_per_passenger, c.name AS country_name 
        FROM routes r 
        JOIN countries c ON r.country_id = c.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<li>';
        echo '<span>' . $row['country_name'] . ': ' . $row['name'] . ' - ' . $row['price_per_passenger'] . ' р.</span>';
        echo '<button onclick="editRoute(' . $row['id'] . ', \'' . $row['country_name'] . '\', \'' . $row['name'] . '\', ' . $row['price_per_passenger'] . ')">Редактировать</button>';
        echo '<button onclick="deleteRoute(' . $row['id'] . ')" style="background-color: red; margin-left: 10px;">Удалить</button>';
        echo '</li>';
    }
} else {
    echo '<li>Нет доступных маршрутов.</li>';
}

$conn->close();
?>
