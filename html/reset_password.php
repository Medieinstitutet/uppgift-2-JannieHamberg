<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/db_data/database.php';
require __DIR__ . '/send_email.php';
include __DIR__ . '/partials/header.php';

echo "Checkpoint 1";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Form was submitted";
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));
    $expires = date('U') + 1800;

    $mysqli = connectDB();
    $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt = $mysqli->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $token, $expires);
    $stmt->execute();

    $resetLink = "http://localhost/set_new_password.php?token=$token";
    sendEmail($email, 'Reset your password', "Click the link to reset your password: $resetLink");

    echo "An email has been sent to reset your password.";
}
?>

<form method="post" action="reset_password.php">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Send Reset Link</button>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
