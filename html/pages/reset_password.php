<?php
session_start();
include_once dirname(__DIR__) . '/db_data/auth.php';
include_once dirname(__DIR__) . '/db_data/database.php';
include_once dirname(__DIR__) . '/send_email.php';
include_once dirname(__DIR__) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email format';
        exit();
    }

    $mysqli = connectDB();
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id);
        $stmt->fetch();

        $token = bin2hex(random_bytes(50));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $stmt = $mysqli->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $email, $token, $expires);
        $stmt->execute();

        $resetLink = "http://localhost:8080/pages/set_new_password.php?token=$token";
        $body = "You requested a password reset. Click the link to reset your password: $resetLink";

        $response = sendEmail($email, 'Password Reset', $body);

        echo 'Password reset email sent';
    } else {
        echo 'Email not found in users table';
    }

    $stmt->close();
    $mysqli->close();
}

include '../partials/header.php';
?>

<main class="mt-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">Reset Password</h2>
            <form action="reset_password.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600">Send Reset Link</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
