<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Language Switch Logic
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'en';
$lang_data = include __DIR__ . "/../languages/{$lang}.php";
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang_data['title']; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <nav class="navbar">
        <div class="logo">🩸 BloodBank</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><?php echo $lang_data['dashboard']; ?></a></li>
            <li><a href="donors.php"><?php echo $lang_data['donors']; ?></a></li>
            <li><a href="inventory.php"><?php echo $lang_data['inventory']; ?></a></li>
            <li><a href="requests.php"><?php echo $lang_data['requests']; ?></a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php"><?php echo $lang_data['logout']; ?></a></li>
            <?php else: ?>
            <li><a href="login.php"><?php echo $lang_data['login']; ?></a></li>
            <li><a href="register.php"><?php echo $lang_data['register']; ?></a></li>
            <?php endif; ?>
        </ul>
        <div class="lang-picker">
            <a href="?lang=en" class="<?php echo $lang=='en'?'active':''; ?>">EN</a> |
            <a href="?lang=hi" class="<?php echo $lang=='hi'?'active':''; ?>">हिंदी</a> |
            <a href="?lang=gu" class="<?php echo $lang=='gu'?'active':''; ?>">ગુજરાતી</a>
        </div>
    </nav>
    <div class="container">