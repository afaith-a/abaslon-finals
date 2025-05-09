<?php
// Connect to Database
$conn = new mysqli('sql312.infinityfree.com', 'if0_38929999', 'faithfaith19', 'if0_38929999_faiths');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize Inputs
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if passwords match
    if ($password != $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists!');</script>";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $insert = "INSERT INTO users (username, email, password)
                       VALUES ('$username', '$email', '$hashedPassword')";

            if ($conn->query($insert) === TRUE) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        }
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
                        <span class="details">Email</span>
                        <input type="email" placeholder=" Enter your Email Address" required name="email">
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" placeholder=" Create your Password" required name="password">
                    </div>
    
                    <div class="input-box">
                        <span class="details">Confirm Password</span>
                        <input type="password" placeholder=" Confirm your Password" required name="confirmPassword">
                    </div>
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
