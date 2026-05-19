<?php

$host   = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "foodBlog";

function getConnection() {
    $con = mysqli_connect(
        $GLOBALS['host'],
        $GLOBALS['dbuser'],
        $GLOBALS['dbpass'],
        $GLOBALS['dbname']
    );
    if (!$con) {
        die(json_encode(['error' => 'DB Connection Failed: ' . mysqli_connect_error()]));
    }
    return $con;
}
