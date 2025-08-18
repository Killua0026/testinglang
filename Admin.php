<?php
session_start();

// Only allow admins
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admins only.");
}

$jsonPath = __DIR__ . '/DATA/ACC.json';
if (!file_exists($jsonPath)) {
    die('User database not found.');
}

$accounts = json_decode(file_get_contents($jsonPath), true) ?: [];

// Handle add account
if (isset($_POST['add_account'])) {
    $newUsername = trim($_POST['new_username']);
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $newRole = $_POST['new_role'] ?? 'user';

    // Prevent duplicate usernames
    foreach ($accounts as $account) {
        if ($account['username'] === $newUsername) {
            $error = "Username already exists!";
            break;
        }
    }

    if (!isset($error)) {
        $accounts[] = [
            'username' => $newUsername,
            'password' => $newPassword,
            'role' => $newRole
        ];
        file_put_contents($jsonPath, json_encode($accounts, JSON_PRETTY_PRINT));
        $success = "Account added successfully!";
    }
}

// Handle delete account
if (isset($_POST['delete_account'])) {
    $usernameToDelete = $_POST['username'];

    $accounts = array_filter($accounts, function ($account) use ($usernameToDelete) {
        return $account['username'] !== $usernameToDelete;
    });

    file_put_contents($jsonPath, json_encode(array_values($accounts), JSON_PRETTY_PRINT));
    $success = "Account deleted successfully!";
}

// Handle edit account
if (isset($_POST['edit_account'])) {
    $oldUsername = $_POST['old_username'];
    $newUsername = trim($_POST['edit_username']);
    $newPassword = !empty($_POST['edit_password']) ? password_hash($_POST['edit_password'], PASSWORD_DEFAULT) : null;

    foreach ($accounts as &$account) {
        if ($account['username'] === $oldUsername) {
            $account['username'] = $newUsername;
            if ($newPassword) {
                $account['password'] = $newPassword;
            }
            break;
        }
    }
    unset($account); // break reference

    file_put_contents($jsonPath, json_encode($accounts, JSON_PRETTY_PRINT));
    $success = "Account updated successfully!";
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: Login.php");
    exit;
}

// Handle reset request
if (isset($_POST['reset_ip'])) {
    $usernameToReset = $_POST['username'];

    foreach ($accounts as &$account) {
        if ($account['username'] === $usernameToReset) {
            unset($account['ip']); // remove IP binding
            break;
        }
    }
    file_put_contents($jsonPath, json_encode($accounts, JSON_PRETTY_PRINT));
    $success = "Account deleted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Account Management</title>
    <link rel="stylesheet" href="Admin.css">
</head>

<body>

    <header class="main-header">
        <div class="logo">
            <h1>ADMIN CONTROL PANEL</h1>
        </div>
        <form method="post" style="display:inline;">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </header>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Add Account Button -->
    <button onclick="document.getElementById('addModal').style.display='block'" class="btnAddNew">Add New
        Account</button>

    <!-- Modal: Add -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
            <h2>Add New Account</h2>
            <form method="post">
                <input type="text" name="new_username" placeholder="Username" required>
                <input type="password" name="new_password" placeholder="Password" required>
                <select name="new_role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="add_account" class="btnAdd">Add Account</button>
            </form>
        </div>
    </div>

    <!-- Modal: Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            <h2>Edit Account</h2>
            <form method="post">
                <input type="hidden" name="old_username" id="old_username">
                <input type="text" name="edit_username" id="edit_username" placeholder="New Username" required>
                <input type="password" name="edit_password" placeholder="New Password (leave blank to keep current)">
                <button type="submit" name="edit_account" class="btnEdit">Update Account</button>
            </form>
        </div>
    </div>

    <h2>Existing Accounts</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($accounts as $account): ?>
            <tr>
                <td><?= htmlspecialchars($account['username']); ?></td>
                <td><?= htmlspecialchars($account['role'] ?? 'user'); ?></td>
                <td>
                    <?php if ($account['username'] !== $_SESSION['username']): ?>
                        <button onclick="openEditModal('<?= htmlspecialchars($account['username']); ?>')">Edit</button>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="username" value="<?= htmlspecialchars($account['username']); ?>">
                            <button type="submit" name="delete_account" class="danger">Delete</button>
                            <button type="submit" name="reset_ip">Reset IP</button>
                        </form>
                    <?php else: ?>
                        <em>Currently Logged In</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function openEditModal(username) {
            document.getElementById('old_username').value = username;
            document.getElementById('edit_username').value = username;
            document.getElementById('editModal').style.display = 'block';
        }

        window.onclick = function (event) {
            var addModal = document.getElementById('addModal');
            var editModal = document.getElementById('editModal');
            if (event.target === addModal) {
                addModal.style.display = "none";
            }
            if (event.target === editModal) {
                editModal.style.display = "none";
            }
        }
    </script>

</body>

</html>