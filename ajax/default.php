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
require_once ('../inc/utility.php');
require_once ('../inc/mysqldb.php');
require_once ('../inc/adminhtml.php');


// Initialize session data
session_start();

if(function_exists($_GET['f']))
{
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

function getSessionValueCallback()
{
    $val = '';

    if($_POST['sessionId'])
        $val = Utility::decode($_SESSION[$_POST['sessionId']]);
    else
        $val = '';

    echo(json_encode($val));
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

function updateDashboradTableCallback()
{
    $tag = "";

    if(!empty($_POST["fillTableFunc"]))
    {
        $callback = null;

        if(empty($_POST["fillTableFuncClass"]))
          $callback = $_POST["fillTableFunc"];
        else
          $callback = array($_POST["fillTableFuncClass"], $_POST["fillTableFunc"]);

        if(empty($_POST["clause"]))
            $tag .= call_user_func($callback);
        else
            $tag .= call_user_func($callback, ($_POST["clause"]));
    }

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

    if ( ! empty($errors))
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    }
    else
    {
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

    if ( ! empty($errors))
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    }
    else
    {
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

    if ( ! empty($errors))
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    }
    else
    {
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
    if ( ! empty($errors))
    {

        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;
    }
    else
    {
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
            $password = getItemFromTable("password", "user", "user_name='".Utility::encode($_POST['username'])."'");
            if($password == "")
                $errors['username'] = 'No account found with that username.';
            else
                $data['msg'] = 'Password for \''.$_POST['username'].'\' is \''.Utility::decode($password).'\'.';
        }
        else
        {
            // check for email
            $username = getItemFromTable("user_name", "user", "email='".Utility::encode($_POST['email'])."'");
            if($username == "")
                $errors['email'] = 'No account found with that email address.';
            else
            $data['msg'] = 'Username for \''.$_POST['email'].'\' is \''.Utility::decode($username).'\'.';
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
    ['password', $_POST['password']], ['title', $_POST['title']], ['department', $_POST['department']],
    ['manager', $_POST['manager']], ['email', $_POST['email']]];

    //$errors = checkEmptyField($post_data);

    // return a response ===========================================================
    $errors = getErrorMsgs($post_data, $_POST['password']);
    if(empty($errors))
    {
        if(create_user($_POST['username'], $_POST['password'], $_POST['firstName'], $_POST['lastName'],
            $_POST['title'], $_POST['department'], $_POST['email'], $_POST['altEmail'], $_POST['manager']) == true)
        {
            $data['success'] = true;
        }
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

    // return all our data to an AJAX call
    echo json_encode($data);
}

function getErrorMsg($tag_id, $tag_val, $password_val)
{
    $errMsg = "";

    if (empty($tag_val))
        $errMsg = 'Please enter '.$tag_id.'.';
    else
    {
        // check for username
        if(($tag_id == "username") && (getItemFromTable("user_name", "user", "user_name = '".Utility::encode($tag_val)."'") <> ""))
        {
            $errMsg = 'Username already exists.';
        }
        // check for password format
        else if(($tag_id == "password") && ((strlen($tag_val) < 8) ||
        (!(preg_match('/[a-z]/', $tag_val)) /*|| !(preg_match('/[0-9]/', $tag_val)) || !(preg_match('/[A-Z]/', $tag_val))*/)))
        {
            $errMsg = 'Password does not match the format.(Password have atleast 8 Characters.)';
        }
        /*else if($tag_id == "confirm-password")
        {
            if((strlen($tag_val) < 8) || (!(preg_match('/[a-z]/', $tag_val)) /*|| !(preg_match('/[0-9]/', $tag_val)) || !(preg_match('/[A-Z]/', $tag_val))*//*))*/
            /*{
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
        }*/
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
            WHERE user_name =  '{$_SESSION['project-managment-username']}' AND session = {$session}
            AND (type <> 'REGRESSION' AND type <> 'OTHERS')";

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

    if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != ""))
    {
        if (($handle = fopen('..\\config\\'.$filePath, "r")) !== FALSE)
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
            {
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
                    $user = Utility::decode($_SESSION['project-managment-username']);
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
                        $task_type = $data[2];
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
                        $errMsg .= "Line # " . strval($row) . " : " . $task_type . "[" . explode('-', $data[0])[0]  ."] Import  successfully." . "\n";
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
    $shortDesc 	= shortDescription($comment, $limit);
    echo(json_encode($shortDesc));
}

// Following tables are depending on 'project'
// - scrum_project_member
// - scrum_sprint
// - scrum_backlog
// - scrum_task
// - scrum_sprint
function deleteProjectRelatedTableElement($project)
{
    if(($project != null) && ($project != ''))
    {
        // - scrum_project_member
        deleteTableElement('scrum_project_member', 'project_title = "'. $project .'"');

        // - scrum_sprint
        deleteTableElement('scrum_sprint', 'project = "'. $project .'"');

        // get backlog title for passing project.
        $backlogs = getTableElements('scrum_backlog', ['title'], 'project = "'. $project .'"');
        // delete tasks for each selected backlog.
        foreach($backlogs as $backlog)
        {
            // - scrum_task - based on backlog title.
            deleteTableElement('scrum_task', 'backlog = "'. $backlog[0] .'"');
        }

        // - scrum_backlog
        deleteTableElement('scrum_backlog', 'project = "'. $project .'"');
    }
}


// delete below table element from child project(scrum_release_planning) first and
// then delete below table element for selected project.

// and all the table element which are department on project.
// Following tables are depending on 'project'
// - scrum_project_member
// - scrum_sprint
// - scrum_backlog
// - scrum_task
// - scrum_sprint
function deleteProjectCallback()
{
    $data       = array();      // array to pass back data
    $data       = array();      // array to pass back data
    $project    = $_POST['project'];

    // get all the child project of the selected project.
    $releaseProjects = getTableElements('scrum_project', ['title'], 'parent = "'. $project .'"');
    // delete release project related table elements.
    foreach($releaseProjects as $releaseProject)
    {
        deleteProjectRelatedTableElement($releaseProject[0]);

        // delete release project.
        deleteTableElement('scrum_project', 'title = "'. $releaseProject[0] .'"');
    }

    // delete project related table elements.
    deleteProjectRelatedTableElement($project);

    // delete corresponding project from 'scrum_project' Table.
    deleteTableElement('scrum_project', 'title = "'. $project .'"');

    $data['success'] = true;
    echo(json_encode($data));
}

function getSprintScheduleSelectCallback()
{
    $tag = '';
    $selectedSchedule = $_POST['selectedSchedule'];

    $qry = "SELECT title FROM scrum_sprint_schedule";
    $mysqlDBObj = new mysqlDB('scrum_sprint_schedule');
    $rows = $mysqlDBObj->select($qry);
    if(!empty($rows))
    {
        $tag .= ' <select id="sprint_schedule-select" style="height: 25px;">';
        foreach($rows as $row)
            $tag .= '   <option value="'. $row[0] .'" ' . (($selectedSchedule == $row[0]) ? 'selected' : '') . '>'. $row[0] .'</option>';
        $tag .= '</select>';
    }

    echo(json_encode($tag));
}

function sprintScheduleErrorCheck()
{
    $errors = array();

    // check for invalid/empty/duplicate sprint schedule title.
    if($_POST['title'] == '')
        $errors['title'] = 'Please enter title.';
    else
    {
        // check for duplicate title
        if($_POST['title'] != $_POST['key_title'])
        {
            $qry = "SELECT title FROM scrum_sprint_schedule WHERE title = '" . $_POST['title'] . "'";

            $mysqlDBObj = new mysqlDB('scrum_sprint_schedule');
            $row = $mysqlDBObj->select($qry);
            if( !empty($row) )
                $errors['title'] = 'Sprint Schedule already exists, Please enter another title.';
        }
    }

    // check for empty/non-numeric len value.
    if($_POST['length'] == '')
        $errors['len'] = 'Please enter Sprint Length.';
    else
    {
        // check numeric value
        if( !(is_numeric($_POST['length'])) )
            $errors['len'] = 'Please enter numeric value.';
    }

    // check for empty/non-numeric gap value.
    if($_POST['gap'] == '')
        $errors['gap'] = 'Please enter Sprint Gap.';
    else
    {
        // check numeric value
        if( !(is_numeric($_POST['gap'])) )
            $errors['gap'] = 'Please enter numeric value.';
    }

    return($errors);
}

function addSprintScheduleCallback()
{
    $data       = array();                          // array to pass back data
    $errors     = sprintScheduleErrorCheck();       // array to hold validation errors

    if(count($errors) == 0)
    {
        // create object of mysqlDB class to add data into mysql database.
        $mysqlDBObj = new mysqlDB('scrum_sprint_schedule');

        $keys = array('title', 'length', 'length_unit', 'gap', 'gap_unit', 'description', 'key_title');
        foreach($keys as $each)
            $mysqlDBObj->appendData($each, $_POST[$each]);

        $msg = $mysqlDBObj->insert();
        if($msg == false)
        {
            $errors['qry'] = $msg;

            $data['success'] = false;
            $data['errors']  = $errors;
        }
        else
        {
            $data['success'] = true;
        }
    }
    else
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    }

    echo(json_encode($data));
}

function updateSprintScheduleCallback()
{
    $data       = array();      // array to pass back data
    $errors     = sprintScheduleErrorCheck();       // array to hold validation errors

    if(count($errors) == 0)
    {
        $keys = array('title', 'length', 'length_unit', 'gap', 'gap_unit', 'description');

        // create object of mysqlDB class to add data into mysql database.
        $mysqlDBObj = new mysqlDB('scrum_sprint_schedule');
        foreach($keys as $each)
            $mysqlDBObj->appendData($each, $_POST[$each]);
        $mysqlDBObj->appendClause("title = '" . $_POST['key_title'] . "'");

        $msg = $mysqlDBObj->update();
        if($msg == false)
        {
            $errors['qry'] = $msg;

            $data['success'] = false;
            $data['errors']  = $errors;
        }
        else
        {
            $data['success'] = true;
        }
    }
    else
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    }

    echo(json_encode($data));
}

function deleteSprintScheduleCallback()
{
    $errors     = array();      // array to hold validation errors
    $data       = array();      // array to pass back data

    // create object of mysqlDB class to add data into mysql database.
    $mysqlDBObj = new mysqlDB('scrum_sprint_schedule');
    $mysqlDBObj->appendClause($_POST['clause']);

    $msg = $mysqlDBObj->delete();
    if($msg == false)
    {
        $errors[0] = ['qry', $msg];

        $data['success'] = false;
        $data['errors']  = $errors;
    }
    else
    {
        $data['success'] = true;
    }

    echo(json_encode($data));
}

function projectErrorCheck()
{
    $errors = array();
    $mysqlDBObj = new mysqlDB('scrum_project');

    // check for invalid/empty/duplicate sprint schedule title.
    if($_POST['title'] == '')
        $errors['title'] = 'Please enter title.';
    else
    {
        // check for duplicate title
        if($_POST['title'] != $_POST['key_title'])
        {
            $qry = "SELECT title FROM scrum_project WHERE title = '" . $_POST['title'] . "' AND parent='". $_POST['parent'] ."'";
            $row = $mysqlDBObj->select($qry);
            if( !empty($row) )
                $errors['title'] = 'Project already exists, Please enter another title.';
        }
    }

    // check for empty/non-numeric len value.
    if($_POST['sprint_schedule'] == '')
        $errors['sprint_schedule'] = 'Please enter Sprint Schedule.';

    // check for empty/non-numeric gap value.
    if($_POST['begin_date'] == '')
        $errors['begin_date'] = 'Please enter Begin Date.';

    // check for empty/non-numeric gap value.
    if($_POST['end_date'] == '')
        $errors['end_date'] = 'Please enter End Date.';
    else
    {
        // check end date is more than begin date.
        if($_POST['begin_date'] != '')
        {
            list($begin_year, $begin_month, $begin_day) = explode('-', $_POST['begin_date']);
            list($end_year, $end_month, $end_day) = explode('-', $_POST['end_date']);

            if(
                (intval($end_year) <= intval($begin_year)) &&
                (intval($end_month) <= intval($begin_month)) &&
                (intval($end_day) <= intval($begin_day))
              )
            {
                $errors['end_date'] = 'End date must be more than Begin date.';
            }
        }
    }

    // check for empty/non-numeric gap value.
    if($_POST['owner'] == '')
        $errors['owner'] = 'Please enter Owner.';
    else
    {
        // check for valid title
        $qry = "SELECT member_id FROM scrum_member WHERE member_id = '" . Utility::encode($_POST['owner']) . "'";
        $row = $mysqlDBObj->select($qry);
        if( empty($row) )
            $errors['owner'] = 'Owner not exists, Please enter valid member as owner.';
    }

    return($errors);
}

function addProjectCallback()
{
    $data       = array();                          // array to pass back data
    $errors     = projectErrorCheck();       // array to hold validation errors

    if(count($errors) == 0)
    {
        // create object of mysqlDB class to add data into mysql database.
        $mysqlDBObj = new mysqlDB('scrum_project');

        $keys = array(
                        'title', 'parent', 'sprint_schedule', 'description', 'begin_date', 'end_date',
                        'owner', 'status', 'target_estimate', 'test_suit', 'target_swag', 'reference'
                     );
        foreach($keys as $each)
        {
            if($each == 'owner')
                $mysqlDBObj->appendData($each, Utility::encode($_POST[$each]));
            else
                $mysqlDBObj->appendData($each, $_POST[$each]);
        }

        $msg = $mysqlDBObj->insert();
        if($msg == false)
        {
            $errors['qry'] = $msg;

            $data['success'] = false;
            $data['errors']  = $errors;
        }
        else
        {
            // Add owner in 'scrum_project_member' as 'system admin'.
            $mysqlDBObj = new mysqlDB('scrum_project_member');

            $mysqlDBObj->appendData('project_title', $_POST['title']);
            $mysqlDBObj->appendData('member_id', Utility::encode($_POST['owner']));
            $mysqlDBObj->appendData('privilage', 'system admin');

            $msg = $mysqlDBObj->insert();

            $data['success'] = true;
        }
    }
    else
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    }

    echo(json_encode($data));
}

function updateProjectCallback()
{
    $data       = array();      // array to pass back data
    $errors     = projectErrorCheck();       // array to hold validation errors

    if(count($errors) == 0)
    {
        $keys = array (
                        'title', 'parent', 'sprint_schedule', 'description', 'begin_date', 'end_date',
                        'owner', 'status', 'target_estimate', 'test_suit', 'target_swag', 'reference'
                      );

        // create object of mysqlDB class to add data into mysql database.
        $mysqlDBObj = new mysqlDB('scrum_project');
        foreach($keys as $each)
        {
            if($each == 'owner')
                $mysqlDBObj->appendData($each, Utility::encode($_POST[$each]));
            else
                $mysqlDBObj->appendData($each, $_POST[$each]);
        }

        $mysqlDBObj->appendClause("title = '" . $_POST['key_title'] . "' AND parent='" . $_POST['parent'] . "'");

        $msg = $mysqlDBObj->update();
        if($msg == false)
        {
            $errors['qry'] = $msg;

            $data['success'] = false;
            $data['errors']  = $errors;
        }
        else
        {
            $data['success'] = true;
        }
    }
    else
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    }

    echo(json_encode($data));
}

