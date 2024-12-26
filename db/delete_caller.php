<?php
session_start();
include 'db/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $caller_id = isset($_POST['caller_id']) ? (int)$_POST['caller_id'] : null;

    if ($caller_id) {
        $delete_sql = "DELETE FROM callers WHERE id=$caller_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Запись о звонке удалена.";
        } else {
            echo "Ошибка удаления звонка: " . $conn->error;
        }
    } else {
        echo "Некорректный ID звонка.";
    }
}

$conn->close();
?>
