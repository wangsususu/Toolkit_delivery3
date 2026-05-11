<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "toolkitdb";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die(json_encode([
        "success" => false,
        "message" => "Failed to connect the database" . mysqli_connect_error()
    ]));
}

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

$keyword = mysqli_real_escape_string($conn, $keyword);
$category = mysqli_real_escape_string($conn, $category);

$sql = "SELECT * FROM products WHERE 1=1";

if (!empty($keyword)) {
    $sql .= " AND (title LIKE '%$keyword%' OR description LIKE '%$keyword%')";
}

if ($category !== "all") {
    $sql .= " AND category = '$category'";
}

$result = mysqli_query($conn, $sql);
$products = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = [
            "name" => $row['title'],
            "desc" => $row['description'],
            "price" => "$" . number_format($row['price'], 2),
            "category" => $row['category'],
            "icon" => getIconByCategory($row['category'])
        ];
    }
}

echo json_encode([
    "success" => true,
    "count" => count($products),
    "data" => $products
]);

mysqli_close($conn);

function getIconByCategory($cat) {
    $icons = [
        "writing" => "fas fa-pen-nib",
        "paper" => "fas fa-book-open",
        "office" => "fas fa-cut",
        "storage" => "fas fa-box",
        "accessories" => "fas fa-eraser",
        "creative" => "fas fa-palette"
    ];
    return $icons[$cat] ?? "fas fa-box";
}
?>
