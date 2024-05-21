<?php
require 'database.php';

function getUserRole($userId) {
    $mysqli = connectDB();
    $sql = "SELECT role FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['role'];
    } else {
        return false;
    }
}
