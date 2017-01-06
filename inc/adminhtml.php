<?php
    /* include header file */
    require_once ('htmltemplate.php');

    class OverviewHTML extends HTMLTemplate
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Overview", "admin", true);
        }

        protected function addDashboard()
        {
            $tag = "";
            $tag .= '<div class="article-container overview-article">' . $this->EOF_LINE;

            $tag .= '   <div class="overview-container">
                            <div class="title-container">
                                <h1 class="title">To start setting up a new system follow these four steps:</h1>
                            </div>
                            <div class="display-table">
                                <div class="display-table-row">
                                    <div class="display-table-cell">
                                        <div class="step-link">
                                            <div class="display-table-row">
                                                <img alt="one" src="../images/one.png" title="one" class="display-table-cell">
                                                <a href="projects.php" class="display-table-cell">
                                                    <span>Create Projects</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="step-desc">
                                            <div class="display-table-row">
                                                <img alt="project" src="../images/project_.png" title="project" class="display-table-cell">
                                                <span class="display-table-cell">Define new project in the project tree by clicking on an Add child project link for an existing project on the project page. All proojects are created under an existing project. The System project is the root node of the tree and will be the first and only Project in the brand new system.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="display-table-cell">
                                            <div class="display-table-row" style="height: 100px;">
                                            </div>
                                            <div class="step-link">
                                                <div class="display-table-row">
                                                    <img alt="two" src="../images/two.png" title="two" class="display-table-cell">
                                                    <a href="members.php" class="display-table-cell">
                                                        <span>Create Members</span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="step-desc">
                                                <div class="display-table-row">
                                                    <img alt="member" src="../images/member.png" title="member" class="display-table-cell">
                                                    <span class="display-table-cell">Define a new member and assign a Username and Admin Privilages. Roles control access rights in the system. The Admin privilages will be used to populate the role of a Member when placed on a Project.</span>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="display-table-row" style="height: 100px;">
                                </div>
                                <div class="display-table-row">
                                    <div class="display-table-cell">
                                        <div class="step-link">
                                            <div class="display-table-row">
                                                <img alt="four" src="../images/four.png" title="four" class="display-table-cell">
                                                <a href="../scrum/product_plan_backlog.php" class="display-table-cell">
                                                    <span>Begin Project</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="step-desc">
                                            <div class="display-table-row">
                                                <img alt="flag" src="../images/flag.png" title="flag" class="display-table-cell">
                                                <span class="display-table-cell">Member can login and access their project to begin working. Enter new item in the Backlog, create sprints and start planning the project. Use the Product planning > Import page to a set of existing items in the Backlog into the Project.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="display-table-cell">
                                        <div class="display-table-row" style="height: 100px;">
                                        </div>
                                        <div class="step-link">
                                            <div class="display-table-row">
                                                <img alt="three" src="../images/three.png" title="three" class="display-table-cell">
                                                <a href="members.php" class="display-table-cell">
                                                    <span>Assign Member to Project</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="step-desc">
                                            <div class="display-table-row">
                                                <img alt="assign-member-to-project" src="../images/assign-member-to-project.png" title="assign-member-to-project" class="display-table-cell">
                                                <span class="display-table-cell">Define which project a member can access using the Members > Project assignment page. Drag a member onto a project to assign the member and set its project role equal to its current admin privilages. Use Members > Project Roles page or the Projects > Member Roles page to assign Project-specific roles that differ from the admin privilages.</span>
                                            </div>
                                        </div>
                                        <div class="display-table-row" style="height:50px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>' . $this->EOF_LINE;

            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }

    abstract class ProjectHTML extends HTMLTemplate
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct($curNav, $curDir, $enableNav);
        }

        protected function getTabMenu($currentTab)
        {
            $lists = array(array('Projects', 'projects.php'), array('Sprint Schedules', 'sprint_schedules.php'),
                            array('Member Roles', 'member_roles.php'), array('Programs', 'programs.php'));

            $tag = Utility::getTabMenu($currentTab, $lists);

            return($tag);
        }
    }

    class ProjectsHTML extends ProjectHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Projects", "admin", true);
        }

        protected function addDashboard()
        {
            $tag = "";
            $tag .= '<div class="main-article display-table article-container">' . $this->EOF_LINE;

            $tag .= parent::getTabMenu("Projects");

            $tag .= '   <div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
            $tag .= '       <div class="main-article-tab-info-container">' . $this->EOF_LINE;

            $tag .=             Utility::getArticleTitle('Projects');

            $tag .= '           <div class="project-container">' . $this->EOF_LINE;
            $tag .=                 $this->getProjectTable();
            $tag .= '           </div>' . $this->EOF_LINE;

            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getProjectTable()
        {
            global $conn;

            //$year = getCurrentSession();
            //$qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session  FROM `spr_tracking` WHERE user_name = '". $_SESSION['project-managment-username'] ."' and session='" . $year . "'";

            $str = "";

            $table = new HTMLTable("project-table", "grippy-table");

            $title_th = '<a href="javascript:void(0);"><span class="icon plus-icon"></span></a>
                        <a href="javascript:void(0);"><span class="icon minus-icon"></span></a>
                        Title';

            // add table header
            $table->thead("project-thead");
                $table->th("&nbsp;", null, null, null, null);
                $table->th($title_th, null, null, null, "data-sort=\"string\"");
                $table->th("Owner", null,  null, null, "data-sort=\"string\"");
                $table->th("Begin Date", null, null, null, "data-sort=\"date\"");
                $table->th("End Date", null, null, null, "data-sort=\"string\"");
                $table->th("Sprint Schedule", null, null, null, "data-sort=\"string\"");
                $table->th("&nbsp;", null, null, null, "data-sort=\"string\"");

            // add Table body
            $table->tbody("project-tbody");

            /// SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session FROM `spr_tracking`
            /*$rows = $conn->result_fetch_array($qry);
            if(!empty($rows))
            {
                // loop over the result and fill the rows
                foreach($rows as $row)
                {*/
                    $table->tr(null, null, null, "align=\"center\"");
                        $table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                        $table->td($this->getProjectTitle('System(All Projects)', false), "system-title", "project-title-td", null, "width=\"25%\"");
                        $table->td("Abhishek Nath", "owner", null, null, "width=\"18%\"");
                        $table->td("06-01-2017", "begin-date", null, null, "width=\"10%\"");
                        $table->td("", "end-date", null, null, "width=\"10%\"");
                        $table->td("DB Conference Sprint", "sprint-schedule", null, null, "width=\"25%\"");
                        $table->td(getQuickActionBtn("project-edit-btn", "Add Child Project", "project-td-btn", "project-table-dropdown"), "project-edit", null, null, "width=\"5%\"");

                    $table->tr(null, null, null, "align=\"center\"");
                        $table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                        $table->td($this->getProjectTitle('Rental Car Service Product', true), "system-title", "project-title-td", null, "width=\"25%\"");
                        $table->td("Abhishek Nath", "owner", null, null, "width=\"15%\"");
                        $table->td("01-02-2017", "begin-date", null, null, "width=\"10%\"");
                        $table->td("31-01-2018", "end-date", null, null, "width=\"10%\"");
                        $table->td("Rental Car Service Product Sprint Schedule", "sprint-schedule", null, null, "width=\"25%\"");
                        $table->td(getQuickActionBtn("project-edit-btn", "Add Child Project", "project-td-btn", "project-table-dropdown"), "project-edit", null, null, "width=\"5%\"");
                /*}
            }
            else
            {
                $table->tr(null, null, null, "align=\"center\"");
                    $table->td("<p>No result !!!</p>", "no-result", null, null, null);
            }*/

            return(utf8_encode($table->toHTML()));
        }

        private function getProjectTitle($val, $isChild)
        {
            $tag = '';

            if($isChild)
                $tag .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>';

            $tag .= '   <a class = "project-title-plus-icon" href="javascript:void(0);"><span class="icon plus-icon"></span></a>';
            $tag .= '   <span class="project-title-image">' . EOF_LINE;
            $tag .= '       <img alt="backlog" src="../images/project_folder.png" title="backlog">'. EOF_LINE;
            $tag .= '   </span>'.EOF_LINE;
            $tag .= '   <span>' . $val . '</span>'.EOF_LINE;

            return($tag);
        }
    }

    class MembersHTML extends HTMLTemplate
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Members", "admin", true);
        }

        protected function addDashboard()
        {
            $tag = "";
            $tag .= '<div class="overview-article">' . $this->EOF_LINE;

            $tag .= '   <p>Members main article.</p>' . $this->EOF_LINE;

            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }

    class TeamsHTML extends HTMLTemplate
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Teams", "admin", true);
        }

        protected function addDashboard()
        {
            $tag = "";
            $tag .= '<div class="overview-article">' . $this->EOF_LINE;

            $tag .= '   <p>Teams main article.</p>' . $this->EOF_LINE;

            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }

    class ConfigurationHTML extends HTMLTemplate
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Configuration", "admin", true);
        }

        protected function addDashboard()
        {
            $tag = "";
            $tag .= '<div class="overview-article">' . $this->EOF_LINE;

            $tag .= '   <p>Configuration main article.</p>' . $this->EOF_LINE;

            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }
?>
