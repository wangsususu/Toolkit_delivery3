<?php
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: toolkit_add.html");
    exit();
}

$sellerID = 1;

$title = $_POST["title"] ?? "";
$description = $_POST["description"] ?? "";
$category = $_POST["category"] ?? "";
$new_category = $_POST["new_category"] ?? "";
$brand = $_POST["brand"] ?? "";
$model = $_POST["model"] ?? "";
$price = $_POST["price"] ?? "";
$original_price = $_POST["original_price"] ?? "";
$shipping_type = $_POST["shipping"] ?? "free";
$shipping_fee = $_POST["shipping_fee"] ?? "0";
$condition = $_POST["condition"] ?? "";
$stock = $_POST["stock"] ?? "";

if ($category === "__new__") {
    $category = $new_category;
}

if (
    $title === "" ||
    $description === "" ||
    $category === "" ||
    $brand === "" ||
    $price === "" ||
    $condition === "" ||
    $stock === ""
) {
    die("
        <h1>Missing required product information.</h1>
        <p>Please go back and complete all required fields.</p>
        <a href='toolkit_add.html'>Back to Add Product</a>
    ");
}

if ($original_price === "") {
    $original_price_sql = "NULL";
} else {
    $original_price_sql = "'" . mysqli_real_escape_string($connection, $original_price) . "'";
}

if ($shipping_fee === "") {
    $shipping_fee = "0";
}

$title = mysqli_real_escape_string($connection, $title);
$description = mysqli_real_escape_string($connection, $description);
$category = mysqli_real_escape_string($connection, $category);
$brand = mysqli_real_escape_string($connection, $brand);
$model = mysqli_real_escape_string($connection, $model);
$price = mysqli_real_escape_string($connection, $price);
$shipping_type = mysqli_real_escape_string($connection, $shipping_type);
$shipping_fee = mysqli_real_escape_string($connection, $shipping_fee);
$condition = mysqli_real_escape_string($connection, $condition);
$stock = mysqli_real_escape_string($connection, $stock);

$image_path = "";

if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] === 0) {
    $upload_folder = "uploads/";

    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }

    $original_file_name = basename($_FILES["product_image"]["name"]);
    $file_extension = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

    $allowed_extensions = ["jpg", "jpeg", "png", "webp"];

    if (in_array($file_extension, $allowed_extensions)) {
        $new_file_name = time() . "_" . uniqid() . "." . $file_extension;
        $target_file = $upload_folder . $new_file_name;

        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }
}

$image_path = mysqli_real_escape_string($connection, $image_path);

$sql = "INSERT INTO products
(sellerID, title, description, category, brand, model, price, original_price, shipping_type, shipping_fee, product_condition, stock, image_path)
VALUES
('$sellerID', '$title', '$description', '$category', '$brand', '$model', '$price', $original_price_sql, '$shipping_type', '$shipping_fee', '$condition', '$stock', '$image_path')";

$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Upload Result — Toolkit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #FEFDF8;
            color: #2C2C2C;
            padding: 60px;
            text-align: center;
        }

        .box {
            max-width: 560px;
            margin: 0 auto;
            background: #FFFFFF;
            border: 1px solid #DDD8CE;
            border-radius: 12px;
            padding: 42px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-bottom: 16px;
            font-size: 32px;
        }

        p {
            font-size: 16px;
            color: #555555;
            line-height: 1.7;
        }

        a {
            display: inline-block;
            margin: 12px 8px 0;
            padding: 12px 24px;
            background: #2C2C2C;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .secondary {
            background: #ABBBD1;
            color: #1A1A1A;
        }

        .error {
            color: #D64444;
            word-break: break-word;
        }
    </style>
</head>

<body>
    <div class="box">
        <?php
        if ($result) {
            echo "<h1>Product uploaded successfully.</h1>";
            echo "<p>The product information has been saved into the database.</p>";

            if ($image_path !== "") {
                echo "<p>Image saved at: " . $image_path . "</p>";
            } else {
                echo "<p>No product image was uploaded, but the product data was saved.</p>";
            }

            echo "<a href='toolkit_add.html'>Add another product</a>";
            echo "<a class='secondary' href='toolkit_home.html'>Back to Home</a>";
        } else {
            echo "<h1>Upload failed.</h1>";
            echo "<p class='error'>Error: " . mysqli_error($connection) . "</p>";
            echo "<a href='toolkit_add.html'>Go back</a>";
        }

        mysqli_close($connection);
        ?>
    </div>
</body>

</html>