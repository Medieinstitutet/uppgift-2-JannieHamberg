<?php
session_start();
include dirname(__DIR__, 1) . '/db_data/auth.php';


checkLogin();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    die("You must be logged in as a customer to delete a newsletter.");
}

include dirname(__DIR__, 1) . '/db_data/database.php';

$mysqli = connectDB();

if (isset($_GET['id'])) {
    $newsletterId = $_GET['id'];

    $stmt = $mysqli->prepare("DELETE FROM newsletters WHERE id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $newsletterId, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Newsletter deleted successfully.";
        $stmt->close();
        $mysqli->close();

        header('Location: my_newsletter.php');
        exit();
    } else {
        $_SESSION['message'] = "Error deleting newsletter: " . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
