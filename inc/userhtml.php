<?php
    /* include header file */
    require_once ('htmltemplate.php');

    abstract class UserHTML extends HTMLTemplate
    {
        protected $table        = null;
        protected $dropdownList = null;

        public function __construct($curNav = null, $curDir = null, $enableNav = false, $currentTab = null)
        {
            $tabs = array   (
                                array('View Profile', 'view_profile.php', SVG::getViewProfile()),
                                array('Edit Profile', 'edit_profile.php', SVG::getEditProfile()),
                                array('Change Password', 'changepwd.php', SVG::getChangePassword())
                            );

            parent::__construct($curNav, $curDir, $enableNav, $tabs, $currentTab);
        }

        protected function addDashboard()
        {
            return( parent::getWidgetbox() );
        }
    }

    class ViewProfileHTML extends UserHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Profile", "user", true, "View Profile");
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            return($tag);
        }
    }

    class EditProfileHTML extends UserHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Profile", "user", true, "Edit Profile");
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            // get user information.
            $cols = ['user_name', 'email', 'first_name', 'last_name', 'title', 'department', 'alt_email', 'manager'];
            $clause = 'user_name="'. $_SESSION['project-managment-username'] .'"';

            $user = getTableElements('user', $cols, $clause);
            if(($user != null) && (count($user) > 0))
            {
                $tag .= '<div class="user-profile">' . $this->EOF_LINE;
                $tag .= '   <form id="editprofile" name="editprofile" class="manifest-form" method="post">' . $this->EOF_LINE;

                // Username
                $tag .= '       <fieldset>' . $this->EOF_LINE;
                $tag .= '           <label for="username" translate="CHANGE_PASSWORD.FIELD_CURRENT_PASSWORD">Username<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
                $tag .= '           <input type="text" id="username" required="" placeholder="Username" value="'.Utility::decode($user[0][0]).'" disabled></input>' . $this->EOF_LINE;
                $tag .= '       </fieldset>' . $this->EOF_LINE;

                // Email
                $tag .= '       <fieldset>' . $this->EOF_LINE;
                $tag .= '           <label for="email">Email<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
                $tag .= '           <input type="email" id="email" required="" placeholder="Email" value="'.Utility::decode($user[0][1]).'"></input>' . $this->EOF_LINE;
                $tag .= '       </fieldset>' . $this->EOF_LINE;

                // Name
                $tag .= '       <fieldset>' . $this->EOF_LINE;
                $tag .= '           <label for="user-full-name">Name<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
                $tag .= '           <input type="text" id="first-name" required="" placeholder="First name" style="width: 49%;" value="'.Utility::decode($user[0][2]).'"></input>' . $this->EOF_LINE;
                $tag .= '           <input type="text" id="last-name" required="" placeholder="Last name" style="width: 45%;" value="'.Utility::decode($user[0][3]).'"></input>' . $this->EOF_LINE;
                $tag .= '       </fieldset>' . $this->EOF_LINE;

                // Title
                $title = Utility::decode($user[0][4]);
                $tag .= '       <fieldset>' . $this->EOF_LINE;
                $tag .= '           <label for="title">Title</label>' . $this->EOF_LINE;
                $tag .= '           <select id="title-select" name="title" onblur="javascript:showErrorMsg(\'title\', \'select\', \'\')" style="width:102.5%;">' . $this->EOF_LINE;
                $tag .= '               <option value="Software Specialist" '.(($title == 'Software Specialist') ? 'selected' : '').'>Software Specialist</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Senior Software Specialist" '.(($title == 'Senior Software Specialist') ? 'selected' : '').'>Senior Software Specialist</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Tech Lead" '.(($title == 'Tech Lead') ? 'selected' : '').'>Tech Lead</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Senior Tech Lead" '.(($title == 'Senior Tech Lead') ? 'selected' : '').'>Senior Tech Lead</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Technical Consultant" '.(($title == 'Technical Consultant') ? 'selected' : '').'>Technical Consultant</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Senior Technical Consultant" '.(($title == 'Senior Technical Consultant') ? 'selected' : '').'>Senior Technical Consultant</option>' . $this->EOF_LINE;
                $tag .= '               <option value="QA" '.(($title == 'QA') ? 'selected' : '').'>QA</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Senior QA" '.(($title == 'Senior QA') ? 'selected' : '').'>Senior QA</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Tech Lead QA" '.(($title == 'Tech Lead QA') ? 'selected' : '').'>Tech Lead QA</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Senior Tech Lead QA" '.(($title == 'Senior Tech Lead QA') ? 'selected' : '').'>Senior Tech Lead QA</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Consultant QA" '.(($title == 'Consultant QA') ? 'selected' : '').'>Consultant QA</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Others" '.(($title == 'Others') ? 'selected' : '').'>Others</option>' . $this->EOF_LINE;
                $tag .= '           </select>' . $this->EOF_LINE;
                $tag .= '       </fieldset>' . $this->EOF_LINE;

                // Department
                $department = Utility::decode($user[0][5]);
                $tag .= '       <fieldset>' . $this->EOF_LINE;
                $tag .= '           <label for="department">Department</label>' . $this->EOF_LINE;
                $tag .= '           <select id="department-select" name="gender" onblur="javascript:showErrorMsg(\'gender\', \'select\', \'\')" style="width:102.5%;">' . $this->EOF_LINE;
                $tag .= '           <option value="Assembly" '.(($department == 'Assembly') ? 'selected' : '').'>Assembly</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Drawing" '.(($department == 'Drawing') ? 'selected' : '').'>Drawing</option>' . $this->EOF_LINE;
                $tag .= '               <option value="QA" '.(($department == 'QA') ? 'selected' : '').'>QA</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Technical Writer" '.(($department == 'Technical Writer') ? 'selected' : '').'>Technical Writer</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Manufacturing" '.(($department == 'Manufacturing') ? 'selected' : '').'>Manufacturing</option>' . $this->EOF_LINE;
                $tag .= '               <option value="Others" '.(($department == 'Others') ? 'selected' : '').'>Others</option>' . $this->EOF_LINE;
                $tag .= '           </select>' . $this->EOF_LINE;
                $tag .= '       </fieldset>' . $this->EOF_LINE;

                // Manager
                $tag .= '       <fieldset>' . $this->EOF_LINE;
                $tag .= '           <label for="manager">Manager<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
                $tag .= '           <input type="text" id="manager" required="" placeholder="Manager" value="'.Utility::decode($user[0][7]).'"></input>' . $this->EOF_LINE;
                $tag .= '       </fieldset>' . $this->EOF_LINE;

                // Alt email
                $tag .= '       <fieldset>' . $this->EOF_LINE;
                $tag .= '           <label for="alt-email">Alternative Email</label>' . $this->EOF_LINE;
                $tag .= '           <input type="email" id="alt-email" placeholder="Alternative Email" value="'.Utility::decode($user[0][6]).'"></input>' . $this->EOF_LINE;
                $tag .= '       </fieldset>' . $this->EOF_LINE;

                // Save button
                $tag .= '       <fieldset>' . $this->EOF_LINE;
                $tag .= '           <button type="submit" title="Save" class="retro-style green-bg" style="width: 102.5%; height: 40px;">Save</button>' . $this->EOF_LINE;
                $tag .= '       </fieldset>' . $this->EOF_LINE;
                $tag .= '   </form>' . $this->EOF_LINE;

                // Delete Account link
                $tag .= '   <div class="delete-account">' . $this->EOF_LINE;
                $tag .= '       <p>' . $this->EOF_LINE;
                $tag .= '           <a href="login.php" title="deleteAccount">Delete your Account</a>' . $this->EOF_LINE;
                $tag .= '       </p>' . $this->EOF_LINE;
                $tag .= '   </div>' . $this->EOF_LINE;
                $tag .= '</div>' . $this->EOF_LINE;
            }

            return($tag);
        }
    }

    class ChangePasswordHTML extends UserHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Profile", "user", true, "Change Password");
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            $tag .= '<div class="user-profile">' . $this->EOF_LINE;
            $tag .= '   <form id="changepwd" name="changepwd" class="manifest-form" method="post">' . $this->EOF_LINE;
            $tag .= '       <fieldset>' . $this->EOF_LINE;
            $tag .= '           <label for="current-password" translate="CHANGE_PASSWORD.FIELD_CURRENT_PASSWORD">Current password<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '           <input type="password" id="current-password"></input>' . $this->EOF_LINE;
            $tag .= '       </fieldset>' . $this->EOF_LINE;

            $tag .= '       <fieldset>' . $this->EOF_LINE;
            $tag .= '           <label for="new-password">New password<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '           <input type="password" id="new-password"></input>' . $this->EOF_LINE;
            $tag .= '       </fieldset>' . $this->EOF_LINE;

            $tag .= '       <fieldset>' . $this->EOF_LINE;
            $tag .= '           <label for="retype-password">Retype new password<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '           <input type="password" id="retype-password"></input>' . $this->EOF_LINE;
            $tag .= '       </fieldset>' . $this->EOF_LINE;

            $tag .= '       <fieldset>' . $this->EOF_LINE;
            $tag .= '           <button type="submit" title="Save" class="retro-style green-bg" style="width: 102.5%; height: 40px;">Save</button>' . $this->EOF_LINE;
            $tag .= '       </fieldset>' . $this->EOF_LINE;
            $tag .= '   </form>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }


    abstract class UserAuthenticateHTML
    {
        protected $EOF_LINE     = "\n";

        public function __construct()
        {
        }

        public function generateBody()
        {
            $tag = '';

            $tag .= '<div class="manifest">' . $this->EOF_LINE;
            $tag .= '   <div class="manifest-container">' . $this->EOF_LINE;
            $tag .= '       <div class="manifest-logo">' . $this->EOF_LINE;
            $tag .= '           <a href="../index.php">' . $this->EOF_LINE;
            $tag .= '               <img src="../images/pm.png" alt="pm.com">' . $this->EOF_LINE;
            $tag .= '           </a>' . $this->EOF_LINE;
            $tag .= '           <h2><a href="../index.php">Project Management</a></h2>' . $this->EOF_LINE;
            $tag .= '       </div>' . $this->EOF_LINE;

            $tag .=     $this->addManifestform() . $this->EOF_LINE;

            $tag .= '   </div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }

        // while writing this abstract function in child class,
        // remember onething, main <div> tag must be having
        // class = 'manifest-form-container'.
        // Otherwise inside conponent might not be getting
        // that class specific style for it's child elements.
        abstract protected function addManifestform();
    }

    class LoginHTML extends UserAuthenticateHTML
    {
        protected $EOF_LINE     = "\n";

        public function __construct()
        {
            parent::__construct();
        }

        protected function addManifestform()
        {
            $tag = '';

            $tag .= '       <div class="manifest-form-container">' . $this->EOF_LINE;
            $tag .= '           <form id="login-form" class="manifest-form" method="post">' . $this->EOF_LINE;
            $tag .= '               <input type="hidden" name="page" id="page" value="login" class="retro-style">' . $this->EOF_LINE;
            $tag .= '               <input type="hidden" name="redirect" id="redirect" value=' . (isset($_GET['redirect']) ? $_GET['redirect'] : '') . '>' . $this->EOF_LINE;

            // Username fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <input id="username-input" type="text" required="" name="username" placeholder="Username (case sensitive)" class="checksley-validated">' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            // Password fieldset
            $tag .= '               <fieldset class="login-password">' . $this->EOF_LINE;
            $tag .= '                   <input id="password-input" type="password" name="password" required="" placeholder="Password (case sensitive)">' . $this->EOF_LINE;
            // link for 'Forgot password'
            $tag .= '                   <a id="forgot-pwd" href="recovery.php" title="Did you forget your password?" class="forgot-pass">Forgot it?</a>' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            // Login fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <button type="submit" title="Login" class="retro-style green-bg submit-button">Login</button>' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;
            $tag .= '           </form>' . $this->EOF_LINE;

            // Sign up link
            $tag .= '           <div>' . $this->EOF_LINE;
            $tag .= '               <p class="login-text">' . $this->EOF_LINE;
            $tag .= '                   <span>Not registered yet?&nbsp;</span>' . $this->EOF_LINE;
            $tag .= '                   <a href="signUp.php" title="Register">create your free account here</a>' . $this->EOF_LINE;
            $tag .= '               </p>' . $this->EOF_LINE;
            $tag .= '           </div>' . $this->EOF_LINE;
            $tag .= '       </div>' . $this->EOF_LINE;

            /*$tag .= '<div class="login-main display-table">';
            $tag .= '<div class="login display-table-cell">' . $this->EOF_LINE;
            $tag .= '    <div id="login-sub">' . $this->EOF_LINE;
            $tag .= '        <h3>Log In</h3>' . $this->EOF_LINE;
            $tag .= '        <form id="login-form" class="login-form" method="post">' . $this->EOF_LINE;
            $tag .= '            <input type="hidden" name="page" id="page" value="login" class="retro-style">' . $this->EOF_LINE;
            $tag .= '            <input type="hidden" name="redirect" id="redirect" value=' . (isset($_GET['redirect']) ? $_GET['redirect'] : '') . '>' . $this->EOF_LINE;
            $tag .=              addInputTag('input', 'text', 'username', 'Username', '', '') . $this->EOF_LINE;
            $tag .=              addInputTag('input', 'password', 'password', 'Password', '', '') . $this->EOF_LINE;
            $tag .= '            <div class="retro-style-form-element">' . $this->EOF_LINE;
            $tag .= '                <input id="signIn" name="signIn" type="submit" value="Sign in" class="retro-style royal-blue">' . $this->EOF_LINE;
            $tag .= '            </div>' . $this->EOF_LINE;
            $tag .= '        </form>' . $this->EOF_LINE;
            $tag .= '        <span><a id="link-forgot-passwd" href="recovery.php">Can&#39;t access your account?</a></span>' . $this->EOF_LINE;
            $tag .= '    </div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div id="signup-nav display-table-cell">' . $this->EOF_LINE;
            $tag .= '    <div id="signup-nav-sub">' . $this->EOF_LINE;
            $tag .= '        <h3>Don\'t have an account?</h3>' . $this->EOF_LINE;
            $tag .= '        <p><a href="signUp.php">Sign up</a> today.</p>' . $this->EOF_LINE;
            $tag .= '    </div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .='</div>' . $this->EOF_LINE;*/

            return($tag);
        }
    }

    class SignupHTML extends UserAuthenticateHTML
    {
        public function __construct()
        {
            parent::__construct();
        }

        protected function addManifestform()
        {
            $tag = '';

            $tag .= '<div class="manifest-form-container">' . $this->EOF_LINE;
            $tag .= '   <form id="createaccount" name="createaccount" class="manifest-form" method="post">' . $this->EOF_LINE;
            $tag .= '        <input type="hidden" name="page" id="page" value="signup">' . $this->EOF_LINE;

            // Username fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <input id="username-input" type="text" required="" name="username" placeholder="Choose a username (case sensitive)">' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            // Name fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <input id="firstName-input" type="text" style="width: 49%; margin-left:0; margin-right:0;" required="" name="firstName" placeholder="First Name">' . $this->EOF_LINE;
            $tag .= '                   <input id="lastName-input" type="text" style="width: 41%; margin-left:0; margin-right:0;" required="" name="lastName" placeholder="Last Name">' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            //Email fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <input id="email-input" type="email" required="" name="email" placeholder="Your email">' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            // Password fieldset
            $tag .= '               <fieldset class="login-password">' . $this->EOF_LINE;
            $tag .= '                   <input id="password-input" type="password" name="password" required="" placeholder="Set a Password (case sensitive)">' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            // title fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <select id="title-select" name="title" title="Title" style="width:104.5%;">' . $this->EOF_LINE;
            $tag .= '                       <option value="Software Specialist">Software Specialist</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Senior Software Specialist">Senior Software Specialist</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Tech Lead">Tech Lead</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Senior Tech Lead">Senior Tech Lead</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Technical Consultant">Technical Consultant</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Senior Technical Consultant">Senior Technical Consultant</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="QA">QA</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Senior QA">Senior QA</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Tech Lead QA">Tech Lead QA</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Senior Tech Lead QA">Senior Tech Lead QA</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Consultant QA">Consultant QA</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Others">Others</option>' . $this->EOF_LINE;
            $tag .= '                   </select>' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            // department fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <select id="department-select" name="department" style="width:104.5%;">' . $this->EOF_LINE;
            $tag .= '                       <option value="Assembly">Assembly</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Drawing">Drawing</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="QA">QA</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Technical Writer">Technical Writer</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Manufacturing">Manufacturing</option>' . $this->EOF_LINE;
            $tag .= '                       <option value="Others">Others</option>' . $this->EOF_LINE;
            $tag .= '                   </select>' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            // manager fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <input id="manager-input" type="text" required="" name="manager" placeholder="Your Manager (username)">' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            //Alternative Email fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <input id="altEmail-input" type="email" name="altEmail" placeholder="Your alt-email (optional)">' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            // Signup fieldset
            $tag .= '               <fieldset>' . $this->EOF_LINE;
            $tag .= '                   <button type="submit" d="submitbutton" name="submitbutton" class="retro-style green-bg submit-button">Sign Up</button>' . $this->EOF_LINE;
            $tag .= '               </fieldset>' . $this->EOF_LINE;

            $tag .= '   </form>' . $this->EOF_LINE;

            // Login link
            $tag .= '   <div>' . $this->EOF_LINE;
            $tag .= '       <p class="login-text">' . $this->EOF_LINE;
            $tag .= '           <span>Are you already registered?&nbsp;</span>' . $this->EOF_LINE;
            $tag .= '           <a href="login.php" title="Register">Log in</a>' . $this->EOF_LINE;
            $tag .= '       </p>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            /*$tag .= '<div id="signup-main" class="signup-main display-table">' . $this->EOF_LINE;

            $tag .= '<div class="signup display-table-cell">' . $this->EOF_LINE;
            $tag .= '    <h3>Sign Up</h3>' . $this->EOF_LINE;
            $tag .= '    <form class="createaccount-form" id="createaccount" name="createaccount" method="post">' . $this->EOF_LINE;
            $tag .= '        <input class="retro-style" type="hidden" name="page" id="page" value="signup">' . $this->EOF_LINE;
            $tag .= '        <div class="retro-style-form-element multi-field name" id="name-form-element">' . $this->EOF_LINE;
            $tag .= '            <fieldset>' . $this->EOF_LINE;
            $tag .= '                <legend><strong>Name</strong></legend>' . $this->EOF_LINE;
            $tag .= '                <label id="firstname-label" class="firstname">' . $this->EOF_LINE;
            $tag .= '                    <strong>First name</strong>' . $this->EOF_LINE;
            $tag .= '                    <input type="text" value="" class="retro-style" name="firstName" id="firstName-input" onblur="javascript:showErrorMsg(\'firstName\', \'input\', \'name\')" placeholder="First" spellcheck="false">' . $this->EOF_LINE;
            $tag .= '                </label>' . $this->EOF_LINE;
            $tag .= '                <label id="lastname-label" class="lastname">' . $this->EOF_LINE;
            $tag .= '                    <strong>Last name</strong>' . $this->EOF_LINE;
            $tag .= '                    <input type="text" class="retro-style" value="" name="lastName" id="lastName-input" onblur="javascript:showErrorMsg(\'lastName\', \'input\', \'name\')" placeholder="Last" spellcheck="false">' . $this->EOF_LINE;
            $tag .= '                </label>' . $this->EOF_LINE;
            $tag .= '            </fieldset>' . $this->EOF_LINE;
            $tag .= '            <div class="retro-style-errmsg" id="name-errmsg"></div>' . $this->EOF_LINE;
            $tag .= '        </div>' . $this->EOF_LINE;
            $tag .= addInputTag('input', 'text', 'username', 'Choose your username', 'onblur="javascript:showErrorMsg(\'username\', \'input\', \'\')"', "");
            $tag .= addInputTag('input', 'password', 'password', 'Create a password', 'onblur="javascript:showErrorMsg(\'password\', \'input\', \'\')"', "");
            $tag .= addInputTag('input', 'password', 'confirm-password', 'Confirm your password', 'onblur="javascript:showErrorMsg(\'confirm-password\', \'input\', \'\')"', "");
            $tag .= addGenderTag('onblur="javascript:showErrorMsg(\'gender\', \'select\', \'\')"');
            $tag .= addTitleTag('onblur="javascript:showErrorMsg(\'title\', \'select\', \'\')"');
            $tag .= addDepertmentTag('onblur="javascript:showErrorMsg(\'department\', \'select\', \'\')"');
            $tag .= addInputTag('input', 'text', 'manager', 'Manager', 'onblur="javascript:showErrorMsg(\'manager\', \'input\', \'\')"', "");
            $tag .= addInputTag('input', 'text', 'email', 'Your current email address', 'onblur="javascript:showErrorMsg(\'email\', \'input\', \'\')"', "");
            $tag .= addInputTag('input', 'text', 'altEmail', 'Your alternative email address(optional)', '', "");
            $tag .= '        <div class="retro-style-form-element">' . $this->EOF_LINE;
            $tag .= '            <input id="submitbutton" name="submitbutton" type="submit" value="Submit" class="retro-style royal-blue">' . $this->EOF_LINE;
            $tag .= '        </div>' . $this->EOF_LINE;
            $tag .= '    </form>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            $tag .= '   <div id="login-nav" class="login display-table-cell">' . $this->EOF_LINE;
            $tag .= '       <div id="login-nav-sub">' . $this->EOF_LINE;
            $tag .= '           <h3>Have an Account?</h3>' . $this->EOF_LINE;
            $tag .= '           <p>If you already have a password, please <a href="login.php">Login</a>.</p>' . $this->EOF_LINE;
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;*/

            return($tag);
        }
    }

    class RecoveryHTML extends UserAuthenticateHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            //parent::__construct("Login", "user");
        }

        protected function addManifestform()
        {
            $tag = '<div id="recovery-main" class="manifest-form-container">' . $this->EOF_LINE;
            $tag .= '    <form id="recovery-form" class="manifest-form" method="post">' . $this->EOF_LINE;
            $tag .= '       <h1>Having trouble loging in?</h1>' . $this->EOF_LINE;
            $tag .= '        <input type="hidden" name="page" id="page" value="recovery">' . $this->EOF_LINE;
            $tag .= '        <div class="retro-style-errmsg" id="recovery-errmsg"></div>' . $this->EOF_LINE;

            //<!-- 1. I don't know my password (radio) and hidden input/text to provide username-->
            $tag .= '        <div id="password-radio-contaioner" class="recovery-radio-container">' . $this->EOF_LINE;
            $tag .= '            <input type="radio" class="retro-style" name="recovery" value="password" onclick="recoveryOptionSelected(\'password-radio-input-container\')">' . $this->EOF_LINE;
            $tag .= '            <label id="password-radio-label" for="password">I don\'t know my password</label>' . $this->EOF_LINE;
            $tag .= '            <div id="password-radio-input-container" class="recovery-radio-input-container">' . $this->EOF_LINE;
            $tag .= '                <label id="username-radio-input-label">To reset your password, enter the username you use to sign in.</label>' . $this->EOF_LINE;
            $tag .=                  addInputTag('input', 'text', 'username', 'username', '', '');
            $tag .= '            </div>' . $this->EOF_LINE;
            $tag .= '        </div>' . $this->EOF_LINE;

            //<!-- 2. I don't know my username (radio) and hidden input/text to provide email id-->
            $tag .= '        <div id="username-radio-contaioner" class="recovery-radio-container">' . $this->EOF_LINE;
            $tag .= '            <input type="radio" class="retro-style" name="recovery" value="username" onclick="recoveryOptionSelected(\'username-radio-input-container\')">' . $this->EOF_LINE;
            $tag .= '            <label id="username-radio-label" for="username">I don\'t know my username</label>' . $this->EOF_LINE;
            $tag .= '            <div id="username-radio-input-container" class="recovery-radio-input-container">' . $this->EOF_LINE;
            $tag .= '                <label id="email-radio-input-label">To know your username, enter the email address associated with your account.</label>' . $this->EOF_LINE;
            $tag .=                  addInputTag('input', 'text', 'email', 'e-mail', '', '');
            $tag .= '            </div>' . $this->EOF_LINE;
            $tag .= '        </div>' . $this->EOF_LINE;

            // Login fieldset
            $tag .= '        <fieldset style="margin-top:20px">' . $this->EOF_LINE;
            $tag .= '           <button id="continue" name="continue" type="submit" title="Continue" class="retro-style royal-blue submit-button">Continue</button>' . $this->EOF_LINE;
            $tag .= '        </fieldset>' . $this->EOF_LINE;
            $tag .= '    </form>' . $this->EOF_LINE;

            // Login link
            $tag .= '           <div>' . $this->EOF_LINE;
            $tag .= '               <p class="login-text">' . $this->EOF_LINE;
            $tag .= '                   <a href="login.php" title="login">Nah, take me back. I think I remember it.</a>' . $this->EOF_LINE;
            $tag .= '               </p>' . $this->EOF_LINE;
            $tag .= '           </div>' . $this->EOF_LINE;

            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div id="recovery-msg-container" class="side-align" style="display:none">' . $this->EOF_LINE;
            $tag .= '    <p id="recovery-p"></p>' . $this->EOF_LINE;
            $tag .= '    <p>To log in, please click <a href="login.php">here</a>.</p>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }
?>
