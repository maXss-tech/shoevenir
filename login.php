<?php
require_once 'config.php';
require_once 'auth.php';

// Initialize variables to prevent 500 errors
$error = '';
$success = '';

function loginUser($username, $password) {
    global $conn;

    // Sanitize to prevent basic SQL injection
    $username = mysqli_real_escape_string($conn, $username);

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Plain text check (Matches your current registration/database format)
        if ($user['password'] == $password) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'] ?? 'user'; // Defaults to user if null

            return [
                'success' => true,
                'role' => $_SESSION['role']
            ];
        }
    }

    return [
        'success' => false,
        'message' => 'Invalid username or password'
    ];
}

// PROCESS THE LOGIN FORM WHEN SUBMITTED
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $loginResult = loginUser($username, $password);

        if ($loginResult['success']) {
            // Redirect based on role
            if ($loginResult['role'] === 'admin') {
                header("Location: admin_dashboard.php"); // Change to your actual admin page
                exit();
            } else {
                header("Location: index.php"); // Change to your actual home page
                exit();
            }
        } else {
            $error = $loginResult['message'];
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Shoevinir</title>
<link rel="stylesheet" href="style.css">
<script src="https://tailwindcss.com"></script>
</head>
<body class="admin-login-page">

<div class="form-container">
    <h2 class="form-title">Welcome Back to Shoevinir</h2>

    <!-- Safe Error Displaying -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error" style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success" style="color: green; margin-bottom: 15px;"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                   placeholder="Enter your username">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
        </div>

        <button type="submit" class="form-btn btn-primary">Login</button>
    </form>

    <p class="form-link">
        Don't have an account? <a href="register.php">Register here</a>
    </p>

    <div style="margin-top:20px; padding:15px; background: rgba(108,92,231,0.1); border-radius:10px; text-align:center;">
        <p class="text-muted" style="font-size:0.9rem;">
            Shoevinir Authentication System
        </p>
    </div>
</div>
</body>
</html>
