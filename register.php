

<?php
session_start();
include 'session.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
    $stmt = db()->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        $stmt->execute([$_POST["name"], $_POST["email"], $password, $_POST["role"]]);

        // Auto-login after registration
        $user_id = db()->lastInsertId();
        $stmt = db()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION["user"] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Redirect by role
        if ($_SESSION["user"]["role"] === "vendor") {
            header("Location: add_food.php");
        } else {
            header("Location: order_food.php");
        }
        exit;

    } catch (Exception $e) {
        $error = "Email already exists or registration error!";
    }
}
?>

<!-- The form stays the same as before -->






<link rel="stylesheet" href="register.css"/>
<div class="register-container">
    <h2>Register</h2>
    <form method="post">
        <input name="name" placeholder="Name" type="name" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>
        <select name="role" type="role" required>
            <option value="student">Student</option>
            <option value="lecturer">Lecturer</option>
            <option value="vendor">Vendor</option>
        </select>
        <button name="register">Register</button>
    </form>
    <div class="backtohome">
    <h>You already have an account? </h><a href="login.php">Log</a>
</div>
</div>
