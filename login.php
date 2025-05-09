<?php
session_start();

$username = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and/or Password is required.";
    } else {
        include "tools/db.php";
        $dbConnection = getDBConnection();

        $statement = $dbConnection->prepare(
            "SELECT id, email, password, createdAt FROM users WHERE username = ?"
        );
        $statement->bind_param('s', $username);
        $statement->execute();
        $statement->bind_result($id, $email, $stored_password, $createdAt);

        if ($statement->fetch()) {
            if (password_verify($password, $stored_password)) {
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $email;
                $_SESSION["createdAt"] = $createdAt;

                header("location: ./home.php");
                exit;
            }
        }

        $statement->close();

        $error = "Username or Password Invalid";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="index.css">

    <title>Log In</title>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="title">Stem&Petals Log In</div>
        <form action="" method="POST">
            <?php if ($message): ?>
                <div class="error-message"><?php echo $message; ?></div>
            <?php endif; ?>
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Email or Username</span>
                    <input type="text" placeholder=" Enter your Email or Username" name="identifier" required>
                </div>
                <div class="input-box">
                    <span class="details">Password</span>
                    <input type="password" placeholder=" Enter your Password" name="password" required>
                </div>
            </div>

            <div class="button">
                <input type="submit" value="Log  In">
            </div>

            <div class="last">
                <a href="index.php">Don't have an account?</a>
            </div>
        </form>
    </div>
    
</body>
</html>
