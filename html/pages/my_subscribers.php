<?php

session_start();
include dirname(__DIR__, 1) . '/db_data/database.php';

createUsersTable();
echo basename(__FILE__); 

function fetchSubscribers() {
    $mysqli = connectDB();
    $sql = "SELECT id, username, role FROM users WHERE role = 'subscriber'";  
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
  
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $mysqli->close();
        return $users;
    } else {
        $mysqli->close();
        return [];
    }
}


$users = fetchSubscribers();


include '../partials/header.php'; ?>

<div class="mt-10">
    <h2 class="mx-auto pb-2 text-xl">My Subscribers</h2>
    <table class="min-w-full leading-normal">
        <thead>
            <tr class="text-left bg-gray-100">
                <th class="px-5 py-3">ID</th>
                <th class="px-5 py-3">Username</th>
                <th class="px-5 py-3">Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-2"><?php echo htmlspecialchars($user['id']); ?></td>
                <td class="px-5 py-2"><?php echo htmlspecialchars($user['username']); ?></td>
                <td class="px-5 py-2"><?php echo htmlspecialchars($user['role']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>






<?php include '../partials/footer.php'; ?>

