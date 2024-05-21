<?php
session_start();

function is_signed_in() {
    return isset($_SESSION['user_id']);
}

function user_has_role($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

if (isset($_POST['clearLogs'])) {
    $mysqli = new mysqli("db", "Jannie.Hamberg@medieinstitutet.se", "userpassword", "test");
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $sql = "DELETE FROM logs";
    if ($mysqli->query($sql)) {
        // Ensure no output before this line
        header('Location: ' . $_SERVER['PHP_SELF'] . '?logsCleared=1');
        exit;
    } else {
        echo "<p>Error clearing logs: " . $mysqli->error . "</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $mysqli = new mysqli("db", "Jannie.Hamberg@medieinstitutet.se", "userpassword", "test");
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    $old_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    $new_name = $mysqli->real_escape_string($_POST['name']);
    $message = "Name updated " . $old_name . " -> " . $new_name;
    $sql = "INSERT INTO logs (message) VALUES ('$message')";

    if ($mysqli->query($sql)) {
        $_SESSION['name'] = $new_name;
        // Ensure no output before this line
        header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
        exit;
    }
}

include './partials/header.php';
echo basename(__FILE__);
?>

<main class="mt-10">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['success']) && $_GET['success'] == "1") {
            echo '<div>Name updated successfully.</div>';
        }
        if (isset($_GET['logsCleared']) && $_GET['logsCleared'] == "1") {
            echo '<div>Logs cleared successfully.</div>';
        }
        if (isset($_SESSION['name']) && $_SESSION['name']) {
            echo "Hello " . $_SESSION['name'];
            ?>
            <p>Change name</p>
            <form method="POST">
                <input class="border-2 border-solid border-gray-600" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" />
                <input class="border-2 bg-green-600 hover:bg-green-700 text-sm text-white font-bold py-1 px-2 rounded" type="submit" />
            </form>
            <?php
        } else {
            ?>
            <p>Who are you?</p>
            <form method="POST">
                <input name="name" />
                <input type="submit" />
            </form>
            <?php
        }
        echo '<h2 class="font-bold">Logs</h2>';
        $mysqli = new mysqli("db", "Jannie.Hamberg@medieinstitutet.se", "userpassword", "test");
        $result = $mysqli->query("SELECT * FROM logs");
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) {
            echo '<p>' . htmlspecialchars($row["message"]) . '</p>';
        }
        ?>
        <form method="POST" action="">
            <button type="submit" name="clearLogs" class="bg-red-500 hover:bg-red-700 text-sm text-white font-bold py-1 px-2 rounded">
                Clear Logs
            </button>
        </form>
    <?php
    }
    ?>
</main>
<?php include './partials/footer.php'; ?>
