<?php
/* 
	File	: ajax/default.php
	Author	: Abhishek Nath
	Date	: 01-Jan-2015
	Desc	: this file is being called through ajax mechanism.
			  it will do all the server-side work and pass the
			  result to client-side through ajax.
			  
			  N.B: whenever you call via ajax, pass this file path as link
			       for server-side work.
--*/

/*-- 
	01-Jan-15   V1-01-00   abhishek   $$1   Created.
	17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
--*/

/* initalize calling function*/

require_once ('../inc/functions.inc.php');
require_once ('../inc/mysql_functions.inc.php');
require_once ('../inc/cipher.inc.php');
	

// Initialize session data
session_start();
	
if(function_exists($_GET['f'])) { 
	// get function name and parameter  
	if($_GET['f'] == 'updateSPRTrackingCallback')
		$_GET['f']($_GET["spr_no"], $_GET["field"], $_GET["value"]);
	else if($_GET['f'] == 'updateSPRTrackingSubmissionCallback')
		$_GET['f']($_GET["spr_no"], $_GET["field"], $_GET["value"]);
	else if($_GET['f'] == 'updateWorkTrackerCallback')
		$_GET['f']($_GET["key"], $_GET["field"], $_GET["value"]);
	else if($_GET['f'] == 'getTagBGColorCallback')
		$_GET['f']($_GET["func"], $_GET["val"]);
	else if($_GET['f'] == 'addUpdateSPRSubmissionStatusCallback')
		$_GET['f']($_GET["spr_no"], $_GET["version"]);
	else if($_GET['f'] == 'shortDescriptionCallback')
		$_GET['f']($_GET["comment"], $_GET["len"]);
	else
		$_GET['f']();
}
else 
{
	echo 'Method Not Exist';
}

function updateSPRTrackingCallback($spr_no, $field, $val)
{
	$bgColor = "";
	
	$status = updateSPRTracking('spr_tracking', $field, $val, "spr_no = '". $spr_no .
									"' AND user_name = '". $_SESSION['project-managment-username'] ."'");
	if(($status == true) && ($field == "status"))
		$bgColor = getSPRTrackingStatusColor($val);
		
	echo(json_encode($bgColor));
}

function updateSPRTrackingSubmissionCallback($spr_no, $field, $val)
{
	$bgColor = "";
	
	$status = updateSPRTracking('spr_submission', $field, $val, "spr_no = '". $spr_no ."'");
	if($status == true)
		$bgColor = getSPRSubmissionColor($val);
		
	echo(json_encode($bgColor));
}

function updateWorkTrackerCallback($key, $field, $val)
{
	updateSPRTracking('work_tracker', $field, $val, "id = '". $key ."' AND user_name = '". $_SESSION['project-managment-username'] ."'");
}

function showDashboardAccdSessionCallback()
{
	$tag = "";
	
	if(!empty($_POST["func"]) && !empty($_POST["session"]))	
		$tag .= $_POST["func"]($_POST["session"]);
		
	echo(json_encode($tag));
}

function getTagBGColorCallback($func, $val)
{
	$color = "";
	
	$color = $func($val);
	
	echo(json_encode($color));
}

