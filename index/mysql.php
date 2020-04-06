 
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

        $sql = "SELECT * FROM userInfo WHERE (userEmail, userPassword) = (SHA1('" . $email . "'), SHA1('" . $user_pass . "'))";
        //$sql = "SELECT * FROM userInfo WHERE (userEmail, userPassword) = ('" . encrypt($email) . "', SHA1('" . encrypt($user_pass) . "'))";
        $result = query_database($conn, $sql);
        if ($result->num_rows > 0) {
            $row = $result -> fetch_row();
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
        $sql = "SELECT * FROM userInfo WHERE (userEmail) = (SHA1('" . $email . "'))";
        //$sql = "SELECT * FROM userInfo WHERE (userEmail, userPassword) = ('" . encrypt($email) . "', SHA1('" . encrypt($user_pass) . "'))";
        $result = query_database($conn, $sql);
        if ($result->num_rows > 0) {
            $row = $result -> fetch_row();
            //echo "Email Exists";
            return true;
        } else {
            //echo "Email does not exist" . $conn->error;
            return false;
        }
    }


    function register_user($email, $password, $first_name, $last_name) {
        $connection = get_connection();

        $sql = "INSERT INTO `cse442_542_2020_spring_teamae_db`.`userInfo` (`userId`, `userEmail`, `userPassword`,`userFirstName`,`userLastName`) VALUES (NULL, SHA1('" . $email . "'), SHA1('". $password ."'),SHA1('" . $first_name . "'),SHA1('" . $last_name . "'))";
        echo $sql; 
        $result = query_database($connection, $sql);
        echo $result;
        //echo $result;
        close_connection($connection);
    }

    // $email = "steve@example.com";
    // $password = "stevepassword";
    // $first_name = "Steve";
    // $last_name = "Peeve";
    // register_user($email, $password, $first_name, $last_name);
    
    
    /********************************************************************************/
    /* FUNCTION:	is_time_conflict                                                */
    /* DESCRIPTION:	Checks to see if two classes have conflicting timeslots.	    */
    /* PARAMETERS:	$s1 - startTime of first class                                  */
    /* 				$e1 - endTime of first class                                    */
    /* 				$s2 - startTime of second class                                 */
    /* 				$e2 - endTime of second class                                   */
    /* RETURNS:		1 if the slots conflict, else 0                                 */
    /********************************************************************************/
    function is_time_conflict($s1, $e1, $s2, $e2) {
		/*Parse the string inputs to floating point numbers to compare properly*/
		/*Parse $s1*/
		if (strlen($s1) == 4) {
			$rs1 = substr($s1, 0);
		}
		else if (strlen($s1) == 5) {
			$rs1 = substr($s1, 0, 2);
		}
		$rs1 += (substr($s1, -2, 2) / 100);
		
		/*Parse $e1*/
		if (strlen($e1) == 4) {
			$re1 = substr($e1, 0);
		}
		else if (strlen($e1) == 5) {
			$re1 = substr($e1, 0, 2);
		}
		$re1 += (substr($e1, -2, 2) / 100);
		
		/*Parse $s2*/
		if (strlen($s2) == 4) {
			$rs2 = substr($s2, 0);
		}
		else if (strlen($s2) == 5) {
			$rs2 = substr($s2, 0, 2);
		}
		$rs2 += (substr($s2, -2, 2) / 100);
		
		/*Parse $e2*/
		if (strlen($e2) == 4) {
			$re2 = substr($e2, 0);
		}
		else if (strlen($e2) == 5) {
			$re2 = substr($e2, 0, 2);
		}
		$re2 += (substr($e2, -2, 2) / 100);
		
		
		/*Do the actual comparison and return*/
		if ( ($re1 < $rs2) || ($re2 < $rs1) ) {
			return 0;
		}
		return 1;
	}
	
	
	/********************************************************************************/
	/* FUNCTION:	is_day_conflict												    */   
	/* DESCRIPTION:	Checks to see if two classes have conflicting days.             */	                                                                        
	/* PARAMETERS:	$classA_days - the days that one of the classes is on           */
	/* 				$classB_days - the days that the other class is on              */                                                            
	/* RETURNS:		1 if the days conflict, else 0                                  */
	/********************************************************************************/    
	function is_day_conflict($classA_days, $classB_days) {
		if ($classA_days == $classB_days) {	
			return 1;
		}
		if ($classA_days == 'M,W,F' && ($classB_days == 'M' || $classB_days == 'W' || $classB_days == 'F')) {
			return 1;
		}
		if ($classB_days == 'M,W,F' && ($classA_days == 'M' || $classA_days == 'W' || $classA_days == 'F')) {
			return 1;
		}
		if ($classA_days == 'T,Th' && ($classB_days == 'T' || $classB_days == 'Th')) {
			return 1;
		}
		if ($classB_days == 'T,Th' && ($classA_days == 'T' || $classA_days == 'Th')) {
			return 1;
		}
		else
			return 0;
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
		$sql_schedule = "SELECT * FROM courseInfo WHERE userId='$userId'";
		
		//store the active user's schedule into $result_schedule
		$result_schedule = query_database($connection, $sql_schedule);
		
		//Now we need to fetch all the $class_to_get courseIds that fit into the active user's schedule
		$sql_get_class = "SELECT * FROM courseInfo WHERE NOT userId='$userId' AND courseId='$class_to_get'";
		$result = query_database($connection, $sql_get_class);
		
		
		//this loops through each row for a result that matches courseId
		while ($row = $result->fetch_assoc()) {
			$total_conflicts = 0;
			while ($row_to_drop = $result_schedule->fetch_assoc()) {
								
				if ($row_to_drop["courseId"] != $class_to_drop) {
					
					$conflict = is_time_conflict($row["startTime"], $row["endTime"], $row_to_drop["startTime"], $row_to_drop["endTime"]);
					
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
	
	
	/********************************************************************************/
	/* FUNCTION: 	test_is_time_conflict                                           */
	/* DESCRIPTION: This function is to test the is_time_conflict function          */
	/* PARAMETERS:	none                                                            */
	/* RETURNS: 	none                                                            */
	/********************************************************************************/	
	function test_is_time_conflict() {
		echo "<br>Testing is_time_conflict function:<br>";
		echo "---------------------------------------------------<br>";
		
		echo "Test 1: inputs - s1: 8:00; e1: 8:50; s2: 10:00; e2: 10:50;    Expected Output: 0;  ";
		$s1 = '8:00';
		$e1 = '8:50';
		$s2 = '10:00';
		$e2 = '10:50';
		
		$ret = is_time_conflict($s1, $e1, $s2, $e2);
		echo "Actual output: " . $ret . "    ";
		if ($ret == 0) {
			echo "<br>Test PASSED.<br><br>";
		}
		else {
			echo "<br>Test FAILED!<br><br>";
		}
		
		echo "Test 2: inputs - s1: 10:00; e1: 10:50; s2: 8:00; e2: 8:50;    Expected Output: 0;  ";
		$s1 = '10:00';
		$e1 = '10:50';
		$s2 = '8:00';
		$e2 = '8:50';
		
		$ret = is_time_conflict($s1, $e1, $s2, $e2);
		echo "Actual output: " . $ret . "    ";
		if ($ret == 0) {
			echo "<br>Test PASSED.<br><br>";
		}
		else {
			echo "<br>Test FAILED!<br><br>";
		}
		
		echo "Test 3: inputs - s1: 8:00; e1: 9:50; s2: 9:00; e2: 10:50;    Expected Output: 1;  ";
		$s1 = '8:00';
		$e1 = '9:50';
		$s2 = '9:00';
		$e2 = '10:50';
		
		$ret = is_time_conflict($s1, $e1, $s2, $e2);
		echo "Actual output: " . $ret . "    ";
		if ($ret == 1) {
			echo "<br>Test PASSED.<br><br>";
		}
		else {
			echo "<br>Test FAILED!<br><br>";
		}
		
		echo "Test 4: inputs - s1: 9:00; e1: 10:50; s2: 8:00; e2: 9:50;    Expected Output: 1;  ";
		$s1 = '9:00';
		$e1 = '10:50';
		$s2 = '8:00';
		$e2 = '9:50';
		
		$ret = is_time_conflict($s1, $e1, $s2, $e2);
		echo "Actual output: " . $ret . "    ";
		if ($ret == 1) {
			echo "<br>Test PASSED.<br><br>";
		}
		else {
			echo "<br>Test FAILED!<br><br>";
		}
		
		echo "Test 5: inputs - s1: 11:00; e1: 11:50; s2: 11:00; e2: 11:50;    Expected Output: 1;  ";
		$s1 = '11:00';
		$e1 = '11:50';
		$s2 = '11:00';
		$e2 = '11:50';
		
		$ret = is_time_conflict($s1, $e1, $s2, $e2);
		echo "Actual output: " . $ret . "    ";
		if ($ret == 1) {
			echo "<br>Test PASSED.<br><br>";
		}
		else {
			echo "<br>Test FAILED!<br><br>";
		}
		
		echo "End of is_time_conflict test<br>";
		echo "-----------------------------------------------<br><br>";
		
	}
	
	
	/********************************************************************************/
	/* FUNCTION: 	test_is_day_conflict                                            */
	/* DESCRIPTION: This function is to test the is_day_conflict function           */
	/* PARAMETERS:	none                                                            */
	/* RETURNS: 	none                                                            */
	/********************************************************************************/
	function test_is_day_conflict() {
		echo "\nTesting is_day_conflict function:\n";
		echo "---------------------------------------------------\n\n";
	}
	


?>
