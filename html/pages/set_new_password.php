<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../db_data/database.php';
include __DIR__ . '/../partials/header.php';

$mysqli = connectDB();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $mysqli->prepare("SELECT email FROM password_resets WHERE token = ? AND expires > NOW()");
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

            echo "Your password has been reset successfully.";
        } else {
            
            echo '
                <form method="post" action="set_new_password.php?token=' . htmlspecialchars($token) . '">
                    <label for="new_password">New Password:</label>
                    <input type="password" name="new_password" id="new_password" required>
                    <button type="submit">Reset Password</button>
                </form>
            ';
        }
    } else {
       
        echo "This password reset link is invalid or has expired.";
    }

    $stmt->close();
} else {
    echo "No token provided.";
}

$mysqli->close();
include __DIR__ . '/../partials/footer.php';
?>
