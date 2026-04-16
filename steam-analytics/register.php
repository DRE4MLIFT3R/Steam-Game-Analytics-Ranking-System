<?php
include "config/database.php";

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if($stmt->execute()){
        echo "<script>alert('Registration Successful!');</script>";
    } else {
        echo "<script>alert('Email Already Exists!');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
<h2>Create Account</h2>

<form method="POST">
<input type="text" name="name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="register">Register</button>
</form>

<p>Already have account? <a href="login.php">Login</a></p>
</div>
</body>
</html>