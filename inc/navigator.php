<?php

    class Navigator
    {
        private $sprTrackingDir = "spr_tracking";
        private $sprTrackingDashboardURL = "dashboard.php";
        private $sprTrackingSubmitStatusURL = "submit_status.php";
        private $sprTrackingReportURL = "report.php";
        private $sprTrackingImportURL = "spr_import.php";

        private $scrumDir = "scrum";
        private $scrumBacklogURL = "product_plan_backlog.php";
        private $scrumBacklogImport = "product_plan_backlog_import.php";
        private $scrumReleasePlanURL = "release_plan_dashboard.php";
        private $scrumSprintPlanURL = "sprint_plan_dashboard.php";
        private $scrumSprintTrackDetailedURL = "sprint_track_detail.php";
        private $scrumSprintTrackStoryboardURL = "sprint_track_storyboard.php";
        private $scrumSprintTrackTaskboardURL = "sprint_track_taskboard.php";
        private $scrumSprintTrackTestboardURL = "sprint_track_testboard.php";
        private $scrumSprintReviewURL = "sprint_review_dashboard.php";

        private $workTrackerDir = "work_tracker";
        private $workTrackerDashboardURL = "dashboard.php";

        private $baseDir = "base";
        private $homeURL = "index.php";
        private $logoutURL = "result.php?action=logout";
        private $aboutURL = "about.php";
        private $contactURL = "";
        private $copyrightURL = "copyright.php";
        private $privacyURL = "privacy.php";

        private $userDir = "user";
        private $adminURL = "admin.php";
        private $profileURL = "profile.php";
        private $loginURL = "login.php";
        private $signinURL = "signUp.php";
        private $changePasswordURL = "changepwd.php";

        private $adminDir = "admin";
        private $overviewURL = "overview.php";
        private $projectsURL = "projects.php";
        private $membersURL = "members.php";
        private $teamsURL = "teams.php";
        private $configurationURL = "configuration.php";

        private $imagesPath;

        public function header($currentDir, $selNav, $enableNav = true)
        {
            global $cipherObj;
            $tag = "";

            if(($currentDir <> "") && ($selNav <> ""))
            {
                $this->imagesPath = ($currentDir === "base") ? "images" : "../images";

                $tag .= '<div class="header display-table-row">' . EOF_LINE;
                $tag .= '    <div class="banner display-table">' . EOF_LINE;
                $tag .= '        <div class="logo display-table-cell">' . EOF_LINE;
                $tag .= '            <a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->homeURL) .'">' . EOF_LINE;
                $tag .= '                <img src="' . $this->imagesPath . '/ptc-master-color_small.png" alt="ptc.com"/>' . EOF_LINE;
                $tag .= '            </a>' . EOF_LINE;
                $tag .= '        </div>' . EOF_LINE;
                $tag .= '        <div class="title display-table-cell">' . EOF_LINE;
                $tag .= '            <h1>PROJECT MANAGEMENT</h1>' . EOF_LINE;
                $tag .= '        </div>' . EOF_LINE;

                if($enableNav == true)
                {
                    $tag .= '        <div class="admin-nav display-table-cell">' . EOF_LINE;
                    $tag .= '            <ul>' . EOF_LINE;

                    if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != ""))
                    {
                        $fname = $cipherObj->decrypt(getItemFromTable("first_name", "user", "user_name = '".$_SESSION['project-managment-username']."'"));
                        $lname = $cipherObj->decrypt(getItemFromTable("last_name", "user", "user_name = '".$_SESSION['project-managment-username']."'"));

                        if($this->isAdmin())
                        {
                            $tag .= '                <li>' . EOF_LINE;
                            $tag .= '                    <a href="" target="_top">ADMIN &#9660;</a>' . EOF_LINE;
                            $tag .= '                    <ul style="text-align: left;">' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->overviewURL) .'" target="_top">Overview</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->projectsURL) .'" target="_top">Projects</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->membersURL) .'" target="_top">Members</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->teamsURL) .'" target="_top">Teams</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->configurationURL) .'" target="_top">Configuration</a></li>' . EOF_LINE;
                            $tag .= '                    </ul>' . EOF_LINE;
                            $tag .= '                </li>' . EOF_LINE;
                        }

                        $tag .= '                <li>' . EOF_LINE;
                        $tag .= '                    <a href="" target="_top">'.$fname.' '.$lname.' &#9660;</a>' . EOF_LINE;
                        $tag .= '                    <ul style="text-align: left;">' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->profileURL) .'" target="_top">Profile</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->changePasswordURL) .'" target="_top">Change Password</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->logoutURL) .'" target="_top">Logout</a></li>' . EOF_LINE;
                        $tag .= '                    </ul>' . EOF_LINE;
                        $tag .= '                </li>' . EOF_LINE;
                    }
                    else
                    {
                        $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->loginURL) .'" target="_top">Login</a></li>' . EOF_LINE . EOF_LINE;
                        $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->signinURL) .'" target="_top">Sign Up</a></li>' . EOF_LINE;
                    }

                    $tag .= '                <li><a href="#" target="_top">HELP</a></li>' . EOF_LINE;
                    $tag .= '            </ul>' . EOF_LINE;
                    $tag .= '        </div>' . EOF_LINE;
                }

                $tag .= '    </div>' . EOF_LINE;

                if($enableNav == true)
                {
                    $tag .= '    <div class="nav display-table">' . EOF_LINE;

                    if($currentDir === "admin")
                        $tag .= $this->getAdminNavigators($selNav);
                    else
                        $tag .= $this->getGeneralNavigators($currentDir, $selNav);

                    $tag .= '    </div>' . EOF_LINE;
                }
                $tag .= '</div>' . EOF_LINE;
            }

            return($tag);
        }

        public function header_new($currentDir, $selNav, $enableNav = true)
        {
            global $cipherObj;
            $tag = "";

            if(($currentDir <> "") && ($selNav <> ""))
            {
                $this->imagesPath = ($currentDir === "base") ? "images" : "../images";

                $tag .= '<div class="header display-table-row">' . EOF_LINE;
                /*$tag .= '    <div class="banner display-table">' . EOF_LINE;
                $tag .= '        <div class="logo display-table-cell">' . EOF_LINE;
                $tag .= '            <a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->homeURL) .'">' . EOF_LINE;
                $tag .= '                <img src="' . $this->imagesPath . '/ptc-master-color_small.png" alt="ptc.com"/>' . EOF_LINE;
                $tag .= '            </a>' . EOF_LINE;
                $tag .= '        </div>' . EOF_LINE;

                if($enableNav == true)
                {
                    $tag .= '        <div class="admin-nav display-table-cell">' . EOF_LINE;
                    $tag .= '            <ul>' . EOF_LINE;

                    if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != ""))
                    {
                        $fname = $cipherObj->decrypt(getItemFromTable("first_name", "user", "user_name = '".$_SESSION['project-managment-username']."'"));
                        $lname = $cipherObj->decrypt(getItemFromTable("last_name", "user", "user_name = '".$_SESSION['project-managment-username']."'"));

                        if($this->isAdmin())
                        {
                            $tag .= '                <li>' . EOF_LINE;
                            $tag .= '                    <a href="" target="_top">ADMIN &#9660;</a>' . EOF_LINE;
                            $tag .= '                    <ul style="text-align: left;">' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->overviewURL) .'" target="_top">Overview</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->projectsURL) .'" target="_top">Projects</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->membersURL) .'" target="_top">Members</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->teamsURL) .'" target="_top">Teams</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->configurationURL) .'" target="_top">Configuration</a></li>' . EOF_LINE;
                            $tag .= '                    </ul>' . EOF_LINE;
                            $tag .= '                </li>' . EOF_LINE;
                        }

                        $tag .= '                <li>' . EOF_LINE;
                        $tag .= '                    <a href="" target="_top">'.$fname.' '.$lname.' &#9660;</a>' . EOF_LINE;
                        $tag .= '                    <ul style="text-align: left;">' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->profileURL) .'" target="_top">Profile</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->changePasswordURL) .'" target="_top">Change Password</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->logoutURL) .'" target="_top">Logout</a></li>' . EOF_LINE;
                        $tag .= '                    </ul>' . EOF_LINE;
                        $tag .= '                </li>' . EOF_LINE;
                    }
                    else
                    {
                        $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->loginURL) .'" target="_top">Login</a></li>' . EOF_LINE . EOF_LINE;
                        $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->signinURL) .'" target="_top">Sign Up</a></li>' . EOF_LINE;
                    }

                    $tag .= '                <li><a href="#" target="_top">HELP</a></li>' . EOF_LINE;
                    $tag .= '            </ul>' . EOF_LINE;
                    $tag .= '        </div>' . EOF_LINE;
                }

                $tag .= '    </div>' . EOF_LINE;*/

                /*if($enableNav == true)
                {
                    $tag .= '    <div class="nav display-table">' . EOF_LINE;

                    if($currentDir === "admin")
                        $tag .= $this->getAdminNavigators($selNav);
                    else
                        $tag .= $this->getGeneralNavigators($currentDir, $selNav);

                    $tag .= '    </div>' . EOF_LINE;
                }*/

                /// header nav for initial page, means before login.
                $tag .= '    <div class="nav display-table">' . EOF_LINE;

                if($enableNav == true)
                {
                    $tag .= '<div class="nav-container display-table-row">' . EOF_LINE;
                    $tag .= '        <div class="logo-nav display-table-cell">' . EOF_LINE;
                    $tag .= '            <a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->homeURL) .'">' . EOF_LINE;
                    $tag .= '                <img src="' . $this->imagesPath . '/pm.png" alt="ptc.com" style="width: 40px; height: 40px;"/>' . EOF_LINE;
                    $tag .= '            </a>' . EOF_LINE;
                    $tag .= '        </div>' . EOF_LINE;

                    if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != ""))
                    {
                        $fname = $cipherObj->decrypt(getItemFromTable("first_name", "user", "user_name = '".$_SESSION['project-managment-username']."'"));
                        $lname = $cipherObj->decrypt(getItemFromTable("last_name", "user", "user_name = '".$_SESSION['project-managment-username']."'"));

                        if($currentDir === 'scrum')
                        {
                            $tag .= '        <div class="display-table-cell" style="width: 40%"></div>' . EOF_LINE;
                            $tag .= '        <div class="nav-container display-table-cell" style="width: 35%">' . EOF_LINE;
                            $tag .= '           <ul class ="navbox">' . EOF_LINE;

                            $tag .= '               <li class="selected">' . EOF_LINE;
                            $tag .= '                   <a class="svg-icon" title="Product Planning" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">' . EOF_LINE;
                            //$tag .= '                   <span>Product Planning</span>' . EOF_LINE;
                            $tag .= '<svg x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M73.75,35h-12.5C60.56,35,60,35.56,60,36.25s0.56,1.25,1.25,1.25h12.5c0.69,0,1.25-0.56,1.25-1.25S74.44,35,73.75,35z"/><path d="M73.75,40h-12.5C60.56,40,60,40.56,60,41.25s0.56,1.25,1.25,1.25h12.5c0.69,0,1.25-0.56,1.25-1.25S74.44,40,73.75,40z"/><path d="M20,8.75v15c0,0.69,0.56,1.25,1.25,1.25H30v57.5c0,1.381,1.119,2.5,2.5,2.5H55v6.25c0,0.69,0.56,1.25,1.25,1.25h22.5  c0.69,0,1.25-0.56,1.25-1.25v-15c0-0.69-0.56-1.25-1.25-1.25h-22.5C55.56,75,55,75.56,55,76.25V80H35V62.5h20v6.25  c0,0.69,0.56,1.25,1.25,1.25h22.5c0.69,0,1.25-0.56,1.25-1.25v-15c0-0.69-0.56-1.25-1.25-1.25h-22.5c-0.69,0-1.25,0.56-1.25,1.25  v3.75H35V40h20v6.25c0,0.69,0.56,1.25,1.25,1.25h22.5c0.69,0,1.25-0.56,1.25-1.25v-15c0-0.69-0.56-1.25-1.25-1.25h-22.5  C55.56,30,55,30.56,55,31.25V35H35V25h8.75c0.69,0,1.25-0.56,1.25-1.25v-15c0-0.69-0.56-1.25-1.25-1.25h-22.5  C20.56,7.5,20,8.06,20,8.75z M57.5,77.5h20V90h-20V77.5z M57.5,55h20v12.5h-20V55z M57.5,32.5h20V45h-20V32.5z M22.5,10h20v12.5h-20  V10z"/><path d="M38.75,12.5h-12.5c-0.69,0-1.25,0.56-1.25,1.25S25.56,15,26.25,15h12.5c0.69,0,1.25-0.56,1.25-1.25S39.44,12.5,38.75,12.5z"/><path d="M38.75,17.5h-12.5c-0.69,0-1.25,0.56-1.25,1.25S25.56,20,26.25,20h12.5c0.69,0,1.25-0.56,1.25-1.25S39.44,17.5,38.75,17.5z"/><path d="M73.75,57.5h-12.5c-0.69,0-1.25,0.56-1.25,1.25S60.56,60,61.25,60h12.5c0.69,0,1.25-0.56,1.25-1.25S74.44,57.5,73.75,57.5z"/><path d="M73.75,62.5h-12.5c-0.69,0-1.25,0.56-1.25,1.25S60.56,65,61.25,65h12.5c0.69,0,1.25-0.56,1.25-1.25S74.44,62.5,73.75,62.5z"/><path d="M73.75,80h-12.5C60.56,80,60,80.56,60,81.25s0.56,1.25,1.25,1.25h12.5c0.69,0,1.25-0.56,1.25-1.25S74.44,80,73.75,80z"/></svg>' . EOF_LINE;
                            //$tag .= addRightPointAngleQuotationSVG();
                            $tag .= '                   </a>' . EOF_LINE;
                            $tag .= '                   <ul>' . EOF_LINE;
                            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">Backlog</a></li>' . EOF_LINE;
                            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogImport) .'" target="_top">Import</a></li>' . EOF_LINE;
                            $tag .= '                   </ul>' . EOF_LINE;
                            $tag .= '               </li>' . EOF_LINE;

                            $tag .= '               <li>' . EOF_LINE;
                            $tag .= '                   <a class="svg-icon" title="Release Planning" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumReleasePlanURL) .'" target="_top">' . EOF_LINE;
                            //$tag .= '                   <span>Release Planning</span>' . EOF_LINE;
                            //$tag .= addRightPointAngleQuotationSVG();
                            $tag .= '<svg x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M93.75,65H85V50c0-1.381-1.119-2.5-2.5-2.5h-30V35h8.75c0.69,0,1.25-0.56,1.25-1.25v-15c0-0.69-0.56-1.25-1.25-1.25h-22.5  c-0.69,0-1.25,0.56-1.25,1.25v15c0,0.69,0.56,1.25,1.25,1.25h8.75v12.5h-30c-1.381,0-2.5,1.119-2.5,2.5v15H6.25  C5.56,65,5,65.56,5,66.25v15c0,0.69,0.56,1.25,1.25,1.25h22.5c0.69,0,1.25-0.56,1.25-1.25v-15c0-0.69-0.56-1.25-1.25-1.25H20V52.5  h27.5V65h-8.75c-0.69,0-1.25,0.56-1.25,1.25v15c0,0.69,0.56,1.25,1.25,1.25h22.5c0.69,0,1.25-0.56,1.25-1.25v-15  c0-0.69-0.56-1.25-1.25-1.25H52.5V52.5H80V65h-8.75C70.56,65,70,65.56,70,66.25v15c0,0.69,0.56,1.25,1.25,1.25h22.5  c0.69,0,1.25-0.56,1.25-1.25v-15C95,65.56,94.44,65,93.75,65z M27.5,67.5V80h-20V67.5H27.5z M60,67.5V80H40V67.5H60z M40,32.5V20h20  v12.5H40z M92.5,80h-20V67.5h20V80z"/><path d="M56.25,22.5h-12.5c-0.69,0-1.25,0.56-1.25,1.25S43.06,25,43.75,25h12.5c0.69,0,1.25-0.56,1.25-1.25S56.94,22.5,56.25,22.5z"/><path d="M56.25,27.5h-12.5c-0.69,0-1.25,0.56-1.25,1.25S43.06,30,43.75,30h12.5c0.69,0,1.25-0.56,1.25-1.25S56.94,27.5,56.25,27.5z"/><path d="M43.75,72.5h12.5c0.69,0,1.25-0.56,1.25-1.25S56.94,70,56.25,70h-12.5c-0.69,0-1.25,0.56-1.25,1.25S43.06,72.5,43.75,72.5z"/><path d="M43.75,77.5h12.5c0.69,0,1.25-0.56,1.25-1.25S56.94,75,56.25,75h-12.5c-0.69,0-1.25,0.56-1.25,1.25S43.06,77.5,43.75,77.5z"/><path d="M11.25,72.5h12.5c0.69,0,1.25-0.56,1.25-1.25S24.44,70,23.75,70h-12.5C10.56,70,10,70.56,10,71.25S10.56,72.5,11.25,72.5z"/><path d="M11.25,77.5h12.5c0.69,0,1.25-0.56,1.25-1.25S24.44,75,23.75,75h-12.5C10.56,75,10,75.56,10,76.25S10.56,77.5,11.25,77.5z"/><path d="M76.25,72.5h12.5c0.69,0,1.25-0.56,1.25-1.25S89.44,70,88.75,70h-12.5C75.56,70,75,70.56,75,71.25S75.56,72.5,76.25,72.5z"/></svg>' . EOF_LINE;
                            $tag .= '                   </a>' . EOF_LINE;
                            $tag .= '               </li>' . EOF_LINE;

                            $tag .= '               <li>' . EOF_LINE;
                            $tag .= '                   <a class="svg-icon" title="Sprint Planning" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintPlanURL) .'" target="_top">' . EOF_LINE;
                            //$tag .= '                   <span>Sprint Planning</span>' . EOF_LINE;
                            //$tag .= addRightPointAngleQuotationSVG();
                            $tag .= '
<svg x="0px" y="0px" viewBox="0 0 96 99.8" enable-background="new 0 0 96 99.8" xml:space="preserve"><g><path d="M83.6,73L81,75.9l2.5,2.3H67.4c-1,0.8-2,1.6-3.1,2.2c-1.4,0.9-2.8,1.6-4.2,2.2h24l-2.9,2.9l2.4,2.7l7.7-7.8   L83.6,73z"/><path d="M18.1,47.6l-0.9-2.3c-2.6,11.3,1.4,23.1,10.2,30.4h8c-11-5.4-16.7-17.7-13.9-29.4L18.1,47.6z"/><path d="M29,41.6l-1.6-3.5L24,39.6c6.4-11.7,20.9-16.8,33.4-11.4c11.9,5.2,17.9,18.1,14.9,30.3l3.1-1.2l1,2.7   c1-3.6,1.2-7.3,0.8-11c6.8-2.5,11.5-9,11.5-16.4c0-9.6-7.8-17.4-17.4-17.4c-2.1,0-4.1,0.4-6.1,1.1l1.2-3.5l-3.4-1.4l-3.5,10.4   l10.2,3.4l1-3.7l-3.2-1l-0.1-0.2c1.3-0.4,2.6-0.6,3.9-0.6c7.1,0,12.9,5.8,12.9,12.9c0,5.2-3.1,9.8-7.8,11.8   c-2.3-8.7-8.4-16.4-17.3-20.3C44.4,17.8,27.3,23.9,19.9,38l-1.4-3.7L15,35.4l4,10.2L29,41.6z"/><path d="M62.9,78.2c4.5-2.7,8.2-6.6,10.9-11.5l1.4,3.6l3.5-1.1l-4-10.2l-10,3.9l1.6,3.5l3.6-1.5c0,0,0,0.1-0.1,0.1   c0,0,0,0,0,0c-0.1,0.2-0.3,0.4-0.4,0.7c0,0,0,0.1-0.1,0.1c-0.1,0.2-0.3,0.4-0.4,0.7c0,0-0.1,0.1-0.1,0.1c-0.1,0.2-0.3,0.4-0.4,0.6   c0,0,0,0,0,0.1c-0.1,0.2-0.3,0.4-0.4,0.6c0,0,0,0.1-0.1,0.1c-0.2,0.2-0.3,0.4-0.5,0.6c0,0-0.1,0.1-0.1,0.1   c-0.2,0.2-0.3,0.4-0.5,0.6c0,0,0,0-0.1,0.1c-0.2,0.2-0.3,0.4-0.5,0.6c0,0,0,0-0.1,0.1c-0.2,0.2-0.4,0.4-0.5,0.6   c0,0-0.1,0.1-0.1,0.1c-0.2,0.2-0.4,0.4-0.6,0.5c0,0,0,0-0.1,0.1c-0.2,0.2-0.4,0.3-0.6,0.5c0,0,0,0,0,0c-0.2,0.2-0.4,0.3-0.6,0.5   c0,0-0.1,0.1-0.1,0.1c-0.2,0.2-0.4,0.3-0.6,0.5c0,0-0.1,0-0.1,0.1c-0.2,0.2-0.4,0.3-0.6,0.5c0,0,0,0,0,0c-0.2,0.2-0.4,0.3-0.7,0.4   c0,0-0.1,0-0.1,0.1c-0.2,0.1-0.4,0.3-0.6,0.4c0,0-0.1,0-0.1,0.1c-0.5,0.3-0.9,0.5-1.4,0.8c0,0-0.1,0-0.1,0   c-0.2,0.1-0.5,0.2-0.7,0.4c0,0-0.1,0-0.1,0c-0.5,0.2-1,0.5-1.5,0.7c0,0,0,0-0.1,0c-0.2,0.1-0.5,0.2-0.7,0.3c0,0-0.1,0-0.1,0   c-0.5,0.2-1,0.4-1.5,0.5c0,0,0,0,0,0c-0.3,0.1-0.5,0.1-0.8,0.2c0,0-0.1,0-0.1,0c-0.3,0.1-0.5,0.1-0.8,0.2c0,0,0,0,0,0   c-0.3,0.1-0.5,0.1-0.8,0.2c0,0,0,0,0,0c-0.3,0.1-0.5,0.1-0.8,0.1c0,0-0.1,0-0.1,0c-0.3,0-0.5,0.1-0.8,0.1c0,0,0,0,0,0   c-0.3,0-0.6,0.1-0.8,0.1c0,0,0,0,0,0c0,0-3.3,0-5.1,0H31H4.8v2.2v2.2h41.2h3.9c3-0.2,6.1-1,8.9-2.2c1.5-0.6,2.9-1.4,4.2-2.2H62.9z"/></g></svg>' . EOF_LINE;
                            $tag .= '                   </a>' . EOF_LINE;
                            $tag .= '               </li>' . EOF_LINE;

                            $tag .= '               <li>' . EOF_LINE;
                            $tag .= '                   <a class="svg-icon" title="Sprint Tracking" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">' . EOF_LINE;
                            //$tag .= '                   <span>Sprint Tracking</span>' . EOF_LINE;
                            //$tag .= addRightPointAngleQuotationSVG();
                            $tag .= '<svg x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><g><g><path d="M11.502,58.913V10c0-0.829-0.672-1.5-1.5-1.5s-1.5,0.671-1.5,1.5v50.527C9.456,59.917,10.457,59.376,11.502,58.913z"/><path d="M25.197,37.566h18.537l13.312,9.509l2.264,10.579l8.437,5.701h12.092l8.142,5.816l0.655,1.447    c0.25,0.555,0.796,0.882,1.367,0.882c0.207,0,0.417-0.043,0.617-0.134c0.755-0.341,1.09-1.23,0.748-1.984l-4.085-9.026h-9.923    L60.358,48.211l-2.921-13.644H41.256L21.896,20.739L21.4,19.458c-0.299-0.773-1.168-1.155-1.94-0.857    c-0.772,0.299-1.156,1.168-0.856,1.941L25.197,37.566z M85.348,63.355l1.81,3.998l-5.597-3.998H85.348z M75.639,60.355h-6.974    l-6.673-4.51l-1.323-6.183L75.639,60.355z M55.012,37.566l1.724,8.057l-11.281-8.057H55.012z M39.534,34.566H27.252l-4.698-12.129    L39.534,34.566z"/><path d="M90.002,78.5H42.98c0.012,0.307,0.023,0.614,0.023,0.924c0,0.7-0.037,1.392-0.099,2.076h47.098c0.828,0,1.5-0.672,1.5-1.5    S90.83,78.5,90.002,78.5z"/></g><path d="M38.953,86.32c-0.135-0.535-0.679-0.857-1.213-0.729l-3.868,0.965c1.175-2.183,1.8-4.641,1.8-7.134   c0-8.311-6.761-15.071-15.071-15.071S5.53,71.112,5.53,79.423c0,8.31,6.761,15.07,15.071,15.07c2.167,0,4.26-0.45,6.22-1.339   c0.503-0.229,0.726-0.821,0.498-1.324c-0.229-0.503-0.82-0.727-1.324-0.498c-1.698,0.771-3.514,1.161-5.394,1.161   c-7.208,0-13.071-5.863-13.071-13.07c0-1.925,0.429-3.748,1.179-5.395c0.191,1.154,0.721,2.221,1.565,3.065   c1.103,1.103,2.551,1.654,3.999,1.654s2.896-0.552,3.999-1.654c2.204-2.205,2.204-5.793,0-7.997   c-0.844-0.844-1.91-1.374-3.064-1.566c1.647-0.75,3.469-1.178,5.393-1.178c7.208,0,13.071,5.863,13.071,13.071   c0,2.234-0.574,4.44-1.659,6.379c-0.003,0.005-0.002,0.01-0.005,0.014l-1.075-4.314c-0.135-0.535-0.681-0.858-1.213-0.729   c-0.535,0.134-0.861,0.677-0.729,1.213l1.601,6.419c0.114,0.454,0.521,0.758,0.97,0.758c0.08,0,0.162-0.01,0.243-0.029l6.419-1.601   C38.76,87.399,39.086,86.856,38.953,86.32z M16.859,75.679c-1.381,1.381-3.789,1.381-5.17,0c-0.69-0.69-1.07-1.608-1.07-2.585   c0-0.976,0.38-1.894,1.07-2.584s1.608-1.071,2.585-1.071s1.895,0.381,2.585,1.071C18.283,71.935,18.283,74.253,16.859,75.679z"/></g></svg>' . EOF_LINE;
                            $tag .= '                   </a>' . EOF_LINE;
                            $tag .= '                   <ul>' . EOF_LINE;
                            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">Detailed Tracking</a></li>' . EOF_LINE;
                            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackStoryboardURL) .'" target="_top">Storyboard</a></li>' . EOF_LINE;
                            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackTaskboardURL) .'" target="_top">Taskboard</a></li>' . EOF_LINE;
                            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackTestboardURL) .'" target="_top">Testboard</a></li>' . EOF_LINE;
                            $tag .= '                   </ul>' . EOF_LINE;
                            $tag .= '               </li>' . EOF_LINE;

                            $tag .= '               <li>' . EOF_LINE;
                            $tag .= '                   <a class="svg-icon" title="Sprint Review" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintReviewURL) .'" target="_top">' . EOF_LINE;
                            $tag .= '
