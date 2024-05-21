<?php
session_start();
require __DIR__ . '/../db_data/database.php';
require_once __DIR__ . '/../send_email.php';
include __DIR__ . '/../partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));
    $expires = date('Y-m-d H:i:s', time() + 1800);

    $mysqli = connectDB();
    $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt = $mysqli->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $token, $expires);
    $stmt->execute();

    $resetLink = "http://localhost:8080/pages/set_new_password.php?token=$token";
    sendEmail($email, 'Reset your password', "Click the link to reset your password: $resetLink");

    echo '<div class="alert alert-success" role="alert">An email has been sent to reset your password.</div>';
    $stmt->close();
    $mysqli->close();
}
?>

<main class="mt-10 max-w-lg mx-auto p-6 bg-white shadow-md rounded-md">
    <h2 class="text-2xl font-semibold mb-4">Reset Password</h2>
    <form method="POST" action="reset_password.php">
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600">Send Reset Link</button>
        </div>
    </form>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
