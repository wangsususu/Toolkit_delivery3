<?php

session_start();

include("dbconnect.php");

$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT * FROM sellers

WHERE username='$username'

AND password='$password'";

$result = mysqli_query($connection, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Result</title>
</head>

<body>

    <?php

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["username"] = $username;

        header("Location: seller.html");
    } else {
        echo "<h1>Invalid Username or Password</h1>";

        echo "<a href='login.html'>
    Try Again
    </a>";
    }

    mysqli_close($connection);

    ?>

</body>

</html>