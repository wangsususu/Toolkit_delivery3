<?php

include("dbconnect.php");

$name = $_POST["name"];
$address = $_POST["address"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$username = $_POST["username"];
$password = $_POST["password"];

$sql = "INSERT INTO sellers
(name,address,phone,email,username,password)

VALUES

('$name','$address','$phone',
'$email','$username','$password')";

$result = mysqli_query($connection, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Registration Result</title>
</head>

<body>

    <?php

    if ($result) {
        echo "<h1>Registration Successful</h1>";

        echo "<a href='login.html'>
    Go to Login
    </a>";
    } else {
        echo "<h1>Registration Failed</h1>";
    }

    mysqli_close($connection);

    ?>

</body>

</html>