function addSPRTrackingDashboardCallback()
{
	$errors         = array();  	// array to hold validation errors
	$data 			= array(); 		// array to pass back data
	
	$spr_no				= $_POST["spr_no"];
	$type 				= $_POST["type"];
	$status 			= $_POST["status"];
	$build_version		= $_POST["build_version"];
	$commit_build		= $_POST["commit_build"];
	$respond_by_date	= $_POST["respond_by_date"];
	$comment			= $_POST["comment"];
	$session			= $_POST["session"];
	
	// validate the variables ======================================================
	$post_data = [['SPR No', $spr_no], ['Build Version', $build_version], ['Respond By', $respond_by_date], ['Session', $session]];
	// check for date format.(pending)
	$errors = checkEmptyField($post_data);
	
	if ( ! empty($errors)) {
		$data['success'] = false;
		$data['errors']  = $errors;
	} 
	else {
		$msg = add_spr($spr_no, $type, $status, $build_version, $commit_build, $respond_by_date, $comment, $session);
		
		if($msg != "")
		{
			$errors[0] = ['qry', $msg];
			
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		else
			$data['success'] = true;
	}
	
	echo(json_encode($data));
}

function addSPRSubmissionStatusCallback()
{
	$errors         = array();  	// array to hold validation errors
	$data 			= array(); 		// array to pass back data
	
	$spr_no		= $_POST["spr_no"];
	$l03 		= $_POST["L03"];
	$p10 		= $_POST["P10"];
	$p20		= $_POST["P20"];
	$p30		= $_POST["P30"];
	$comment	= $_POST["comment"];
	
	// validate the variables ======================================================
	$post_data = [['SPR No', $spr_no], ['L03', $l03], ['P10', $p10], ['P20', $p20], ['P30', $p30]];
	$errors = checkEmptyField($post_data);
	
	if ( ! empty($errors)) {
		$data['success'] = false;
		$data['errors']  = $errors;
	} 
	else {
		$msg = addSPRSubmissionStatus($spr_no, $l03, $p10, $p20, $p30, $comment);
		
		if($msg != "")
		{
			$errors[0] = ['qry', $msg];
			
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		else
			$data['success'] = true;
	}
	
	echo(json_encode($data));
}

function addWorkTrackerCallback()
{
	$errors         = array();  	// array to hold validation errors
	$data 			= array(); 		// array to pass back data
	
	$day 		= $_POST["day"];
	$task 		= $_POST["task"];
	$category 	= $_POST["category"];
	$time 		= $_POST["time"];
	$comment 	= $_POST["comment"];
	
	// validate the variables ======================================================
	$post_data = [['Date', $day], ['Task', $task], ['Time(Hrs)', $time]];
	// check for date format.(pending)
	$errors = checkEmptyField($post_data);
	
	if ( ! empty($errors)) {
		$data['success'] = false;
		$data['errors']  = $errors;
	} 
	else {
		$msg = addWorkTracker($day, $task, $category, $time, $comment);
		
		if($msg != "")
		{
			$errors[0] = ['qry', $msg];
			
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		else
			$data['success'] = true;
	}
	
	echo(json_encode($data));
}

function addUpdateSPRSubmissionStatusCallback($spr_no, $version)
{
	addUpdateSPRSubmissionStatus($spr_no, $version);
}

function loginSubmitCallback()
{
	$errors         = array();  	// array to hold validation errors
	$data 			= array(); 		// array to pass back data

// validate the variables ======================================================
	if (empty($_POST['username']))
		$errors['username'] = 'Enter your username.';

	if (empty($_POST['password']))
		$errors['password'] = 'Enter your password.';

// return a response ===========================================================
	// if there are any errors in our errors array, return a success boolean of false
	if ( ! empty($errors)) {

		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors']  = $errors;
	} else {
		$user_name = get_user();
		if($user_name != "")
		{
			$_SESSION['project-managment-username'] = $user_name;
			
			$data['success'] = true;
		}
		else
		{
			$errors['password'] = 'The username or password you entered is incorrect.';
			
			$data['success'] = false;
			$data['errors']  = $errors;
		}
	}

	// return all our data to an AJAX call
	echo json_encode($data);
}

function recoverySubmitCallback()
{
	global $cipherObj;
	
	$errors         = array();  	// array to hold validation errors
	$data 			= array(); 		// array to pass back data
	$username		= "";
	
// validate the variables ======================================================
	// check for empty field.
	if( ! isset($_POST['recovery']))
		$errors['recovery'] = 'Please select one of the following options.';
	else
	{
		if(($_POST['recovery'] == "password") && (empty($_POST['username'])))
			$errors['username'] = 'Please enter an username.';
		
		if(($_POST['recovery'] == "username") && (empty($_POST['email'])))
			$errors['email'] = 'Please enter an email address.';
	}
	
// return a response ===========================================================
	if ( ! empty($errors)) 
	{
		$data['success'] = false;
		$data['errors']  = $errors;
	}
	else 
	{
		// check for username/email
		if($_POST['recovery'] == "password")
		{
			// check for username
			$password = getItemFromTable("password", "user", "user_name='".$cipherObj->encrypt($_POST['username'])."'");
			if($password == "")
				$errors['username'] = 'No account found with that username.';
			else
				$data['msg'] = 'Password for \''.$_POST['username'].'\' is \''.$cipherObj->decrypt($password).'\'.';
		}
		else
		{
			// check for email
			$username = getItemFromTable("user_name", "user", "email='".$cipherObj->encrypt($_POST['email'])."'");
			if($username == "")
				$errors['email'] = 'No account found with that email address.';
			else
				$data['msg'] = 'Username for \''.$_POST['email'].'\' is \''.$cipherObj->decrypt($username).'\'.';
		}
		
		if ( ! empty($errors))
		{
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		else
		{
			// get username/password and mail to user
			$data['success'] = true;
		}
	}
	
	// return all our data to an AJAX call
	echo json_encode($data);
}

function checkEmptyField($data)
{
	$errors = array();
	$inx = 0;
	
	foreach($data as $each)
	{
		if(empty($each[1]))
		{
			$errors[$inx] = [$each[0], 'Please enter '.$each[0].'.'];
			$inx++;
		}
	}
	
	return($errors);
}

function getErrorMsgs($data, $password_val)
{
	$errors = array();
	$inx = 0;
	
	foreach($data as $each)
	{
		$errMsg = "";
		$errMsg = getErrorMsg($each[0], $each[1], $password_val);
		if($errMsg <> "")
		{
			$errors[$inx] = [$each[0], $errMsg];
			$inx++;
		}
	}
	return($errors);
}

function signupSubmitCallback()
{
	$errors         = array();  	// array to hold validation errors
	$data 			= array(); 		// array to pass back data
	$errMsg			= "";
	$inx			= 0;

// validate the variables ======================================================
	$post_data = [['firstName', $_POST['firstName']], ['lastName', $_POST['lastName']], ['username', $_POST['username']],
					['password', $_POST['password']], ['confirm-password', $_POST['confirm-password']], ['gender', $_POST['gender']],
					['title', $_POST['title']], ['department', $_POST['department']], ['manager', $_POST['manager']],
					['email', $_POST['email']]];
					
	//$errors = checkEmptyField($post_data);

// return a response ===========================================================
		$errors = getErrorMsgs($post_data, $_POST['password']);
		if(empty($errors))
		{
			if(create_user($_POST['username'], $_POST['password'], $_POST['firstName'], $_POST['lastName'], $_POST['gender'],
							$_POST['title'], $_POST['department'], $_POST['email'], $_POST['altEmail'], $_POST['manager']) == true)
				$data['success'] = true;
			else
			{
				$data['success'] = false;
				$errors[$inx] = [$each[0], 'Please enter '.$each[0].'.'];
				$data['errors']  = $errors;
			}
		}
		else
		{
			$data['success'] = false;
			$data['errors']  = $errors;
		}
	//}

	// return all our data to an AJAX call
	echo json_encode($data);
}

function getErrorMsg($tag_id, $tag_val, $password_val)
{
	global $cipherObj;
	
	$errMsg = "";
	
	if (empty($tag_val))
		$errMsg = 'Please enter '.$tag_id.'.';
	else {
		// check for username
		if(($tag_id == "username") && (getItemFromTable("user_name", "user", "user_name = '".$cipherObj->encrypt($tag_val)."'") <> ""))
		{
			$errMsg = 'Username already exists.';
		}
		// check for password format
		else if(($tag_id == "password") && ((strlen($tag_val) < 8) ||
				(!(preg_match('/[a-z]/', $tag_val)) /*|| !(preg_match('/[0-9]/', $tag_val)) || !(preg_match('/[A-Z]/', $tag_val))*/)))
		{
			$errMsg = 'Password does not match the format.(Password have atleast 8 Characters.)';
		}
		else if($tag_id == "confirm-password")
		{
			if((strlen($tag_val) < 8) || (!(preg_match('/[a-z]/', $tag_val)) /*|| !(preg_match('/[0-9]/', $tag_val)) || !(preg_match('/[A-Z]/', $tag_val))*/))
			{
				$errMsg = 'Password does not match the format.(Password have atleast 8 Characters.)';
			}
			else if($password_val <> $tag_val)
			{
				$errMsg = 'Password and Comfirm Password doesn\'t match.';
			}
		}
		else if(($tag_id == "gender") && ($tag_val == "Select ..."))
		{
			$errMsg = 'Please Select your Gender';
		}
		else  if (($tag_id == "email") && (!filter_var($tag_val, FILTER_VALIDATE_EMAIL)))
		{
			$errMsg = 'Please enter valid e-mail.';
		}
	}
	
	return($errMsg);
}

function showErrorMsgCallback()
{
	$errors         = array();  	// array to hold validation errors
	$data 			= array(); 		// array to pass back data
	$errMsg 		= "";
	
	$tag_id = $_POST['tag_id'];
	$tag_val = $_POST['tag_val'];
	$password_val = $_POST['password_val'];

	$errMsg = getErrorMsg($tag_id, $tag_val, $password_val);
	if($errMsg <> "")
	{
		$data['success'] = false;
		$errors[$tag_id] = $errMsg;
		$data['errors']  = $errors;
	}
	else
		$data['success'] = true;

	// return all our data to an AJAX call
	echo json_encode($data);
}

function deleteWorkTrackerCallback()
{
	$data = array(); 		// array to pass back data
	
	$where = $_POST['where'];
	if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != "") && (!empty($where)))
	{
		$where .= " AND user_name = '".$_SESSION['project-managment-username']."'";
		$data['success'] = deleteTableElement('work_tracker', $where);
	}
	else
		$data['success'] = false;

	// return all our data to an AJAX call
	echo json_encode($data);
}

function deleteSPRTrackingSubmissionStatusCallback()
{
	$data = array(); 		// array to pass back data
	
	$where = $_POST['where'];
	if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != "") && (!empty($where)))
	{
		$data['success'] = deleteTableElement('spr_submission', $where);
	}
	else
		$data['success'] = false;

	// return all our data to an AJAX call
	echo json_encode($data);
}

