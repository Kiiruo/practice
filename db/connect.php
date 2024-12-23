<?php
$servername = 'practice-main';
$username = 'root';
$password = '';
$dbname = 'tour_agency';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    die("Connection failed". mysqli_connect_error());
}

?>
