<?php
    require_once 'connect.php';

    $usr_id = 69;
    $course_id = $_POST['course_id'];
    $course_name = "cseCourse";
    $days = $_POST['days'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $loc = $_POST['location'];

    $sql = "INSERT INTO courseInfo (userId, courseId, courseName, days, startTime, endTime, location)
    VALUES ('$usr_id', '$course_id', '$course_name', '$days', '$start', '$end', '$loc');";
    mysqli_query($conn, $sql);
    header("Location: ../account.html?courseSent=success");
?>