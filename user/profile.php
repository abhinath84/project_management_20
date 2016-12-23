<!--
	File	: profile.php
	Author	: Abhishek Nath
	Date	: 01-Jan-2015
	Desc	: Page for user profile.
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

	// Create Database and required tables
	build_db();

    // Initialize session data
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PTC:Project Management</title>
        <link rel="stylesheet" type="text/css" href="../css/global.css" />
        <script type="text/javascript" src="../ajax/xmlHttp.js"></script>
        <script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="../js/functions.js"></script>
        <script type="text/javascript">
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
			echo addHeader("Sign Up", false, "user");
		?>
        <div id="wrapper" class="wrapper">
            <?php
                //echo addHeader();
            ?>
            <div class="clear" style="padding-top:20px">
				<?php
					echo addFooter("user");
				?>
			</div>
        </div>
    </body>
</html>
