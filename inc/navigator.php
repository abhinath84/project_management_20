<?php

    require_once ('svg.php');

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
        private $viewProfileURL = "view_profile.php";
        private $editProfileURL = "edit_profile.php";
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

        public function header_new($currentDir, $selNav, $enableNav = true)
        {
            $tag = '';

            if(($currentDir <> "") && ($selNav <> "") && ($enableNav == true))
            {
                $this->imagesPath = ($currentDir === "base") ? "images" : "../images";

                /// header nav for initial page, means before login.
                $tag .= '<div class="nav-container display-flex">' . EOF_LINE;
                $tag .= '    <div class="logo-nav">' . EOF_LINE;
                $tag .= '        <a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->homeURL) .'">' . EOF_LINE;
                $tag .= '            <img src="' . $this->imagesPath . '/pm.png" alt="ptc.com"/>' . EOF_LINE;
                $tag .= '        </a>' . EOF_LINE;
                $tag .= '    </div>' . EOF_LINE;

                $tag .= '    <div class="display-flex" style="margin-left: auto;">';
                if((isset($_SESSION['project-managment-username'])) && ($_SESSION['project-managment-username'] != ""))
                {
                    if($currentDir === 'scrum')
                    {
                        $tag .= $this->getScrumNavigator($currentDir, $selNav);
                    }
                    else if(($currentDir === 'admin') && (Utility::isAdmin($_SESSION['project-managment-username'])))
                    {
                        $tag .= $this->getAdminNavigator($currentDir, $selNav);
                    }
                    else
                    {
                        $tag .= $this->getGeneralNavigator($currentDir, $selNav);
                    }

                    $tag .= $this->getAdministratorNavigator($currentDir, $selNav);
                }
                else
                {
                    $tag .= $this->getLoginNavigator($currentDir);
                }
                $tag .='    </div>' . EOF_LINE;
                $tag .= '</div>';
            }

            return($tag);
        }

        public function footer($currentDir, $hrTagFlag = true)
        {
            $tag = "";

            if($currentDir <> "")
            {
                $this->imagesPath = ($currentDir === "base") ? "images" : "../images";

                $tag .= '<div class="footer">' . EOF_LINE;
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

        private function getGeneralNavigator($currentDir, $selNav)
        {
            $tag = '';

            $tag .= '<div class="h-navigator general-nav">' . EOF_LINE;
            $tag .= '   <ul>' . EOF_LINE;
            $tag .= '       <li class="has-dropdown-manu ' . ((($selNav == "SPR Tracking-Dashboard") || ($selNav == "SPR Tracking-Submit Status") || ($selNav == "SPR Tracking-Report")) ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '           <a class="dropbtn" title="SPR Tracking" href="" target="_top">' . EOF_LINE;
            $tag .= '               <span>' . EOF_LINE;
            $tag .=                     SVG::getSPRTrack();
            $tag .= '               </span>' . EOF_LINE;
            $tag .= '           </a>' . EOF_LINE;
            $tag .= '           <div class="dropdown-menu-content">' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingDashboardURL) .'" target="_top">Dashboard</a>' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingSubmitStatusURL) .'" target="_top">Submit Status</a>' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingReportURL) .'" target="_top">Reports</a>' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->sprTrackingDir, $this->sprTrackingImportURL) .'" target="_top">Import</a>' . EOF_LINE;
            $tag .= '           </div>' . EOF_LINE;
            $tag .= '       </li>' . EOF_LINE;
            $tag .= '       <li class="' . ($selNav == "Work Tracker" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '           <a title="Work Tracker" href="'. $this->getNavURL($currentDir, $this->workTrackerDir, $this->workTrackerDashboardURL) .'" target="_top">' . EOF_LINE;
            $tag .= '               <span>' . EOF_LINE;
            $tag .=                     SVG::getWorkTracker();
            $tag .= '               </span>' . EOF_LINE;
            $tag .= '           </a>' . EOF_LINE;
            $tag .= '       </li>' . EOF_LINE;
            $tag .= '       <li class="has-dropdown-manu ' . ((($selNav === "Scrum-Product-Planning-Backlog") || ($selNav === "Scrum-Release-Planning") || ($selNav === "Scrum-Sprint-Planning") || ($selNav === "Scrum-Sprint-Tracking-Taskboard") || ($selNav === "Scrum-Sprint-Review")) ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '           <a class="bropbtn" title="Scrum"  href="" target="_top">' . EOF_LINE;
            $tag .= '               <span>' . EOF_LINE;
            $tag .=                     SVG::getScrum();
            $tag .= '               </span>' . EOF_LINE;
            $tag .= '           </a>' . EOF_LINE;
            $tag .= '           <div class="dropdown-menu-content">' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">Product Planning</a>' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumReleasePlanURL) .'" target="_top">Release Planning</a>' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintPlanURL) .'" target="_top">Sprint Planning</a>' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">Sprint Tracking</a>' . EOF_LINE;
            $tag .= '               <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintReviewURL) .'" target="_top">Sprint Review</a>' . EOF_LINE;
            $tag .= '           </div>' . EOF_LINE;
            $tag .= '       </li>' . EOF_LINE;
            $tag .= '   </ul>' . EOF_LINE;
            $tag .= '</div>' . EOF_LINE;

            $tag .= '<div class="h-navigator general-nav">' . EOF_LINE;
            $tag .= '   <ul>' . EOF_LINE;
            $tag .= '       <li class="' . ($selNav == "About" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '           <a title="About" href="'. $this->getNavURL("admin", $this->scrumDir, $this->membersURL) .'" target="_top">' . EOF_LINE;
            $tag .= '               <span>' . EOF_LINE;
            $tag .=                     SVG::getAbout();
            $tag .= '               </span>' . EOF_LINE;
            $tag .= '           </a>' . EOF_LINE;
            $tag .= '       </li>' . EOF_LINE;
            $tag .= '       <li class="' . ($selNav == "Contact us" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '           <a title="Contact us" href="'. $this->getNavURL("admin", $this->scrumDir, $this->teamsURL) .'" target="_top">' . EOF_LINE;
            $tag .= '               <span>' . EOF_LINE;
            $tag .=                     SVG::getContactUs();
            $tag .= '               </span>' . EOF_LINE;
            $tag .= '           </a>' . EOF_LINE;
            $tag .= '       </li>' . EOF_LINE;
            $tag .= '   </ul>' . EOF_LINE;
            $tag .= '</div>' . EOF_LINE;

            return($tag);
        }

        private function getScrumNavigator($currentDir, $selNav)
        {

            $tag = '';

            $tag .= '<div class="h-navigator scrum-nav">' . EOF_LINE;
            $tag .= '        <ul>' . EOF_LINE;
            $tag .= '          <li class="has-dropdown-manu ' . ($selNav == "Scrum-Product-Planning-Backlog" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '              <a class="dropbtn" title="Product Planning" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                <span>' . EOF_LINE;
            $tag .=             SVG::getProductPlan();
            $tag .= '       </span>' . EOF_LINE;
            $tag .= '              </a>' . EOF_LINE;
            $tag .= '              <div class="dropdown-menu-content">' . EOF_LINE;
            $tag .= '                <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">Backlog</a>' . EOF_LINE;
            $tag .= '                <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumBacklogImport) .'" target="_top">Import</a>' . EOF_LINE;
            $tag .= '              </div>' . EOF_LINE;
            $tag .= '          </li>' . EOF_LINE;
            $tag .= '          <li class="' . ($selNav == "Scrum-Release-Planning" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '            <a title="Release Planning" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumReleasePlanURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                <span>' . EOF_LINE;
            $tag .=             SVG::getReleasePlan();
            $tag .= '       </span>' . EOF_LINE;
            $tag .= '            </a>' . EOF_LINE;
            $tag .= '          </li>' . EOF_LINE;
            $tag .= '          <li class="' . ($selNav == "Scrum-Sprint-Planning" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '            <a title="Sprint Planning" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintPlanURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                <span>' . EOF_LINE;
            $tag .=             SVG::getSprintPlan();
            $tag .= '       </span>' . EOF_LINE;
            $tag .= '            </a>' . EOF_LINE;
            $tag .= '          </li>' . EOF_LINE;
            $tag .= '          <li class="has-dropdown-manu ' . ((($selNav === "Scrum-Sprint-Tracking-Detail") || ($selNav === "Scrum-Sprint-Tracking-Taskboard")) ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '              <a class="dropbtn" title="Sprint Tracking" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                  <span>' . EOF_LINE;
            $tag .=             SVG::getSprintTrack();
            $tag .= '         </span>' . EOF_LINE;
            $tag .= '              </a>' . EOF_LINE;
            $tag .= '              <div class="dropdown-menu-content">' . EOF_LINE;
            $tag .= '                <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackDetailedURL) .'" target="_top">Detailed Tracking</a>' . EOF_LINE;
            $tag .= '                <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackStoryboardURL) .'" target="_top">Storyboard</a>' . EOF_LINE;
            $tag .= '                <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackTaskboardURL) .'" target="_top">Taskboard</a>' . EOF_LINE;
            $tag .= '                <a href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintTrackTestboardURL) .'" target="_top">Testboard</a>' . EOF_LINE;
            $tag .= '              </div>' . EOF_LINE;
            $tag .= '          </li>' . EOF_LINE;
            $tag .= '          <li class="' . ($selNav == "Scrum-Sprint-Review" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '            <a title="Sprint Review" href="'. $this->getNavURL($currentDir, $this->scrumDir, $this->scrumSprintReviewURL) .'" target="_top">' . EOF_LINE;
            $tag .= '                <span>' . EOF_LINE;
            $tag .=             SVG::getSprintReview();
            $tag .= '       </span>' . EOF_LINE;
            $tag .= '            </a>' . EOF_LINE;
            $tag .= '          </li>' . EOF_LINE;
            $tag .= '        </ul>' . EOF_LINE;
            $tag .= '    </div>' . EOF_LINE;

            return($tag);
        }

        private function getAdminNavigator($currentDir, $selNav)
        {
            $tag = '';

            $tag .= '<div class="h-navigator" style="margin-right: 30px;">' . EOF_LINE;
            $tag .= '   <ul>' . EOF_LINE;
            $tag .= '       <li class="' . ($selNav == "Scrum" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '           <a title="Scrum" href="'. $this->getNavURL("admin", $this->scrumDir, $this->scrumBacklogURL) .'" target="_top">' . EOF_LINE;
            $tag .= '               <span>' . EOF_LINE;
            $tag .=                     SVG::getScrum();
            $tag .= '               </span>' . EOF_LINE;
            $tag .= '           </a>' . EOF_LINE;
            $tag .= '       </li>' . EOF_LINE;
            $tag .= '   </ul>' . EOF_LINE;
            $tag .= '</div>' . EOF_LINE;

            $tag .= '<div class="h-navigator admin-nav">' . EOF_LINE;
            $tag .= '   <ul>' . EOF_LINE;
            $tag .= '       <li class="' . ($selNav == "Overview" ? 'selected' : '') . '">' . EOF_LINE;
            $tag .= '           <a title="Overview" href="'. $this->getNavURL("admin", $this->adminDir, $this->overviewURL) .'" target="_top">' . EOF_LINE;
            $tag .= '               <span>' . EOF_LINE;
            $tag .=                     SVG::getOverview();
            $tag .= '               </span>' . EOF_LINE;
            $tag .= '           </a>' . EOF_LINE;
            $tag .= '       </li>' . EOF_LINE;

            if(
                (Utility::isSystemAdmin($_SESSION['project-managment-username'])) ||
                (Utility::isProjectAdmin($_SESSION['project-managment-username']))
              )
            {
                $tag .= '       <li class="' . ($selNav == "Projects" ? 'selected' : '') . '">' . EOF_LINE;
                $tag .= '           <a title="Projects" href="'. $this->getNavURL("admin", $this->adminDir, $this->projectsURL) .'" target="_top">' . EOF_LINE;
                $tag .= '               <span>' . EOF_LINE;
                $tag .=                     SVG::getProject();
                $tag .= '               </span>' . EOF_LINE;
                $tag .= '           </a>' . EOF_LINE;
                $tag .= '       </li>' . EOF_LINE;
            }

            if(
                (Utility::isSystemAdmin($_SESSION['project-managment-username'])) ||
                (Utility::isMemberAdmin($_SESSION['project-managment-username']))
              )
            {
                $tag .= '       <li class="' . ($selNav == "Members" ? 'selected' : '') . '">' . EOF_LINE;
                $tag .= '           <a title="Members" href="'. $this->getNavURL("admin", $this->adminDir, $this->membersURL) .'" target="_top">' . EOF_LINE;
                $tag .= '               <span>' . EOF_LINE;
                $tag .=                     SVG::getMember();
                $tag .= '               </span>' . EOF_LINE;
                $tag .= '           </a>' . EOF_LINE;
                $tag .= '       </li>' . EOF_LINE;
            }

            if(
                (Utility::isSystemAdmin($_SESSION['project-managment-username'])) ||
                (Utility::isProjectAdmin($_SESSION['project-managment-username']))
              )
            {
                $tag .= '       <li class="' . ($selNav == "Teams" ? 'selected' : '') . '"  >' . EOF_LINE;
                $tag .= '           <a title="Teams" href="'. $this->getNavURL("admin", $this->adminDir, $this->teamsURL) .'" target="_top">' . EOF_LINE;
                $tag .= '               <span>' . EOF_LINE;
                $tag .=                     SVG::getTeam();
                $tag .= '               </span>' . EOF_LINE;
                $tag .= '           </a>' . EOF_LINE;
                $tag .= '       </li>' . EOF_LINE;
            }

            if( Utility::isSystemAdmin($_SESSION['project-managment-username']) )
            {
                $tag .= '       <li class="' . ($selNav == "Configuration" ? 'selected' : '') . '"  >' . EOF_LINE;
                $tag .= '           <a title="Configuration" href="'. $this->getNavURL("admin", $this->adminDir, $this->configurationURL) .'" target="_top">' . EOF_LINE;
                $tag .= '               <span>' . EOF_LINE;
                $tag .=                     SVG::getConfiguration();
                $tag .= '               </span>' . EOF_LINE;
                $tag .= '           </a>' . EOF_LINE;
                $tag .= '       </li>' . EOF_LINE;
            }

            $tag .= '   </ul>' . EOF_LINE;
            $tag .= '</div>' . EOF_LINE;

            return($tag);
        }

        private function getAdministratorNavigator($currentDir, $selNav)
        {
            global $cipherObj;
            $tag ='';

            $tag .= '<div id="administrator-nav" class="h-navigator administrator-nav">' . EOF_LINE;
            $tag .= '   <ul>' . EOF_LINE;

            if(
                ($currentDir === 'scrum') &&
                (Utility::isAdmin($_SESSION['project-managment-username']))
              )
            {
                $tag .= '       <li class="has-dropdown-manu">' . EOF_LINE;
                $tag .= '           <a class="svg-nav" title="Admin" href="javascript:void(0)" class="dropbtn">' . EOF_LINE;
                $tag .= '               <span>' . EOF_LINE;
                $tag .=                     SVG::getAdministrator();
                $tag .= '               </span>' . EOF_LINE;
                $tag .= '           </a>' . EOF_LINE;

                $tag .= '               <div class="dropdown-menu-content">' . EOF_LINE;
                $tag .= '                   <a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->overviewURL) .'" target="_top">Overview</a>' . EOF_LINE;

                if(
                    (Utility::isSystemAdmin($_SESSION['project-managment-username'])) ||
                    (Utility::isProjectAdmin($_SESSION['project-managment-username']))
                  )
                {
                    $tag .= '                   <a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->projectsURL) .'" target="_top">Projects</a>' . EOF_LINE;
                }

                if(
                    (Utility::isSystemAdmin($_SESSION['project-managment-username'])) ||
                    (Utility::isMemberAdmin($_SESSION['project-managment-username']))
                  )
                {
                    $tag .= '                   <a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->membersURL) .'" target="_top">Members</a>' . EOF_LINE;
                }

                if(
                    (Utility::isSystemAdmin($_SESSION['project-managment-username'])) ||
                    (Utility::isProjectAdmin($_SESSION['project-managment-username']))
                  )
                {
                    $tag .= '                   <a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->teamsURL) .'" target="_top">Teams</a>' . EOF_LINE;
                }

                if( (Utility::isSystemAdmin($_SESSION['project-managment-username'])) )
                {
                    $tag .= '                   <a href="'. $this->getNavURL($currentDir, $this->adminDir, $this->configurationURL) .'" target="_top">Configuration</a>' . EOF_LINE;
                }

                $tag .= '               </div>' . EOF_LINE;
                $tag .= '       </li>' . EOF_LINE;
            }

            // User nagivator.
            $fname = $cipherObj->decrypt(getItemFromTable("first_name", "user", "user_name = '".$_SESSION['project-managment-username']."'"));
            $lname = $cipherObj->decrypt(getItemFromTable("last_name", "user", "user_name = '".$_SESSION['project-managment-username']."'"));

            $tag .='            <li class="has-dropdown-manu">' . EOF_LINE;
            $tag .='                <a class="text-nav dropbtn"  href="javascript:void(0)" target="_top">'.$fname.' '.$lname.'</a>' . EOF_LINE;
            $tag .='                    <div class="dropdown-menu-content">' . EOF_LINE;
            $tag .='                        <a href="'. $this->getNavURL($currentDir, $this->userDir, $this->viewProfileURL) .'" target="_top">View Profile</a>' . EOF_LINE;
            $tag .='                        <a href="'. $this->getNavURL($currentDir, $this->userDir, $this->editProfileURL) .'" target="_top">Edit Profile</a>' . EOF_LINE;
            $tag .='                        <a href="'. $this->getNavURL($currentDir, $this->userDir, $this->changePasswordURL) .'" target="_top">Change Password</a>' . EOF_LINE;
            $tag .='                        <a href="'. $this->getNavURL($currentDir, $this->baseDir, $this->logoutURL) .'" target="_top">Logout</a>' . EOF_LINE;
            $tag .='                    </div>' . EOF_LINE;
            $tag .='            </li>' . EOF_LINE;
            $tag .='        </ul>' . EOF_LINE;
            $tag .='    </div>' . EOF_LINE;

            return($tag);
        }

        private function getLoginNavigator($currentDir)
        {
            $tag = '<div class="h-navigator">' . EOF_LINE;
            $tag .= '    <ul>' . EOF_LINE;
            $tag .= '        <li>' . EOF_LINE;
            $tag .= '            <a class="text-nav" href="'. $this->getNavURL($currentDir, $this->userDir, $this->loginURL) .'" target="_top">Login</a>' . EOF_LINE;
            $tag .= '        </li>' . EOF_LINE;
            $tag .= '        <li>' . EOF_LINE;
            $tag .= '            <a class="text-nav" href="'. $this->getNavURL($currentDir, $this->userDir, $this->signinURL) .'" target="_top">Sign up</a>' . EOF_LINE;
            $tag .= '        </li>' . EOF_LINE;
            $tag .= '    </ul>' . EOF_LINE;
            $tag .= '</div>';

            return($tag);
        }
    }
?>
