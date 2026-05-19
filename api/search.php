<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$q        = trim($_GET['q'] ?? '');
$location = trim($_GET['location'] ?? '');
$area     = trim($_GET['area'] ?? '');

$con = getConnection();

$searchText   = '%' . $q . '%';
$locationText = '%' . $location . '%';
$areaText     = '%' . $area . '%';

$sql = "SELECT 
            restaurants.id AS restaurant_id,
            restaurants.name AS restaurant_name,
            restaurants.location,
            restaurants.area,
            restaurants.short_background,
            menu_items.id AS item_id,
            menu_items.name AS item_name,
            menu_items.price
        FROM restaurants
        LEFT JOIN menu_items ON restaurants.id = menu_items.restaurant_id
        WHERE (restaurants.name LIKE ? OR menu_items.name LIKE ? OR menu_items.description LIKE ?)
        AND restaurants.location LIKE ?
        AND restaurants.area LIKE ?
        ORDER BY restaurants.name ASC";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "sssss", $searchText, $searchText, $searchText, $locationText, $areaText);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

mysqli_close($con);
echo json_encode($data);
?>