/*function addMemberCallback()
{
    $errors     = array();      // array to hold validation errors
    $data       = array();      // array to pass back data

    $member_id = $_POST['member_id'];
    $project = $_POST['project'];
    $privilage = $_POST['privilage'];

    // create object of mysqlDB class to add data into mysql database.
    $mysqlDBObj = new mysqlDB('scrum_member');
    $mysqlDBObj->appendData('member_id', $member_id);
    $mysqlDBObj->appendData('privilage', $privilage);

    $msg = $mysqlDBObj->insert();
    if($msg == false)
    {
        $errors[0] = ['qry', $msg];

        $data['success'] = false;
        $data['errors']  = $errors;
    }
    else
    {
        $data['success'] = true;

        /// assign member to the project
        if($project != '')
        {
            $mysqlDBObj = new mysqlDB('scrum_project_member');
            $mysqlDBObj->appendData('project_title', $member_id);
            $mysqlDBObj->appendData('member_id', $member_id);
            $mysqlDBObj->appendData('privilage', $privilage);

            $msg = $mysqlDBObj->insert();
        }
    }

    echo(json_encode($data));
}*/

function getProjectListCallback()
{
    echo(json_encode(getScrumProjects()));
}

function getUsersCallback()
{
    echo(json_encode(getUsers($_POST['user_hints'])));
}

