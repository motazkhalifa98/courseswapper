<?php
    //ends session
    function end_session() {
        session_unset();
        session_destroy();
    }

    // session_start();
    // $_SESSION["userId"] = 1;
    // $_SESSION["firstName"] = "steve";
    // $_SESSION["lastName"] = "boy";
    // $_SESSION["userEmail"] = "steve@boy.com";
    // print_r($_SESSION);

    // end_session();
?>