<?php
session_start();
include 'db/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : null;

    if ($booking_id) {
        $delete_sql = "DELETE FROM bookings WHERE id=$booking_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Запись о бронировании удалена.";
        } else {
            echo "Ошибка удаления бронирования: " . $conn->error;
        }
    } else {
        echo "Некорректный ID бронирования.";
    }
}

$conn->close();
?>
