<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$base_url = "http://localhost:8080/";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<header class="mb-1">
    <nav class="bg-gray-800 p-4">
        <ul class="flex justify-between items-center">
            <?php if (!isset($_SESSION['role'])): ?>
                <li><a href="<?= $base_url; ?>index.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">Home</a></li>
                <li><a href="<?= $base_url; ?>pages/login.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">Login</a></li>
                <li><a href="<?= $base_url; ?>pages/create_account.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">Create Account</a></li>
            <?php elseif ($_SESSION['role'] == 'subscriber'): ?>
                <li><a href="<?= $base_url; ?>pages/all_newsletters.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">All Newsletters</a></li>
                <li><a href="<?= $base_url; ?>pages/my_subscriptions.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">My Subscriptions</a></li>
                <li><a href="<?= $base_url ?>pages/my_pages.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">My Pages</a></li>
                <li><a href="<?= $base_url; ?>session.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">Logout</a></li>
            <?php elseif ($_SESSION['role'] == 'customer'): ?>
                <li><a href="<?= $base_url; ?>pages/my_subscribers.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">My Subscribers</a></li>
                <li><a href="<?= $base_url; ?>pages/my_newsletter.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">My Newsletter</a></li>
                <li><a href="<?= $base_url; ?>pages/create_newsletter.php" class="text-white">Create Newsletter</a></li>
                <li><a href="<?= $base_url ?>pages/my_pages.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">My Pages</a></li>
                <li><a href="<?= $base_url; ?>session.php" class="text-white hover:text-blue-500 px-3 py-2 rounded-md text-sm font-medium">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
