<?php
session_start();
include 'session.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $stmt = db()->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST["email"]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST["password"], $user["password"])) {
        $_SESSION["user"] = $user;

        // Redirect based on role
        if ($user["role"] === "vendor") {
            header("Location: add_food.php");
        } else {
            header("Location: order_food.php");
        }
        exit;
    } else {
        $error = "Invalid login credentials.";
    }
}
?>

<!-- The form stays the same as before -->


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - SUSLConnect Food</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body>
  

<div class="login-container">
  <h2>Login to SUSL Connect</h2>

  <?php if (!empty($error)): ?>
    <p class="error"><?= $error ?></p>
  <?php endif; ?>

  <form method="post">
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button name="login">Login</button>
  </form>

  <div class="link">
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</div>

</body>
</html>
