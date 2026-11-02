<?php
require_once 'config/db.php';
include 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect to Dashboard Overview
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No account found with this email address.";
    }
}
?>

<div class="container" style="max-width: 450px; margin-top: 50px;">
    <div class="form-card">
        <h2><?php echo $lang_data['login']; ?> to BloodBank</h2>

        <?php if (!empty($error)): ?>
        <p class="error" style="color: #d9534f; margin-bottom: 15px; font-weight: 500;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" class="btn"
                style="width: 100%; margin-top: 10px;"><?php echo $lang_data['login']; ?></button>
        </form>

        <p style="margin-top: 20px; text-align: center; font-size: 0.9rem;">
            Don't have an account? <a href="register.php"
                style="color: #b71c1c; font-weight: 600; text-decoration: none;"><?php echo $lang_data['register']; ?></a>
        </p>
    </div>
</div>

</body>

</html>