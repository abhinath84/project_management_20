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

            $tag .= '<div class="user-profile">' . $this->EOF_LINE;
            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="user-name" translate="CHANGE_PASSWORD.FIELD_CURRENT_PASSWORD">Username<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '       <input type="text" id="user-name" placeholder="Username"></input>' . $this->EOF_LINE;
            $tag .= '       <div class="retro-style-errmsg" id="user-name-errmsg"></div>';
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="email">Email<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '       <input type="email" id="email" placeholder="Email"></input>' . $this->EOF_LINE;
            $tag .= '       <div class="retro-style-errmsg" id="email-errmsg"></div>';
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="user-full-name">Name<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '       <input type="text" id="first-name" placeholder="First name" style="width: 49%;"></input>' . $this->EOF_LINE;
            $tag .= '       <input type="text" id="last-name" placeholder="Last name" style="width: 45%;"></input>' . $this->EOF_LINE;
            $tag .= '       <div class="retro-style-errmsg" id="first-name-errmsg"></div>';
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="gender">Gender</label>' . $this->EOF_LINE;
            $tag .= '       <select id="gender-select" name="gender" onblur="javascript:showErrorMsg(\'gender\', \'select\', \'\')" style="width:102.5%;">' . $this->EOF_LINE;
            $tag .= '           <option value="Male">Male</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Female">Female</option>' . $this->EOF_LINE;
            $tag .= '       </select>' . $this->EOF_LINE;
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            /* '', '', '', '', '', '',
                                '', '', '', '', '', '') */
            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="title">Title</label>' . $this->EOF_LINE;
            $tag .= '       <select id="title-select" name="title" onblur="javascript:showErrorMsg(\'title\', \'select\', \'\')" style="width:102.5%;">' . $this->EOF_LINE;
            $tag .= '           <option value="Software Specialist">Software Specialist</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Senior Software Specialist">Senior Software Specialist</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Tech Lead">Tech Lead</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Senior Tech Lead">Senior Tech Lead</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Technical Consultant">Technical Consultant</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Senior Technical Consultant">Senior Technical Consultant</option>' . $this->EOF_LINE;
            $tag .= '           <option value="QA">QA</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Senior QA">Senior QA</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Tech Lead QA">Tech Lead QA</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Senior Tech Lead QA">Senior Tech Lead QA</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Consultant QA">Consultant QA</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Others">Others</option>' . $this->EOF_LINE;
            $tag .= '       </select>' . $this->EOF_LINE;
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="department">Department</label>' . $this->EOF_LINE;
            $tag .= '       <select id="department-select" name="gender" onblur="javascript:showErrorMsg(\'gender\', \'select\', \'\')" style="width:102.5%;">' . $this->EOF_LINE;
            $tag .= '           <option value="Assembly">Assembly</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Drawing">Drawing</option>' . $this->EOF_LINE;
            $tag .= '           <option value="QA">QA</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Technical Writer">Technical Writer</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Manufacturing">Manufacturing</option>' . $this->EOF_LINE;
            $tag .= '           <option value="Others">Others</option>' . $this->EOF_LINE;
            $tag .= '       </select>' . $this->EOF_LINE;
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="alt-email">Alternative Email</label>' . $this->EOF_LINE;
            $tag .= '       <input type="email" id="alt-email" placeholder="Alternative Email"></input>' . $this->EOF_LINE;
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="manager">Manager<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '       <input type="text" id="manager" placeholder="Manager"></input>' . $this->EOF_LINE;
            $tag .= '       <div class="retro-style-errmsg" id="manager-errmsg"></div>';
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <button type="submit" title="Save" class="retro-style green-bg" style="width: 102.5%; height: 40px;" onclick="Profile.update()">Save</button>' . $this->EOF_LINE;
            $tag .= '   </fieldset>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

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
            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="current-password" translate="CHANGE_PASSWORD.FIELD_CURRENT_PASSWORD">Current password<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '       <input type="password" id="current-password" placeholder="Your current password (or empty if you have no password yet)"></input>' . $this->EOF_LINE;
            $tag .= '       <div class="retro-style-errmsg" id="user-name-errmsg"></div>';
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="new-password">New password<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '       <input data-required="true" type="password" id="new-password" placeholder="Type a new password"></input>' . $this->EOF_LINE;
            $tag .= '       <div class="retro-style-errmsg" id="user-name-errmsg"></div>';
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <label for="retype-password">Retype new password<span class="red-asterisk">*</span></label>' . $this->EOF_LINE;
            $tag .= '       <input data-required="true" type="password" id="retype-password" placeholder="Retype the new password"></input>' . $this->EOF_LINE;
            $tag .= '       <div class="retro-style-errmsg" id="user-name-errmsg"></div>';
            $tag .= '   </fieldset>' . $this->EOF_LINE;

            $tag .= '   <fieldset>' . $this->EOF_LINE;
            $tag .= '       <button type="submit" title="Save" class="retro-style green-bg" style="width: 102.5%; height: 40px;" onclick="ChangePwd.update()">Save</button>' . $this->EOF_LINE;
            $tag .= '   </fieldset>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }
?>
