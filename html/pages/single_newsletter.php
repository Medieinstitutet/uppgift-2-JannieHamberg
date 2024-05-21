<?php
session_start();
include dirname(__DIR__, 1) . '/db_data/auth.php';
checkLogin();
include dirname(__DIR__, 1) . '/db_data/database.php';
include '../partials/header.php';


$mysqli = connectDB();

if (isset($_GET['id'])) {
    $newsletterId = $_GET['id'];

    $stmt = $mysqli->prepare("SELECT id, title, description FROM newsletters WHERE id = ?");
    $stmt->bind_param("i", $newsletterId);
    $stmt->execute();
    $result = $stmt->get_result();
    $newsletter = $result->fetch_assoc();

    if ($newsletter) {
        echo '<main class="mt-10 mx-auto max-w-4xl">';
        echo '<h2 class="text-2xl font-bold mb-5">' . htmlspecialchars($newsletter['title']) . '</h2>';
        echo '<p>' . htmlspecialchars($newsletter['description']) . '</p>';

        if (isset($_SESSION['user_id'])) {
            $subscriber_id = $_SESSION['user_id'];
            $checkSubscription = "SELECT * FROM subscriptions WHERE subscriber_id = ? AND newsletter_id = ?";
            $stmt = $mysqli->prepare($checkSubscription);
            $stmt->bind_param("ii", $subscriber_id, $newsletterId);
            $stmt->execute();
            $subscriptionResult = $stmt->get_result();

            if ($subscriptionResult->num_rows > 0) {
                echo '<form method="POST" action="unsubscribe.php">';
                echo '<input type="hidden" name="newsletter_id" value="' . $newsletter['id'] . '" />';
                echo '<button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md">Unsubscribe</button>';
                echo '</form>';
            } else {
                echo '<form method="POST" action="subscribe.php">';
                echo '<input type="hidden" name="newsletter_id" value="' . $newsletter['id'] . '" />';
                echo '<button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Subscribe</button>';
                echo '</form>';
            }
        }
        echo '</main>';
    } else {
        echo '<p>Newsletter not found.</p>';
    }
    $stmt->close();
} else {
    echo '<p>No newsletter ID provided.</p>';
}
$mysqli->close();

include '../partials/footer.php';
?>