function deleteSPRTrackingDashboardCallback()
{
	$data = array(); 		// array to pass back data
	
	$where = $_POST['where'];
	if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != "") && (!empty($where)))
	{
		$where .= " AND user_name = '".$_SESSION['project-managment-username']."'";
		$data['success'] = deleteTableElement('spr_tracking', $where);
	}
	else
		$data['success'] = false;

	// return all our data to an AJAX call
	echo json_encode($data);
}

function showSPRTrackingReportCallback()
{
	$data = array(); 		// array to pass back data
	// $data['success'] = deleteTableElement('spr_tracking', $where);
	
	$session = $_POST['session'];
	$main_search = $_POST['main_search'];
	$sub_search = $_POST['sub_search'];
	
	$qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session FROM `spr_tracking` 
			WHERE user_name =  '{$_SESSION['project-managment-username']}' AND session = {$session} AND (type <> 'REGRESSION' AND type <> 'OTHERS')";
	
	if($main_search == "Commit Build")
	{	
		if($sub_search == "Having Commit Build")	
			$qry .= " AND commit_build <> ''";
		else if($sub_search == "Without Commit Build")
			$qry .= " AND commit_build = ''";
	}
	//else if($main_search == "Respond By")
	//{
		//$qry .= " AND commit_build <> ''";
	//}
	
	$str = generateSPRTrackingReport($qry);
	
	echo json_encode($str);
}

