<?php

$user = "root";
$password = "";
$host = "localhost";
$dbname = "nearbyyourlocation";

$db = mysqli_connect($host, $user, $password)
or die("Keine Verbindung!");

mysqli_select_db($db, $dbname);
echo "<script>console.log('Zugriff db ok') </script>";



?>