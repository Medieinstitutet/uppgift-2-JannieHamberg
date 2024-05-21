<?php
session_start();
include dirname(__DIR__, 1) . '/db_data/auth.php';


checkLogin();


include dirname(__DIR__, 1) . '/db_data/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $customerId = $_SESSION['user_id'];

    $mysqli = connectDB();
    $stmt = $mysqli->prepare("INSERT INTO newsletters (customer_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $customerId, $title, $description);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Newsletter created successfully.";
        header('Location: my_newsletter.php');
        exit();
    } else {
        $_SESSION['message'] = "Error creating newsletter: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}

include '../partials/header.php';
?>

<main class="mt-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">Create Newsletter</h2>
        </div>
        <div class="border-t border-gray-200">
            <?php
            if (isset($_SESSION['message'])) {
                echo '<p class="text-red-500 px-4 py-5 sm:px-6">' . $_SESSION['message'] . '</p>';
                unset($_SESSION['message']);
            }
            ?>
            <form class="px-4 py-5 sm:px-6" method="POST" action="">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                        <input type="text" id="title" name="title" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                        <textarea id="description" name="description" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Newsletter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
