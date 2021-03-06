<?php
    /*ini_set('display_errors', 'On');
    error_reporting(E_ALL);*/

    require_once ('../inc/functions.inc.php');
    require_once ('../inc/mysql_functions.inc.php');
    require_once ('../inc/scrumhtml.php');

    // Create Database and required tables
    build_db();

    // Initialize session data
    session_start();

    // if not log in then redirect to login page.
    if(!isset($_SESSION['project-managment-username']))
        header("Location: ../user/login.php?redirect=../scrum/sprint_detailed.php");
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>HTML - Holy Grail Layout with Sticky Footer</title>
        <link rel="stylesheet" type="text/css" href="../css/global.css">
        <link rel="stylesheet" type="text/css" href="../css/sprint_storyboard.css">
    </head>
    <body>
        <?php
            $htmlBody = new SprintStoryboardHTML();

            echo $htmlBody->generateBody();
        ?>

        <script src="../js/addtable.js"></script>
    </body>
</html>
