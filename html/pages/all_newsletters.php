<?php
include dirname(__DIR__) . '/db_data/auth.php';
checkLogin();
include dirname(__DIR__) . '/db_data/database.php';
include '../partials/header.php';

$mysqli = connectDB();
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$sql = 'SELECT * FROM newsletters';
$result = $mysqli->query($sql);
?>

<main class="mt-10 mx-auto max-w-4xl">
    <h2 class="text-2xl font-bold mb-5">All Newsletters</h2>
    <?php
    if ($result->num_rows > 0) {
        echo '<div class="space-y-4">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="bg-white shadow-md rounded-lg p-6">';
            echo '<h3 class="text-xl font-semibold mb-2"><a href="single_newsletter.php?id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a></h3>';
            echo '<p class="mb-4">' . htmlspecialchars($row['description']) . '</p>';
            if (isset($_SESSION['user_id'])) {
                $subscriber_id = $_SESSION['user_id'];
                $newsletter_id = $row['id'];
                $checkSubscription = "SELECT * FROM subscriptions WHERE subscriber_id = $subscriber_id AND newsletter_id = $newsletter_id";
                $subscriptionResult = $mysqli->query($checkSubscription);
                if ($subscriptionResult->num_rows > 0) {
                    echo '<form method="POST" action="unsubscribe.php">';
                    echo '<input type="hidden" name="newsletter_id" value="' . $row['id'] . '" />';
                    echo '<button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md">Unsubscribe</button>';
                    echo '</form>';
                } else {
                    echo '<form method="POST" action="subscribe.php">';
                    echo '<input type="hidden" name="newsletter_id" value="' . $row['id'] . '" />';
                    echo '<button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Subscribe</button>';
                    echo '</form>';
                }
            }
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No newsletters found.</p>';
    }
    $mysqli->close();
    ?>
</main>

<?php include '../partials/footer.php'; ?>
