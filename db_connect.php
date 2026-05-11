<?php
$connection = mysqli_connect("localhost", "root", "", "toolkitdb");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>