<?php
    /*--
        File    : inc/mysql_functions.inc.php
        Author    : Abhishek Nath
        Date    : 01-Jan-2015
        Desc    : Established mysql connection.
                  This file having all the mysql query related functions.
    --*/

    /*--
        01-Jan-15   V1-01-00   abhishek   $$1   Created.
        17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
    --*/

    require_once 'mysql_conn.inc.php';
    require_once 'db.inc.php';
    require_once 'variables.inc.php';
    require_once 'cipher.inc.php';



    // Global cipher object.
    $cipherObj = new Cipher($key);

    // Open connection.
    $conn = new mysql_conn(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS, DB_NAME);

    function build_db()
    {
        global $conn;

        $conn->create_db(DB_NAME);

        $qry = "CREATE TABLE IF NOT EXISTS user
                (
                    user_name VARBINARY(100) NOT NULL,
                    password VARBINARY(100),
                    first_name VARBINARY(100),
                    last_name VARBINARY(100),
                    title VARBINARY(100),
                    department VARBINARY(100),
                    email VARBINARY(100),
                    alt_email VARBINARY(100),
                    manager VARBINARY(100),
                    PRIMARY KEY (`user_name`)
                )";
        $conn->execute_query($qry);

        $qry = "CREATE TABLE IF NOT EXISTS spr_tracking
                (
                    spr_no INT(10) NOT NULL,
                    user_name VARBINARY(100) NOT NULL,
                    type ENUM('SPR','INTEGRITY SPR','REGRESSION', 'OTHERS'),
                    status ENUM('NONE', 'INVESTIGATING','NOT AN ISSUE','SUBMITTED',
                    'RESOLVED','PASS FOR TESTING','CLOSED','ON HOLD', 'TESTING COMPLETE',
                    'PASS TO CORRESPONDING GROUP', 'NEED MORE INFO', 'OTHERS'),
                    comment VARCHAR(1500),
                    session INT(10),
                    build_version VARCHAR(25) NOT NULL,
                    commit_build VARCHAR(25),
                    respond_by_date DATE NOT NULL,
                    PRIMARY KEY (`spr_no`, `user_name`),
                    INDEX (user_name)
                )";
        $conn->execute_query($qry);

        $qry = "CREATE TABLE IF NOT EXISTS spr_submission
                (
                    spr_no INT(10) NOT NULL,
                    L03 ENUM('YES', 'NO', 'N/A', 'IDLING', 'REOPENED'),
                    P10 ENUM('YES', 'NO', 'N/A', 'IDLING', 'REOPENED'),
                    P20 ENUM('YES', 'NO', 'N/A', 'IDLING', 'REOPENED'),
                    P30 ENUM('YES', 'NO', 'N/A', 'IDLING', 'REOPENED'),
                    comment VARCHAR( 500 ),
                    PRIMARY KEY (spr_no),
                    INDEX (spr_no)
                )";
        $conn->execute_query($qry);

        $qry = "CREATE TABLE IF NOT EXISTS work_tracker
                (
                    id INT(10) NOT NULL AUTO_INCREMENT,
                    day DATE NOT NULL,
                    user_name VARBINARY(100) NOT NULL,
                    task VARCHAR(50) NOT NULL,
                    category ENUM('SPR', 'REG FIX', 'REGRESSION TEST', 'SF', 'REG CLEAN-UP', 'CONSULTATION', 'PROJECT', 'MISC', 'OTHERS'),
                    time DOUBLE,
                    comment VARCHAR(1500),
                    PRIMARY KEY (id),
                    INDEX (user_name)
                )";
        $conn->execute_query($qry);

        /*$qry = "CREATE TABLE IF NOT EXISTS scrum
                (
                    name VARCHAR(50) NOT NULL,
                    description VARCHAR(150),
                    PRIMARY KEY (name)
                )";
        $conn->execute_query($qry);*/

        /*$qry = "CREATE TABLE IF NOT EXISTS scrum_member
                (
                    id INT(10) NOT NULL AUTO_INCREMENT,
                    user_name VARCHAR(50) NOT NULL,
                    scrum_name VARCHAR(50) NOT NULL,
                    PRIMARY KEY (id),
                    INDEX (user_name),
                    INDEX (scrum_name)
                )";
        $conn->execute_query($qry);*/

        /*$qry = "CREATE TABLE IF NOT EXISTS sprint
                (
                    scrum_name VARCHAR(50) NOT NULL,
                    name VARCHAR(50) NOT NULL,
                    duration VARCHAR(45) NOT NULL,
                    perday INT(10) NOT NULL,
                    PRIMARY KEY (name, scrum_name),
                    INDEX (scrum_name)
                )";
        $conn->execute_query($qry);*/

        /*$qry = "CREATE TABLE IF NOT EXISTS sprint_member
                (
                    id INT(10) NOT NULL AUTO_INCREMENT,
                    user_name VARCHAR(50) NOT NULL,
                    sprint_name VARCHAR(50) NOT NULL,
                    working_day    INT(10),
                    buffer_time    INT(10),
                    PRIMARY KEY (id),
                    INDEX (user_name),
                    INDEX (sprint_name)
                )";
        $conn->execute_query($qry);*/

        /*$qry = "CREATE TABLE IF NOT EXISTS backlog
                (
                    name VARCHAR(50) NOT NULL,
                    user_name VARCHAR(50) NOT NULL,
                    sprint_name VARCHAR(50) NOT NULL,
                    description VARCHAR(150),
                    priority INT(10),
                    comment VARCHAR(150),
                    PRIMARY KEY (name, sprint_name),
                    INDEX (user_name),
                    INDEX (sprint_name)
                )";
        $conn->execute_query($qry);*/

        /*$qry = "CREATE TABLE IF NOT EXISTS task
                (
                    id INT(10) NOT NULL AUTO_INCREMENT,
                    name VARCHAR(50) NOT NULL,
                    sprint_name VARCHAR(50) NOT NULL,
                    estimated_time INT(10),
                    spent_time INT(10),
                    status ENUM('BLOCK', 'IN-PROCESS', 'COMPLETE'),
                    comment VARCHAR(150),
                    PRIMARY KEY (id),
                    INDEX (sprint_name)
                )";
        $conn->execute_query($qry);*/
    }

    function create_user($username, $password, $firstName, $lastName, $title, $dept, $email, $altEmail, $manager)
    {
        global $conn;
        global $cipherObj;

        $qry = "INSERT INTO user (user_name, password, first_name, last_name, title, department, email, alt_email, manager)
                VALUES ('".$cipherObj->encrypt($username)."',
                    '".$cipherObj->encrypt($password)."',
                    '".$cipherObj->encrypt($firstName)."',
                    '".$cipherObj->encrypt($lastName)."',
                    '".$cipherObj->encrypt($title)."',
                    '".$cipherObj->encrypt($dept)."',
                    '".$cipherObj->encrypt($email)."',
                    '".$cipherObj->encrypt($altEmail)."',
                    '".$cipherObj->encrypt($manager)."')";

        if($conn->execute_query($qry))
            return(true);
        else
            return(false);
    }

    function get_user()
    {
        global $conn;
        global $cipherObj;

        $result = "";

        $qry = "SELECT user_name FROM user WHERE
                user_name = '".$cipherObj->encrypt($_POST['username'])."' AND
                password = '".$cipherObj->encrypt($_POST['password'])."'";

        $row = $conn->result_fetch_row($qry);
        if(empty($row))
            $result = "";
        else
            $result = $row[0][0];

        return($result);
    }

    function getUsers($like)
    {
        global $conn;
        global $cipherObj;
        $users = array();

        $qry = "SELECT user_name FROM user ORDER BY user_name DESC";
        $rows = $conn->result_fetch_array($qry);
        foreach($rows as $row)
        {
            $userName = $cipherObj->decrypt($row[0]);
            if (strpos($userName, $like) !== false)
                array_push($users, $userName);
        }

        return($users);
    }

    function getItemFromTable($item, $table, $clause)
    {
        global $conn;
        $result = "";

        if(($item <> "") && ($table <> ""))
        {
            $qry = "SELECT ".$item." FROM ".$table;
            if($clause)
                $qry .= " WHERE ".$clause;

            $row = $conn->result_fetch_row($qry);
            if(empty($row))
                $result = "";
            else
                $result = $row[0][0];
        }

        return($result);
    }

    function add_spr($spr_no, $type, $status, $build_version, $commit_build, $respond_by_date, $comment, $session)
    {
        global $conn;
        $msg = "";

        try
        {
            $checkCharArr = array(array("'", "\'"), array("\"", "\\\""));

            $username = replaceCharArr($_SESSION['project-managment-username'], $checkCharArr);

            // Check whether SPR no already exist or not with current user.
            $qry = "SELECT spr_no FROM spr_tracking WHERE spr_no = ". replaceCharArr($spr_no, $checkCharArr) ." and user_name = '".$username."'";
            $row = $conn->result_fetch_row($qry);
            if(empty($row))
            {
                $qry = "INSERT INTO spr_tracking (spr_no , user_name , type , status, comment, session, build_version, commit_build, respond_by_date)
                        VALUES ('".replaceCharArr($spr_no, $checkCharArr)."',
                            '".$username."',
                            '".replaceCharArr($type, $checkCharArr)."',
                            '".replaceCharArr($status, $checkCharArr)."',
                            '".replaceCharArr($comment, $checkCharArr)."',
                            '".replaceCharArr($session, $checkCharArr)."',
                            '".replaceCharArr($build_version, $checkCharArr)."',
                            '".replaceCharArr($commit_build, $checkCharArr)."',
                            '".replaceCharArr($respond_by_date, $checkCharArr)."')";

                //echo $qry;
                $conn->execute_query($qry);
            }
            else
            {
                $msg = "SPR<".$spr_no."> already exists.";
            }
        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
        }

        return($msg);
    }

    function updateSPRTracking($table_name, $field, $val, $where_clause)
    {
        global $conn;

        $qry = "UPDATE ".$table_name." SET ".
                        $field." = '".$val."'
                        WHERE ".$where_clause;

        if($conn->execute_query($qry))
            return(true);
        else
            return(false);
    }

    function getEnumOptions($table_name, $column_name)
    {
        global $conn;
        $enumList;

        $qry = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table_name."' AND COLUMN_NAME = '".$column_name."'";
        $row = $conn->result_fetch_array($qry);
        if(!empty($row))
            $enumList = explode(",", str_replace("'", "", substr($row[0][0], 5, (strlen($row[0][0])-6))));

        return($enumList);
    }

    function getColumnName($table_name)
    {
        global $conn;
        $colNameList = array();

        $qry = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table_name."'";
        $rows = $conn->result_fetch_array($qry);
        foreach($rows as $row)
            array_push($colNameList, $row[0]);

        return($colNameList);
    }

    function addSPRSubmissionStatus($spr_no, $l03, $p10, $p20, $p30, $comment)
    {
        global $conn;
        $msg = "";

        try
        {
            $checkCharArr = array(array("'", "\'"), array("\"", "\\\""));

            // Check whether SPR no already exist or not with current user.
            $qry = "SELECT spr_no FROM spr_tracking
                        WHERE spr_no = ". replaceCharArr($spr_no, $checkCharArr) ."
                                and user_name = '".replaceCharArr($_SESSION['project-managment-username'], $checkCharArr)."'
                                and type <> 'REGRESSION'";
            $row = $conn->result_fetch_row($qry);
            if(!empty($row))
            {
                $qry = "INSERT INTO spr_submission (spr_no , L03 , P10, P20, P30, comment)
                        VALUES ('".replaceCharArr($spr_no, $checkCharArr)."',
                            '".replaceCharArr($l03, $checkCharArr)."',
                            '".replaceCharArr($p10, $checkCharArr)."',
                            '".replaceCharArr($p20, $checkCharArr)."',
                            '".replaceCharArr($p30, $checkCharArr)."',
                            '".replaceCharArr($comment, $checkCharArr)."')";

                //echo $qry;
                $conn->execute_query($qry);
            }
            else
            {
                $msg = "SPR<".$spr_no."> is not present in SPR Tracking list, Please add in SPR Tracking list and then update submission status.";
            }
        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
        }

        return($msg);
    }

    function addUpdateSPRSubmissionStatus($spr_no, $version)
    {
        global $conn;
        $l03 = "";
        $p10 = "";
        $p20 = "";
        $p30 = "";

        // check whethet spr_no already exists or not?
        $qry = "SELECT spr_no FROM spr_submission WHERE spr_no='".$spr_no."'";
        $row = $conn->result_fetch_row($qry);
        if(!empty($row))
        {
            // if exists then update it.
            updateSPRTracking("spr_submission", $version, "YES", "spr_no = '". $spr_no ."'");
        }
        else
        {
            // if not exists then add it.
            if($version == "l03")
                $l03 = "YES";
            else
                $l03 = "NO";

            if($version == "p10")
                $p10 = "YES";
            else
                $p10 = "NO";

            if($version == "p20")
                $p20 = "YES";
            else
                $p20 = "NO";

            if($version == "p30")
                $p30 = "YES";
            else
                $p30 = "NO";

            addSPRSubmissionStatus($spr_no, $l03, $p10, $p20, $p30, "");
        }
    }

    function addWorkTracker($day, $task, $category, $time, $comment)
    {
        global $conn;
        $msg = "";

        try
        {
            $checkCharArr = array(array("'", "\'"), array("\"", "\\\""));

            $qry = "INSERT INTO work_tracker (day, user_name , task , category , time, comment)
                    VALUES ('".replaceCharArr($day, $checkCharArr)."',
                        '".replaceCharArr($_SESSION['project-managment-username'], $checkCharArr)."',
                        '".replaceCharArr($task, $checkCharArr)."',
                        '".replaceCharArr($category, $checkCharArr)."',
                        '".replaceCharArr($time, $checkCharArr)."',
                        '".replaceCharArr($comment, $checkCharArr)."')";

            //echo $qry;
            $conn->execute_query($qry);
        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
        }

        return($msg);
    }

    function getTableElements($table, $elementList, $clause)
    {
        global $conn;
        $rows = null;

        // check tableName and elementList are black or not.
        if(
            (($table != null) && ($table != '')) &&
            (($elementList != null) && (count($elementList) > 0))
          )
        {
            // build select query.
            $coma = 0;

            $qry = 'SELECT';
            foreach($elementList as $element)
            {
                $qry .= (($coma > 0) ? ',' : '') . ' ' . $element;
                $coma++;
            }
            $qry .= ' FROM ' . $table;
            if(($clause != null) && ($clause != ''))
                $qry .= ' WHERE ' . $clause;

            $rows = $conn->result_fetch_array($qry);
        }

        return($rows);
    }

    function deleteTableElement($table, $where)
    {
        global $conn;
        $qry = "DELETE FROM ".$table;

        if(!empty($where))
            $qry .= " WHERE ".$where;

        //return $qry;
        if($conn->execute_query($qry))
            return(true);
        else
            return(false);
    }

    function getScrumProjects()
    {
        global $conn;
        $projectList = array();

        $qry = "SELECT title FROM scrum_project  WHERE parent = 'System(All Projects)' AND owner = '". $_SESSION['project-managment-username'] ."'";

        $rows = $conn->result_fetch_array($qry);
        foreach($rows as $row)
            array_push($projectList, $row[0]);

        return($projectList);
    }

    /*
        Return member information in decrypted form and return format as follows.
        return = array(
                        array(first name, last name, email, privilage), ...
                      )
    */
    function getScrumMembers($member)
    {
        global $conn;
        global $cipherObj;
        $users = array();

        $qry = "SELECT user.first_name, user.last_name, user.user_name, user.email, scrum_member.privilage FROM user INNER JOIN scrum_member ON scrum_member.member_id = user.user_name";
        if(($member != null) && ($member != ''))
            $qry .=" AND scrum_member.member_id = '" . $cipherObj->encrypt($member) . "'";
        $qry .=" ORDER BY user.user_name DESC";

        $rows = $conn->result_fetch_array($qry);
        foreach($rows as $row)
        {
            $each = array (
                            $cipherObj->decrypt($row[0]),
                            $cipherObj->decrypt($row[1]),
                            $cipherObj->decrypt($row[2]),
                            $cipherObj->decrypt($row[3]),
                            $row[4]
                        );
            array_push($users, $each);
        }

        return($users);
    }
?>
