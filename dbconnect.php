<?php

$connection = mysqli_connect(
    "localhost",
    "root",
    "",
    "toolkitdb"
);

if (!$connection) {
    die("Connection Failed");
}

?>