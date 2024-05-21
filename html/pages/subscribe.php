<?php
session_start();
$mysqli = new mysqli("db", "Jannie.Hamberg@medieinstitutet.se", "userpassword", "test");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subscriber_id = $_SESSION['user_id'];
    $newsletter_id = $_POST['newsletter_id'];

    $sql = "INSERT INTO subscriptions (subscriber_id, newsletter_id) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $subscriber_id, $newsletter_id);

    if ($stmt->execute()) {
        header("Location: all_newsletters.php?subscribed=1");
    } else {
        echo "Error: " . $mysqli->error;
    }
    $stmt->close();
}
$mysqli->close();
?>
