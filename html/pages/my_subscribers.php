<?php
session_start();
include dirname(__DIR__) . '/db_data/auth.php';
checkLogin();
include dirname(__DIR__) . '/db_data/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    echo "You must be logged in as a customer to view this page.";
    exit();
}

function fetchSubscribers($customerId) {
    $mysqli = connectDB();
    $sql = "SELECT u.id, u.first_name, u.last_name, u.email 
            FROM users AS u
            JOIN subscriptions AS s ON u.id = s.subscriber_id
            JOIN newsletters AS n ON s.newsletter_id = n.id
            WHERE u.role = 'subscriber' AND n.customer_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $subscribers = [];
    while ($row = $result->fetch_assoc()) {
        $subscribers[] = $row;
    }
    $stmt->close();
    $mysqli->close();
    return $subscribers;
}

$subscribers = fetchSubscribers($_SESSION['user_id']); 

include '../partials/header.php';
?>

<main class="mt-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h2 class="text-lg leading-6 font-medium text-gray-900">My Subscribers</h2>
                <div class="border-t border-gray-200">
                    <dl class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">First Name</dt>
                        <dt class="text-sm font-medium text-gray-500">Last Name</dt>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                    </dl>
                    <?php foreach ($subscribers as $subscriber): ?>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dd class="text-sm text-gray-900"><?php echo htmlspecialchars($subscriber['first_name']); ?></dd>
                            <dd class="text-sm text-gray-900"><?php echo htmlspecialchars($subscriber['last_name']); ?></dd>
                            <dd class="text-sm text-gray-900"><?php echo htmlspecialchars($subscriber['email']); ?></dd>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
