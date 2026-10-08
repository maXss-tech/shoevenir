<?php
require_once 'config.php';

function registerUser($username, $email, $password) {
    global $conn;

    $sql = "SELECT id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        return ['success' => false, 'message' => 'Username already taken'];
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, role)
            VALUES ('$username', '$email', '$hashedPassword', 'user')";

    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Registration successful!'];
    }

    return ['success' => false, 'message' => 'Registration failed'];
}

function loginUser($username, $password) {
    global $conn;

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        return [
            'success' => true,
            'role' => $user['role']
        ];
    }

    return ['success' => false, 'message' => 'Invalid username or password'];
}

function logoutUser() {
    $_SESSION = [];
    session_destroy();
    return true;
}

function getUserById($userId) {
    global $conn;

    $sql = "SELECT id, username, email, role FROM users WHERE id=$userId";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function getAllUsers() {
    global $conn;

    $sql = "SELECT id, username, email, role FROM users ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    return $users;
}
?>