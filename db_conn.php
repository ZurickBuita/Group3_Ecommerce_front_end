<?php
$conn = mysqli_connect("localhost", "root", "", "codetech");


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<script>
        console.log('Connected successfully!');
    </script>";

?>