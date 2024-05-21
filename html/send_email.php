<?php
require 'config.php';

function sendEmail($to, $subject, $body) {
    global $env;
    $apiKey = $env['MAILGUN_API_KEY'];
    $domain = $env['MAILGUN_DOMAIN'];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/$domain/messages");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'from' => 'noreply@yourdomain.com',
        'to' => $to,
        'subject' => $subject,
        'text' => $body
    ]);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
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

// Place this check around your existing code for email handling
if (basename($_SERVER['PHP_SELF']) == 'reset_password.php') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $to = $_POST['email'];
        $subject = 'Password Reset';
        $body = 'You requested a password reset. Here is your link: ...';

        if (emailExists($to)) {
            $randomCode = bin2hex(random_bytes(25)); // Generate random code for reset link
            $body .= "\n\nYour verification code is: $randomCode";

            $response = sendEmail($to, $subject, $body);
            echo '<p>Email sent successfully.</p>';
            echo '<p>Response: ' . htmlspecialchars($response) . '</p>';
        } else {
            echo '<p>Email not found in users table.</p>';
        }
    }
}
?>


<!-- 
<form method="post" action="send_email.php">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Send Reset Link</button>
</form> -->


<!-- <div class="flex items-center justify-center h-screen bg-green-700">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md bg-green-700">
        <h1 class="text-xl mb-4">Send Email via Mailgun</h1>
        <form action="send_email.php" method="post">
            <div class="mb-4">
                <label for="to" class="block text-gray-700">To:</label>
                <input type="email" name="to" id="to" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-gray-700">Subject:</label>
                <input type="text" name="subject" id="subject" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="body" class="block text-gray-700">Body:</label>
                <textarea name="body" id="body" rows="4" class="w-full px-3 py-2 border rounded" required></textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Send Email</button>
        </form>
    </div>
</div>
 -->