<?php
    /*ini_set('display_errors', 'On');
    error_reporting(E_ALL);*/

    require_once ('../inc/functions.inc.php');
    require_once ('../inc/mysql_functions.inc.php');
    require_once ('../inc/adminhtml.php');

    // Create Database and required tables
    build_db();

    // Initialize session data
    session_start();

    // if not log in then redirect to login page.
    if(!isset($_SESSION['project-managment-username']))
        header("Location: ../user/login.php?redirect=../admin/projects.php");
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Scrum-Product Planning</title>
        <link rel="stylesheet" type="text/css" href="../css/retro_style.css">
        <link rel="stylesheet" type="text/css" href="../css/grippy_table.css">
        <link rel="stylesheet" type="text/css" href="../css/shield.css">
        <link rel="stylesheet" type="text/css" href="../css/global.css">
        <link rel="stylesheet" type="text/css" href="../css/admin.css">
        <script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="../js/jqry.js"></script>
        <script type="text/javascript" src="../js/utility.js"></script>
        <script type="text/javascript" src="../js/shield.js"></script>
        <script type="text/javascript" src="../js/functions.js"></script>
        <script type="text/javascript" src="../js/stupidtable.min.js?dev"></script>
        <script type="text/javascript" src="../js/addtable.js"></script>
        <script>
            $(document).ready(function(){
               $(".up").click(function() {
                  $('html, body').animate({
                  scrollTop: 0
               }, 2000);
             });
            });

        </script>
    </head>
    <body>
        <?php
            $htmlBody = new ProjectsHTML();
            echo $htmlBody->generateBody();
        ?>
    </body>
</html>
