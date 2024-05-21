<?php
session_start();
include '../partials/header.php';
$mysqli = new mysqli("db", "Jannie.Hamberg@medieinstitutet.se", "userpassword", "test");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT * FROM newsletters";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>All Newsletters</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        if (isset($_SESSION['user_id'])) {
            $subscriber_id = $_SESSION['user_id'];
            $newsletter_id = $row['id'];
            $checkSubscription = "SELECT * FROM subscriptions WHERE subscriber_id = $subscriber_id AND newsletter_id = $newsletter_id";
            $subscriptionResult = $mysqli->query($checkSubscription);
            if ($subscriptionResult->num_rows > 0) {
                echo "<form method='POST' action='unsubscribe.php'>";
                echo "<input type='hidden' name='newsletter_id' value='" . $row['id'] . "' />";
                echo "<input type='submit' value='Unsubscribe' />";
                echo "</form>";
            } else {
                echo "<form method='POST' action='subscribe.php'>";
                echo "<input type='hidden' name='newsletter_id' value='" . $row['id'] . "' />";
                echo "<input type='submit' value='Subscribe' />";
                echo "</form>";
            }
        }
        echo "</div>";
    }
} else {
    echo "No newsletters found.";
}
$mysqli->close();
include '../partials/footer.php';
?>
