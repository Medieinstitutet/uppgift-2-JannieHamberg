<?php
session_start();
include dirname(__DIR__, 1) . '/db_data/auth.php';
checkLogin();
include dirname(__DIR__) . '/db_data/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    echo "You must be logged in as a customer to view this page.";
    exit;
}

$customer_id = $_SESSION['user_id'];
$mysqli = connectDB();
$stmt = $mysqli->prepare("SELECT * FROM newsletters WHERE customer_id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$newsletters = [];
while ($row = $result->fetch_assoc()) {
    $newsletters[] = $row;
}

$mysqli->close();

include '../partials/header.php';
?>

<main class="mt-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h2 class="text-lg leading-6 font-medium text-gray-900">My Newsletters</h2>
                <a href="create_newsletter.php" class="text-blue-500 hover:text-blue-700">Create New Newsletter</a>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Title</dt>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dt class="text-sm font-medium text-gray-500">Actions</dt>
                    </div>
                    <?php foreach ($newsletters as $newsletter): ?>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dd class="text-sm text-gray-900"><?php echo htmlspecialchars($newsletter['title']); ?></dd>
                            <dd class="text-sm text-gray-900"><?php echo htmlspecialchars($newsletter['description']); ?></dd>
                            <dd class="text-sm text-gray-900">
                                <a href="edit_newsletter.php?id=<?php echo $newsletter['id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <a href="delete_newsletter.php?id=<?php echo $newsletter['id']; ?>" class="text-red-500 hover:text-red-700">Delete</a>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
