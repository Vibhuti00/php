<?php
require_once 'config/db.php';
include 'includes/header.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle Inventory Stock Updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_inventory'])) {
    $inventory_id = (int)$_POST['inventory_id'];
    $units_available = (int)$_POST['units_available'];

    if ($units_available >= 0) {
        $stmt = $conn->prepare("UPDATE blood_inventory SET units_available = ? WHERE id = ?");
        $stmt->bind_param("ii", $units_available, $inventory_id);
        if ($stmt->execute()) {
            $message = "Inventory stock updated successfully!";
        } else {
            $message = "Error updating stock.";
        }
        $stmt->close();
    } else {
        $message = "Units cannot be negative.";
    }
}

// Fetch Common Blood Groups
$common_inventory = $conn->query("SELECT * FROM blood_inventory WHERE category = 'Common' ORDER BY blood_group ASC");

// Fetch Rare Blood Groups
$rare_inventory = $conn->query("SELECT * FROM blood_inventory WHERE category = 'Rare' ORDER BY blood_group ASC");
?>

<h2>Blood Inventory Tracker</h2>
<p style="color: #666; margin-bottom: 20px;">Monitor and manage available units for common and rare blood groups in real
    time.</p>

<?php if($message): ?>
<p class="success" style="color: #5cb85c; margin-bottom: 15px; font-weight: 500;"><?php echo $message; ?></p>
<?php endif; ?>

<!-- Common Blood Groups Section -->
<h3 style="margin-top: 10px; color: #333; border-bottom: 2px solid #0275d8; padding-bottom: 5px;">Common Blood Groups
</h3>
<div class="table-responsive" style="margin-bottom: 30px;">
    <table>
        <thead>
            <tr>
                <th>Blood Group</th>
                <th>Category</th>
                <th>Units Available</th>
                <th>Status / Stock Level</th>
                <th>Update Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $common_inventory->fetch_assoc()): ?>
            <tr>
                <td><strong style="font-size: 1.1rem; color: #b71c1c;"><?php echo $row['blood_group']; ?></strong></td>
                <td><span class="badge common"><?php echo $row['category']; ?></span></td>
                <td><strong><?php echo $row['units_available']; ?> Units</strong></td>
                <td>
                    <?php 
                        if ($row['units_available'] <= 5) {
                            echo '<span style="color: #d9534f; font-weight: bold;">Low Stock ⚠️</span>';
                        } else {
                            echo '<span style="color: #5cb85c; font-weight: bold;">Adequate Stock</span>';
                        }
                    ?>
                </td>
                <td>
                    <form action="inventory.php" method="POST" style="display: flex; gap: 8px; align-items: center;">
                        <input type="hidden" name="inventory_id" value="<?php echo $row['id']; ?>">
                        <input type="number" name="units_available" value="<?php echo $row['units_available']; ?>"
                            min="0" style="width: 80px; padding: 5px;" required>
                        <button type="submit" name="update_inventory" class="btn"
                            style="padding: 5px 10px; font-size: 0.85rem;">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Rare Blood Groups Section -->
<h3 style="color: #333; border-bottom: 2px solid #d9534f; padding-bottom: 5px;">Rare Blood Groups</h3>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Blood Group</th>
                <th>Category</th>
                <th>Units Available</th>
                <th>Status / Stock Level</th>
                <th>Update Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $rare_inventory->fetch_assoc()): ?>
            <tr>
                <td><strong style="font-size: 1.1rem; color: #b71c1c;"><?php echo $row['blood_group']; ?></strong></td>
                <td><span class="badge rare"><?php echo $row['category']; ?></span></td>
                <td><strong><?php echo $row['units_available']; ?> Units</strong></td>
                <td>
                    <?php 
                        if ($row['units_available'] == 0) {
                            echo '<span style="color: #d9534f; font-weight: bold;">Critical / Out of Stock ❌</span>';
                        } else {
                            echo '<span style="color: #f0ad4e; font-weight: bold;">Available (Rare)</span>';
                        }
                    ?>
                </td>
                <td>
                    <form action="inventory.php" method="POST" style="display: flex; gap: 8px; align-items: center;">
                        <input type="hidden" name="inventory_id" value="<?php echo $row['id']; ?>">
                        <input type="number" name="units_available" value="<?php echo $row['units_available']; ?>"
                            min="0" style="width: 80px; padding: 5px;" required>
                        <button type="submit" name="update_inventory" class="btn"
                            style="padding: 5px 10px; font-size: 0.85rem; background: #d9534f;">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>

</html>