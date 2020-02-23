 
<?php
$email_exists = false;

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

function verify_email($conn, $email) {
    //SUCCESSFULLY READ DATA FOR SPECIFIED PLAINTEXT EMAIL
    //$row[0] is userId 
    //$row[1] is userEmail
    //$row[2] is userPassword
    $sql = "SELECT * FROM userInfo WHERE userEmail = " . $email;
    $result = query_database($conn, $sql);
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
$email = "'admin@example.com'";
verify_email($connection, $email);
close_connection($connection);


?>