<?php

$host="127.0.0.1";
$dbuser="root";
$dbname="online_food_blog";
$dbpass="";
function getConnection(){

global $host;
global $dbuser;
global $dbname;
global $dbpass;
$con=mysqli_connect($host,$dbuser,$dbpass,$dbname);
if(!$con){
        die("Connection Failed: " . mysqli_connect_error());
    }
    else{
        echo "Database Connected Successfully";
    }

    return $con;
}

getConnection();


?>
