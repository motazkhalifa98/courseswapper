<?php 
$password_good = false;

//echo "hello\n";



//call this function to hash your password
//takes in raw password (plaintext) and returns hashed password
function hash_pass($raw_pass) {
	return password_hash($raw_pass, PASSWORD_BCRYPT, array('cost' => 12));
}

//example on how to call it
//this echo's the password hash back
// echo hash_pass("password");

//call this funciton to verify your password
//takes in HASHED password and returns true if matches password for user false otherwise 
function verify_hash($raw_pass, $hash_pass) {
	if (password_verify($raw_pass,$hash_pass)) {
		echo "GOOD";
		return True;
	} else {
		echo "BAD";
		return False;
	}
}

//SQL STUFF

//TODO: check if email is within database
//takes in email string and returns true if HASHED value is found within database else return FALSE 
function check_email($email) {

	return false;
}


//TODO: check if password is within database (CALL AFTER EMAIL IS VERIFIED TO EXIST)
//takes in password string and returns true if password hashes match (GRANT ENTRY) false otherwise (DENY)
function check_password($passwd) {

	return false;
}


//tests
$email_hash = hash_pass("admin@example.com");
$pass_hash = hash_pass("password");
echo $email_hash . "\n";
echo $pass_hash;
// $hash = '$2y$12$hmMCJVP3rMRp0UKkcGrbMuqEbjs3EwTaMI6NekP0V2tpQFxmq2fFu';
// verify_pass($hash);


?>


<!-- /**
	INSERT INTO userInfo VALUES (3, "$2y$12$MWJuRlvQxleFzsdEmDIC8.OMUXz.0FQZkfWGk.u.wN6psWEx9GsC.", "$2y$12$lGfZCv7e.L8XvJFL7Wxlf.cshFSdi20xbIOYFbyIeDUM9Eh2bpcJa%");
	SELECT * FROM userInfo WHERE (userEmail, userPassword) = (SHA1('admin@example.com'), SHA1('password'));
 */ -->
