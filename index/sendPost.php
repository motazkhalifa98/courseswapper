<?php
    require_once 'mysql.php';
    require_once 'session.php';
    session_start();
    $conn = get_connection();
    $usr_id = $_SESSION["userId"];
    $courseOffer = $_POST['courseOffer'];
    $courseRequest = $_POST['courseRequest'];
    $sql = "INSERT INTO swapRequest (userId, courseOffer, courseRequest)
    VALUES ('$usr_id', '$courseOffer', '$courseRequest');";
    query_database($conn, $sql);
    header("Location: post.php?id=".$usr_id);
    exit();
?>