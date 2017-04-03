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
        public function __construct($curNav = null, $curDir = null, $enableNav = false)
        {
            parent::__construct("Scrum-Product-Planning-Backlog", "scrum", true, 'Backlog');
        }

        protected function getWidgetTitlebarContent()
        {
            $tag = '<span id="project-title" class="project-title" onclick="shieldProjectPlanBacklog.showProject(this)">PROJECT 2017</span>'. $this->EOF_LINE;

            return($tag);
        }

        protected function getWidgetboxContent()
        {
            $dropdownList = null;
            $tag = '';

            $tag .= '                    <div id="project-form-container"></div>'. $this->EOF_LINE;
            $tag .= '                    <div class="project-backlog-container">'. $this->EOF_LINE;
            $tag .= '                       <div class="session-button">'. $this->EOF_LINE;

            $tag .= $this->getMoveToProjectDropdown();
            $tag .= $this->getStoryInlineDropdown();
            $tag .= $this->getEditBacklogDropdown();

            $tag .= Utility::getQuickActionBtn("move-to-project-btn", "Move to Project", "move-to-project-btn", "", "", "move-to-project-dropdown");
            $tag .= Utility::getQuickActionBtn("story-inline-btn", "Add to Story Inline", "story-inline-btn", "", "", "story-inline-dropdown");

            $tag .= '                        </div>'.$this->EOF_LINE;
            $tag .= '                        <div class="wtable">'. $this->EOF_LINE;
            $tag .=                             $this->createDasboardTable();
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
                                    array("Move To Iteration", ""),
                                    array("Quick Close", ""),
                                    array("Close", ""),
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
                                    array('Add Task', ''),
                                    array('Copy', 'class="dashed-bottom-border"'),
                                    array('Quick Close', ''),
                                    array('Close', 'class="dashed-bottom-border"'),
                                    array('Convert to Defect', ''),
                                    array('Delete', '')
                                );
            return(Utility::getQuickActionBtnDropdown('backlog-table-dropdown', $dropdownList));
        }

        private function createDasboardTable()
        {
           /*global $conn;

            $qry = "SELECT order_no, title, id, owner, priority, estimate, project  FROM `spr_tracking` WHERE user_name = '". $_SESSION['project-managment-username'] ."'";
            */
            $str = '';

            $Table = new HTMLTable("project-backlog-table", "grippy-table");

            $Table->thead("project-backlog-thead");
            $Table->th("&nbsp;", null, null, null, null);
            $Table->th('<input type="checkbox" id="select_all" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll(\'_fjvevqi\');">', null, null, null, null);
            $Table->th("Order", null, null, null, "data-sort=\"int\"");
            $Table->th("Title", null,  null, null, "data-sort=\"string\"");
            $Table->th("ID", null,  null, null, "data-sort=\"string\"");
            $Table->th("Owner", null,  null, null, "data-sort=\"string\"");
            $Table->th("Priority", null,  null, null, "data-sort=\"string\"");
            $Table->th("Estimate Pts", null,  null, null, "data-sort=\"int\"");
            $Table->th("Project", null,  null, null, "data-sort=\"string\"");
            $Table->th("&nbsp", null,  null, null, null);

            $Table->tbody("project-backlog-tbody");

          /* $rows = $conn->result_fetch_array($qry);
           if(!empty($rows))
           {*/
             //loop over the result and fill the rows
               for($i = 1; $i <= 10; $i++)
               {
                   $Table->tr(null, null, null, "align=\"center\"");
                       $Table->td(getGreppyDotTag(), "{$i}-greppy", "hasGrippy", "text-align: center; width: 2%;");

                       $Table->td('<input type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll(\'_fjvevqi\');" class="checkbox">', "{$i}", null, "width: 4%", "text-align=\"center\"");

                       $Table->td("{$i}", "{$i}", null, null, null);

                       $Table->td($this->getBacklogTitle(true, "6587294"),"{$i}-title_container", "backlog-title-container");

                       $Table->td("s00001", "{$i}-id", null, null);

                       $Table->td("Pooja", "{$i}-owner", null, null);

                       $Table->td("High", "{$i}-priority", null, null);

                       $Table->td("15.0", "{$i}-estimate", null, null);

                       $Table->td("Release 1.0", "{$i}-project", null, null);

                       $Table->td(Utility::getQuickActionBtn("{$i}-edit-btn", "Edit", "quick-action-btn backlog-table-btn", "", "", "backlog-table-dropdown"), "{$i}-edit", null, null, "width=\"2%\"");

               }
           //}

           //else
           //{
            //$Table->tr(null, null, null, "align=\"center\"");
            //$Table->td(null, null, null, null);
        //}
            return(utf8_encode($Table->toHTML()));
        }

        private function getBacklogTitle($isBacklog, $val)
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
