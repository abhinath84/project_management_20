<?php
    $imagesPath = "../images";
    $homeURL = "../index.php";
	
    $aboutURL = "";
    $contactURL = "";
    $profileURL = "";
    
    $loginURL = "login.php";
    $signinURL = "signUp.php";
	
    $copyrightURL = "../about/about_copyright.php";
    $privacyURL = "../about/about_privacy.php";

    require_once '../inc/functions.inc.php';
    require_once '../inc/mysql_functions.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PTC:Project Management</title>
        <link rel="stylesheet" type="text/css" href="../css/global.css" />
        <style>
			.login-main, #recovery-msg-container {
				font-size: 15px;
			}
			
			.side-align {
				height:500px; 
				margin-left:150px; 
				margin-top:20px;
			}
        </style>
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
    </head>
    <body>
		<?php
			echo addHeader("", false);
		?>
        <div id="wrapper" class="wrapper page-wrap">
            <!-- Create login form -->
            <div id="recovery-main" class="login-main side-align">
				<h1>Having trouble loging in?</h1>
				<form id="recovery-form" class="login-form" method="post">
					<input type="hidden" name="page" id="page" value="recovery">
					<div class="errmsg" id="recovery-errmsg"></div>
					<!-- 1. I don't know my password (radio) and hidden input/text to provide username-->
					<div id='password-radio-contaioner' class="recovery-radio-container">
						<input type="radio" name="recovery" value="password" onclick="javascript:recoveryOptionSelected('password-radio-input-container')">
							<label id="password-radio-label" for="password">I don't know my password</label>
						<div id="password-radio-input-container" class="recovery-radio-input-container">
							<label id="username-radio-input-label">To reset your password, enter the username you use to sign in.</label>
							<?php
								echo addInputTag('input', 'text', 'username', 'username', '');
							?>
						</div>
					</div>
					<!-- 2. I don't know my username (radio) and hidden input/text to provide email id-->
					<div id='username-radio-contaioner' class="recovery-radio-container">
						<input type="radio" name="recovery" value="username" onclick="javascript:recoveryOptionSelected('username-radio-input-container')">
							<label id="username-radio-label" for="username">I don't know my username</label>
						<div id="username-radio-input-container" class="recovery-radio-input-container">
							<label id="email-radio-input-label">To know your username, enter the email address associated with your account.</label>
							<?php
								echo addInputTag('input', 'text', 'email', 'e-mail', '');
							?>
						</div>
					</div>
					<div style="margin-top:20px">
						<input id="continue" name="continue" type="submit" value="Continue" class="ent-button ent-button-submit">
					</div>
				</form>
            </div>
            <div id="recovery-msg-container" class="side-align" style="display:none">
				<p id="recovery-p"></p>
				<p>To log in, please click <a href="login.php">here</a>.</p>
            </div>
        </div>
        <?php
		   echo addFooter();
		?>
    </body>
</html>
