<?php
	/*****************************************************************
	 * admin/user/modify_action.php  (c) 2005 Jonathan Dieter
	 *
	 * Run query to modify a user in the database.
	 *****************************************************************/

	/* Get variables */
	$error    = false;        // Boolean to store any errors
	$uname    = safe(dbfuncInt2String($_GET['key']));
	$fullname = dbfuncInt2String($_GET['keyname']) . " (" . $uname . ")";
	
	 /* Check whether user is authorized to modify users */
	if($is_admin) {

		/* Modify user */
		$query =    "UPDATE user SET FirstName = '{$_POST['fname']}', " .
									"Surname = '{$_POST['sname']}', " .
									"Gender = '{$_POST['gender']}', " .
									"PhoneNumber = '{$_POST['phone']}', " .
									"DOB = {$_POST['DOB']}, " .
									"Permissions = {$_POST['perms']}, " .
									"Title = {$_POST['title']}, " .
									"DateType = {$_POST['datetype']}, " .
									"DateSeparator = {$_POST['datesep']}, " .
									"ActiveStudent = {$_POST['activestudent']}, " .
									"ActiveTeacher = {$_POST['activeteacher']}, " .
									"SupportTeacher = {$_POST['supportteacher']}, " .
									"DepartmentIndex = {$_POST['department']}, " .
									"User1 = {$_POST['user1']}, " .
									"User2 = {$_POST['user2']}";
		if(isset($_POST['password']) && $_POST['password'] != "") {
			$query .= ", Password = MD5('{$_POST['password']}')";
		}
		if(isset($_POST['password2']) && $_POST['password2'] != "") {
			$query .= ", Password2 = MD5('{$_POST['password2']}')";
		}
		$query .=   " WHERE username = '$uname'";
		$aRes =& $db->query($query);
		if(DB::isError($aRes)) die($aRes->getDebugInfo());           // Check for errors in query
		log_event($LOG_LEVEL_ADMIN, "admin/user/modify_action.php", $LOG_ADMIN,
				"Modified {$_POST['fname']} {$_POST['sname']} ($uname).");
	} else {  // User isn't authorized to view or change users.
		/* Log unauthorized access attempt */
		log_event($LOG_LEVEL_ERROR, "admin/user/modify_action.php", $LOG_DENIED_ACCESS,
				"Attempted to modify user $fullname.");
		echo "</p>\n      <p>You do not have permission to modify this user.</p>\n      <p>";
		$error = true;
	}
?>
