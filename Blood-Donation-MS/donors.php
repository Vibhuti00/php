<?php
require_once 'config/db.php';
include 'includes/header.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $age = (int)$_POST['age'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);

    // Determine category
    $rare_list = ['Bombay Blood Group', 'Rh-null', 'Lutheran', 'Duffy'];
    $category = in_array($blood_group, $rare_list) ? 'Rare' : 'Common';

    $sql = "INSERT INTO donors (full_name, age, gender, blood_group, blood_category, phone, city) 
            VALUES ('$name', $age, '$gender', '$blood_group', '$category', '$phone', '$city')";
    
    if ($conn->query($sql)) {
        $message = "Donor registered successfully!";
    } else {
        $message = "Error registering donor.";
    }
}

$donors = $conn->query("SELECT * FROM donors ORDER BY id DESC");
?>

<h2><?php echo $lang_data['donor_reg']; ?></h2>
<?php if($message) echo "<p class='success'>$message</p>"; ?>

<form action="donors.php" method="POST" class="form-card">
    <div class="form-group">
        <label><?php echo $lang_data['full_name']; ?></label>
        <input type="text" name="full_name" required>
    </div>
    <div class="form-group">
        <label><?php echo $lang_data['age']; ?></label>
        <input type="number" name="age" required>
    </div>
    <div class="form-group">
        <label><?php echo $lang_data['gender']; ?></label>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="form-group">
        <label><?php echo $lang_data['blood_group']; ?></label>
        <select name="blood_group" required>
            <optgroup label="Common Groups">
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </optgroup>
            <optgroup label="Rare Groups">
                <option value="Bombay Blood Group">Bombay Blood Group</option>
                <option value="Rh-null">Rh-null</option>
                <option value="Lutheran">Lutheran</option>
                <option value="Duffy">Duffy</option>
            </optgroup>
        </select>
    </div>
    <div class="form-group">
        <label><?php echo $lang_data['phone']; ?></label>
        <input type="text" name="phone" required>
    </div>
    <div class="form-group">
        <label><?php echo $lang_data['city']; ?></label>
        <input type="text" name="city" required>
    </div>
    <button type="submit" class="btn"><?php echo $lang_data['submit']; ?></button>
</form>

<hr>

<h2>Registered Donors List</h2>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Age/Gender</th>
                <th>Blood Group</th>
                <th>Category</th>
                <th>City</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $donors->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo $row['age'] . ' / ' . $row['gender']; ?></td>
                <td><strong><?php echo $row['blood_group']; ?></strong></td>
                <td><span
                        class="badge <?php echo strtolower($row['blood_category']); ?>"><?php echo $row['blood_category']; ?></span>
                </td>
                <td><?php echo htmlspecialchars($row['city']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>

</html>