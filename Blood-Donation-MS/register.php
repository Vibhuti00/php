<?php
require_once 'config/db.php';
include 'includes/header.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $message = "Email already registered!";
    } else {
        $sql = "INSERT INTO users (name, email, password, role, phone) VALUES ('$name', '$email', '$password', '$role', '$phone')";
        if ($conn->query($sql)) {
            $user_id = $conn->insert_id;
            
            // If registering as Doctor, save Portfolio profile
            if ($role === 'doctor') {
                $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
                $hosp = mysqli_real_escape_string($conn, $_POST['hospital_name']);
                $exp = (int)$_POST['experience_years'];
                $bio = mysqli_real_escape_string($conn, $_POST['bio']);
                $conn->query("INSERT INTO doctor_profiles (user_id, specialization, hospital_name, experience_years, bio) VALUES ($user_id, '$spec', '$hosp', $exp, '$bio')");
            }
            header("Location: login.php");
            exit();
        } else {
            $message = "Error creating account.";
        }
    }
}
?>

<h2>Create an Account</h2>
<?php if($message) echo "<p class='error'>$message</p>"; ?>

<form action="register.php" method="POST" class="form-card">
    <div class="form-group">
        <label>Account Type</label>
        <select name="role" id="roleSelect" onchange="toggleDoctorFields()" required>
            <option value="patient">Patient</option>
            <option value="doctor">Doctor</option>
        </select>
    </div>
    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Phone Number</label>
        <input type="text" name="phone" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <!-- Dynamic Doctor Fields -->
    <div id="doctorFields" style="display: none;">
        <h3>Doctor Portfolio Details</h3>
        <div class="form-group">
            <label>Specialization</label>
            <input type="text" name="specialization">
        </div>
        <div class="form-group">
            <label>Hospital Name</label>
            <input type="text" name="hospital_name">
        </div>
        <div class="form-group">
            <label>Experience (Years)</label>
            <input type="number" name="experience_years">
        </div>
        <div class="form-group">
            <label>Bio / Background</label>
            <textarea name="bio"></textarea>
        </div>
    </div>

    <button type="submit" class="btn"><?php echo $lang_data['register']; ?></button>
</form>

<script>
function toggleDoctorFields() {
    var role = document.getElementById('roleSelect').value;
    document.getElementById('doctorFields').style.display = (role === 'doctor') ? 'block' : 'none';
}
</script>
</body>

</html>