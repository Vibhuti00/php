<?php
require_once 'config/db.php';
include 'includes/header.php';

// Fetch quick counts for display on the landing page
$donor_count = $conn->query("SELECT COUNT(*) as total FROM donors")->fetch_assoc()['total'];
$request_count = $conn->query("SELECT COUNT(*) as total FROM blood_requests")->fetch_assoc()['total'];
$inventory_total = $conn->query("SELECT SUM(units_available) as total FROM blood_inventory")->fetch_assoc()['total'];
?>

<div class="hero-section"
    style="text-align: center; padding: 40px 20px; background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <h1 style="font-size: 2.5rem; color: #b71c1c; margin-bottom: 15px;">🩸 <?php echo $lang_data['title']; ?></h1>
    <p style="font-size: 1.2rem; color: #555; max-width: 700px; margin: 0 auto 25px auto;">
        Connecting patients, donors, and doctors seamlessly. Track common and rare blood groups, manage urgent dispatch
        requests, and save lives together.
    </p>

    <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <?php if(isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php" class="btn" style="padding: 12px 25px; font-size: 1rem; text-decoration: none;">Go to
            Dashboard</a>
        <?php else: ?>
        <a href="login.php" class="btn"
            style="padding: 12px 25px; font-size: 1rem; text-decoration: none;"><?php echo $lang_data['login']; ?></a>
        <a href="register.php" class="btn"
            style="padding: 12px 25px; font-size: 1rem; background: #0275d8; text-decoration: none;"><?php echo $lang_data['register']; ?></a>
        <?php endif; ?>
    </div>
</div>

<!-- Quick System Statistics Grid -->
<div
    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">

    <div class="form-card" style="text-align: center; padding: 25px;">
        <h3 style="color: #666; font-size: 1rem; margin-bottom: 10px;">Total Registered Donors</h3>
        <p style="font-size: 2rem; font-weight: bold; color: #b71c1c;"><?php echo $donor_count; ?></p>
        <a href="donors.php"
            style="color: #0275d8; text-decoration: none; font-size: 0.9rem; margin-top: 10px; display: inline-block;">Manage
            Donors &rarr;</a>
    </div>

    <div class="form-card" style="text-align: center; padding: 25px;">
        <h3 style="color: #666; font-size: 1rem; margin-bottom: 10px;">Blood Units in Stock</h3>
        <p style="font-size: 2rem; font-weight: bold; color: #5cb85c;"><?php echo (int)$inventory_total; ?> Units</p>
        <a href="inventory.php"
            style="color: #0275d8; text-decoration: none; font-size: 0.9rem; margin-top: 10px; display: inline-block;">View
            Inventory Tracker &rarr;</a>
    </div>

    <div class="form-card" style="text-align: center; padding: 25px;">
        <h3 style="color: #666; font-size: 1rem; margin-bottom: 10px;">Blood Requests Handled</h3>
        <p style="font-size: 2rem; font-weight: bold; color: #0275d8;"><?php echo $request_count; ?></p>
        <a href="requests.php"
            style="color: #0275d8; text-decoration: none; font-size: 0.9rem; margin-top: 10px; display: inline-block;">Requests
            & Dispatch &rarr;</a>
    </div>
</div>

<!-- Feature Highlights -->
<div class="form-card">

    <h2 style="margin-bottom: 15px; color: #333;">System Key Features</h2>

    <ul style="line-height: 1.8; padding-left: 20px; color: #555;">
        <li>
            <strong>Role-Based Accounts:</strong> Dedicated portals and portfolio profiles for Doctors and Patients.
        </li>
        <li>
            <strong>Multi-Language Support:</strong> Seamlessly switch between English, Hindi (हिंदी), and Gujarati
            (ગુજરાતી).
        </li>
        <li>
            <strong>Common & Rare Blood Groups:</strong> Comprehensive tracking for standard types (A+, O-, etc.) as
            well as rare groups (Bombay Blood Group, Rh-null, Lutheran, Duffy).
        </li>
        <li>
            <strong>Inventory Bar Graphs:</strong> Visual real-time metrics powered by Chart.js.
        </li>
        <li>
            <strong>Responsive Design:</strong> Optimized layout for Desktop PCs, Tablets, and Mobile UI screens.
        </li>
    </ul>

</div>

</body>

</html>