<?php
// views/partials/header.php
// Works for files inside views/admin/, views/restaurant/, views/menu/
$role = $_SESSION['role'] ?? 'visitor';
$name = $_SESSION['name'] ?? '';

// Compute path depth to root (task2/)
$depth = 2; // all view files are 2 levels deep: views/subdir/file.php
$root  = str_repeat('../', $depth);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'FoodBlog') ?></title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f0; color: #222; min-height: 100vh; }

        /* ── Navbar ── */
        nav { background: #1a1a2e; padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 60px; box-shadow: 0 2px 8px rgba(0,0,0,.3); }
        nav .brand { font-size: 1.3rem; font-weight: 700; color: #e94560; text-decoration: none; letter-spacing: 1px; }
        nav ul { list-style: none; display: flex; gap: 1.2rem; align-items: center; }
        nav ul a { color: #ccc; text-decoration: none; font-size: .92rem; padding: .3rem .6rem; border-radius: 4px; transition: background .2s, color .2s; }
        nav ul a:hover { background: #e94560; color: #fff; }
        nav .user-info { color: #aaa; font-size: .85rem; white-space: nowrap; }

        /* ── Container ── */
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1.5rem; }

        /* ── Flash ── */
        .flash { padding: .85rem 1.2rem; border-radius: 6px; margin-bottom: 1.5rem; font-weight: 500; }
        .flash.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash.error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* ── Card ── */
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,.08); padding: 2rem; margin-bottom: 1.5rem; }

        /* ── Page header ── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: .8rem; }
        .page-header h1 { font-size: 1.6rem; color: #1a1a2e; }

        /* ── Buttons ── */
        .btn { display: inline-block; padding: .55rem 1.2rem; border-radius: 6px; font-size: .9rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: opacity .15s; }
        .btn:hover { opacity: .85; }
        .btn-primary   { background: #e94560; color: #fff; }
        .btn-secondary { background: #1a1a2e; color: #fff; }
        .btn-warning   { background: #f0ad4e; color: #fff; }
        .btn-danger    { background: #dc3545; color: #fff; }
        .btn-back      { background: #6c757d; color: #fff; }
        .btn-sm        { padding: .35rem .8rem; font-size: .82rem; }

        /* ── Table ── */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: .75rem 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f8f8; font-weight: 700; color: #555; font-size: .85rem; text-transform: uppercase; letter-spacing: .5px; }
        tr:hover td { background: #fafafa; }
        .action-btns { display: flex; gap: .5rem; flex-wrap: wrap; }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: .4rem; font-weight: 600; font-size: .9rem; color: #444; }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%; padding: .6rem .9rem; border: 1px solid #ddd;
            border-radius: 6px; font-size: .95rem; font-family: inherit; transition: border-color .2s;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #e94560; }
        .form-group textarea { resize: vertical; min-height: 90px; }
        .field-error { color: #dc3545; font-size: .82rem; margin-top: .3rem; min-height: 1rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* ── Dashboard stats ── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.2rem; margin-bottom: 2rem; }
        .stat-card { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,.07); }
        .stat-card .stat-number { font-size: 2.2rem; font-weight: 800; color: #e94560; }
        .stat-card .stat-label  { font-size: .85rem; color: #888; margin-top: .3rem; }

        /* ── Image thumb ── */
        .thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }

        /* ── Menu item detail ── */
        .item-detail-img { width: 100%; max-width: 400px; border-radius: 10px; margin-bottom: 1rem; }
        .price-badge { display: inline-block; background: #e94560; color: #fff; font-weight: 700; font-size: 1.1rem; padding: .3rem .9rem; border-radius: 20px; margin: .5rem 0 1rem; }

        /* ── Restaurant card grid ── */
        .restaurant-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.2rem; }
        .restaurant-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); padding: 1.5rem; transition: transform .2s, box-shadow .2s; }
        .restaurant-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.12); }
        .restaurant-card h3 { color: #1a1a2e; margin-bottom: .4rem; }
        .restaurant-card .meta { color: #888; font-size: .85rem; margin-bottom: .8rem; }

        /* ── Breadcrumb ── */
        .breadcrumb { color: #888; font-size: .85rem; margin-bottom: 1rem; }
        .breadcrumb a { color: #e94560; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        /* ── Back bar ── */
        .back-bar { margin-bottom: 1rem; }

        footer { text-align: center; padding: 2rem; color: #aaa; font-size: .85rem; margin-top: 3rem; border-top: 1px solid #eee; }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
            nav ul { gap: .6rem; }
            .page-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<nav>
    <?php if ($role === 'admin'): ?>
        <a class="brand" href="<?= $root ?>views/admin/dashboard.php">🍽 FoodBlog</a>
        <ul>
            <li><a href="<?= $root ?>views/admin/dashboard.php">Dashboard</a></li>
            <li><a href="<?= $root ?>views/admin/restaurants.php">Restaurants</a></li>
            <li><a href="<?= $root ?>views/restaurant/list.php">Public View</a></li>
        </ul>
    <?php else: ?>
        <a class="brand" href="<?= $root ?>index.php">🍽 FoodBlog</a>
        <ul>
            <li><a href="<?= $root ?>views/restaurant/list.php">Restaurants</a></li>
            <li><a href="<?= $root ?>index.php">Admin Login</a></li>
        </ul>
    <?php endif; ?>

    <div class="user-info">
        <?php if ($name): ?>
            👤 <?= htmlspecialchars($name) ?> &nbsp;|&nbsp;
            <a href="<?= $root ?>controllers/authController.php?action=logout" style="color:#e94560;">Logout</a>
        <?php endif; ?>
    </div>
</nav>
