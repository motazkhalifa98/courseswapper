 
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

        
    function encrypt($string) {
        return sha1($string);
    }

    //sql statement verifies email & password with SHA1 encryption`
    function verify_email($conn, $email, $user_pass) {

        $stmt = $conn->prepare("SELECT * FROM userInfo WHERE (userEmail, userPassword) = (SHA1(?) , SHA1(?))");
        $stmt->bind_param("ss", $hash_em, $hash_pass);  

        $hash_em = $email;
        $hash_pass = $user_pass;
        $stmt->execute();

        $res = $stmt->get_result();
        $result = $res->fetch_assoc();


        //hash parameters

//        $sql = "SELECT * FROM userInfo WHERE (userEmail, userPassword) = (SHA1('" . $email . "'), SHA1('" . $user_pass . "'))";

        if ($result != null) {
            // $row = $result -> fetch_row();
            //echo "Email Exists AND Password correct!";
            return true;
        } else {
            //echo "EMAIL/PASSWORD INCORRECT " . $conn->error;
            return false;
        }

    }

    function close_connection($conn) {
        $conn->close();
    }


    function run_db_check_email($email, $pass) {

        $connection = get_connection();
        //$email = "'admin@example.com'";
        $email_exists = (verify_email($connection, $email, $pass));
        close_connection($connection);
        return $email_exists;

    }

    function get_connection() {
        //SHOULD PROBABLY NOT KEEP THIS IN PLAINTEXT
        $servername = "tethys";
        $username = "arielshe";
        $password = "50233154";
        $dbname = "cse442_542_2020_spring_teamae_db";

        return establish_connection($servername, $username, $password, $dbname);
    }

    function check_email_only($email) {
        $connection = get_connection();
        $bool = email_only($connection, $email);
        close_connection($connection);
        return $bool;
    }

    function email_only($conn, $email) {

        $stmt = $conn->prepare("SELECT * FROM userInfo WHERE (userEmail)= (SHA1(?))");
        $stmt->bind_param("s", $hash_em);  

        $hash_em = $email;
        $stmt->execute();

        $res = $stmt->get_result();
        $result = $res->fetch_assoc();
        $stmt->close();

        if ($result != null) {

            return true;
        } else {

            return false;
        }

    }


    function get_name($email) {
        $connection = get_connection();
        $result = get_name_helper($connection, $email);
        close_connection($connection);
        return $result;
    }

    /*
    TODO: 
    takes in userEmail and returns a array firstName ,lastName) returns ("ERROR", "ERROR") if not found
    */
    function get_name_helper($conn, $email) {
        $stmt = $conn->prepare("SELECT * FROM userInfo WHERE (userEmail)= (SHA1(?))");
        $stmt->bind_param("s", $hash_em);  

        $hash_em = $email;
        $stmt->execute();

        $res = $stmt->get_result();
        $result = $res->fetch_assoc();
        // var_dump($result);
        $stmt->close();

        if ($result != null) {
            $f_name = $result["userFirstName"];
            $l_name = $result["userLastName"]; 
            $uid = $result["userId"];
            return array("firstName" => $f_name, "lastName" => $l_name, "userId" => $uid);
        } else {
            return array("firstName" => "ERROR", "lastName" => "ERROR", "userId" => -1);
        }
    }

    // function test_get_name() {
    //     var_dump(get_name("steve@boy.com"));
    // }
    // test_get_name();

    function register_user($email, $password, $first_name, $last_name) {
        $connection = get_connection();

        $stmt = $connection->prepare("INSERT INTO `cse442_542_2020_spring_teamae_db`.`userInfo` (`userId`, `userEmail`, `userPassword`,`userFirstName`,`userLastName`) VALUES (NULL, SHA1(?), SHA1(?), (?), (?))");
        $stmt->bind_param("ssss", $email, $password, $first_name, $last_name);
        $stmt->execute();

        $stmt->close();
        
//        $sql = "INSERT INTO `cse442_542_2020_spring_teamae_db`.`userInfo` (`userId`, `userEmail`, `userPassword`,`userFirstName`,`userLastName`) VALUES (NULL, SHA1('" . $email . "'), SHA1('". $password ."'),SHA1('" . $first_name . "'),SHA1('" . $last_name . "'))";

        // $res = $stmt->get_result();
        // $result = $res->fetch_assoc();
        //echo $result;
        close_connection($connection);
    }

    // register_user("ariel@ariel.com","password","ariel","bariel");

    // $email = "steve@example.com";
    // $password = "stevepassword";
    // $first_name = "Steve";
    // $last_name = "Peeve";
    // register_user($email, $password, $first_name, $last_name);
    
    
    /********************************************************************************/
    /* FUNCTION:	is_conflict                                                     */
    /* DESCRIPTION:	Checks to see if two classes have conflicting timeslots.	    */
    /* PARAMETERS:	$sT1 - startTime of first class                                 */
    /* 				$eT1 - endTime of first class                                   */
    /* 				$sT2 - startTime of second class                                */
    /* 				$eT2 - endTime of second class                                  */
    /* RETURNS:		1 if the slots conflict, else 0                                */
    /********************************************************************************/
    function is_conflict($sT1, $eT1, $sT2, $eT2) {
		if ( (($sT1 < $sT2) && ($sT2 < $eT1)) || (($sT2 < $sT1) && ($sT1 < $eT2) )) {
			return 0;
		}
		else return 1;
	}
	

	/********************************************************************************/
	/* FUNCTION: 	get_matches                                                     */
	/* DESCRIPTION: This function is to get a list of matches that the user can     */
	/* 				then message - possibly with an interactive interface.          */
	/* PARAMETERS:	$userId - the id number of the active user                      */
	/*				$class_to_drop - the courseId that the user is willing to drop  */
	/*				$class_to_get - the courseID that the user wants                */
	/* RETURNS: 	array of userIds to contact that match the criteria             */
	/********************************************************************************/
	function get_matches($userId, $class_to_drop, $class_to_get) {
		$ra = array();	//the return array; starts empty
		$connection = get_connection();
		
        //sql string to get current user's schedule using their userId
        
        $stmt_sched = $connection->prepare("SELECT * FROM courseInfo WHERE userId=(?)");
        $stmt_sched->bind_param("i", $userId);
        $stmt_sched->execute();

        

//		$sql_schedule = "SELECT * FROM courseInfo WHERE userId='$userId'";
		
		//store the active user's schedule into $result_schedule
		$result_schedule = $stmt_sched->get_result();
		
		//Now we need to fetch all the $class_to_get courseIds that fit into the active user's schedule
        $sql_get_class = $connection->prepare("SELECT * FROM courseInfo WHERE NOT userId=(?) AND courseId=(?)");
        $sql_get_class->bind_param("is",$userId, $class_to_get);
//        $sql_get_class = "SELECT * FROM courseInfo WHERE NOT userId='$userId' AND courseId='$class_to_get'";
		$result = $sql_get_class->get_result();
		
		
		//this loops through each row for a result that matches courseId
		while ($row = $result->fetch_assoc()) {
			$total_conflicts = 0;
			while ($row_to_drop = $result_schedule->fetch_assoc()) {
								
				if ($row_to_drop["courseId"] != $class_to_drop) {
					
					$conflict = is_conflict($row["startTime"], $row["endTime"], $row_to_drop["startTime"], $row_to_drop["endTime"]);
					
				}
				//echo "Conflict = " . $conflict . "<br>";
				if ($conflict == 1) {
					$total_conflicts += 1;
				}
			}
			if ($total_conflicts == 0) {
				//echo "What are we pushing: " . $row["userId"] . "<br>";
				array_push($ra, $row["userId"]);
				//echo "DGB: After pushing to array.<br>";
			}
        }
        
		
		close_connection($connection);
		
		/*
		$arr_len = count($ra);
		
		echo "The array being returned from get_matches()<br>";
		echo "# Elements: " . $arr_len . "<br>";

		for ($i = 0; $i < $arr_len; $i++) {
			echo "Element: " . $i . " - " . $ra[$i] . "<br>";
		}
		*/
		
		return $ra;
    }
    
    
	/********************************************************************************/
	/* FUNCTION: 	test_matches                                                    */
	/* DESCRIPTION: This function is to test the get_matches function               */
	/* PARAMETERS:	none                                                            */
	/* RETURNS: 	none                                                            */
	/********************************************************************************/
	
	function test_matches() {
		$curr_id = 8;
		$first_class = 'CSE 474';
		$second_class = 'MTH 241';
		
        $connection = get_connection();
        /**
         *  KEEPING NOT PREPARED STATEMENTS SINCE THIS IS ONLY A TEST FUNCTION AND DOES NOT
         *  TAKE IN ANY USER INPUT (NOT A SECURITY VULNERABILITY)
         */
		$sql = "SELECT * FROM courseInfo WHERE userId='$curr_id'";
		$result = query_database($connection, $sql);
		
		while ($row = $result->fetch_assoc()) {
			if ($row["courseId"] == $first_class) {
				break;
			}
		}
		
		echo "Current userId: " . $row["userId"] . " Class to drop- courseId: " . $row["courseId"] . " days: " . $row["days"] . " startTime: " . $row["startTime"] . " endTime: " . $row["endTime"] . "<br>";

				
		$arr = get_matches($curr_id, $first_class, $second_class);
		echo "<br>Matches: <br>";
		
		$arr_len = count($arr);
		echo "# Elements: " . $arr_len . "<br>";

		for ($i = 0; $i < $arr_len; $i++) {
			echo "Element: " . $i . " - " . $arr[$i] . "<br>";
			$sql = "SELECT * FROM courseInfo WHERE userId='$arr[$i]' AND courseId='$second_class'";
			$result = query_database($connection, $sql);
			$row = $result->fetch_assoc();
			echo "userId: " . $row["userId"] . " courseId: " . $row["courseId"] . " days: " . $row["days"] . " startTime: " . $row["startTime"] . " endTime: " . $row["endTime"] . "<br>";
		}
		
	}
	


?>
