<?php
session_start();
require_once __DIR__ . '/../db_data/database.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../send_email.php';
include __DIR__ . '/../partials/header.php';

echo basename(__FILE__);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (emailExists($email)) {
        echo "Email is already in use.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $mysqli = connectDB();
        $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashedPassword, $role);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Account created successfully.";
        } else {
            echo "Error creating account: " . $mysqli->error;
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>

<form method="post" action="create_account.php">
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" required>
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" required>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <label for="role">Role:</label>
    <select name="role" id="role" required>
        <option value="customer">Customer</option>
        <option value="subscriber">Subscriber</option>
    </select>
    <button type="submit">Create Account</button>
</form>

<?php include '../partials/footer.php'; ?>