function importExportCSVCallback()
{
	$errors         = array();  	// array to hold validation errors
	$data 			= array(); 		// array to pass back data
	$errMsg 		= "";
	
	$type = $_POST['type'];
	$filePath = $_POST['filePath'];

	if($type == "")
		$errors[$type] = 'Please enter '.$type.'.';

	if($filePath == "")
		$errors[$type] = 'Please enter '.$filePath.'.';
	
	if(empty($errors))
	{
		if($type == "Import")
		{
			$errMsg = readCSVFile($filePath);
			if($errMsg <> "")
			{
				$data['success'] = false;
				$errors['misc'] = $errMsg;
				$data['errors']  = $errors;
			}
			else
				$data['success'] = true;
		}
		else if($type == "Export")
		{
			
		}
		
	}
	else
	{
		$data['success'] = false;
		$data['errors']  = $errors;
	}

	// return all our data to an AJAX call
	echo json_encode($data);
}

function readCSVFile($filePath)
{
	$errMsg = "";
	$task_type = "";
	$row = 1;
	
	// Global cipher object.
	global $cipherObj;
	
	if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != ""))
	{
		if (($handle = fopen('..\\config\\'.$filePath, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				// check for file format
				$colNum = count($data);
				if($colNum != 18)
				{
					$errMsg = "CSV file format is not correct.";
					break;
				}
				
				if($row == 1)
				{
					// check for user name
					$user = $cipherObj->decrypt($_SESSION['project-managment-username']);
					if(($data[1] <> $user))
					{
						$errMsg = "User name mismatch between user name mentioned in CSV file [Line : 1, Col : 2] 
									and current logged user name.";
						break;
					}
					
					// check and get type.
					if(($data[2] <> "SPR") && ($data[2] <> "REGRESSION"))
					{
						$errMsg = "Please specify proper task type [SPR/REGRESSION] in [Line : 1, Col : 3].";
						break;
					}
					else
					{
						$task_type = $data[2];
					}
				}
				
				// check 'Task number' is on line #4 & line have 18 column
				if(($row == 4) && (($data[0] <> "Task Number") || ($data[17] <> "Target Build")))
				{
					$errMsg = "Line 4: Format of the line is not correct";
					break;
				}
				
				// start tacking input form line #5 onwards
				if($row > 4)
				{
					$msg = updateSPRTrackingFromData($data, $task_type, $_SESSION['project-managment-username']);
					if($msg <> "")
						$errMsg .= "Line # " . strval($row) . " : " . $msg . "\n";
					else
						$errMsg .= "Line # " . strval($row) . " : " . $task_type . "[" . explode('-', $data[0])[0]  ."] Import successfully." . "\n";
				}
				
				$row++;
			}
			fclose($handle);
		}
		else
			$errMsg = "file path is wrong.";
	}
	else
		$errMsg = "User name is not valid.";
		
	return $errMsg;
}

