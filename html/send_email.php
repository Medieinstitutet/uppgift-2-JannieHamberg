<?php
include 'config.php';

function sendEmail($to, $subject, $body) {
    global $env;
    $apiKey = $env['MAILGUN_API_KEY'];
    $domain = $env['MAILGUN_DOMAIN'];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/$domain/messages");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'from' => 'noreply@yourdomain.com',
        'to' => $to,
        'subject' => $subject,
        'text' => $body
    ]);

    $result = curl_exec($ch);
    if(curl_errno($ch)) {
        $error = 'Error: ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    curl_close($ch);
    return $result;
}

function emailExists($email) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    $mysqli->close();
    return $exists;
}

if (basename($_SERVER['PHP_SELF']) == 'reset_password.php') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];

        if (emailExists($email)) {
            $randomCode = bin2hex(random_bytes(25));
            $resetLink = "http://localhost:8080/pages/set_new_password.php?token=$randomCode";
            $body = "You requested a password reset. Click the link to reset your password: $resetLink";
            $subject = 'Password Reset';

            $mysqli = connectDB();
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

            $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $stmt = $mysqli->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $randomCode, $expiresAt);
            $stmt->execute();

            $response = sendEmail($email, $subject, $body);
           
        } else {
            echo '<p>Email not found in users table.</p>';
        }
    }
}
?>

