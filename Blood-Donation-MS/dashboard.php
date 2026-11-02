<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_res = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $user_res->fetch_assoc();

// If doctor, load profile
$doctor_info = null;
if ($user['role'] === 'doctor') {
    $doc_res = $conn->query("SELECT * FROM doctor_profiles WHERE user_id=$user_id");
    $doctor_info = $doc_res->fetch_assoc();
}

// Fetch Inventory Data for Bar Graph
$inv_res = $conn->query("SELECT blood_group, units_available, category FROM blood_inventory");
$labels = [];
$units = [];
$colors = [];

while ($row = $inv_res->fetch_assoc()) {
    $labels[] = $row['blood_group'];
    $units[] = $row['units_available'];
    $colors[] = ($row['category'] === 'Rare') ? '#d9534f' : '#0275d8';
}
?>

<div class="dashboard-grid">
    <div class="profile-card">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
        <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

        <?php if ($user['role'] === 'doctor' && $doctor_info): ?>
        <hr>
        <h3>Doctor Portfolio</h3>
        <p><strong>Hospital:</strong> <?php echo htmlspecialchars($doctor_info['hospital_name']); ?></p>
        <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor_info['specialization']); ?></p>
        <p><strong>Experience:</strong> <?php echo $doctor_info['experience_years']; ?> years</p>
        <p><strong>Bio:</strong> <?php echo htmlspecialchars($doctor_info['bio']); ?></p>
        <?php endif; ?>
    </div>

    <div class="chart-card">
        <h3>Live Blood Stock (Common vs Rare Groups)</h3>
        <canvas id="inventoryChart"></canvas>
    </div>
</div>

<script>
const ctx = document.getElementById('inventoryChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Units Available',
            data: <?php echo json_encode($units); ?>,
            backgroundColor: <?php echo json_encode($colors); ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
</body>

</html>