function updateSPRTrackingFromData($data, $task_type, $user)
{
	$errMsg = "";
	
	$task_number_pieces = explode("-", $data[0]);
	$spr_no = "";
	$user_name = $user;
	$type = $task_type;
	$status = "NONE";
	$comment = $data[4];
	$session = date("Y"); // current year
	$build_version = getBVersion($data[15]); // get build version 'col-15' L-03-48
	$commit_build = $data[13]; // col-13
	$respond_by_date = ""; // get respond by date 'col-12' 05-Dec-14
	
	// check each field in a row.
	if(empty($task_number_pieces))
	{
		$errMsg .= "Please specify proper task number.";
	}
	else
	{
		$spr_no = $task_number_pieces[0];
	}
	if(($type <> "SPR") && ($type <> "REGRESSION"))
	{
		$errMsg .= "Please specify proper task type [SPR/REGRESSION].";
	}
	if($user_name == "")
	{
		$errMsg .= "User mane is invalid.";
	}
	if($build_version == "")
	{
		$errMsg .= "Please specify proper Build version.";
	}
	
	// Please check for commit build field also, I'm feeling tired now, please do it later.
	
	if($data[12] <> "")
	{
		$respond_by_date = getRespondBy($data[12]);
		if($respond_by_date == "")
		{
			$errMsg .= "Please specify proper Respond By.";
		}
	}
	
	if($errMsg == "")
	{
		// check for the duplicate row before insert into database.
		// make insert qry.
		// insert into database.
		$msg = add_spr($spr_no, $type, $status, $build_version, $commit_build, $respond_by_date, $comment, $session);;
		if($msg <> "")
		{
			$errMsg .= $type . "[" . $spr_no. "] alread exists.";
		}
	}
	
	return($errMsg);
}

function getBVersion($bv)
{
	$version = "";
	
	// check format Ex: L-03-48
	$pieces = explode("-", $bv);
	
	if(!empty($pieces))
	{
		// check 'L', 'P'
		if($pieces[0] == "L")
		{
			if(($pieces[1] == "03") || ($pieces[1] == "05"))
			{
				$version = $pieces[0] . $pieces[1] . ",P10,P20,P30"; 	// Add other version
			}
		}
		else if($pieces[0] == "P")
		{
			if(($pieces[1] == "10"))
			{
				$version = $pieces[0] . $pieces[1] . ",P20,P30";		// Add other version
			}
			else if(($pieces[1] == "20"))
			{
				$version = $pieces[0] . $pieces[1] . ",P30";			// Add other version
			}
			else if(($pieces[1] == "30"))
			{
				$version = $pieces[0] . $pieces[1];
			}
		}
	}
	
	return($version);
}

function getRespondBy($rbd)
{
	$respondBy = "";
	$months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$month = "";
	$year = date("y");
	
	// check format Ex : 05-Dec-14
	$pieces = explode("-", $rbd);
	
	if(!empty($pieces))
	{
		// get the month in numeric format
		for($i = 0; $i < sizeof($months); $i++)
		 {
			 if($pieces[1] == $months[$i])
			 {
				 $month = strval($i + 1);
				 $month = (($month < 10 ? "0".$month : $month));
				 break;
			 }
		 }
		 
		 if(((intval($pieces[0]) > 0) && (intval($pieces[0]) < 32)) && ($month <> "") && (intval($pieces[2] <= $year)))
		 {
			 $respondBy = "20" . $pieces[2] . "-" . $month . "-" . $pieces[0];
		 }
	}
	 
	return($respondBy);
}

function shortDescriptionCallback($comment, $limit)
{
	$shortDesc  = "";
	
	$shortDesc 	= shortDescription($comment, $limit);
	
	echo(json_encode($shortDesc));
}

?>
