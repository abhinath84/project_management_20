<!--
    File	: result.php
    Author	: Abhishek Nath
    Date	: 01-Jan-2015
    Desc	: This file will be called by other files while they
      need to redirect for some result.
      Like: After succesfully creation of the userid,
      signup.php file call this file to pass the userid
      creation msg and redirect to index.php page.
-->

<!--
    01-Jan-15   V1-01-00   abhishek   $$1   Created.
    17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
-->


<?php
    require_once 'inc/functions.inc.php';
    require_once 'inc/mysql_functions.inc.php';

    // Initialize session data
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PTC:Project Management</title>
        <link rel="stylesheet" type="text/css" href="css/global.css" />
        <link rel="stylesheet" type="text/css" href="css/result.css" />
        <script type="text/javascript" src="ajax/xmlHttp.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
    </head>
    <body>
        <div id="wrapper" class="wrapper">
            <?php
                $nav = new Navigator();
                echo $nav->header_new("base", "Login", false);
            ?>
            <!-- Create login form -->
            <div id="result-main" class="result-main">
                <div class="congratulation">
                    <?php
                        if((isset($_GET['action'])) && ($_GET['action'] == "logout"))
                        {
                            // or this would remove all the variables in the session, but not the session itself
                            session_unset();

                            // this would destroy the session variables
                            session_destroy();

                            // Redirect to home page.
                            header("Location: index.php");
                        }
                        else if((isset($_GET['user'])) && ($_GET['user'] == "created"))
                        {
                            echo "<p>User created successfully. To login please click <a href='user/login.php'>here</a></p>";
                        }
                    ?>
                </div>
            </div>
            <?php
                $nav = new Navigator();
                echo $nav->footer("base");
            ?>
        </div>
    </body>
</html>
