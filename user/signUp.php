<!--
    File	: signUp.php
    Author	: Abhishek Nath
    Date	: 01-Jan-2015
    Desc	: Page for new signup.
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
    require_once ('../inc/htmltemplate.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PTC:Project Management</title>
        <link rel="stylesheet" type="text/css" href="../css/retro_style.css">
        <link rel="stylesheet" type="text/css" href="../css/global.css" />
        <link rel="stylesheet" type="text/css" href="../css/sign_up.css" />
        <script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="../js/functions.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                // process the form
                $('#createaccount').submit(function(event) {

                    signupSubmit();

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
    <body class="signup-body">
        <div class="middle-align">
            <?php
                $htmlBody = new SignupHTML();

                echo $htmlBody->generateBody();
            ?>
            <!--<div id="signup-main" class="signup-main display-table">
                <div class="signup display-table-cell">
                    <h3>Sign Up</h3>
                    <form class="createaccount-form" id="createaccount" name="createaccount" method="post">
                        <input class="retro-style" type="hidden" name="page" id="page" value="signup">
                        <div class="retro-style-form-element multi-field name" id="name-form-element">
                            <fieldset>
                                <legend><strong>Name</strong></legend>
                                <label id="firstname-label" class="firstname">
                                    <strong>First name</strong>
                                    <input type="text" value="" class="retro-style" name="firstName" id="firstName-input" onblur="javascript:showErrorMsg('firstName', 'input', 'name')"
                                    placeholder="First" spellcheck="false">
                                </label>
                                <label id="lastname-label" class="lastname">
                                    <strong>Last name</strong>
                                    <input type="text" class="retro-style" value="" name="lastName" id="lastName-input" onblur="javascript:showErrorMsg('lastName', 'input', 'name')"
                                    placeholder="Last" spellcheck="false">
                                </label>
                            </fieldset>
                            <div class="retro-style-errmsg" id="name-errmsg"></div>
                        </div>
                        <?php
                            //echo addInputTag('input', 'text', 'username', 'Choose your username', 'onblur="javascript:showErrorMsg(\'username\', \'input\', \'\')"', "");
                            //echo addInputTag('input', 'password', 'password', 'Create a password', 'onblur="javascript:showErrorMsg(\'password\', \'input\', \'\')"', "");
                            //echo addInputTag('input', 'password', 'confirm-password', 'Confirm your password', 'onblur="javascript:showErrorMsg(\'confirm-password\', \'input\', \'\')"', "");
                            //echo addGenderTag('onblur="javascript:showErrorMsg(\'gender\', \'select\', \'\')"');
                            ////echo addInputTag('input', 'text', 'title', 'Title', 'onblur="javascript:showErrorMsg(\'title\', \'input\', \'\')"');
                            //echo addTitleTag('onblur="javascript:showErrorMsg(\'title\', \'select\', \'\')"');
                            //echo addDepertmentTag('onblur="javascript:showErrorMsg(\'department\', \'select\', \'\')"');
                            //echo addInputTag('input', 'text', 'manager', 'Manager', 'onblur="javascript:showErrorMsg(\'manager\', \'input\', \'\')"', "");
                            //echo addInputTag('input', 'text', 'email', 'Your current email address', 'onblur="javascript:showErrorMsg(\'email\', \'input\', \'\')"', "");
                            //echo addInputTag('input', 'text', 'altEmail', 'Your alternative email address(optional)', '', "");
                        ?>
                        <div class="form-element">
                            <input id="submitbutton" name="submitbutton" type="submit" value="Submit" class="retro-style blue">
                        </div>
                    </form>
                </div>
                <div id="login-nav" class="login display-table-cell">
                    <div id="login-nav-sub">
                        <h3>Have an Account?</h3>
                        <p>If you already have a password, please <a href="login.php">Login</a>.</p>
                    </div>
                </div>
            </div>-->
        </div>
    </body>
</html>
