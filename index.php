<?php
session_start();

$username = "";
$email = "";

$username_err = "";
$email_err = "";
$pass_err = "";
$Cpass_err = "";

$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];  // Fixed: assign to $username
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmed_pass = $_POST['Cpass'];

    if (empty($username)) {
        $username_err = "Username is required.";
        $error = true;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Email format invalid.";
        $error = true;
    }

    include "tools/db.php";
    $dbConnection = getDBConnection();

    $statement = $dbConnection->prepare("SELECT id FROM users WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows > 0) {
        $email_err = "Email already used.";
        $error = true;
    }

    $statement->close();

    if (strlen($password) < 6) {
        $pass_err = "Password must be at least 6 characters.";
        $error = true;
    }

    if ($confirmed_pass != $password) {
        $Cpass_err = "Passwords do not match.";
        $error = true;
    }

    if (!$error) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');

        $statement = $dbConnection->prepare(
            "INSERT INTO users (username, email, password, createdAt) VALUES (?, ?, ?, ?)"
        );
        $statement->bind_param('ssss', $username, $email, $password, $created_at);
        $statement->execute();
        $insert_id = $statement->insert_id;
        $statement->close();

        $_SESSION["id"] = $insert_id;
        $_SESSION["username"] = $username;
        $_SESSION["email"] = $email;
        $_SESSION["created_at"] = $created_at;

        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="index.css">

    <title>Sign Up</title>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="title"> Sign Up</div>
        <form action="" method="POST">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Username</span>
                    <input type="text" placeholder=" Enter Username" name="username" required>
                </div>
                <div class="input-box">
                        <label for="username">Username</label>
            <input type="text" name="username" required>

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" minlength="6" required>
            
            <label for="Cpass" class="label">Confirm Password</label>
            <input type="password" id="Cpass" name="Cpass" class="textbox" placeholder="Re-enter password here" required>
            <span style="color:red;"><?php echo $Cpass_err; ?></span><br><br>
            </div>
                <div class="button">
                    <input type="submit" value="Register">
                </div>

                <div class="last">
                    <a href="login.php">Already have an account?</a>
                </div>
            
        </form>
    </div>
    
</body>
</html>
