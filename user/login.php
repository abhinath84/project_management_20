<?php
    /*ini_set('display_errors', 'On');
    error_reporting(E_ALL);*/

    require_once ('../inc/functions.inc.php');
    require_once ('../inc/mysql_functions.inc.php');
    require_once ('../inc/userhtml.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PTC:Project Management</title>
        <link rel="stylesheet" type="text/css" href="../css/retro_style.css">
        <link rel="stylesheet" type="text/css" href="../css/global.css" />
        <link rel="stylesheet" type="text/css" href="../css/manifest.css" />
    </head>
    <body class="manifest-body">
        <?php
            $htmlBody = new LoginHTML();

            echo $htmlBody->generateBody();
        ?>

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

                // event for 'password-input' while update
                // visibility of 'forgot-pwd' link.
                var $pwdInput = $('#password-input');

                $pwdInput.click(function(event) {
                        $('#forgot-pwd').css('display', 'none');
                });

                $pwdInput.blur(function(event) {
                    var pwdValue = $('#password-input').val();

                    // check empty or not, if so then don't show the 'forgot it' link.
                    if((pwdValue != null) && (pwdValue != ''))
                        $('#forgot-pwd').css('display', 'none');
                    else
                        $('#forgot-pwd').css('display', 'block');
                });

                $(".up").click(function() {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 2000);
                });

            });
        </script>
    </body>
</html>
