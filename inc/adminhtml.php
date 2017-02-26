<?php
    /* include header file */
    require_once ('htmltemplate.php');

    abstract class AdminHTML extends HTMLTemplate
    {
        protected $table        = null;
        protected $dropdownList = null;

        public function __construct($curNav = null, $curDir = null, $enableNav = false, $tabItems = null, $currentTab = null)
        {
            parent::__construct($curNav, $curDir, $enableNav, $tabItems, $currentTab);
        }

        protected function addDashboard()
        {
            return( parent::getWidgetbox() );
        }
    }

    abstract class ProjectHTML extends AdminHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false, $currentTab = null)
        {
            $tabs = array
                            (
                                array('Projects', 'projects.php', SVG::getProject()),
                                array('Sprint Schedules', 'sprint_schedules.php', SVG::getSprintSchedule()),
                                array('Member Roles', 'member_roles.php', SVG::getMemberRoles())
                            );

            parent::__construct($curNav, $curDir, $enableNav, $tabs, $currentTab);
        }
    }

    abstract class MemberHTML extends AdminHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false, $currentTab = null)
        {
            $tabItems = array
                            (
                                array('Members', 'members.php', SVG::getMember()),
                                array('Project Assignment', 'projecct_assignment.php', SVG::getProjectAssignment()),
                                array('Project Roles', 'project_roles.php', SVG::getProjectRoles())/*,
                                array('Member Groups', 'member_groups.php')*/
                            );

            parent::__construct($curNav, $curDir, $enableNav, $tabItems, $currentTab);
        }
    }

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
                                                <a href="projecct_assignment.php" class="display-table-cell">
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

    class ProjectsHTML extends ProjectHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Projects", "admin", true, "Projects");

            $this->table = new HTMLTable("project-table", "grippy-table");
            $this->dropdownList = array
                                  (
                                      array("Edit", 'onclick="shieldProject.openEditDialog(\'\', \'project-tbody\', true)"'),
                                      array("Delete", 'onclick="shieldProject.delete()"')
                                  );
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            $tag .= '<div id="project-add-form-container"></div>' . $this->EOF_LINE;
            $tag .=  Utility::getQuickActionBtnDropdown('project-table-dropdown', $this->dropdownList);
            $tag .= '<div style="float: right; margin-bottom: 30px;">' . $this->EOF_LINE;
            $tag .=     Utility::getRetroButton('Add Project', 'green add-padding', 'onclick="shieldProject.openAddDialog(\'\', \'project-tbody\', false)"');
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div id="project-table-container">' . $this->EOF_LINE;
            $tag .=     $this->getProjectTable();
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getProjectTable()
        {
            $thList = array
                            (
                                array("&nbsp;", null, null, null, null),
                                array("Title", null, null, null, "data-sort=\"string\""),
                                array("Owner", null,  null, null, "data-sort=\"string\""),
                                array("Begin Date", null, null, null, "data-sort=\"string\""),
                                array("End Date", null, null, null, "data-sort=\"string\""),
                                array("Sprint Schedule", null, null, null, "data-sort=\"string\""),
                                array("&nbsp;", null, null, null, null)
                            );

            //$qry = "SELECT title, owner, begin_date, end_date, sprint_schedule, parent, description, status, target_estimate, test_suit, target_swag, reference FROM scrum_project  WHERE parent = 'System(All Projects)' AND ((owner = '". $_SESSION['project-managment-username'] ."') OR (title IN (SELECT project_title FROM scrum_project_member WHERE member_id='". $_SESSION['project-managment-username'] ."')))";

            $qry = "SELECT title, owner, begin_date, end_date, sprint_schedule, parent, description, status, target_estimate, test_suit, target_swag, reference, privilage FROM scrum_project, scrum_project_member WHERE scrum_project.parent = 'System(All Projects)' AND scrum_project.title = scrum_project_member.project_title AND scrum_project_member.member_id='". $_SESSION['project-managment-username'] ."'";

            // fill table components to display Projects.
            $grippyTable = new GrippyTable("project-table", "grippy-table");

            $grippyTable->fillHead("project-thead", $thList);
            $grippyTable->fillBody("project-tbody", $qry, array("ProjectsHTML", "addTableRow"));

            return(utf8_encode($grippyTable->toHTML()));
        }

        static function addTableRow($table, $row, $inx)
        {
            if(($row != null) && (count($row) > 0))
            {
                if($row[0] != 'System(All Projects)')
                {
                    $table->tr(null, null, null, "align=\"center\"");
                        $table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                        $table->td("{$row[0]}", "{$inx}-title", "project-title-td", null, "width=\"30%\"");
                        $table->td(Utility::decode($row[1]), "{$inx}-owner", null, null, "width=\"18%\"");
                        $table->td("{$row[2]}", "{$inx}-begin_date", null, null, "width=\"10%\"");
                        $table->td("{$row[3]}", "{$inx}-end_date", null, null, "width=\"10%\"");
                        $table->td("{$row[4]}", "{$inx}-sprint_schedule", null, null, "width=\"25%\"");

                        $table->td("{$row[5]}", "{$inx}-parent", null, "display: none;");
                        $table->td("{$row[6]}", "{$inx}-description", null, "display: none;");
                        $table->td("{$row[7]}", "{$inx}-status", null, "display: none;");
                        $table->td("{$row[8]}", "{$inx}-target_estimate", null, "display: none;");
                        $table->td("{$row[9]}", "{$inx}-test_suit", null, "display: none;");
                        $table->td("{$row[10]}", "{$inx}-target_swag", null, "display: none;");
                        $table->td("{$row[11]}", "{$inx}-reference", null, "display: none;");

                        if(($row[12] == 'System Admin') || ($row[12] == 'Project Admin'))
                        {
                            $table->td(Utility::getQuickActionBtn("{$inx}", "Edit", "project-td-btn", "onclick=\"shieldProject.openEditDialog('{$inx}', 'project-tbody', false)\"", "{$inx}", "project-table-dropdown"), "project-edit", null, null, "width=\"5%\"");
                        }
                        else
                            $table->td("&nbsp;", "project-edit", null, null, "width=\"10%\"");
                }
            }
        }

        static function getTableBodyElement()
        {
            $qry = "SELECT title, owner, begin_date, end_date, sprint_schedule, parent, description, status, target_estimate, test_suit, target_swag, reference, privilage FROM scrum_project, scrum_project_member WHERE scrum_project.parent = 'System(All Projects)' AND scrum_project.title = scrum_project_member.project_title AND scrum_project_member.member_id='". $_SESSION['project-managment-username'] ."'";

            // fill table components to display Projects.
            $grippyTable = new GrippyTable("project-table", "grippy-table");
            $grippyTable->fillBody("project-tbody", $qry, array("ProjectsHTML", "addTableRow"));

            return(utf8_encode($grippyTable->getBodyElement()));
        }
    }

    class SprintScheduleHTML extends ProjectHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Projects", "admin", true, "Sprint Schedules");

            $this->table = new HTMLTable("sprint-schedule-table", "grippy-table");
            $this->dropdownList = array
                                  (
                                      array('Edit', 'onclick="shieldSprintSchedule.openEditDialog(\'sprint-schedule-table-dropdown\', \'sprint-schedule-tbody\', true)"'),
                                      array('Delete', 'onclick="shieldSprintSchedule.delete(\'sprint-schedule-table-dropdown\', \'sprint-schedule-tbody\')"')
                                  );
        }

        protected function getWidgetboxContent()
        {
            $content = '               <div id="sprint-schedule-add-form-container">' . $this->EOF_LINE;
            $content .= '               </div>' . $this->EOF_LINE;

            $content .= '               <div style="float: right; margin-bottom: 30px;">' . $this->EOF_LINE;
            $content .=                     Utility::getRetroButton('Add Sprint Schedule', 'green add-padding', 'onclick="shieldSprintSchedule.openAddDialog(\'sprint-schedule-tbody\');"');
            $content .= '               </div>' . $this->EOF_LINE;

            $content .=                 Utility::getQuickActionBtnDropdown('sprint-schedule-table-dropdown', $this->dropdownList);

            $content .= '               <div id="sprint-schedule-table-container">' . $this->EOF_LINE;
            $content .=                     $this->getSprintScheduleTable();
            $content .= '               </div>' . $this->EOF_LINE;

            return($content);
        }

        private function getSprintScheduleTable()
        {
            $thList = array (
                                array("&nbsp;", null, null, null, null),
                                array("Title", null, null, null, "data-sort=\"string\""),
                                array("Iteration Length", null,  null, null, "data-sort=\"string\""),
                                array("Iteration Gap", null, null, null, "data-sort=\"string\""),
                                array("&nbsp;", null, null, null, null)
                            );
            $qry = "SELECT title, length, length_unit, gap, gap_unit, description FROM scrum_sprint_schedule";

            // fill table components to display Projects.
            $grippyTable = new GrippyTable("sprint-schedule-table", "grippy-table");

            $grippyTable->fillHead("sprint-schedule-thead", $thList);
            $grippyTable->fillBody("sprint-schedule-tbody", $qry, array("SprintScheduleHTML", "addTableRow"));

            return(utf8_encode($grippyTable->toHTML()));
        }

        static function addTableRow($table, $row, $inx)
        {
            if(($row != null) && (count($row) > 0))
            {
                if($row[0] != 'System(All Projects)')
                {
                    $table->tr(null, null, null, "align=\"center\"");
                        $table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"5%\"");
                        $table->td("{$row[0]}", "{$inx}-title", "project-title-td", null, "width=\"43%\"");
                        $table->td("{$row[1]} {$row[2]}", "{$inx}-length", null, null, "width=\"25%\"");
                        $table->td("{$row[3]} {$row[4]}", "{$inx}-gap", null, null, "width=\"25%\"");
                        $table->td("{$row[5]}", "{$inx}-description", null, "display: none;");
                        $table->td(Utility::getQuickActionBtn("{$inx}-sprint-schedule-edit-btn", "Edit", "project-td-btn", "onclick=\"shieldSprintSchedule.openEditDialog('{$inx}-sprint-schedule-edit-btn', 'sprint-schedule-tbody', false)\"", "{$inx}", "sprint-schedule-table-dropdown"), "sprint-schedule-edit", null, null, "width=\"5%\"");
                }
            }
        }

        static function getTableBodyElement()
        {
            $qry = "SELECT title, length, length_unit, gap, gap_unit, description FROM scrum_sprint_schedule";

            // fill table components to display Projects.
            $grippyTable = new GrippyTable("sprint-schedule-table", "grippy-table");
            $grippyTable->fillBody("sprint-schedule-tbody", $qry, array("SprintScheduleHTML", "addTableRow"));

            return(utf8_encode($grippyTable->getBodyElement()));
        }
    }

    class MemberRolesHTML extends ProjectHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Projects", "admin", true, "Member Roles");
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            $tag .= '<div id="project-div">';
            $tag .= '   <div class="doom-line">';
            $tag .= '       <span>PROJECTS</span>';
            $tag .= '   </div>';
            $tag .=     $this->getProjectTable();
            $tag .= '</div>';

            $tag .= '<div id="member-div" class="member-role-member-content">';
            $tag .= '   <div class="doom-line">';
            $tag .= '       <span>MEMBERS</span>';
            $tag .= '   </div>';
            $tag .= '   <div style="margin: 20px 0; display: flex;">';
            $tag .= '       <div style="margin-left: auto">' . $this->EOF_LINE;
            $tag .=             Utility::getRetroButton('Save', 'green', 'onclick="memberRoles.save();"');
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '       <div style="margin-left: 25px;">' . $this->EOF_LINE;
            $tag .=             Utility::getRetroButton('Close', 'red', 'onclick="memberRoles.close();"');
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>';
            $tag .= '   <div>';
            $tag .=         $this->getMemberTable();
            $tag .= '   </div>';
            $tag .= '</div>';

            return($tag);
        }

        private function getProjectTable()
        {
            $str = "";
            $this->table = new HTMLTable("project-table", "grippy-table");

            $qry = "SELECT title, owner, begin_date, end_date, sprint_schedule, parent, description, status, target_estimate, test_suit, target_swag, reference FROM scrum_project  WHERE parent = 'System(All Projects)' AND ((owner = '". $_SESSION['project-managment-username'] ."') OR (title IN (SELECT project_title FROM scrum_project_member WHERE member_id='". $_SESSION['project-managment-username'] ."')))";

            // fill table components to display Sprint Schedule.
            // add table header
            $thList = array
                            (
                                array("&nbsp;", null, null, null, null),
                                array("Title", null, null, null, 'data-sort="string"'),
                                array("Owner", null,  null, null, 'data-sort="string"'),
                                array("Begin Date", null, null, null, 'data-sort="string"'),
                                array("End Date", null, null, null, 'data-sort="string"'),
                                array("&nbsp;", null, null, null, null)
                            );

            // fill table components to display Projects.
            $grippyTable = new GrippyTable("project-table", "grippy-table");

            $grippyTable->fillHead("project-thead", $thList);
            $grippyTable->fillBody("project-tbody", $qry, array("MemberRolesHTML", "addProjectTableRow"));

            return(utf8_encode($grippyTable->toHTML()));
        }

        static function addProjectTableRow($table, $row, $inx)
        {
            if(($row != null) && (count($row) > 0))
            {
                if($row[0] != 'System(All Projects)')
                {
                    $table->tr("{$inx}-project-tr");
                        $table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                        $table->td("{$row[0]}", "{$inx}-title", null, null, "width=\"32%\"");
                        $table->td(Utility::decode($row[1]), "{$inx}-owner", null, null, "width=\"20%\"");
                        $table->td("{$row[2]}", "{$inx}-begin_date", null, null, "width=\"20%\"");
                        $table->td("{$row[3]}", "{$inx}-end_date", null, null, "width=\"20%\"");
                        $table->td(Utility::getRetroButton('Manage', 'iris-blue', 'onclick="memberRoles.showMemberTable(\''.$inx. '\')"'), null, null, null, "width=\"6%\"");
                }
            }
        }

        private function getMemberTable()
        {
            $str = "";
            $this->table = new HTMLTable("member-table", "grippy-table");

            // fill table components to display Sprint Schedule.
            // add table header
            $thList = array
                            (
                                array("&nbsp;", null, null, null, null),
                                array('Name', null, null, null, 'data-sort="string"'),
                                array("Username", null,  null, null, 'data-sort="string"'),
                                array("Project Role", null, null, null, 'data-sort="string"'),
                                array("New Project Role", null, null, null, null)
                            );


            // fill table components to display Members.
            $grippyTable = new GrippyTable("member-table", "grippy-table");

            $grippyTable->fillHead("member-thead", $thList);
            $grippyTable->fillBody("member-tbody", '', null);

            return(utf8_encode($grippyTable->toHTML()));
        }

        static function addMemberTableRow($table, $row, $inx)
        {
            if(($row != null) && (count($row) > 0))
            {
                $name = Utility::decode($row[0]) . ' ' . Utility::decode($row[1]);
                $newProjectRoleSelect = '<select id="'. $inx .'-privilage-select" class="retro-style session-select">
                                            <option value="none"></option>
                                            <option value="system admin">system admin</option>
                                            <option value="member admin">member admin</option>
                                            <option value="project admin">project admin</option>
                                            <option value="team member">team member</option>
                                            <option value="developer">developer</option>
                                            <option value="tester">tester</option>
                                            <option value="customer">customer</option>
                                        </select>';

                $table->tr(null, null, null, "align=\"center\"");
                    $table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                    $table->td($name, "{$inx}-name", "project-title-td", null, "width=\"38%\"");
                    $table->td(Utility::decode($row[2]), "{$inx}-member_id", null, null, "width=\"20%\"");
                    $table->td("{$row[3]}", "{$inx}-old-privilage", null, null, "width=\"20%\"");
                    $table->td("{$newProjectRoleSelect}", "{$inx}-new-privilage", null, null, "width=\"20%\"");
            }
        }

        static function getMemberTableElement($clause)
        {
            //global $conn;
            $tag = "";

            if(($clause != null) && ($clause != ''))
            {
                $qry = "SELECT user.first_name, user.last_name, user.user_name, scrum_project_member.privilage FROM user INNER JOIN scrum_project_member ON scrum_project_member.member_id = user.user_name AND scrum_project_member.project_title = '{$clause}' ORDER BY user.user_name DESC";

                // fill table components to display Projects.
                $grippyTable = new GrippyTable("member-table", "grippy-table");
                $grippyTable->fillBody("member-tbody", $qry, array("MemberRolesHTML", "addMemberTableRow"));

                $tag = $grippyTable->getBodyElement();
            }

            return(utf8_encode($tag));
        }
    }

    class MembersHTML extends MemberHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Members", "admin", true, "Members");

            $this->dropdownList = array
                                  (
                                      array("Edit", ""),
                                      array("Inactive", "")
                                  );
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            $tag .= '<div id="member-form-container"></div>' . $this->EOF_LINE;
            $tag .= Utility::getQuickActionBtnDropdown('member-edit-dropdown', $this->dropdownList);
            $tag .= '<div id="add-member-btn-container">' . $this->EOF_LINE;
            $tag .=     Utility::getRetroButton('Add Member', 'green add-padding', 'onclick="shieldMembers.openAddDialog(\'member-tbody\');"');
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div id="member-table-container" style="clear: both;">' . $this->EOF_LINE;
            $tag .=     $this->getMemberTable();
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getMemberTable()
        {
            $str = "";

            $this->table = new HTMLTable("member-table", "grippy-table");
            // fill table components to display Sprint Schedule.
            // add table header
            $this->fillTableHead();
            // add Table body
            $this->fillTableBody();

            return(utf8_encode($this->table->toHTML()));
        }

        public function fillTableHead()
        {
            if($this->table != null)
            {
                $thList = array
                                (
                                    array("&nbsp;", null, null, null, null),
                                    array('Name', null, null, null, 'data-sort="string"'),
                                    array("Username", null,  null, null, 'data-sort="string"'),
                                    array("Admin Privilage", null, null, null, 'data-sort="string"'),
                                    array("Email", null, null, null, 'data-sort="string"'),
                                    array("&nbsp;", null, null, null, null)
                                );

                // add table header
                $this->table->thead('member-thead');
                foreach($thList as $th)
                    $this->table->th($th[0], $th[1], $th[2], $th[3], $th[4]);
            }
        }

        public function fillTableBody()
        {
            if($this->table != null)
            {
                // add Table body
                $this->table->tbody("member-tbody");

                $rows = getScrumMembers('');
                if(!empty($rows))
                {
                    // loop over the result and fill the rows
                    $inx = 1;
                    foreach($rows as $row)
                    {
                        $name = $row[0] . ' ' .$row[1];
                        $this->table->tr("{$inx}-project-tr");
                            $this->table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                            $this->table->td($name, "{$inx}-name", "project-title-td", null, "width=\"34%\"");
                            $this->table->td($row[2], "{$inx}-username", null, null, "width=\"20%\"");
                            $this->table->td("{$row[4]}", "{$inx}-privilage", null, null, "width=\"20%\"");
                            $this->table->td($row[3], "{$inx}-end_date", null, null, "width=\"20%\"");
                            $this->table->td('&nbsp;', "{$inx}-member-edit", null, null, "width=\"2%\"");
                            //$this->table->td(Utility::getQuickActionBtn("{$inx}-member-edit-btn", "Edit", "project-td-btn", "onclick=\"shieldProject.openEditDialog('{$inx}-member-edit-btn', 'member-tbody', false)\"", "{$inx}", "member-edit-dropdown"), "{$inx}-member-edit", null, null, "width=\"2%\"");

                        $inx++;
                    }
                }
                else
                {
                    $this->table->tr(null, null, null, "align=\"center\"");
                        $this->table->td("<p>No result !!!</p>", "no-result", null, null, null);
                }
            }
        }
    }

    class ProjectAssignmentHTML extends MemberHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Members", "admin", true, "Project Assignment");
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            $tag .= '<div id="project-div" style="padding-bottom: 50px;">';
            $tag .= '   <div class="doom-line">';
            $tag .= '       <span>PROJECTS</span>';
            $tag .= '   </div>';
            $tag .=     $this->getProjectTable();
            $tag .= '</div>';

            $tag .= '<div id="member-div">';
            $tag .= ' <div class="doom-line">';
            $tag .= '     <span>MEMBERS</span>';
            $tag .= ' </div>';
            $tag .= '               <div style="margin: 30px 0;">' . $this->EOF_LINE;
            $tag .=                     Utility::getRetroButton('Assign to Project', 'green add-padding', 'onclick=""');
            $tag .= '               </div>' . $this->EOF_LINE;
            $tag .= ' <div>';
            $tag .=       $this->getMemberTable();
            $tag .= ' </div>';
            $tag .= '</div>';

            return($tag);
        }

        private function getProjectTable()
        {
            $str = "";
            $this->table = new HTMLTable("project-table", "grippy-table");

            $qry = "SELECT title, owner, begin_date, end_date, sprint_schedule, parent, description, status, target_estimate, test_suit, target_swag, reference FROM scrum_project  WHERE parent = 'System(All Projects)' AND ((owner = '". $_SESSION['project-managment-username'] ."') OR (title IN (SELECT project_title FROM scrum_project_member WHERE member_id='". $_SESSION['project-managment-username'] ."')))";

            // fill table components to display Sprint Schedule.
            // add table header
            $thList = array
                            (
                                array("&nbsp;", null, null, null, null),
                                array("Title", null, null, null, 'data-sort="string"'),
                                array("Owner", null,  null, null, 'data-sort="string"'),
                                array("Begin Date", null, null, null, 'data-sort="string"'),
                                array("End Date", null, null, null, 'data-sort="string"')
                            );
            $this->fillTableHead("project-thead", $thList);

            // add Table body
            $this->fillTableBody("project-tbody", $qry);

            return(utf8_encode($this->table->toHTML()));
        }

        private function getMemberTable()
        {
            $str = "";
            $this->table = new HTMLTable("member-table", "grippy-table");

            // fill table components to display Sprint Schedule.
            // add table header
            $thList = array
                            (
                                array("&nbsp;", null, null, null, null),
                                array('<input type="checkbox" id="select_all" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll(\'_fjvevqi\');">', null, null, null, null),
                                array('Name', null, null, null, 'data-sort="string"'),
                                array("Username", null,  null, null, 'data-sort="string"'),
                                array("Admin Privilage", null, null, null, 'data-sort="string"'),
                                array("Project Membership", null, null, null, null)
                            );
            $this->fillTableHead("member-thead", $thList);

            $qry = "SELECT user.first_name, user.last_name, user.user_name, scrum_member.privilage FROM user INNER JOIN scrum_member ON scrum_member.member_id = user.user_name ORDER BY user.user_name DESC";
            // add Table body
            $this->fillTableBody("member-tbody", $qry);

            /*$this->table->tbody("member-tbody");
                $this->table->tr(null, null, null, "align=\"center\"");
                    $this->table->td("<p>No result !!!</p>", "no-result", null, null, null);*/

            return(utf8_encode($this->table->toHTML()));
        }

        public function fillTableHead($title, $thList)
        {
            // add table header
            $this->table->thead($title);
            foreach($thList as $th)
                $this->table->th($th[0], $th[1], $th[2], $th[3], $th[4]);
        }

        public function fillTableBody($tbody, $qry)
        {
            global $conn;
            $status = false;

            if($this->table != null)
            {
                $status = true;

                // add Table body
                $this->table->tbody($tbody);

                $rows = $conn->result_fetch_array($qry);
                if(!empty($rows))
                {
                    // loop over the result and fill the rows
                    $inx = 1;
                    foreach($rows as $row)
                    {
                        if($row[0] != 'System(All Projects)')
                        {
                            if($tbody == "project-tbody")
                                $this->addProjectRow($inx, $row);
                            else
                                $this->addMemberRow($inx, $row);
                            $inx++;
                        }
                    }
                }
                else
                {
                    $this->table->tr(null, null, null, "align=\"center\"");
                        $this->table->td("<p>No result !!!</p>", "no-result", null, null, null);
                }
            }

            return($status);
        }

        private function addProjectRow($inx, $row)
        {
            $this->table->tr("{$inx}-project-tr");
                $this->table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                $this->table->td("{$row[0]}", "{$inx}-title", "project-title-td", null, "width=\"35%\"");
                $this->table->td(Utility::decode($row[1]), "{$inx}-owner", null, null, "width=\"25%\"");
                $this->table->td("{$row[2]}", "{$inx}-begin_date", null, null, "width=\"20%\"");
                $this->table->td("{$row[3]}", "{$inx}-end_date", null, null, "width=\"20%\"");
        }

        private function addMemberRow($inx, $row)
        {
            $name = Utility::decode($row[0]) . ' ' . Utility::decode($row[1]);

            $this->table->tr(null, null, null, "align=\"center\"");
                $this->table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                $this->table->td('<input type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll(\'_fjvevqi\');" class="checkbox">', "{$inx}", null, "width: 2%", "text-align=\"center\"");
                $this->table->td($name, "{$inx}-name", "project-title-td", null, "width=\"20%\"");
                $this->table->td(Utility::decode($row[2]), "{$inx}-member_id", null, null, "width=\"20%\"");
                $this->table->td("{$row[3]}", "{$inx}-old-privilage", null, null, "width=\"20%\"");
                $this->table->td("{$row[3]}", "{$inx}-old-privilage", null, null, "width=\"40%\"");
        }

        public function getTBodyElementHTML()
        {
            return($this->table->getTBodyElementHTML());
        }
    }

    class ProjectRolesHTML extends MemberHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Members", "admin", true, "Project Roles");
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            $tag .= '               <div id="project-table-container" class="project-container">' . $this->EOF_LINE;
            $tag .= '                   <div style="float: right; margin-right: 25px;">' . $this->EOF_LINE;
            $tag .=                         Utility::getRetroButton('Add Member', 'green', 'onclick="members.addMembers();"');
            $tag .= '                   </div>' . $this->EOF_LINE;
            $tag .= '                   <div id="member-table-container" style="clear: both;">' . $this->EOF_LINE;
            $tag .=                         $this->getMemberTable();
            $tag .= '                   </div>' . $this->EOF_LINE;
            $tag .= '               </div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getMemberTable()
        {
            $tag = '';

            $tag .= '<p>#block for Table</p>' . $this->EOF_LINE;

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
