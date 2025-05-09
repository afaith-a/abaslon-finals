<?php
$host = 'sql312.infinityfree.com';
$username = 'if0_38929999';  
$password = 'faithfaith19';      
$dbname = 'if0_38929999_faiths';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier']; // can be email or username
    $pass = $_POST['password'];

// Fetch user from database using email or username
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashedPassword);
    $stmt->fetch();

    if (password_verify($pass, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        header("Location: https://bouqs.com/");
        exit();
    } else {
        $message = "Invalid password.";
    }
} else {
    $message = "No account found with that email or username.";
}
}

if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    echo "<script>alert('Registration successful! You can now log in.');</script>";
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