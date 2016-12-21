<?php
/**
* @file      htmltemplate.php
* @author    Abhishek Nath
* @date      22-Oct-2016
* @version   1.0
*
* @section DESCRIPTION
* Class to create HTML page with passing information.
*
* @section LICENSE
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License as
* published by the Free Software Foundation; either version 2 of
* the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
* General Public License for more details at
* http://www.gnu.org/copyleft/gpl.html
*
*
*** Basic Coding Standard :
*** https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
*** http://www.php-fig.org/psr/psr-2/
*
*/

/*--
22-Oct-16   V1-01-00   abhishek   $$1   Created.
--*/


/* include header file */
require_once ('navigator.php');
require_once ('htmltable.php');


/**
 * @abstract    HTMLTemplate
 * @author      Abhishek Nath
 * @version     1.0
 *
 * @section DESCRIPTION
 *
 * .
 *
 */
abstract class HTMLTemplate
{
    private $currentDir = null;
    private $enableNav = null;
    private $currentNav = null;
    protected $EOF_LINE = "\n";

    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        $this->currentDir  = $curDir;
        $this->enableNav   = $enableNav;
        $this->currentNav   = $curNav;
    }

    public function generateBody()
    {
        $tag = "";

        if(($this->currentDir != null) && ($this->currentNav != null))
        {
            $tag  = '<body>' . $this->EOF_LINE;
            $tag .= '    <div class="wrapper display-table">' . $this->EOF_LINE;
            $tag .= $this->addHeader($this->currentDir, $this->currentNav, $this->enableNav) . $this->EOF_LINE;
            $tag .= $this->addArticle() . $this->EOF_LINE;
            $tag .= $this->addFooter($this->currentDir) . $this->EOF_LINE;
            $tag .= '    </div>' . $this->EOF_LINE;
            $tag .= '</body>' . $this->EOF_LINE;
        }

        return($tag);
    }

    abstract protected function addDashboard();

    /**
    * Add header block.
    *
    * Add header block including header caption and navigator.
    *
    * @return string $tag
    *   header tag string.
    */
    protected function addHeader($currentDir, $selNav, $enableNav)
    {
        $nav = new Navigator();
        $tag = $nav->header_new($currentDir, $selNav, $enableNav);

        return($tag);
    }

    protected function addArticle() {
        $tag = "";

        $tag = '<div class="article display-table-row">' . $this->EOF_LINE;
        $tag .= '    <div class="display-table article-container spr-tracking-dashboard">' . $this->EOF_LINE;
        $tag .= '        <div class="main-article display-table">' . $this->EOF_LINE;
        $tag .= $this->addDashboard() . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= '    </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    /**
    * Add footer block for this app.
    *
    * Add footer block including footer caption and navigator.
    *
    * @return string $tag
    *   header tag string.
    */
    protected function addFooter($currentDir, $hrTagFlag = true)
    {
        $nav = new Navigator();
        $tag = $nav->footer($currentDir, $hrTagFlag);

        return($tag);
    }
}

class HomeHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("HOME", "base", true);
    }

    protected function addDashboard()
    {
        $tag = "<p>#Home page article block</p>";
        /*$tag  = "<!-- Main Article-->" . $this->EOF_LINE;
        $tag .= "<div class=\"clear\">" . $this->EOF_LINE;
        $tag .= "    <div id=\"main-article-container\">" . $this->EOF_LINE;

        if((isset($_SESSION["project-managment-username"])) && ($_SESSION["project-managment-username"] != ""))
        {
            $tag .= $this->getUserInfoContainer();
        }
        else
        {
            $tag .= $this->getPMShortDesc();
            $tag .= $this->getArticleContainer();
        }

        $tag .= "    </div>" . $this->EOF_LINE;
        $tag .= "</div>" . $this->EOF_LINE;*/

        return($tag);
    }

    private function getUserInfoContainer()
    {
        $tag = "";
        $nc = getNextCutOff('config/ptc_info.xml');

        if(!empty($nc))
        {
            $tag .= '<div id="userinfo-container" class="left" style="width:72%; height: 100%; margin:0;">';

            // get upcoming Respond by date (next 2 months)
            $tag .= $this->getRespondByDiv();

            // get upcoming Commit Build information.
            $tag .= $this->getCommitBuildDiv([$nc[0][0], $nc[1][0], $nc[2][0], $nc[3][0]]);

            // Get Submission Status Information.
            $tag .= $this->getSubmissionStatusDiv();

            $tag .= '</div>';
            $tag .= '<div class="left" style="width:2%">&nbsp;</div>';

            // get Next Cutoff info
            $tag .= $this->getNextCutOffDiv($nc);
        }

        return(utf8_encode($tag));
    }

    private function getRespondByDiv()
    {
        $NearByDate = 20;
        $tag = "";
        $tag .= '<div id="respondBy-container" class="dashboard-table left" style="width:25%;">';
        $tag .= '<table>
                    <thead>
                        <tr>
                            <th colspan="2"><h2 style="text-align:center;">Respond By</h2></th>
                        </tr>
                    </thead>
                    <tbody>';
        $respond_by = $this->getSPRHavingNextRespondBy();
        foreach($respond_by as $each)
        {
            $tag .= '        <tr>
                                <td>
                                    <p style="text-align:center; margin-top:4px; margin-bottom:4px;"><strong>
                                        <a href="'.getSPRLink($each[0], $each[2]).'" target="_blank">'.$each[0].'</a></strong></p>
                                </td>
                                <td><p style="text-align:center; color: '.((isNearByDate($each[1], $NearByDate) == true) ? "red" : "black").'">'.$each[1].'</p></td>
                            </tr>';
        }
        $tag .= '    </tbody>
                </table>';
        $tag .= '</div>';

        return($tag);
    }

    private function getSPRHavingNextRespondBy()
    {
        global $conn;
        $sprs = array();

        if((isset($_SESSION["project-managment-username"])) && ($_SESSION["project-managment-username"] != ""))
        {
            $date = date('Y-m-d');

            // date after next 1 month
            list($year, $month, $day) = explode('-', $date);
            if($month == '12')
            {
                $year = $year + 1;
                $month = 1;
            }
            else
                $month = $month + 1;

            if($month < 10)
                $month = '0'.$month;

            $next_date = $year . '-' . $month . '-' . $day;

            $qry = "SELECT spr_no, respond_by_date, commit_build, type
                    FROM `spr_tracking` WHERE user_name =  '".$_SESSION["project-managment-username"]."'
                    AND (TYPE =  'SPR' OR TYPE =  'INTEGRITY SPR') AND (STATUS <>  'NOT AN ISSUE'
                    AND STATUS <>  'RESOLVED' AND STATUS <> 'CLOSED' AND STATUS <> 'SUBMITTED'
                    AND STATUS <> 'NEED MORE INFO' AND STATUS <> 'PASS TO CORRESPONDING GROUP') AND respond_by_date BETWEEN  '".$date."' AND  '".$next_date."'";

            $rows = $conn->result_fetch_array($qry);
            foreach($rows as $row)
            {
                if($row[2] == "")
                    array_push($sprs, [$row[0], $row[1], $row[3]]);
            }

        }

        return($sprs);
    }

    private function getCommitBuildDiv($buildVersions)
    {
        $tag = "";
        if(!empty($buildVersions))
        {
            $tag .= '<div id="commit-build-container" class="dashboard-table left" style="width:39%;">';
            $tag .= '<table style="width:100%;">
                        <thead>
                            <tr>
                                <th colspan="2"><h2 style="text-align:center;">SPRs having Commit Build</h2></th>
                            </tr>
                        </thead>
                        <tbody>';

            foreach($buildVersions as $each)
            {
                // get SPRs having Commit build equals to current build.
                $tag .= getCommitBuildRows(getPrevBuildVersion($each));

                // get SPRs having Commit build equals to next build.
                $tag .= getCommitBuildRows($each);
            }

            $tag .= '    </tbody>
                    </table>';
            $tag .= '</div>';
        }

        return($tag);
    }

    private function getSubmissionStatusDiv()
    {
        $tag = "";
        $tag .= '<div id="submission-status-container" class="dashboard-table left" style="width:35.7%;">';
        $tag .= '<table style="width:100%;">
                    <thead>
                        <tr>
                            <th colspan="2"><h2 style="text-align:center;">Submission/Port</h2></th>
                        </tr>
                    </thead>
                    <tbody>';
        $submission_status = getSubmissionStatus();
        foreach($submission_status as $each)
        {
            $tag .= '    <tr>
                            <td>
                                <p style="text-align:center;"><strong><a href="'.getSPRLink($each[0], $each[2]).'"
                                            target="_blank">'.$each[0].'</a></strong></p>
                            </td>
                            <td><p style="text-align:center;">'.$each[1].'</p></td>
                        </tr>';
        }
        $tag .= '    </tbody>
                </table>';
        $tag .= '</div>';

        return($tag);
    }

    private function getNextCutOffDiv($nc)
    {
        $tag = "";
        $nearByDateRange = 14;

        if(!empty($nc))
        {
            $tag .= '<div id="nextCutOff-container" class="nextCutOff left">';
            $tag .='    <div>
                            <div id="build-status-container">
                                <h2 style="text-align: center;"><strong>Build Status</strong></h2>
                                <hr>';

            foreach($nc as $each)
                $tag .='         <p style="text-align: center;"><strong>'.$each[0].'</strong> :
                                    <label style="color: '.((isNearByDate($each[1], $nearByDateRange) == true) ? "red" : "black").';">'.((isBuildReleased($each[1]) == true) ? "<strong>Releashed</strong>" : $each[1]).'<label></p>';

            $tag .='        </div>';
            $tag .='    </div>
                    </div>';
        }

        return($tag);
    }

    private function getPMShortDesc()
    {
        return('<div id="main-article-desc-container" style="align:center; padding-top:20px;">
                    <label id="session-label" style="font-size:15px">
                        <strong id="desc-strong">Project Management</strong> Tool help user to organize
                        SPRs(those are assinging to them). </br> It will help to tack SPR status, submission status and many other facilites.
                        It also track user\'s daily work. It will generate weekly, monthly work reports.
                        It also track your Scrum activities.
                    </label>
                </div>
                <br>
                <hr>');
    }

    private function getArticleContainer()
    {
        $imagesPath = "images";

        $sprTrackingDashboardURL = "spr_tracking/dashboard.php";
        $sprTrackingSubmitStatusURL = "spr_tracking/submit_status.php";
        $workTrackerDashboardURL = "work_tracker/dashboard.php";

        $tag  = '<div id="article-container" class="float-division" style="width:';
        $tag .='%; height: 100%; margin-top: 10px;">
                    <div id="spr-tracking-article-container" class="float-division" style="width: 50%;">
                        <a href="'. $sprTrackingDashboardURL .'">
                            <img src="'. $imagesPath .'/spr_tracking_screen_.png" alt="SPR Tracking"
                                style="border:0;" height="150px" />
                        </a>
                        <h4 style="font-size: 15px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="'. $sprTrackingDashboardURL .'" style="text-decoration:none; color: #000">SPR Tacking</a>
                        </h4>
                        <p style="font-size: 13px;">Help user to manage SPRs those are assign to them.</p>
                    </div>
                    <div id="spr-submission-article-container" class="float-division" style="width: 45%;">
                        <a href="'.$sprTrackingSubmitStatusURL.'">
                            <img src="'. $imagesPath .'/spr_submission_status_screen_.png" alt="SPR Submission Status"
                                    style="border:0;" height="150px" />
                        </a>
                        <h4 style="font-size: 15px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="'.$sprTrackingSubmitStatusURL .'" style="text-decoration:none; color: #000">SPR Submission Status</a>
                        </h4>
                        <p style="font-size: 13px;">Help user to manage Submission status of SPRs.</p>
                    </div>
                    <div id="work-tracker-article-container" class="float-division" style="width: 45%;">
                        <a href="'. $workTrackerDashboardURL .'">
                            <img src="'. $imagesPath .'/work_tracker_screen_.png" alt="Work Tracker"
                                    style="border:0;" height="150px" />
                        </a>
                        <h4 style="font-size: 15px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo $workTrackerDashboardURL ?>" style="text-decoration:none; color: #000">Work Tracker</a>
                        </h4>
                        <p style="font-size: 13px;">
                            Help user to track their daily work.
                            It will help them to understand how much time they spend for an SPR.
                            This will help them in future for project planning purpose.
                        </p>
                    </div>
                </div>';

        return(utf8_encode($tag));
    }
}

class SPRTrackingHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("SPR Tracking-Dashboard", "spr_tracking", true);
    }

    protected function addDashboard()
    {
        $tag = '<div class="main-article-nav-container display-table-row">' . $this->EOF_LINE;
        $tag .= '    <ul class="float-box-nav main-article-nav">' . $this->EOF_LINE;
        $tag .= '        <li><a class="selected-tab" href="#" target="_top">Dashboard</a></li>' . $this->EOF_LINE;
        $tag .= '        <li><a href="#" target="_top">Submission Status</a></li>' . $this->EOF_LINE;
        $tag .= '        <li><a href="#" target="_top">Report</a></li>' . $this->EOF_LINE;
        $tag .= '        <li><a href="#" target="_top">Import</a></li>' . $this->EOF_LINE;
        $tag .= '    </ul>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;
        $tag .= '<div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
        $tag .= '    <div class="main-article-tab-info-container">' . $this->EOF_LINE;
        $tag .= '        <div class="main-article-info-header">' . $this->EOF_LINE;
        $tag .= '            <div class="header-tag">' . $this->EOF_LINE;
        $tag .= '                <h1>SPR Tracking Dashboard</h1>' . $this->EOF_LINE;
        $tag .= '            </div>' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= '        <div class="article-container">' . $this->EOF_LINE;
        $tag .= '            <div class="project-backlog-container">' . $this->EOF_LINE;
        $tag .= '                <div class="spr-tracking-menu-container">' . $this->EOF_LINE;
        $tag .= '                    <div id="session-btn" class="session-btn">' . $this->EOF_LINE;
        $tag .= '                        <select id="session-select" class="retro-style session-select" onchange="javascript:showDashboardAccdSession(\'spr-tracking-dashboard-tbody\', \'fillSPRTrackingDashboardRow\')">' . $this->EOF_LINE;
        $tag .= '                            <option value="All">All</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2017" selected="">2017</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2016">2016</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2015">2015</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2014">2014</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2013">2013</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2012">2012</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2011">2011</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2010">2010</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2009">2009</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2008">2008</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2007">2007</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2006">2006</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2005">2005</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2004">2004</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2003">2003</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2002">2002</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2001">2001</option>' . $this->EOF_LINE;
        $tag .= '                            <option value="2000">2000</option>' . $this->EOF_LINE;
        $tag .= '                        </select>' . $this->EOF_LINE;
        $tag .= '                    </div>' . $this->EOF_LINE;
        $tag .= '                    <div style="float: right; margin-right: 10px;">' . $this->EOF_LINE;
        $tag .= '                        <button class="retro-style green add-spr" type="button">' . $this->EOF_LINE;
        $tag .= '                            <span>Add SPR to Track</span>' . $this->EOF_LINE;
        $tag .= '                        </button>' . $this->EOF_LINE;
        $tag .= '                        <button class="retro-style red" type="button">' . $this->EOF_LINE;
        $tag .= '                            <span>Delete SPR(s)</span>' . $this->EOF_LINE;
        $tag .= '                        </button>' . $this->EOF_LINE;
        $tag .= '                    </div>' . $this->EOF_LINE;
        $tag .= '                </div>' . $this->EOF_LINE;
        $tag .= '                <div id="spr-tracking-table-dropdown" class="dropdown-content">' . $this->EOF_LINE;
        $tag .= '                    <a href="#">Save</a>' . $this->EOF_LINE;
        $tag .= '                    <a href="#">Cancel</a>' . $this->EOF_LINE;
        $tag .= '                </div>' . $this->EOF_LINE;
        $tag .= '                <div class="spr-tracking-table-container">' . $this->EOF_LINE;

        $qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session  FROM `spr_tracking` WHERE session='2017'";
        $tag .= $this->createDasboardTable($qry);

        $tag .= '                </div>' . $this->EOF_LINE;
        $tag .= '            </div>' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= '    </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        //return(showSPRTrackingDashboard());
        return($tag);
    }

    private function createDasboardTable($qry)
    {
        global $conn;
        $str = "";

        /// SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session FROM `spr_tracking`
        $rows = $conn->result_fetch_array($qry);
        if(!empty($rows))
        {
            $Table = new HTMLTable("spr-tracking-dashboard-table", "grippy-table sprint-taskboard-table");
                // add table header
                $Table->thead("spr-tracking-dashboard-thead");
                    $Table->th("&nbsp;", null, null, null, null);
                    $Table->th("Item number", null, null, null, "data-sort=\"int\"");
                    $Table->th("Type", null,  null, null, "data-sort=\"string\"");
                    $Table->th("Status", null, null, null, "data-sort=\"string\"");
                    $Table->th("Build Version", null, null, null, "data-sort=\"string\"");
                    $Table->th("Commit Build", null, null, null, "data-sort=\"string\"");
                    $Table->th("Respond By", null, null, null, "data-sort=\"string\"");
                    $Table->th("Comment", null, null, null);
                    $Table->th("Session", null, null, null, "data-sort=\"string\"");
                    //$Table->th("&nbsp;", null, null, null, null);

                // add Table body
                $Table->tbody("spr-tracking-dashboard-tbody");
                    // loop over the result and fill the rows
                    foreach($rows as $row)
                    {
                        $Table->tr(null, null, null, "align=\"center\"");
                            $Table->td(getGreppyDotTag(), "{$row[0]}-greppy", "hasGrippy", "text-align:center;");
                            $Table->td("<a href=\"#\">" . getSPRString($row[1], $row[0]) . "</a>", "{$row[0]}-spr-no", null, null, "width=\"12%\"");
                            $Table->td("{$row[1]}", "{$row[0]}-type", null, null, "width=\"10%\"");
                            $Table->td("{$row[2]}", "{$row[0]}-status", null, "background-color:" . getSPRTrackingStatusColor($row[2]) . ";", "width=\"15%\"");
                            $Table->td("{$row[3]}", "{$row[0]}-build-version", null, null, "width=\"12%\"");
                            $Table->td("{$row[4]}", "{$row[0]}-commit-build", null, null, "width=\"12%\"");
                            $Table->td("{$row[5]}", "{$row[0]}-respond-by", null, null, "width=\"12%\"");
                            $Table->td(shortDescription($row[6], 25), "{$row[0]}-comment");
                            $Table->td("{$row[6]}", "{$row[0]}-comment-full", null, "display: none;");
                            $Table->td("{$row[7]}", "{$row[0]}-session", null, null, "width=\"8%\"");
                            //$Table->td(getQuickActionBtn("{$row[0]}-edit-btn", "spr-tracking-tbl-td-btn", "spr-tracking-dashboard-table-dropdown"), "{$row[0]}-edit", null, null, "width=\"2%\"");
                    }

            $str = $Table->toHTML();
        }
        else
        {
            $str = "<p>No result !!!</p>";
        }

        return(utf8_encode($str));
    }
}

class SPRSubmitStatusHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("SPR Tracking-Submit Status", "spr_tracking", true);
    }

    protected function addDashboard()
    {
        return(showSPRTrackingSubmissionStatus());
    }
}

class WorkTrackerHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("Work Tracker", "work_tracker", true);
    }

    protected function addDashboard()
    {
        return(showWorkTrackerDashboard());
    }
}

class ScrumPPBHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("Scrum-Product-Planning-backlog", "scrum", true);
    }

    protected function addDashboard()
    {
        return("");
        //return(showWorkTrackerDashboard());
    }
}

class ScrumSTTaskboardHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("Scrum-Sprint-Tracking-Taskboard", "scrum", true);
    }

    protected function addDashboard()
    {
        return("");
        //return(showWorkTrackerDashboard());
    }
}

?>
