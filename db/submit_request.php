<?php
include('connect.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_POST); // Выводим данные для проверки
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $arrival_date = mysqli_real_escape_string($conn, $_POST['arrival_date']);
    $departure_date = mysqli_real_escape_string($conn, $_POST['departure_date']);
    $country_id = mysqli_real_escape_string($conn, $_POST['country_id']);
    $route_id = mysqli_real_escape_string($conn, $_POST['route_id']);
    $passengers = (int)$_POST['passengers'];
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $sql = "INSERT INTO `bookings` (name, phone, email, country_id, route_id, arrival_date, departure_date, passengers, total_price) 
    VALUES ('$name', '$phone', '$email', '$country_id', '$route_id', '$arrival_date', '$departure_date', '$passengers', '$price')";
    
if (mysqli_query($conn, $sql)) {
echo "Запрос отправлен успешно";
} else {
echo "Ошибка: " . mysqli_error($conn);
}

// Для отладки: выведем все записи из таблицы bookings
$result = mysqli_query($conn, "SELECT * FROM bookings");
while ($row = mysqli_fetch_assoc($result)) {
echo "Имя: " . $row['name'] . ", Телефон: " . $row['phone'] . ", Email: " . $row['email'] . ", Кол-во пассажиров: " . $row['passengers'] . ", Общая цена: " . $row['total_price'] . "<br>";
}
}

?>