<svg x="0px" y="0px" viewBox="0 0 100 100"><g transform="translate(0,-952.36218)"><path style="color:#000000;enable-background:accumulate;" d="m 53,961.36217 0,38.00003 38,0 C 91,978.3754 73.98682,961.36217 53,961.36217 z m -5,4 c -21.539106,0 -39,17.46093 -39,39.00003 0,21.5391 17.460894,39 39,39 21.53911,0 39,-17.4609 39,-39 l -39,0 0,-39.00003 z" troke="none" marker="none" visibility="visible" display="inline" overflow="visible"/></g></svg>' . EOF_LINE;
                            $tag .= '                   </a></li>' . EOF_LINE;
                            $tag .= '           </ul>' . EOF_LINE;
                            $tag .= '       </div>' . EOF_LINE;
                        }
                        else
                        {
                            $tag .= '        <div class="nav-container display-table-cell" style="width: 77%">' . EOF_LINE;
                            $tag .= '            <ul class ="navbox">' . EOF_LINE;
                            $tag .= '                <li><a ' . ($selNav == "HOME" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->baseDir, $this->homeURL) .'" target="_top">HOME</a></li>' . EOF_LINE;
                            $tag .= '                <li>' . EOF_LINE;
                            $tag .= '                    <a ' . ((($selNav == "SPR Tracking-Dashboard") || ($selNav == "SPR Tracking-Submit Status") || ($selNav == "SPR Tracking-Report")) ? 'class="selected"' : '') . ' href="">SPR Tracking</a>' . EOF_LINE;
                            $tag .= '                    <ul>' . EOF_LINE;
                            $tag .= '                        <li><a ' . ($selNav == "SPR Tracking-Dashboard" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingDashboardURL) .'" target="_top">Dashboard</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a ' . ($selNav == "SPR Tracking-Submit Status" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingSubmitStatusURL) .'" target="_top">Submit Status</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a ' . ($selNav == "SPR Tracking-Report" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingReportURL) .'" target="_top">Report</a></li>' . EOF_LINE;
                            $tag .= '                    </ul>' . EOF_LINE;
                            $tag .= '                </li>' . EOF_LINE;
                            $tag .= '                <li><a ' . ($selNav == "Work Tracker" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->workTrackerDir, $this->workTrackerDashboardURL) .'" target="_top">Work Tracker</a></li>' . EOF_LINE;
                            $tag .= '                <li>' . EOF_LINE;
                            $tag .= '                    <a ' . ((($selNav === "Scrum-Product-Planning-Backlog") || ($selNav === "Scrum-Release-Planning") || ($selNav === "Scrum-Sprint-Planning") || ($selNav === "Scrum-Sprint-Tracking-Taskboard") || ($selNav === "Scrum-Sprint-Review")) ? 'class="selected"' : '') . ' href="">Scrum</a>' . EOF_LINE;
                            $tag .= '                    <ul>' . EOF_LINE;
                            $tag .= '                        <li><a ' . ($selNav == "Scrum-Product-Planning-Backlog" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">Product Planning</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a ' . ($selNav == "Scrum-Release-Planning" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumReleasePlanURL) .'" target="_top">Release Planning</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a ' . ($selNav == "Scrum-Sprint-Planning" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintPlanURL) .'" target="_top">Sprint Planning</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a ' . ($selNav == "Scrum-Sprint-Tracking-Taskboard" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">Sprint Tracking</a></li>' . EOF_LINE;
                            $tag .= '                        <li><a ' . ($selNav == "Scrum-Sprint-Review" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintReviewURL) .'" target="_top">Sprint Review</a></li>' . EOF_LINE;
                            $tag .= '                    </ul>' . EOF_LINE;
                            $tag .= '                </li>' . EOF_LINE;
                            $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->aboutURL) .'" target="_top">About</a></li>' . EOF_LINE;
                            $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->contactURL) .'" target="_top">Contact us</a></li>' . EOF_LINE;
                            $tag .= '            </ul>' . EOF_LINE;
                            $tag .= '        </div>' . EOF_LINE;
                        }


                        $tag .= '        <div class="nav-container display-table-cell">' . EOF_LINE;
                        $tag .= '            <ul class ="navbox">' . EOF_LINE;
                        $tag .= '                <li>' . EOF_LINE;
                        $tag .= '                    <a class="svg-icon" title="ADMIN" href="" target="_top">' . EOF_LINE;
                        //$tag .= 'ADMIN' . EOF_LINE;
                        //$tag .= '<span>' . EOF_LINE;
                        $tag .= '<svg x="0px" y="0px" viewBox="-549 551 100 100" enable-background="new -549 551 100 100" xml:space="preserve"><g><path d="M-463.057,600.751l-0.186-1.845c-0.107-1.056,0.491-2.041,1.467-2.46c1.448-0.622,2.89-1.296,4.323-2.023   c0.994-0.504,1.506-1.643,1.253-2.728c-0.45-1.936-1.116-3.834-1.741-5.734c-0.354-1.076-1.412-1.781-2.539-1.662   c-1.579,0.167-3.141,0.383-4.68,0.644c-1.025,0.174-2.042-0.305-2.538-1.218l-1.025-1.888c-0.304-0.614-0.726-1.148-1.169-1.667   c-0.704-0.826-0.749-2.009-0.145-2.91c0.873-1.301,1.711-2.642,2.513-4.022c0.555-0.955,0.374-2.175-0.409-2.953   c-1.421-1.411-3.044-2.624-4.617-3.881c-0.871-0.696-2.115-0.721-2.987-0.026c-1.23,0.98-2.437,2.018-3.594,3.073   c-0.767,0.699-1.884,0.849-2.796,0.353l-1.888-1.025c-0.563-0.378-1.19-0.601-1.832-0.79c-1.059-0.312-1.766-1.282-1.745-2.386   c0.03-1.569,0.008-3.155-0.067-4.753c-0.05-1.081-0.832-2.011-1.887-2.251c-1.989-0.452-4.052-0.528-6.092-0.704   c-1.089-0.094-2.124,0.541-2.482,1.573c-0.507,1.46-0.962,2.967-1.363,4.504c-0.258,0.987-1.085,1.719-2.1,1.821l-2.179,0.22   c-0.726,0.042-1.446,0.118-2.153,0.26c-1.019,0.204-2.04-0.216-2.586-1.099c-0.813-1.317-1.679-2.619-2.595-3.904   c-0.629-0.882-1.785-1.224-2.808-0.866c-1.936,0.679-3.754,1.634-5.556,2.628c-0.948,0.523-1.449,1.615-1.198,2.668   c0.366,1.536,0.771,3.028,1.254,4.54c0.305,0.953,0.023,1.998-0.749,2.635l-1.715,1.414c-0.593,0.502-1.218,0.971-1.785,1.498   c-0.719,0.668-1.78,0.774-2.666,0.352c-1.391-0.664-2.818-1.288-4.277-1.871c-1.005-0.401-2.16-0.066-2.826,0.787   c-1.263,1.617-2.301,3.389-3.274,5.205c-0.509,0.951-0.343,2.136,0.435,2.884c1.146,1.102,2.311,2.151,3.521,3.161   c0.758,0.632,1.076,1.647,0.783,2.59l-0.67,2.152c-0.183,0.762-0.496,1.499-0.715,2.255c-0.268,0.925-1.065,1.577-2.02,1.699   c-1.528,0.196-3.065,0.443-4.606,0.745c-1.021,0.2-1.828,1.048-1.934,2.083c-0.217,2.116-0.217,4.229,0,6.343   c0.106,1.034,0.912,1.882,1.932,2.082c1.544,0.303,3.083,0.551,4.612,0.747c0.954,0.122,1.75,0.774,2.018,1.697   c0.22,0.756,0.533,1.493,0.716,2.258l0.668,2.151c0.293,0.943-0.027,1.957-0.785,2.589c-1.21,1.008-2.374,2.058-3.52,3.161   c-0.778,0.748-0.944,1.934-0.434,2.885c0.973,1.815,2.01,3.586,3.273,5.202c0.666,0.853,1.822,1.188,2.826,0.787   c1.46-0.583,2.888-1.208,4.279-1.872c0.886-0.423,1.947-0.318,2.666,0.35c0.567,0.527,1.193,0.996,1.786,1.498l1.718,1.418   c0.772,0.637,1.053,1.682,0.748,2.635c-0.485,1.512-0.89,3.004-1.257,4.54c-0.251,1.053,0.25,2.144,1.198,2.667   c1.803,0.995,3.622,1.949,5.56,2.628c1.022,0.358,2.179,0.016,2.807-0.867c0.916-1.285,1.781-2.588,2.595-3.904   c0.546-0.882,1.566-1.302,2.583-1.099c0.707,0.141,1.427,0.217,2.156,0.261l2.175,0.219c1.014,0.102,1.841,0.834,2.1,1.821   c0.403,1.538,0.858,3.045,1.367,4.506c0.359,1.031,1.394,1.666,2.482,1.572c2.039-0.176,4.101-0.252,6.09-0.703   c1.056-0.24,1.838-1.169,1.889-2.251c0.075-1.6,0.096-3.186,0.066-4.755c-0.021-1.103,0.683-2.072,1.741-2.385   c0.642-0.19,1.27-0.413,1.834-0.789l1.889-1.024c0.912-0.495,2.028-0.345,2.795,0.354c1.158,1.055,2.367,2.093,3.599,3.073   c0.872,0.693,2.115,0.667,2.985-0.028c1.573-1.257,3.194-2.471,4.615-3.884c0.782-0.778,0.962-1.998,0.408-2.952   c-0.801-1.379-1.64-2.72-2.513-4.02c-0.605-0.901-0.56-2.085,0.144-2.911c0.444-0.52,0.865-1.055,1.169-1.67l1.024-1.885   c0.496-0.914,1.514-1.392,2.539-1.217c1.539,0.262,3.101,0.477,4.68,0.643c1.125,0.118,2.182-0.585,2.536-1.66   c0.621-1.89,1.284-3.78,1.735-5.707c0.257-1.095-0.241-2.247-1.244-2.756c-1.433-0.727-2.875-1.402-4.324-2.024   c-0.976-0.419-1.574-1.403-1.468-2.459l0.186-1.856C-463.04,601.075-463.04,600.912-463.057,600.751z M-487.342,625.506   c-4.694,1.774-9.917,3.196-15.016,2.087c-5.007-0.798-10.017-2.703-13.757-6.29c-3.83-3.352-6.971-7.739-8.177-12.736   c-1.566-4.869-1.566-10.266,0-15.136c1.208-4.998,4.349-9.382,8.177-12.736c3.741-3.585,8.752-5.493,13.754-6.29   c5.101-1.112,10.321,0.312,15.018,2.087c4.739,2.07,8.533,5.79,11.492,9.938c2.798,4.29,3.953,9.305,4.325,14.362   c0.01,0.137,0.01,0.275,0,0.412c-0.369,5.058-1.527,10.074-4.325,14.362C-478.809,619.713-482.601,623.436-487.342,625.506z"/>
                        <g><path d="M-499,609.867c-0.706,0-1.28,0.573-1.28,1.28v4.49c0,0.706,0.573,1.28,1.28,1.28s1.28-0.573,1.28-1.28v-4.49    C-497.72,610.44-498.294,609.867-499,609.867z"/><path d="M-485.251,594.416c0-6.023-3.857-11.292-9.598-13.108c-0.388-0.123-0.813-0.053-1.142,0.187    c-0.329,0.241-0.523,0.624-0.523,1.032v12.057l-2.486,1.905l-2.486-1.905v-12.057c0-0.408-0.194-0.792-0.523-1.032    c-0.33-0.241-0.752-0.311-1.143-0.187c-5.74,1.817-9.597,7.085-9.597,13.108c0,5.511,3.267,10.262,7.959,12.453v12.601    c0,0.706,0.573,1.28,1.28,1.28c0.706,0,1.28-0.573,1.28-1.28v-11.704c1.038,0.251,2.116,0.399,3.231,0.399    s2.193-0.148,3.232-0.399v11.705c0,0.706,0.573,1.28,1.28,1.28c0.706,0,1.28-0.573,1.28-1.28v-12.602    C-488.517,604.677-485.251,599.927-485.251,594.416z M-501.196,605.395c-4.517-0.864-8.115-4.581-8.845-9.122    c-0.808-5.024,1.727-9.706,5.996-11.85v10.791c0,0.398,0.185,0.774,0.502,1.016l3.766,2.886c0.459,0.352,1.097,0.352,1.556,0    l3.766-2.886c0.316-0.242,0.502-0.618,0.502-1.016v-10.791c3.736,1.876,6.145,5.698,6.145,9.992    C-487.81,601.304-494.067,606.759-501.196,605.395z"/></g></g></svg>';
                        //$tag .= '</span>' . EOF_LINE;
                        //$tag .= '                       <img vertical-align=middle src="' . $this->imagesPath . '/admin_gray.png" alt="ptc.com" style="width: 40px; height: 40px;"/>' . EOF_LINE;
                        $tag .= '                    </a>' . EOF_LINE;
                        $tag .= '                    <ul style="text-align: left;">' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->overviewURL) .'" target="_top">Overview</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->projectsURL) .'" target="_top">Projects</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->membersURL) .'" target="_top">Members</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->teamsURL) .'" target="_top">Teams</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->configurationURL) .'" target="_top">Configuration</a></li>' . EOF_LINE;
                        $tag .= '                    </ul>' . EOF_LINE;
                        $tag .= '                </li>' . EOF_LINE;
                        $tag .= '                <li>' . EOF_LINE;
                        $tag .= '                    <a href="" target="_top">'.$fname.' '.$lname.'</a>' . EOF_LINE;
                        $tag .= '                    <ul style="text-align: left;">' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->profileURL) .'" target="_top">Profile</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->changePasswordURL) .'" target="_top">Change Password</a></li>' . EOF_LINE;
                        $tag .= '                        <li><a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->logoutURL) .'" target="_top">Logout</a></li>' . EOF_LINE;
                        $tag .= '                    </ul>' . EOF_LINE;
                        $tag .= '                </li>' . EOF_LINE;
                        $tag .= '            </ul>' . EOF_LINE;
                        $tag .= '        </div>' . EOF_LINE;
                    }
                    else
                    {
                        $tag .= '<div class="nav-container display-table-cell" style="width: 80%"></div>' . EOF_LINE;
                        $tag .= '        <div class="nav-container display-table-cell" style="text-align: center;">' . EOF_LINE;
                        $tag .= '            <ul class ="navbox">' . EOF_LINE;
                        $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->loginURL) .'" target="_top">Login</a></li>' . EOF_LINE . EOF_LINE;
                        $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->userDir, $this->signinURL) .'" target="_top">Sign Up</a></li>' . EOF_LINE;
                        $tag .= '            </ul>' . EOF_LINE;
                        $tag .= '        </div>' . EOF_LINE;
                    }
                    $tag .= '   </div>' . EOF_LINE;
                    $tag .= '</div>' . EOF_LINE;
                }
                $tag .= '</div>' . EOF_LINE;
            }

            return($tag);
        }

        public function footer($currentDir, $hrTagFlag = true)
        {
            $tag = "";

            if($currentDir <> "")
            {
                $this->imagesPath = ($currentDir === "base") ? "images" : "../images";

                $tag .= '<div class="footer display-table-row">' . EOF_LINE;
                $tag .= '    <div class="banner display-table">' . EOF_LINE;
                $tag .= '        <div class="logo display-table-cell">';
                $tag .= '            <a id="footer-logo" class="footer-logo" href="' . $this->getNavURL($currentDir, "base", $this->homeURL) . '">' . EOF_LINE;
                $tag .= '                <img src="'. $this->imagesPath .'/ptc-master-color_small.png" alt="ptc.com">' . EOF_LINE;
                $tag .= '            </a>' . EOF_LINE;
                $tag .= '        </div>' . EOF_LINE;
                $tag .= '        <div class="footer-nav display-table-cell">' . EOF_LINE;
                $tag .= '            <ul>' . EOF_LINE;
                $tag .= '                <li><a href="'. $this->getNavURL($currentDir, "base", $this->homeURL) .'">HOME</a> | </li>' . EOF_LINE;
                $tag .= '                <li><a class="up" href="">TOP</a> | </li>' . EOF_LINE;
                $tag .= '                <li><a href="'. $this->getNavURL($currentDir, "base", $this->aboutURL) .'">ABOUT</a></li>' . EOF_LINE;
                $tag .= '            </ul>' . EOF_LINE;
                $tag .= '        </div>' . EOF_LINE;
                $tag .= '    </div>' . EOF_LINE;
                $tag .= '    <div class="copyright">' . EOF_LINE;
                $tag .= '        PTC project management is optimized for SPR tracking, Scrum.' . EOF_LINE;
                $tag .= '        Its help user to maintain SPR records and scrum methodology.<br>' . EOF_LINE;
                $tag .= '        While using this site, you agree to have read and accepted our' . EOF_LINE;
                $tag .= '        <a href="'. $this->getNavURL($currentDir, "about", $this->copyrightURL) .'">terms of use</a> and <a href="'. $this->getNavURL($currentDir, "about", $this->privacyURL) .'">privacy policy</a>.<br>' . EOF_LINE;
                $tag .= '        <a href="'. $this->getNavURL($currentDir, "about", $this->copyrightURL) .'">Copyright 1999-2016</a> by Abhishek Nath. All Rights Reserved.<br><br>' . EOF_LINE;
                $tag .= '    </div>' . EOF_LINE;
                $tag .= '</div>' . EOF_LINE;
            }

            return($tag);
        }

        /// private methods
        private function getNavURL($currentDir, $pageDir, $page)
        {
            $finalURL = "";

            if(($currentDir <> "") && ($pageDir <> "") && ($page <> ""))
            {
                /// check currentDir and pageDir is same or not.
                /// if so then pass only page name.
                if($currentDir === $pageDir)
                    $finalURL = $page;
                /// else check currentDir == "base" or not, if so then no need to add leading '..'.
                else if($currentDir === "base")
                    $finalURL = "{$pageDir}/{$page}";
                /// else check pageDir = "base" or not, if so, then add leading '..' not pageDir name.
                else if($pageDir === "base")
                    $finalURL = "../{$page}";
                /// else add leading '..' to propagate proper directory.
                else
                    $finalURL = "../{$pageDir}/{$page}";
            }

            return($finalURL);
        }

        private function isAdmin()
        {
            return(true);
        }

        private function getGeneralNavigators($currentDir, $selNav)
        {
            $tag = $this->getMainNavigators($currentDir, $selNav);

            if($currentDir === 'scrum')
                $tag .= $this->getScrumNavigators($currentDir, $selNav);

            return($tag);
        }

        private function getMainNavigators($currentDir, $selNav)
        {
            $tag = '        <div class="main-nav display-table-row">' . EOF_LINE;
            $tag .= '            <ul class ="float-box-nav">' . EOF_LINE;
            $tag .= '                <li><a ' . ($selNav == "HOME" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->baseDir, $this->homeURL) .'" target="_top">HOME</a></li>' . EOF_LINE;
            $tag .= '                <li>' . EOF_LINE;
            $tag .= '                    <a ' . ((($selNav == "SPR Tracking-Dashboard") || ($selNav == "SPR Tracking-Submit Status") || ($selNav == "SPR Tracking-Report")) ? 'class="selected"' : '') . ' href="">SPR Tracking</a>' . EOF_LINE;
            $tag .= '                    <ul>' . EOF_LINE;
            $tag .= '                        <li><a ' . ($selNav == "SPR Tracking-Dashboard" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingDashboardURL) .'" target="_top">Dashboard</a></li>' . EOF_LINE;
            $tag .= '                        <li><a ' . ($selNav == "SPR Tracking-Submit Status" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingSubmitStatusURL) .'" target="_top">Submit Status</a></li>' . EOF_LINE;
            $tag .= '                        <li><a ' . ($selNav == "SPR Tracking-Report" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingReportURL) .'" target="_top">Report</a></li>' . EOF_LINE;
            $tag .= '                    </ul>' . EOF_LINE;
            $tag .= '                </li>' . EOF_LINE;
            $tag .= '                <li><a ' . ($selNav == "Work Tracker" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->workTrackerDir, $this->workTrackerDashboardURL) .'" target="_top">Work Tracker</a></li>' . EOF_LINE;
            $tag .= '                <li>' . EOF_LINE;
            $tag .= '                    <a ' . ((($selNav === "Scrum-Product-Planning-Backlog") || ($selNav === "Scrum-Release-Planning") || ($selNav === "Scrum-Sprint-Planning") || ($selNav === "Scrum-Sprint-Tracking-Taskboard") || ($selNav === "Scrum-Sprint-Review")) ? 'class="selected"' : '') . ' href="">Scrum</a>' . EOF_LINE;
            $tag .= '                    <ul>' . EOF_LINE;
            $tag .= '                        <li><a ' . ($selNav == "Scrum-Product-Planning-Backlog" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">Product Planning</a></li>' . EOF_LINE;
            $tag .= '                        <li><a ' . ($selNav == "Scrum-Release-Planning" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumReleasePlanURL) .'" target="_top">Release Planning</a></li>' . EOF_LINE;
            $tag .= '                        <li><a ' . ($selNav == "Scrum-Sprint-Planning" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintPlanURL) .'" target="_top">Sprint Planning</a></li>' . EOF_LINE;
            $tag .= '                        <li><a ' . ($selNav == "Scrum-Sprint-Tracking-Taskboard" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">Sprint Tracking</a></li>' . EOF_LINE;
            $tag .= '                        <li><a ' . ($selNav == "Scrum-Sprint-Review" ? 'class="selected"' : '') . ' href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintReviewURL) .'" target="_top">Sprint Review</a></li>' . EOF_LINE;
            $tag .= '                    </ul>' . EOF_LINE;
            $tag .= '                </li>' . EOF_LINE;
            $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->aboutURL) .'" target="_top">About</a></li>' . EOF_LINE;
            $tag .= '                <li><a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->contactURL) .'" target="_top">Contact us</a></li>' . EOF_LINE;
            $tag .= '            </ul>' . EOF_LINE;
            $tag .= '        </div>' . EOF_LINE;

            return($tag);
        }

        private function getScrumNavigators($currentDir, $selNav)
        {
            $tag = '        <div class="sub-nav-container display-table-row">' . EOF_LINE;
            $tag .= '           <ul class ="float-box-nav sub-nav">' . EOF_LINE;
            $tag .= '               <li>' . EOF_LINE;
            $tag .= '                   <a class="selected-after" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                       <span>Product Planning</span>' . EOF_LINE;
            $tag .= addRightPointAngleQuotationSVG();
            $tag .= '                   </a>' . EOF_LINE;
            $tag .= '                   <ul>' . EOF_LINE;
            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">Backlog</a></li>' . EOF_LINE;
            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogImport) .'" target="_top">Import</a></li>' . EOF_LINE;
            $tag .= '                   </ul>' . EOF_LINE;
            $tag .= '               </li>' . EOF_LINE;
            $tag .= '               <li>' . EOF_LINE;
            $tag .= '                   <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumReleasePlanURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                   <span>Release Planning</span>' . EOF_LINE;
            $tag .= addRightPointAngleQuotationSVG();
            $tag .= '                   </a>' . EOF_LINE;
            $tag .= '               </li>' . EOF_LINE;
            $tag .= '               <li>' . EOF_LINE;
            $tag .= '                   <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintPlanURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                   <span>Sprint Planning</span>' . EOF_LINE;
            $tag .= addRightPointAngleQuotationSVG();
            $tag .= '                   </a>' . EOF_LINE;
            $tag .= '               </li>' . EOF_LINE;
            $tag .= '               <li>' . EOF_LINE;
            $tag .= '                   <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                   <span>Sprint Tracking</span>' . EOF_LINE;
            $tag .= addRightPointAngleQuotationSVG();
            $tag .= '                   </a>' . EOF_LINE;
            $tag .= '                   <ul>' . EOF_LINE;
            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">Detailed Tracking</a></li>' . EOF_LINE;
            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackStoryboardURL) .'" target="_top">Storyboard</a></li>' . EOF_LINE;
            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackTaskboardURL) .'" target="_top">Taskboard</a></li>' . EOF_LINE;
            $tag .= '                       <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackTestboardURL) .'" target="_top">Testboard</a></li>' . EOF_LINE;
            $tag .= '                   </ul>' . EOF_LINE;
            $tag .= '               </li>' . EOF_LINE;
            $tag .= '               <li><a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintReviewURL) .'" target="_top">Sprint Review</a></li>' . EOF_LINE;
            $tag .= '           </ul>' . EOF_LINE;
            $tag .= '       </div>' . EOF_LINE;

            return($tag);
        }

        private function getAdminNavigators($selNav)
        {
            $tag = '       <div class="main-nav display-table-row">' . EOF_LINE;
            $tag .= '            <ul class ="float-box-nav">' . EOF_LINE;

            $tag .= '                <li><a ' . ($selNav == "HOME" ? 'class="selected"' : '') . ' href="'. $this->getNavURL("admin", $this->baseDir, $this->homeURL) .'" target="_top">HOME</a></li>' . EOF_LINE;
            $tag .= '                <li><a ' . ($selNav == "Scrum" ? 'class="selected"' : '') . ' href="'. $this->getNavURL("admin", $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">Scrum</a></li>' . EOF_LINE;
            $tag .= '                <li><a ' . ($selNav == "Overview" ? 'class="selected"' : '') . ' href="'. $this->getNavURL("admin", $this->adminDir, $this->overviewURL) .'" target="_top">Overview</a></li>' . EOF_LINE;
            $tag .= '                <li><a ' . ($selNav == "Projects" ? 'class="selected"' : '') . ' href="'. $this->getNavURL("admin", $this->adminDir, $this->projectsURL) .'" target="_top">Projects</a></li>' . EOF_LINE;
            $tag .= '                <li><a ' . ($selNav == "Members" ? 'class="selected"' : '') . ' href="'. $this->getNavURL("admin", $this->adminDir, $this->membersURL) .'" target="_top">Members</a></li>' . EOF_LINE;
            $tag .= '                <li><a ' . ($selNav == "Teams" ? 'class="selected"' : '') . ' href="'. $this->getNavURL("admin", $this->adminDir, $this->teamsURL) .'" target="_top">Teams</a></li>' . EOF_LINE;
            $tag .= '                <li><a ' . ($selNav == "Configuration" ? 'class="selected"' : '') . ' href="'. $this->getNavURL("admin", $this->adminDir, $this->configurationURL) .'" target="_top">Configuration</a></li>' . EOF_LINE;

            $tag .= '            </ul>' . EOF_LINE;
            $tag .= '       </div>' . EOF_LINE;

            return($tag);
        }
    }
?>
