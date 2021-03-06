<!--
    File    : recovery.php
    Author    : Abhishek Nath
    Date    : 01-Jan-2015
    Desc    : Page for recovering user related info.
-->

<!--
    01-Jan-15   V1-01-00   abhishek   $$1   Created.
    17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
-->

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
        <link rel="stylesheet" type="text/css" href="../css/recovery.css" />
    </head>
    <body class="manifest-body">
        <?php
            $htmlBody = new RecoveryHTML();

            echo $htmlBody->generateBody();
        ?>

        <script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="../js/functions.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                // set/reset default properties
                $('#recovery-msg-container').css( "display", "none");

                // process the form
                $('#recovery-form').submit(function(event) {
                    recoverySubmit();

                    // stop the form from submitting the normal way and refreshing the page
                    event.preventDefault();
                });

            });
        </script>
    </body>
</html>
