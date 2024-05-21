<?php
function connectDB() {
    $mysqli = new mysqli("db", "Jannie.Hamberg@medieinstitutet.se", "userpassword", "test");
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}
?>
