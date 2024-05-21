<?php
session_start();
include dirname(__DIR__, 1) . '/db_data/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    die("You must be logged in as a customer to view your newsletters.");
}

$mysqli = connectDB();
$userId = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, title, description FROM newsletters WHERE customer_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$newsletters = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$mysqli->close();

include '../partials/header.php';
?>

<main class="mt-10">
    <h2>My Newsletters</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']); 
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($newsletters as $newsletter): ?>
            <tr>
                <td><?php echo htmlspecialchars($newsletter['title']); ?></td>
                <td><?php echo htmlspecialchars($newsletter['description']); ?></td>
                <td>
                    <a href="edit_newsletter.php?id=<?php echo $newsletter['id']; ?>">Edit</a>
                    <a href="delete_newsletter.php?id=<?php echo $newsletter['id']; ?>" onclick="return confirm('Are you sure you want to delete this newsletter?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include '../partials/footer.php'; ?>
