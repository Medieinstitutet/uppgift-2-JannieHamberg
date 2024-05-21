<?php
session_start();
include dirname(__DIR__) . '/db_data/database.php';
echo basename(__FILE__);

function fetchSubscriptions($userId) {
    $mysqli = connectDB();
    $sql = "SELECT subscriptions.id, newsletters.title AS newsletter_title
            FROM subscriptions
            JOIN newsletters ON subscriptions.newsletter_id = newsletters.id
            WHERE subscriptions.subscriber_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $subscriptions = [];
    while ($row = $result->fetch_assoc()) {
        $subscriptions[] = $row;
    }
    $mysqli->close();
    return $subscriptions;
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $subscriptions = fetchSubscriptions($userId);
} else {
    echo "You must be logged in to view this page.";
    exit;
}

include '../partials/header.php';
?>

<div class="mt-10">
    <h2 class="mx-auto pb-2 text-xl">My Subscriptions</h2>
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="text-left bg-gray-100 px-5 py-3">ID</th>
                <th class="text-left bg-gray-100 px-5 py-3">Newsletter Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subscriptions as $subscription) { ?>
                <tr>
                    <td class="px-5 py-3"><?php echo htmlspecialchars($subscription['id']); ?></td>
                    <td class="px-5 py-3"><?php echo htmlspecialchars($subscription['newsletter_title']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../partials/footer.php'; ?>
