<?php
include 'connect.php';

$name = $_POST['name'];
$tel = $_POST['tel'];

$sql = "INSERT INTO `callers` (name, telephone) VALUES ('$name', '$tel')";
$conn -> query($sql)
?>
