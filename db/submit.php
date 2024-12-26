<?php
include 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $route = $_POST['route'];
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];
    $passengers = (int)$_POST['passengers'];

    $stmt = mysqli_prepare($conn, "SELECT price_per_passenger FROM routes WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $route);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $routeData = mysqli_fetch_assoc($result);

    if ($routeData) {
        $pricePerPassenger = $routeData['price_per_passenger'];
        $totalPrice = $pricePerPassenger * $passengers;
        $stmt = mysqli_prepare($conn, "INSERT INTO `bookings` (name, phone, email, country_id, route_id, arrival_date, departure_date, passengers, total_price) VALUES ('$name','$phone','$email','$country','$route','$arrival','$departure','$passengers','$totalPrice')");
        mysqli_stmt_execute($stmt);
    }
}
?>