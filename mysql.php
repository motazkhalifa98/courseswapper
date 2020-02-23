 
<?php

function establish_connection($servername, $username, $password, $dbname) {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


//queries database with given sql command and returns result 
function query_database($conn, $sql_command) {
    return $conn->query($sql_command);

}

function verify_email($result) {
    //SUCCESSFULLY READ DATA FOR SPECIFIED PLAINTEXT EMAIL
    //$row[0] is userId 
    //$row[1] is userEmail
    //$row[2] is userPassword

    if ($result->num_rows > 0) {
        $row = $result -> fetch_row();
        foreach ($row as $item) {
        printf("%s \n", $item);
        }    
        //printf ("%s \n", $row);
        echo "Email Exists!";
    } else {
        echo "EMAIL DOESN'T EXIST " . $conn->error;
    }

}

function close_connection($conn) {
    $conn->close();
}

//SHOULD PROBABLY NOT KEEP THIS IN PLAINTEXT
$servername = "tethys";
$username = "arielshe";
$password = "50233154";
$dbname = "cse442_542_2020_spring_teamae_db";

$connection = establish_connection($servername, $username, $password, $dbname);
$sql = "SELECT * FROM userInfo WHERE userEmail = 'admin@example.com'";
$result = query_database($connection, $sql);
verify_email($result);
close_connection($connection);


?>