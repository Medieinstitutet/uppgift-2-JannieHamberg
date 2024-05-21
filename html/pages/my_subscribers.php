<?php
session_start();
include dirname(__DIR__, 1) . '/db_data/auth.php';
checkLogin();
include dirname(__DIR__) . '/db_data/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    echo "You must be logged in as a customer to view this page.";
    exit;
}

function fetchSubscribers($customerId) {
    $mysqli = connectDB();
    $sql = "SELECT id, first_name, last_name, email FROM users WHERE role = 'subscriber'";
    $result = $mysqli->query($sql);

    $subscribers = [];
    while ($row = $result->fetch_assoc()) {
        $subscribers[] = $row;
    }

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
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">First Name</dt>
                        <dt class="text-sm font-medium text-gray-500">Last Name</dt>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                    </div>
                    <?php foreach ($subscribers as $subscriber): ?>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dd class="text-sm text-gray-900"><?php echo htmlspecialchars($subscriber['first_name']); ?></dd>
                            <dd class="text-sm text-gray-900"><?php echo htmlspecialchars($subscriber['last_name']); ?></dd>
                            <dd class="text-sm text-gray-900"><?php echo htmlspecialchars($subscriber['email']); ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
