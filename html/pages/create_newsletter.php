<?php
session_start();

include dirname(__DIR__, 1) . '/db_data/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $customerId = $_SESSION['user_id'];

    $mysqli = connectDB();
    $stmt = $mysqli->prepare("INSERT INTO newsletters (customer_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $customerId, $title, $description);

    if ($stmt->execute()) {
        echo "Newsletter created successfully.";
    } else {
        echo "Error creating newsletter: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}

include '../partials/header.php';
?>

<main class="mt-10">
    <h2 class="mx-auto pb-2 text-xl">Create Newsletter</h2>
    <form method="POST" action="">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <br>
        <button type="submit">Create Newsletter</button>
    </form>
</main>

<?php include '../partials/footer.php'; ?>
