<?php
session_start();
include dirname(__DIR__, 1) . '/db_data/auth.php';


checkLogin();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    die("You must be logged in as a customer to edit a newsletter.");
}

include dirname(__DIR__, 1) . '/db_data/database.php';

$mysqli = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newsletterId = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $mysqli->prepare("UPDATE newsletters SET title = ?, description = ? WHERE id = ? AND customer_id = ?");
    $stmt->bind_param("ssii", $title, $description, $newsletterId, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Newsletter updated successfully.";
        $stmt->close();
        $mysqli->close();
    
        header('Location: my_newsletter.php');
        exit();
    } else {
        $_SESSION['message'] = "Error updating newsletter: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['id'])) {
    $newsletterId = $_GET['id'];

    $stmt = $mysqli->prepare("SELECT id, title, description FROM newsletters WHERE id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $newsletterId, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $newsletter = $result->fetch_assoc();

    if (!$newsletter) {
        die("Newsletter not found.");
    }

    $stmt->close();
}

$mysqli->close();
include '../partials/header.php';
?>

<main class="mt-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">Edit Newsletter</h2>
        </div>
        <div class="border-t border-gray-200">
            <?php
            if (isset($_SESSION['message'])) {
                echo '<p class="text-red-500 px-4 py-5 sm:px-6">' . $_SESSION['message'] . '</p>';
                unset($_SESSION['message']);
            }
            ?>
            <form class="px-4 py-5 sm:px-6" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($newsletter['id']); ?>">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($newsletter['title']); ?>" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                        <textarea id="description" name="description" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"><?php echo htmlspecialchars($newsletter['description']); ?></textarea>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Newsletter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
