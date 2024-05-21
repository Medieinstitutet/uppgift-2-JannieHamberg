<?php 
include dirname(__DIR__, 1) . '/db_data/auth.php';
checkLogin();
include '../partials/header.php'; 
?>

<main class="mt-10 mx-auto w-1/3">
    <h2 class="text-xl font-bold mb-4">Welcome to My Pages</h2>
    <p><a href="reset_password.php" class="text-blue-600 hover:text-blue-800">Reset Password</a></p>
</main>

<?php include '../partials/footer.php'; ?>
