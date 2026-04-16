<?php
include "config/database.php";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == 'admin'){
                header("Location: admin/dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }

        } else {
            echo "<script>alert('Invalid Password!');</script>";
        }

    } else {
        echo "<script>alert('User Not Found!');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Steam Analytics - Login</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
}

.container {
    width: 100%;
    max-width: 450px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 40px 30px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.container h2 {
    font-size: 28px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 30px;
    text-align: center;
}

form {
    display: flex;
    flex-direction: column;
}

input[type="email"],
input[type="password"] {
    padding: 15px 18px;
    margin-bottom: 20px;
    border-radius: 12px;
    border: none;
    background: rgba(255,255,255,0.1);
    color: #ffffff;
    font-size: 14px;
    outline: none;
    transition: 0.3s;
}

input[type="email"]:focus,
input[type="password"]:focus {
    background: rgba(255,255,255,0.2);
}

button {
    padding: 15px 18px;
    border-radius: 12px;
    border: none;
    background: #1b2838;
    color: #66c0f4;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #2a475e;
    transform: translateY(-2px);
}

p {
    margin-top: 20px;
    text-align: center;
    color: #c7d5e0;
    font-size: 14px;
}

p a {
    color: #66c0f4;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}

p a:hover {
    color: #ffffff;
}

@media(max-width: 500px) {
    .container {
        padding: 30px 20px;
    }

    .container h2 {
        font-size: 24px;
    }
}
</style>
</head>
<body>

<div class="container">
    <h2>🔑 Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p>No account? <a href="register.php">Register</a></p>
</div>

</body>
</html>