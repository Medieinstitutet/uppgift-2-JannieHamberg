<?php
session_start();
include dirname(__DIR__, 1) . '/db_data/auth.php';
checkLogin();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../db_data/database.php';
include __DIR__ . '/../partials/header.php';


$mysqli = connectDB();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $mysqli->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $new_password, $email);
            $stmt->execute();

            $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

        } else {
            echo '
                <main class="mt-10 max-w-lg mx-auto p-6 bg-white shadow-md rounded-md">
                    <h2 class="text-2xl font-semibold mb-4">Reset Password</h2>
                    <form method="post" action="set_new_password.php?token=' . htmlspecialchars($token) . '">
                        <div class="mb-4">
                            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password:</label>
                            <input type="password" name="new_password" id="new_password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600">Reset Password</button>
                        </div>
                    </form>
                </main>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">This password reset link is invalid or has expired.</div>';
    }

    $stmt->close();
} else {
    echo '<div class="alert alert-danger" role="alert">No token provided.</div>';
}

$mysqli->close();

?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
