<?php
// views/partials/header_plain.php
// Used by root index.php (login page)
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

        nav {
            background: #1a1a2e;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
        }
        nav .brand { font-size: 1.3rem; font-weight: 700; color: #e94560; text-decoration: none; letter-spacing: 1px; }
        nav ul { list-style: none; display: flex; gap: 1.2rem; align-items: center; }
        nav ul a { color: #ccc; text-decoration: none; font-size: .92rem; padding: .3rem .6rem; border-radius: 4px; transition: background .2s, color .2s; }
        nav ul a:hover { background: #e94560; color: #fff; }

        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1.5rem; }

        .flash { padding: .85rem 1.2rem; border-radius: 6px; margin-bottom: 1.5rem; font-weight: 500; }
        .flash.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash.error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,.08); padding: 2rem; margin-bottom: 1.5rem; }

        .btn { display: inline-block; padding: .55rem 1.2rem; border-radius: 6px; font-size: .9rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: opacity .15s; }
        .btn:hover { opacity: .85; }
        .btn-primary   { background: #e94560; color: #fff; }
        .btn-secondary { background: #1a1a2e; color: #fff; }
        .btn-sm        { padding: .35rem .8rem; font-size: .82rem; }

        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: .4rem; font-weight: 600; font-size: .9rem; color: #444; }
        .form-group input {
            width: 100%; padding: .65rem .9rem; border: 1px solid #ddd;
            border-radius: 6px; font-size: .95rem; font-family: inherit; transition: border-color .2s;
        }
        .form-group input:focus { outline: none; border-color: #e94560; }
        .field-error { color: #dc3545; font-size: .82rem; margin-top: .3rem; min-height: 1rem; }

        footer { text-align: center; padding: 2rem; color: #aaa; font-size: .85rem; margin-top: 3rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<nav>
    <a class="brand" href="index.php">🍽 FoodBlog</a>
    <ul>
        <li><a href="views/restaurant/list.php">Restaurants</a></li>
    </ul>
</nav>
