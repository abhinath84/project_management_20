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
