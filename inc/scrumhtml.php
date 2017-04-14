<?php
    /* include header file */
    require_once ('htmltemplate.php');

    abstract class ScrumHTML extends HTMLTemplate
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

    abstract class ProductHTML extends ScrumHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false, $currentTab = null)
        {
            $tabs = array
                          (
                            array('Backlog', 'product_plan_backlog.php', SVG::getBacklog()),
                            array('Import', 'product_plan_backlog_import.php', SVG::getImport())
                          );

            parent::__construct($curNav, $curDir, $enableNav, $tabs, $currentTab);
        }
    }

    abstract class SprintTrackHTML extends ScrumHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false, $currentTab = null)
        {
            $tabs = array
                          (
                            array('Detail Tracking', 'sprint_detailed.php', SVG::getSprintDetailedTrack()),
                            array('Storyboard', 'sprint_storyboard.php', SVG::getSprintStoryboard()),
                            array('Taskboard', 'sprint_taskboard.php', SVG::getSprintTaskboard()),
                            array('Testboard', 'sprint_testboard.php', SVG::getSprintTestboard())
                          );

            parent::__construct($curNav, $curDir, $enableNav, $tabs, $currentTab);
        }
    }

    class ProductBacklogHTML extends ProductHTML
    {
        private $project = 'Project 2017';

        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Scrum-Product-Planning-Backlog", "scrum", true, 'Backlog');
        }

        protected function getWidgetTitlebarContent()
        {
            $tag = '<span id="project-title" class="project-title" onclick="shieldProductBacklog.showProject(this)">'.$this->project.'</span>'. $this->EOF_LINE;

            return($tag);
        }

        protected function getWidgetboxContent()
        {
            $dropdownList = null;
            $tag = '';

            $tag .= '                    <div id="project-form-container"></div>'. $this->EOF_LINE;
            $tag .= '                    <div class="project-backlog-container">'. $this->EOF_LINE;
            $tag .= '                       <div class="session-button">'. $this->EOF_LINE;

            $tag .= Utility::getQuickActionBtn("move-to-project-btn", "Move to Project", "", 'onclick="shieldProductBacklog.moveToProjectDialog(this, false)"', "project-backlog-tbody", "shieldProductBacklog.moveToProjectDropdown");

            $tag .= Utility::getQuickActionBtn("story-inline-btn", "Add to Story Inline", "story-inline-btn", 'onclick="shieldProductBacklog.addStoryInline(this, false)"', "project-backlog-tbody", "shieldProductBacklog.storyInlineDropdown");

            $tag .= '                        </div>'.$this->EOF_LINE;
            $tag .= '                        <div class="wtable">'. $this->EOF_LINE;
            $tag .=                             $this->getProjectTable();
            $tag .= '                        </div>'. $this->EOF_LINE;
            $tag .= '                    </div>'. $this->EOF_LINE;
            $tag .= '                </div>'. $this->EOF_LINE;

            return($tag);
        }

        private function getMoveToProjectDropdown()
        {
            $dropdownList = array
                                (
                                    array("Move To Project", ""),
                                    array("Move To Sprint", 'class="dashed-bottom-border"'),
                                    array("Quick Close", ""),
                                    array("Close", 'class="dashed-bottom-border"'),
                                    array("Reopen", ""),
                                    array("Delete", "")
                                );
            return(Utility::getQuickActionBtnDropdown('move-to-project-dropdown', $dropdownList));
        }

        private function getStoryInlineDropdown()
        {
            $dropdownList = array
                                (
                                    array("Add Story Inline", ""),
                                    array("Add Story", ""),
                                    array("Add Defect Inline", ""),
                                    array("Add Defect", "")
                                );
            return(Utility::getQuickActionBtnDropdown('story-inline-dropdown', $dropdownList));
        }

        private function getEditBacklogDropdown()
        {
            $dropdownList = array
                                (
                                    array('Edit', 'class="dashed-bottom-border"'),
                                    array('Plan Story', ''),
                                    array('Add Task', 'class="dashed-bottom-border"'),
                                    array('Quick Close', ''),
                                    array('Close', 'class="dashed-bottom-border"'),
                                    array('Delete', '')
                                );
            return(Utility::getQuickActionBtnDropdown('backlog-table-dropdown', $dropdownList));
        }

        private function getProjectTable()
        {
            $thList = array
                            (
                                array("&nbsp;", null, null, null, null),
                                array('<input type="checkbox" id="select_all">', null, null, null, null),
                                array("Title", null,  null, null, "data-sort=\"string\""),
                                array("Owner", null,  null, null, "data-sort=\"string\""),
                                array("Priority", null,  null, null, "data-sort=\"string\""),
                                array("Status", null,  null, null, "data-sort=\"string\""),
                                array("Estimate Pts", null,  null, null, "data-sort=\"int\""),
                                array("Project", null,  null, null, "data-sort=\"string\""),
                                array("&nbsp", null,  null, null, null)
                            );

            $qry = "SELECT title, owner, priority, status, estimated, project, sprint, description, type, risk, etype, source, reference, build, resolution FROM scrum_backlog WHERE project='". $this->project ."'";

            // create table and it's components to display Projects.
            return(Utility::createGrippyTable('project-backlog-table', 'project-backlog-thead', $thList,
                                'project-backlog-tbody', $qry, array("ProductBacklogHTML", "addTableRow")));
        }

        static function getTableBodyElement($clause)
        {
            if(($clause != null) && ($clause != ''))
            {
                $qry = "SELECT title, owner, priority, status, estimated, project, sprint, description, type, risk, etype, source, reference, build, resolution FROM scrum_backlog " . $clause;

                //echo $qry;
                // fill table components to display Projects.
                return(Utility::getGrippyTableBodyElements('project-table', 'project-tbody',
                                    $qry, array("ProductBacklogHTML", "addTableRow")));
            }
            else
                return('');
        }

        static function addTableRow($table, $row, $inx)
        {
            if(($row != null) && (count($row) > 0))
            {
                $table->tr(null, null, null, "align=\"center\"");
                    $table->td(getGreppyDotTag(), "{$inx}-greppy", "hasGrippy", "text-align: center; width: 2%;");
                    $table->td('<input type="checkbox" class="checkbox">', "{$inx}", null, "width: 2%", "text-align=\"center\"");
                    $table->td(self::getBacklogTitle(true, $row[0]),"{$inx}-title_container", "backlog-title-container", "width: 30%");
                    $table->td(Utility::decode($row[1]), "{$inx}-owner", null, "width: 30%");
                    $table->td($row[2], "{$inx}-priority", null, null);
                    $table->td($row[3], "{$inx}-status", null, null);
                    $table->td($row[4], "{$inx}-estimate", null, null);
                    $table->td($row[5], "{$inx}-project", null, null);

                    $table->td($row[6], "{$inx}-sprint", null, "display:none;");
                    $table->td($row[7], "{$inx}-description", null, "display:none;");
                    $table->td($row[8], "{$inx}-type", null, "display:none;");
                    $table->td($row[9], "{$inx}-risk", null, "display:none;");
                    $table->td($row[10], "{$inx}-etype", null, "display:none;");
                    $table->td($row[11], "{$inx}-source", null, "display:none;");
                    $table->td($row[12], "{$inx}-reference", null, "display:none;");
                    $table->td($row[13], "{$inx}-build", null, "display:none;");
                    $table->td(Utility::getQuickActionBtn("{$inx}-backlog-edit-btn", "Edit", "black-border-quick-action-btn", 'onclick="shieldProductBacklog.editDialog(this, false)"', "{$inx}", "shieldProductBacklog.editDropdown"), "{$inx}-edit", null, null, "width=\"2%\"");
            }
        }

        static function getBacklogTitle($isBacklog, $val)
        {
            $tag = '   <span class="backlog-image-container">' . EOF_LINE;
            if($isBacklog == true)
                $tag .= '      <img alt="backlog" src="../images/Feature-Icon.gif" title="backlog">'. EOF_LINE;
            else
                $tag .= '      <img alt="defect" src="../images/Defect-Icon.gif" title="defect">'. EOF_LINE;
            $tag .= '   </span>'.EOF_LINE;
            $tag .= '   <a>' . $val . '</a>'.EOF_LINE;

            return($tag);
        }
    }

    class ProductBacklogImportHTML extends ProductHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Scrum-Product-Planning-Backlog", "scrum", true, 'Import');
        }
    }

    class SprintDetailedHTML extends SprintTrackHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
        parent::__construct("Scrum-Sprint-Tracking-Detail", "scrum", true, 'Detail Tracking');
        }

        protected function getWidgetboxContent()
        {
            $tag = '';
            $tag .= '<div class="main-article display-table">' . $this->EOF_LINE;
            $tag .= '   <p>#Sprint Tracking Detail - Artcle block</p>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }

    class SprintStoryboardHTML extends SprintTrackHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
        parent::__construct("Scrum-Sprint-Tracking-Detail", "scrum", true, 'Storyboard');
        }

        protected function getWidgetboxContent()
        {
            $tag = '';
            $tag .= '<div class="main-article display-table">' . $this->EOF_LINE;
            $tag .= '   <p>#Sprint Storyboard - Artcle block</p>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }

    class SprintTrackTaskboardHTML extends SprintTrackHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Scrum-Sprint-Tracking-Taskboard", "scrum", true, 'Taskboard');
        }

        protected function getWidgetboxContent()
        {
            $tag = '';

            $tag .= '<div class="sprint-planning-container" style="border: 1px solid #dde2e9;">' . $this->EOF_LINE;
            $tag .= '       <table id="addrow" class="sprint-taskboard-table">' . $this->EOF_LINE;
            $tag .= '           <thead>' . $this->EOF_LINE;
            $tag .= '               <tr>' . $this->EOF_LINE;
            $tag .= '                   <th class="backlog-th">Backlog</th>' . $this->EOF_LINE;
            $tag .= '                   <th>(None)</th>' . $this->EOF_LINE;
            $tag .= '                   <th>Blocked</th>' . $this->EOF_LINE;
            $tag .= '                   <th>In Progress</th>' . $this->EOF_LINE;
            $tag .= '                   <th>Completed</th>' . $this->EOF_LINE;
            $tag .= '                   <th class="summary-th">Summary</th>' . $this->EOF_LINE;
            $tag .= '               </tr>' . $this->EOF_LINE;
            $tag .= '           </thead>' . $this->EOF_LINE;
            $tag .= '           <tbody>' . $this->EOF_LINE;
            $tag .= '               <tr>' . $this->EOF_LINE;
            $tag .= '                   <td class="backlog-td">' . $this->EOF_LINE;
            $tag .= '                       <div class="story-card-container">' . $this->EOF_LINE;
            $tag .= '                           <div class="identity">' . $this->EOF_LINE;
            $tag .= '                               <div class="identity-left">' . $this->EOF_LINE;
            $tag .= '                                   <span class="number">s00001</span>' . $this->EOF_LINE;
            $tag .= '                               </div>' . $this->EOF_LINE;
            $tag .= '                           <div class="identity-right">' . $this->EOF_LINE;
            $tag .= '                               <span class="story-card-actions">' . $this->EOF_LINE;
            $tag .= '                                   <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '                                       <g>' . $this->EOF_LINE;
            $tag .= '                                           <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '                                       </g>' . $this->EOF_LINE;
            $tag .= '                                   </svg>' . $this->EOF_LINE;
            $tag .= '                               </span>' . $this->EOF_LINE;
            $tag .= '                           </div>' . $this->EOF_LINE;
            $tag .= '                       </div>' . $this->EOF_LINE;
            $tag .= '                       <div class="title">' . $this->EOF_LINE;
            $tag .= '                           <a href="#">6534211</a>' . $this->EOF_LINE;
            $tag .= '                       </div>' . $this->EOF_LINE;
            $tag .= '                       <div class="backlog-td-status" >In Progress</div>' . $this->EOF_LINE;
            $tag .= '                           <div class="backlog-td-bottom">' . $this->EOF_LINE;
            $tag .= '                               <span class="backlog-td-bottom-owner-name">' . $this->EOF_LINE;
            $tag .= '                                   <a href="#">Pooja Kumari</a>' . $this->EOF_LINE;
            $tag .= '                               </span>' . $this->EOF_LINE;
            $tag .= '                               <span class="backlog-td-bottom-time">15.00</span>' . $this->EOF_LINE;
            $tag .= '                           </div>' . $this->EOF_LINE;
            $tag .= '                       </div>' . $this->EOF_LINE;
            $tag .= '                   </td>' . $this->EOF_LINE;
            $tag .= '<td class="status-td">' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Resolve</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">2.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Integrate</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">2.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="status-td">' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Communication</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">1.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="status-td">' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="status-td">' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Analysis</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">2.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="summary-td">' . $this->EOF_LINE;
            $tag .= '<dl class="non-card">' . $this->EOF_LINE;
            $tag .= '<dt>Test Results:</dt>' . $this->EOF_LINE;
            $tag .= '<dd class="value">' . $this->EOF_LINE;

            $tag .= '</dd>' . $this->EOF_LINE;
            $tag .= '<dt>To Do:</dt>' . $this->EOF_LINE;
            $tag .= '<dd class="value">12.00</dd>' . $this->EOF_LINE;
            $tag .= '</dl>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '</tr>' . $this->EOF_LINE;
            $tag .= '<tr>' . $this->EOF_LINE;
            $tag .= '<td class="backlog-td story-card-container">' . $this->EOF_LINE;
            $tag .= '<div class="story-card-container">' . $this->EOF_LINE;
            $tag .= '<div class="identity">' . $this->EOF_LINE;
            $tag .= '<div class="identity-left">' . $this->EOF_LINE;
            $tag .= '<span class="number">s00002</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="identity-right">' . $this->EOF_LINE;
            $tag .= '<span class="story-card-actions">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="title">' . $this->EOF_LINE;
            $tag .= '<a href="#">6534215</a>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="backlog-td-status" >In Progress</div>' . $this->EOF_LINE;
            $tag .= '<div class="backlog-td-bottom">' . $this->EOF_LINE;
            $tag .= '<span class="backlog-td-bottom-owner-name"><a href="#">Pooja Kumari</a>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '<span class="backlog-td-bottom-time">15.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="status-td">' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Testing</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">2.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Code Review</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">2.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Integrate</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">2.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="status-td">' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="status-td">' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Communication</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">0.50</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Resolve</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">1.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="status-td">' . $this->EOF_LINE;
            $tag .= '<div class="task-card">' . $this->EOF_LINE;
            $tag .= '<div class="task-card-title">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-title-name">Analysis</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-arrow" style="float:right;">' . $this->EOF_LINE;
            $tag .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . $this->EOF_LINE;
            $tag .= '<g>' . $this->EOF_LINE;
            $tag .= '<path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . $this->EOF_LINE;
            $tag .= '</g>' . $this->EOF_LINE;
            $tag .= '</svg>' . $this->EOF_LINE;
            $tag .= '</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '<div class="task-card-owner">' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-name">Pooja Kumari</span>' . $this->EOF_LINE;
            $tag .= '<span class="task-card-owner-time" style="float:right">2.00</span>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '<td class="summary-td">' . $this->EOF_LINE;
            $tag .= '<dl class="non-card">' . $this->EOF_LINE;
            $tag .= '<dt>Test Results:</dt>' . $this->EOF_LINE;
            $tag .= '<dd class="value">' . $this->EOF_LINE;

            $tag .= '</dd>' . $this->EOF_LINE;
            $tag .= '<dt>To Do:</dt>' . $this->EOF_LINE;
            $tag .= '<dd class="value">10.50</dd>' . $this->EOF_LINE;
            $tag .= '</dl>' . $this->EOF_LINE;
            $tag .= '</td>' . $this->EOF_LINE;
            $tag .= '</tr>' . $this->EOF_LINE;
            $tag .= '</tbody>' . $this->EOF_LINE;
            $tag .= '</table>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }

    class SprintTestboardHTML extends SprintTrackHTML
    {
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
        parent::__construct("Scrum-Sprint-Tracking-Detail", "scrum", true, 'Testboard');
        }

        protected function getWidgetboxContent()
        {
            $tag = '';
            $tag .= '<div class="main-article display-table">' . $this->EOF_LINE;
            $tag .= '   <p>#Sprint Testboard - Artcle block</p>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;

            return($tag);
        }
    }
?>
