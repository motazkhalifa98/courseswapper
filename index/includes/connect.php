<?php
    $servername = "tethys";
    $username = "mjspagna";
    $password = "50148235";
    $dbname = "cse442_542_2020_spring_teamae_db";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>