function getEnumOptionsCallback()
{
    echo(json_encode(getEnumOptions($_POST['tableName'], $_POST['colName'])));
}

function addMemberCallback()
{
    $errors     = array();  	// array to hold validation errors
    $data       = array(); 		// array to pass back data

    $memberId   = $_POST['member_id'];
    $project    = $_POST['project'];
    $privilage  = $_POST['privilage'];

    // check member is exists or not.
    $member = getScrumMembers($memberId);
    if(!empty($member))
    {
        $errors[0] = 'Member already listed.';

        // display error msg.
        $data['success'] = false;
        $data['errors']  = $errors;
    }
    else
    {
        // Add member.
        // create object of mysqlDB class to add data into mysql database.
        $mysqlDBObj = new mysqlDB('scrum_member');
        $mysqlDBObj->appendData('member_id', Utility::encode($memberId));
        $mysqlDBObj->appendData('privilage', $privilage);

        $msg = $mysqlDBObj->insert();
        if($msg == false)
        {
            $errors[0] = ['qry', $msg];

            $data['success'] = false;
            $data['errors']  = $errors;
        }
        else
        {
            $data['success'] = true;

            /// assign member to the project
            if($project != '')
            {
                $mysqlDBObj = new mysqlDB('scrum_project_member');
                $mysqlDBObj->appendData('project_title', $project);
                $mysqlDBObj->appendData('member_id', Utility::encode($memberId));
                $mysqlDBObj->appendData('privilage', $privilage);

                $msg = $mysqlDBObj->insert();
            }
        }
    }

    echo(json_encode($data));
}

function updateMemberRolesCallback()
{
    $project = $_POST['project'];
    if($project != '')
    {
        $memberRoles = json_decode($_POST['memberRoles'], true);
        if($memberRoles != null)
        {
            $mysqlDBObj = new mysqlDB('scrum_project_member');
            foreach ($memberRoles as $memberRole)
            {
                // update database for member role.
                $mysqlDBObj->unsetData();
                $mysqlDBObj->unsetClause();

                // append element
                $mysqlDBObj->appendData('privilage', $memberRole[1]);
                $mysqlDBObj->appendClause('member_id="'. Utility::encode($memberRole[0]) .'" AND project_title="'. $project .'"');

                $msg = $mysqlDBObj->update();
            }
        }
    }
}

?>
