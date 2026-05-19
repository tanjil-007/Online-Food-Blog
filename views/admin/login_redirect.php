<?php
session_start();
$_SESSION['flash_error'] = 'You must be logged in as Admin to access that page.';
header('Location: ../../index.php');
exit;
