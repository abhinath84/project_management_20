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

        abstract protected function fillDashboard();

        protected function addDashboard()
        {
            $tag = '';
            $tag .= '<div class="main-article display-table article-container">' . $this->EOF_LINE;
            $tag .=     parent::getTabMenu();
            $tag .=     $this->fillDashboard();
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
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
                                      array("Add Child Project", ""),
                                      array("Edit", ""),
                                      array("Move Project", ""),
                                      array("Close Project", ""),
                                      array("Delete", "")
                                  );
        }

        protected function fillDashboard()
        {
            $tag = "";

            $tag .= '   <div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
            $tag .= '       <div class="main-article-tab-info-container">' . $this->EOF_LINE;
            $tag .=             Utility::getWidgetBox('Projects', 'project-div', '', '', '', $this->getWidgetContent());
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getWidgetContent()
        {
            $tag = '               <div id="project-add-form-container"></div>' . $this->EOF_LINE;
            $tag .=                 Utility::getQuickActionBtnDropdown('project-table-dropdown', $this->dropdownList);
            $tag .= '               <div id="project-table-container">' . $this->EOF_LINE;
            $tag .=                     $this->getProjectTable();
            $tag .= '               </div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getProjectTable()
        {
            $str = "";

            $qry = "SELECT title, owner, begin_date, end_date, sprint_schedule, parent, description, status, target_estimate, test_suit, target_swag, reference FROM scrum_project  WHERE parent = 'System(All Projects)' AND ((owner = '". $_SESSION['project-managment-username'] ."') OR (title IN (SELECT project_title FROM scrum_project_member WHERE member_id='". $_SESSION['project-managment-username'] ."')))";

            // fill table components to display Sprint Schedule.
            // add table header
            $this->fillTableHead();
            // add Table body
            $this->fillTableBody($qry);

            return(utf8_encode($this->table->toHTML()));
        }

        public function fillTableHead()
        {
            $title_th = '<a href="javascript:void(0);"><span class="icon plus-icon"></span></a>
                        <a href="javascript:void(0);"><span class="icon minus-icon"></span></a>
                        Title';

            // add table header
            $this->table->thead("project-thead");
                $this->table->th("&nbsp;", null, null, null, null);
                $this->table->th($title_th, null, null, null, "data-sort=\"string\"");
                $this->table->th("Owner", null,  null, null, "data-sort=\"string\"");
                $this->table->th("Begin Date", null, null, null, "data-sort=\"string\"");
                $this->table->th("End Date", null, null, null, "data-sort=\"string\"");
                $this->table->th("Sprint Schedule", null, null, null, "data-sort=\"string\"");
                $this->table->th("&nbsp;", null, null, null);
        }

        public function fillTableBody($qry)
        {
            global $conn;
            $status = false;

            if($this->table != null)
            {
                $status = true;

                // add Table body
                $this->table->tbody("project-tbody");

                $rows = $conn->result_fetch_array($qry);
                if(!empty($rows))
                {
                    // loop over the result and fill the rows
                    $inx = 1;
                    foreach($rows as $row)
                    {
                        if($row[0] != 'System(All Projects)')
                        {
                            $this->table->tr(null, null, null, "align=\"center\"");
                                $this->table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                                $this->table->td($this->getProjectTitle($inx, $row[0], false), "{$inx}-title", "project-title-td", null, "width=\"25%\"");
                                $this->table->td(Utility::decode($row[1]), "{$inx}-owner", null, null, "width=\"18%\"");
                                $this->table->td("{$row[2]}", "{$inx}-begin_date", null, null, "width=\"10%\"");
                                $this->table->td("{$row[3]}", "{$inx}-end_date", null, null, "width=\"10%\"");
                                $this->table->td("{$row[4]}", "{$inx}-sprint_schedule", null, null, "width=\"25%\"");

                                $this->table->td("{$row[5]}", "{$inx}-parent", null, "display: none;");
                                $this->table->td("{$row[6]}", "{$inx}-description", null, "display: none;");
                                $this->table->td("{$row[7]}", "{$inx}-status", null, "display: none;");
                                $this->table->td("{$row[8]}", "{$inx}-target_estimate", null, "display: none;");
                                $this->table->td("{$row[9]}", "{$inx}-test_suit", null, "display: none;");
                                $this->table->td("{$row[10]}", "{$inx}-target_swag", null, "display: none;");
                                $this->table->td("{$row[11]}", "{$inx}-reference", null, "display: none;");

                                $this->table->td(Utility::getQuickActionBtn("{$inx}-project-edit-btn", "Add Child Project", "project-td-btn", "onclick=\"shieldProject.openAddDialog('{$inx}-project-edit-btn', 'project-tbody', false)\"", "{$inx}", "project-table-dropdown"), "project-edit", null, null, "width=\"5%\"");

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

        private function getProjectTitle($id, $val, $isChild)
        {
            $tag = '';

            if($isChild)
                $tag .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>';

            $tag .= '   <a class = "project-title-plus-icon" href="javascript:void(0);"><span class="icon plus-icon"></span></a>';
            /*$tag .= '   <span class="project-title-image">' . EOF_LINE;
            $tag .= '       <img alt="backlog" src="../images/project_folder.png" title="backlog">'. EOF_LINE;
            $tag .= '   </span>'.EOF_LINE;*/
            $tag .= '   <span id="'. $id .'-title-span">' . $val . '</span>'.EOF_LINE;

            return($tag);
        }

        public function getTBodyElementHTML() {
            return($this->table->getTBodyElementHTML());
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

        protected function fillDashboard()
        {
            $tag = '';

            $tag .= '   <div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
            $tag .= '       <div class="main-article-tab-info-container">' . $this->EOF_LINE;
            $tag .=             Utility::getWidgetBox('Sprint Schedules', 'sprint-schedule-div', '', '', '', $this->getWidgetContent());
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getWidgetContent()
        {
            $content = '               <div id="sprint-schedule-add-form-container">' . $this->EOF_LINE;
            $content .= '               </div>' . $this->EOF_LINE;

            $content .= '               <div style="float: right; margin-right: 25px;">' . $this->EOF_LINE;
            $content .=                     Utility::getRetroButton('Add Sprint Schedule', 'green add-spr', 'onclick="shieldSprintSchedule.openAddDialog(\'sprint-schedule-tbody\');"');
            $content .= '               </div>' . $this->EOF_LINE;

            $content .=                 Utility::getQuickActionBtnDropdown('sprint-schedule-table-dropdown', $this->dropdownList);

            $content .= '               <div id="sprint-schedule-table-container">' . $this->EOF_LINE;
            $content .=                     $this->getProjectTable();
            $content .= '               </div>' . $this->EOF_LINE;

            return($content);
        }

        private function getProjectTable()
        {
            global $conn;
            $str = "";

            $qry = "SELECT title, length, length_unit, gap, gap_unit, description FROM scrum_sprint_schedule";
            $rows = $conn->result_fetch_array($qry);

            // fill table components to display Sprint Schedule.
            // add table header
            $this->fillTableHead();
            // add Table body
            $this->fillTableBody($rows);

            return(utf8_encode($this->table->toHTML()));
        }

        public function fillTableHead()
        {
            // add table header
            $this->table->thead("sprint-schedule-thead");
                $this->table->th("&nbsp;", null, null, null, null);
                $this->table->th("Title", null, null, null, "data-sort=\"string\"");
                $this->table->th("Iteration Length", null,  null, null, "data-sort=\"string\"");
                $this->table->th("Iteration Gap", null, null, null, "data-sort=\"string\"");
                $this->table->th("&nbsp;", null, null, null);
        }

        public function fillTableBody($rows)
        {
            $status = false;

            if(($this->table != null) && ($rows != null))
            {
                $status = true;
                $this->table->tbody("sprint-schedule-tbody");

                if(!empty($rows))
                {
                    // loop over the result and fill the rows
                    $inx = 1;
                    foreach($rows as $row)
                    {
                        $this->table->tr(null, null, null, "align=\"center\"");
                            $this->table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"5%\"");
                            $this->table->td("{$row[0]}", "{$inx}-title", "project-title-td", null, "width=\"43%\"");
                            $this->table->td("{$row[1]} {$row[2]}", "{$inx}-length", null, null, "width=\"25%\"");
                            $this->table->td("{$row[3]} {$row[4]}", "{$inx}-gap", null, null, "width=\"25%\"");
                            $this->table->td("{$row[5]}", "{$inx}-description", null, "display: none;");
                            $this->table->td(Utility::getQuickActionBtn("{$inx}-sprint-schedule-edit-btn", "Edit", "project-td-btn", "onclick=\"shieldSprintSchedule.openEditDialog('{$inx}-sprint-schedule-edit-btn', 'sprint-schedule-tbody', false)\"", "{$inx}", "sprint-schedule-table-dropdown"), "sprint-schedule-edit", null, null, "width=\"5%\"");

                        $inx++;
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

        public function getTBodyElementHTML() {
            return($this->table->getTBodyElementHTML());
        }
    }

    class MemberRolesHTML extends ProjectHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Projects", "admin", true, "Member Roles");
        }

        protected function fillDashboard()
        {
            $memberContent = '                   <div style="float: right; margin-right: 25px;">' . $this->EOF_LINE;
            $memberContent .=                         Utility::getRetroButton('Close', 'red', 'onclick="memberRoles.close();"');
            $memberContent .= '                   </div>' . $this->EOF_LINE;
            $memberContent .= '                   <div style="float: right; margin-right: 25px;">' . $this->EOF_LINE;
            $memberContent .=                         Utility::getRetroButton('Save', 'green', 'onclick="memberRoles.save();"');
            $memberContent .= '                   </div>' . $this->EOF_LINE;
            $memberContent .=                     $this->getMemberTable();

            $tag = '';

            $tag .= '   <div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
            $tag .= '       <div class="main-article-tab-info-container member-role-info-container">' . $this->EOF_LINE;

            $tag .=             Utility::getWidgetBox('Projects', 'project-div', '', '', '', $this->getProjectTable());
            $tag .=             Utility::getWidgetBox('Members', 'member-div', 'member-role-member-content', '', '', $memberContent);

            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getProjectTable()
        {
            $str = "";
            $this->table = new HTMLTable("project-table", "grippy-table");

            $qry = "SELECT title, owner, begin_date, end_date, sprint_schedule, parent, description, status, target_estimate, test_suit, target_swag, reference FROM scrum_project  WHERE parent = 'System(All Projects)' AND ((owner = '". $_SESSION['project-managment-username'] ."') OR (title IN (SELECT project_title FROM scrum_project_member WHERE member_id='". $_SESSION['project-managment-username'] ."')))";

            // fill table components to display Sprint Schedule.
            // add table header
            $title_th = '<a href="javascript:void(0);"><span class="icon plus-icon"></span></a>
                        <a href="javascript:void(0);"><span class="icon minus-icon"></span></a>
                        Title';
            $thList = array
                            (
                                array("&nbsp;", null, null, null, null),
                                array($title_th, null, null, null, 'data-sort="string"'),
                                array("Owner", null,  null, null, 'data-sort="string"'),
                                array("Begin Date", null, null, null, 'data-sort="string"'),
                                array("End Date", null, null, null, 'data-sort="string"'),
                                array("&nbsp;", null, null, null, null)
                            );
            $this->fillTableHead("project-thead", $thList);

            // add Table body
            $this->fillTableBody($qry);

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
                                array('Name', null, null, null, 'data-sort="string"'),
                                array("Username", null,  null, null, 'data-sort="string"'),
                                array("Project Role", null, null, null, null),
                                array("New Project Role", null, null, null, null)
                            );
            $this->fillTableHead("member-thead", $thList);

            // add Table body
            $this->table->tbody("member-tbody");
                $this->table->tr(null, null, null, "align=\"center\"");
                    $this->table->td("<p>No result !!!</p>", "no-result", null, null, null);

            return(utf8_encode($this->table->toHTML()));
        }

        public function fillTableHead($title, $thList)
        {
            // add table header
            $this->table->thead($title);
            foreach($thList as $th)
                $this->table->th($th[0], $th[1], $th[2], $th[3], $th[4]);
        }

        public function fillTableBody($qry)
        {
            global $conn;
            $status = false;

            if($this->table != null)
            {
                $status = true;

                // add Table body
                $this->table->tbody("project-tbody");

                $rows = $conn->result_fetch_array($qry);
                if(!empty($rows))
                {
                    // loop over the result and fill the rows
                    $inx = 1;
                    foreach($rows as $row)
                    {
                        if($row[0] != 'System(All Projects)')
                        {
                            $this->table->tr("{$inx}-project-tr");
                                $this->table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                                $this->table->td($this->getProjectTitle($inx, $row[0], false), "{$inx}-title", "project-title-td", null, "width=\"32%\"");
                                $this->table->td(Utility::decode($row[1]), "{$inx}-owner", null, null, "width=\"20%\"");
                                $this->table->td("{$row[2]}", "{$inx}-begin_date", null, null, "width=\"20%\"");
                                $this->table->td("{$row[3]}", "{$inx}-end_date", null, null, "width=\"20%\"");
                                $this->table->td(Utility::getRetroButton('Manage', 'iris-blue', 'onclick="memberRoles.showMemberTable(\''.$inx. '\')"'), null, null, null, "width=\"6%\"");

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

        private function getProjectTitle($id, $val, $isChild)
        {
            $tag = '';

            if($isChild)
                $tag .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>';

            $tag .= '   <a class = "project-title-plus-icon" href="javascript:void(0);"><span class="icon plus-icon"></span></a>';
            /*$tag .= '   <span class="project-title-image">' . EOF_LINE;
            $tag .= '       <img alt="backlog" src="../images/project_folder.png" title="backlog">'. EOF_LINE;
            $tag .= '   </span>'.EOF_LINE;*/
            $tag .= '   <span id="'. $id .'-title-span">' . $val . '</span>'.EOF_LINE;

            return($tag);
        }

        public function getTBodyElementHTML()
        {
            return($this->table->getTBodyElementHTML());
        }
    }

    class ProgramsHTML extends ProjectHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Projects", "admin", true, "Programs");
        }

        protected function fillDashboard()
        {
            $tag = "";

            $tag .= '   <div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
            $tag .= '       <div class="main-article-tab-info-container">' . $this->EOF_LINE;

            $tag .=             Utility::getArticleTitle('Programs');

            $tag .= '           <div class="project-container">' . $this->EOF_LINE;
            //$tag .=                 $this->getProjectTable();
            $tag .= '           </div>' . $this->EOF_LINE;

            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;

            return($tag);
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

        protected function fillDashboard()
        {
            $tag = '';

            $tag .= '<div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
            $tag .= '   <div class="main-article-tab-info-container member-role-info-container">' . $this->EOF_LINE;
            $tag .=         Utility::getWidgetBox('Members', 'member-div', '', '', '', $this->getWidgetContent());
            $tag .= '   </div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getWidgetContent()
        {
            $tag = '';

            $tag .= '<div id="member-form-container"></div>' . $this->EOF_LINE;
            $tag .= Utility::getQuickActionBtnDropdown('member-edit-dropdown', $this->dropdownList);
            $tag .= '<div style="float: right; margin-right: 25px;">' . $this->EOF_LINE;
            $tag .=     Utility::getRetroButton('Add Member', 'green', 'onclick="shieldMembers.openAddDialog(\'member-tbody\');"');
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
                                    array("Admin Privilage", null, null, null, null),
                                    array("Email", null, null, null, null),
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

        protected function fillDashboard()
        {
            $tag = "";

            $tag .= '   <div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
            $tag .= '       <div class="main-article-tab-info-container">' . $this->EOF_LINE;

            $tag .=             Utility::getArticleTitle('Projects');

            $tag .= '               <div id="project-table-container" class="project-container">' . $this->EOF_LINE;
            $tag .= '                   <div style="float: right; margin-right: 25px;">' . $this->EOF_LINE;
            $tag .=                         Utility::getRetroButton('Add Member', 'green', 'onclick="members.addMembers();"');
            $tag .= '                   </div>' . $this->EOF_LINE;
            $tag .= '                   <div id="member-table-container" style="clear: both;">' . $this->EOF_LINE;
            $tag .=                         $this->getMemberTable();
            $tag .= '                   </div>' . $this->EOF_LINE;
            $tag .= '               </div>' . $this->EOF_LINE;
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;

            return($tag);
        }

        private function getMemberTable()
        {
            $tag = '';

            $tag .= '                       <p>#block for Table</p>' . $this->EOF_LINE;

            return($tag);
        }
    }

    class ProjectRolesHTML extends MemberHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Members", "admin", true, "Project Roles");
        }

        protected function fillDashboard()
        {
            $tag = "";

            $tag .= '   <div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
            $tag .= '       <div class="main-article-tab-info-container">' . $this->EOF_LINE;

            $tag .=             Utility::getArticleTitle('Members');

            $tag .= '               <div id="project-table-container" class="project-container">' . $this->EOF_LINE;
            $tag .= '                   <div style="float: right; margin-right: 25px;">' . $this->EOF_LINE;
            $tag .=                         Utility::getRetroButton('Add Member', 'green', 'onclick="members.addMembers();"');
            $tag .= '                   </div>' . $this->EOF_LINE;
            $tag .= '                   <div id="member-table-container" style="clear: both;">' . $this->EOF_LINE;
            $tag .=                         $this->getMemberTable();
            $tag .= '                   </div>' . $this->EOF_LINE;
            $tag .= '               </div>' . $this->EOF_LINE;
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;

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
