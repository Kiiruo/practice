<?php
$servername = 'asd';
$username = 'root';
$password = '';
$dbname = 'tour-agency';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    die("Connection failed". mysqli_connect_error());
}

?>
