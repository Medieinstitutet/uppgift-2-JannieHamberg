<?php
session_start();

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

<main class="mt-10">
    <h2>Edit Newsletter</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']); 
    }
    ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($newsletter['id']); ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($newsletter['title']); ?>" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($newsletter['description']); ?></textarea>
        <br>
        <input type="submit" value="Update Newsletter">
    </form>
</main>

<?php include '../partials/footer.php'; ?>
