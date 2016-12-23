<?php
    /*ini_set('display_errors', 'On');
    error_reporting(E_ALL);*/

    require_once ('../inc/functions.inc.php');
    require_once ('../inc/mysql_functions.inc.php');
    require_once ('../inc/htmltemplate.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PTC:Project Management</title>
        <link rel="stylesheet" type="text/css" href="../css/retro_style.css">
        <link rel="stylesheet" type="text/css" href="../css/global.css" />
        <link rel="stylesheet" type="text/css" href="../css/login.css" />
        <script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="../js/functions.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {

            // process the form
            $('#login-form').submit(function(event) {

                loginSubmit();

                // stop the form from submitting the normal way and refreshing the page
                event.preventDefault();
            });

            $(".up").click(function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 2000);
            });

        });
        </script>
    </head>
    <body class="login-body">
        <div class="middle-align">
            <?php
                $htmlBody = new UserLoginHTML();

                echo $htmlBody->generateBody();
            ?>
        </div>
    </body>
</html>
