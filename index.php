<?php
session_start(); // ✅ Must always be first line before HTML

if (isset($_POST['loginbtn'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $jsonPath = __DIR__ . '/DATA/ACC.json';
    if (!file_exists($jsonPath)) {
        die('User database not found.');
    }

    $accounts = json_decode(file_get_contents($jsonPath), true);
    $loginSuccess = false;
    $isAdmin = false;
    $userIP = $_SERVER['REMOTE_ADDR']; // Get client IP

    if (is_array($accounts)) {
        foreach ($accounts as &$account) {
            if (isset($account['username'], $account['password']) && $account['username'] === $username) {
                // ✅ Use password_verify for hashed passwords
                if (password_verify($password, $account['password'])) {
                    // Check if account already has an IP bound
                    if (!isset($account['ip']) || $account['ip'] === $userIP) {
                        // Bind the IP permanently if not yet set
                        if (!isset($account['ip'])) {
                            $account['ip'] = $userIP;
                            file_put_contents($jsonPath, json_encode($accounts, JSON_PRETTY_PRINT));
                        }

                        $loginSuccess = true;
                        $isAdmin = (isset($account['role']) && $account['role'] === 'admin');
                    } else {
                        echo "<script>alert('This account is already owned by another person. Access denied.');</script>";
                    }
                }
                break;
            }
        }
    }

    if ($loginSuccess) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $isAdmin ? 'admin' : 'user';

        if ($isAdmin) {
            echo "<script>alert('Admin login successful!'); window.location.href='Admin.php';</script>";
        } else {
            echo "<script>alert('Login successful!'); window.location.href='Home.php';</script>";
        }
        exit;
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KUPAL LOGIN</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="container" id="login-container">
        <h1>LOGIN</h1>
        <!-- ✅ Only one form -->
        <form method="post" action="">
            <input type="text" placeholder="Username" id="username" name="username" required>
            <input type="password" placeholder="Password" id="password" name="password" required>
            <button type="submit" name="loginbtn">LOGIN</button>
        </form>
    </div>
    <div id="footer">
        <p>Footer content goes here</p>
    </div>
</body>

</html>