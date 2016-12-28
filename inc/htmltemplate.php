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
require_once ('functions.inc.php');
require_once ('mysql_functions.inc.php');


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
            $tag = '    <div class="wrapper display-table">' . $this->EOF_LINE;
            $tag .= $this->addHeader($this->currentDir, $this->currentNav, $this->enableNav) . $this->EOF_LINE;
            $tag .= $this->addArticle() . $this->EOF_LINE;
            $tag .= $this->addFooter($this->currentDir) . $this->EOF_LINE;
            $tag .= '    </div>' . $this->EOF_LINE;
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
        $tag = $nav->header($currentDir, $selNav, $enableNav);

        return($tag);
    }

    protected function addArticle()
    {
        $tag = "";

        $tag = '<div class="article display-table-row">' . $this->EOF_LINE;
        $tag .= '    <div class="display-table article-container">' . $this->EOF_LINE;
        $tag .= $this->addDashboard() . $this->EOF_LINE;
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
        $tag = "";

        $tag .= '<div class="home-article">' . $this->EOF_LINE;

        if((isset($_SESSION["project-managment-username"])) && ($_SESSION["project-managment-username"] != ""))
        {
            $tag .= $this->getUserInfoContainer();
        }
        else
        {
            $tag .= $this->getProjectManagementInfo();
        }

        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getUserInfoContainer()
    {
        $tag = "";
        $nc = getNextCutOff('config/ptc_info.xml');

        if(!empty($nc))
        {
            $tag .= '   <div class="display-table" style="height: 650px;">' . $this->EOF_LINE;
            $tag .= '       <div class="display-table-cell" style="width: 50%; border-right: 1px solid #d4d4d4;">' . $this->EOF_LINE;
            $tag .= '           <div class="display-table">' . $this->EOF_LINE;

            // get Next Cutoff info.
            $tag .= '               <div id="nextCutOff-container" class="display-table-cell" style="width:12%;">' . $this->EOF_LINE;
            $tag .=                     $this->getNextCutOffDiv($nc);
            $tag .= '               </div>' . $this->EOF_LINE;

            // get upcoming Respond by date (next 2 months).
            $tag .= '               <div id="respondBy-container" class="display-table-cell" style="width: 12%">' . $this->EOF_LINE;
            $tag .=                     $this->getRespondByDiv();
            $tag .= '               </div>' . $this->EOF_LINE;

            // get upcoming Commit Build information.
            $tag .= '               <div id="commit-build-container" class="display-table-cell" style="width:22%;">' . $this->EOF_LINE;
            $tag .= $this->getCommitBuildDiv([$nc[0][0], $nc[1][0], $nc[2][0], $nc[3][0]]);
            $tag .= '               </div>' . $this->EOF_LINE;

            // get Submission Status Information.
            $tag .= '               <div id="submission-status-container" class="display-table-cell" style="width: 15%;">' . $this->EOF_LINE;
            $tag .=                     $this->getSubmissionStatusDiv();
            $tag .= '               </div>' . $this->EOF_LINE;

            $tag .= '           </div>' . $this->EOF_LINE;
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '       <div class="display-table-cell">' . $this->EOF_LINE;
            $tag .= '           <div class="display-table" style="margin-left: 25px;">' . $this->EOF_LINE;
            $tag .= '               <p>#Scrum Section</p>' . $this->EOF_LINE;
            $tag .= '           </div>' . $this->EOF_LINE;
            $tag .= '       </div>' . $this->EOF_LINE;
            $tag .= '    </div>' . $this->EOF_LINE;
        }

        return(utf8_encode($tag));
    }

    private function getRespondByDiv()
    {
        $NearByDate = 20;
        $tag = "";

        $tag .= '<div>' . $this->EOF_LINE;
        $tag .= '       <table>' . $this->EOF_LINE;
        $tag .= '           <thead>' . $this->EOF_LINE;
        $tag .= '               <tr>' . $this->EOF_LINE;
        $tag .= '                   <th colspan="2"><h2 style="text-align:center;">Respond By</h2></th>' . $this->EOF_LINE;
        $tag .= '               </tr>' . $this->EOF_LINE;
        $tag .= '           </thead>' . $this->EOF_LINE;
        $tag .= '           <tbody>' . $this->EOF_LINE;

        $respond_by = $this->getSPRHavingNextRespondBy();
        foreach($respond_by as $each)
        {
            $tag .= '           <tr>' . $this->EOF_LINE;
            $tag .= '               <td>' . $this->EOF_LINE;
            $tag .= '                   <p style="text-align:center; margin-top:4px; margin-bottom:4px;">' . $this->EOF_LINE;
            $tag .= '                       <strong>' . $this->EOF_LINE;
            $tag .= '                           <a href="'.getSPRLink($each[0], $each[2]).'" target="_blank">'.$each[0].'</a>' . $this->EOF_LINE;
            $tag .= '                       </strong>' . $this->EOF_LINE;
            $tag .= '                   </p>' . $this->EOF_LINE;
            $tag .= '               </td>' . $this->EOF_LINE;
            $tag .= '               <td class="td-border">' . $this->EOF_LINE;
            $tag .= '                   <p style="text-align:center; color: '.((isNearByDate($each[1], $NearByDate) == true) ? "red" : "black").'">'.$each[1].'</p>' . $this->EOF_LINE;
            $tag .= '               </td>' . $this->EOF_LINE;
            $tag .= '           </tr>' . $this->EOF_LINE;
        }

        $tag .= '           </tbody>' . $this->EOF_LINE;
        $tag .= '       </table>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

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
            $tag .= '<div>' . $this->EOF_LINE;
            $tag .= '   <table style="width:100%;">' . $this->EOF_LINE;
            $tag .= '       <thead>' . $this->EOF_LINE;
            $tag .= '           <tr>' . $this->EOF_LINE;
            $tag .= '               <th colspan="2"><h2 style="text-align:center;">SPRs having Commit Build</h2></th>' . $this->EOF_LINE;
            $tag .= '           </tr>' . $this->EOF_LINE;
            $tag .= '       </thead>' . $this->EOF_LINE;
            $tag .= '       <tbody>' . $this->EOF_LINE;

            foreach($buildVersions as $each)
            {
                // get SPRs having Commit build equals to current build.
                $tag .= getCommitBuildRows(getPrevBuildVersion($each));

                // get SPRs having Commit build equals to next build.
                $tag .= getCommitBuildRows($each);
            }

            $tag .= '       </tbody>' . $this->EOF_LINE;
            $tag .= '   </table>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
        }

        return($tag);
    }

    private function getSubmissionStatusDiv()
    {
        $tag = "";

        $tag .= '   <div style="margin-right: 25px;">' . $this->EOF_LINE;
        $tag .= '   <table style="width:100%;">' . $this->EOF_LINE;
        $tag .= '       <thead>' . $this->EOF_LINE;
        $tag .= '           <tr>' . $this->EOF_LINE;
        $tag .= '               <th colspan="2"><h2 style="text-align:center;">Submission/Port</h2></th>' . $this->EOF_LINE;
        $tag .= '           </tr>' . $this->EOF_LINE;
        $tag .= '       </thead>' . $this->EOF_LINE;
        $tag .= '       <tbody>' . $this->EOF_LINE;

        $submission_status = getSubmissionStatus();
        foreach($submission_status as $each)
        {
            $tag .= '       <tr>' . $this->EOF_LINE;
            $tag .= '           <td class="td-border">' . $this->EOF_LINE;
            $tag .= '               <p style="text-align:center;">' . $this->EOF_LINE;
            $tag .= '                            <strong>' . $this->EOF_LINE;
            $tag .= '                                <a href="'.getSPRLink($each[0], $each[2]).'" target="_blank">'.$each[0].'</a>' . $this->EOF_LINE;
            $tag .= '                            </strong>' . $this->EOF_LINE;
            $tag .= '                        </p>' . $this->EOF_LINE;
            $tag .= '           </td>' . $this->EOF_LINE;
            $tag .= '           <td class="td-border">' . $this->EOF_LINE;
            $tag .= '               <p style="text-align:center;">'.$each[1].'</p>' . $this->EOF_LINE;
            $tag .= '           </td>' . $this->EOF_LINE;
            $tag .= '       </tr>' . $this->EOF_LINE;
        }

        $tag .= '       </tbody>' . $this->EOF_LINE;
        $tag .= '   </table>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getNextCutOffDiv($nc)
    {
        $tag = "";
        $nearByDateRange = 15;

        if(!empty($nc))
        {
            $tag .= '   <div style="margin-top: 25px; margin-right: 25px; margin-left: 25px;">' . $this->EOF_LINE;
            $tag .= '       <table>' . $this->EOF_LINE;
            $tag .= '           <thead>' . $this->EOF_LINE;
            $tag .= '               <tr>' . $this->EOF_LINE;
            $tag .= '                   <th colspan="2">' . $this->EOF_LINE;
            $tag .= '                       <h2 style="text-align:center;"><strong>Build Status</strong></h2>' . $this->EOF_LINE;
            $tag .= '                   </th>' . $this->EOF_LINE;
            $tag .= '               </tr>' . $this->EOF_LINE;
            $tag .= '           </thead>' . $this->EOF_LINE;
            $tag .= '           <tbody>' . $this->EOF_LINE;

            foreach($nc as $each)
            {
                $tag .= '               <tr>' . $this->EOF_LINE;
                $tag .= '                   <td style="text-align: right;"><p style="margin:5px;"><strong>'.$each[0].'</strong> :</p></td>' . $this->EOF_LINE;
                $tag .= '                   <td style="text-align: left;"><p style="margin:5px;"><label style="color: '.((isNearByDate($each[1], $nearByDateRange) == true) ? "red" : "black").';">'.((isBuildReleased($each[1]) == true) ? "<strong>Releashed</strong>" : $each[1]).'<label></p></td>' . $this->EOF_LINE;
            }

            $tag .= '           </tbody>' . $this->EOF_LINE;
            $tag .= '       </table>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;
        }

        return($tag);
    }

    private function getProjectManagementInfo()
    {
        $tag = '';

        $tag .= '   <div class="display-table">' . $this->EOF_LINE;
        $tag .= '       <div class="display-table-row" style="border-bottom: 1px solid #d4d4d4;">' . $this->EOF_LINE;
        $tag .=             $this->getPMShortDesc();
        $tag .= '       </div>' . $this->EOF_LINE;
        $tag .= '       <div class="display-table-row">' . $this->EOF_LINE;
        $tag .= '           <div class="display-table" style="width: 100%; height: 100%">' . $this->EOF_LINE;
        $tag .= '               <div class="display-table-cell" style="width: 50%;">' . $this->EOF_LINE;
        $tag .=                     $this->getArticleContainer();
        $tag .= '               </div>' . $this->EOF_LINE;
        $tag .= '               <div class="display-table-cell">' . $this->EOF_LINE;
        $tag .=                     $this->getScrumProcessFlowSVG();
        $tag .= '               </div>' . $this->EOF_LINE;
        $tag .= '           </div>' . $this->EOF_LINE;
        $tag .= '       </div>' . $this->EOF_LINE;
        $tag .= '    </div>' . $this->EOF_LINE;

        return(utf8_encode($tag));
    }

    private function getPMShortDesc()
    {
        $tag = '';
        $tag .= '<div id="main-article-desc-container" style="align:center; margin: 25px;">' . $this->EOF_LINE;
        $tag .= '   <label id="session-label" style="font-size:15px">' . $this->EOF_LINE;
        $tag .= '       <strong style="font-size: 22px;">Project Management</strong>' . $this->EOF_LINE;
        $tag .= '                Tool help user to organize SPRs assign to them. </br> It will help to tack SPR status, submission status and many other facilites. It also track user\'s daily work. It will generate weekly, monthly work reports. </br> It also manage <strong id="desc-strong">Scrum Methodology</strong>.' . $this->EOF_LINE;
        $tag .= '   </label>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
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

    private function getScrumProcessFlowSVG()
    {
        $tag = '<div id="scrum-process-flow-svg-container" style="width:400px; height:400px;">
            <svg id="scrum-process-flow-svg" x="0px" y="0px" viewBox="0 0 593.9 593.9" enable-background="new 0 0 593.9 593.9" xml:space="preserve">
                <g id="Layer_8">
                    <g id="Layer_1">
                        <g id="Layer_2"></g>
                        <g id="XMLID_10_">
                            <path id="XMLID_11_" fill="#CBDB7C" d="M296.7,58.5c131.2,0,237.7,106,238.5,237h58.3c-0.8-163-133.2-295-296.4-295
                            c-0.2,0-0.5,0-0.7,0v58C296.4,58.5,296.5,58.5,296.7,58.5z"></path>
                            <path id="XMLID_12_" fill="#8ED3D4" d="M296.2,58.5v-58C133.3,0.9,1.3,132.7,0.5,295.5h57.7C59,164.6,165.2,58.7,296.2,58.5z"></path>
                            <path id="XMLID_13_" fill="#F4D173" d="M535.1,295.5c0,0.5,0,1,0,1.5c0,131.7-106.8,238.5-238.5,238.5c-0.1,0-0.3,0-0.4,0v58
                            c0.2,0,0.5,0,0.7,0c163.7,0,296.5-132.7,296.5-296.5c0-0.5,0-1,0-1.5H535.1z"></path>
                            <path id="XMLID_14_" fill="#E88272" d="M58.2,297c0-0.5,0-1,0-1.5H0.5c0,0.5,0,1,0,1.5c0,163.5,132.3,296.1,295.7,296.5v-58
                            C164.7,535.2,58.2,428.5,58.2,297z"></path>
                            <g id="XMLID_469_">
                                <path id="XMLID_16_" fill="#FFFFFF" d="M232.6,297c0-0.5,0-1,0-1.5H58.2c0,0.5,0,1,0,1.5c0,131.6,106.6,238.3,238.1,238.5V361.3
                                C261,360.9,232.6,332.3,232.6,297z"></path>
                                <path id="XMLID_466_" fill="#E6E6E6" d="M296.7,536l-0.5,0c-63.7-0.1-123.7-25-168.7-70.1C82.5,420.7,57.7,360.7,57.7,297
                                c0-0.3,0-0.6,0-0.8l0-1.2h175.4l0,1.1c0,0.3,0,0.6,0,0.9c0,34.8,28.3,63.5,63.2,63.9l0.5,0V536z M58.7,296l0,0.2
                                c0,0.3,0,0.5,0,0.8c0,130.8,106.3,237.5,237.1,238V361.8c-35.2-0.6-63.7-29.6-63.7-64.9c0-0.3,0-0.6,0-1l0,0H58.7z"></path>
                            </g>
                            <g id="XMLID_465_">
                                <path id="XMLID_17_" fill="#FFFFFF" d="M296.2,232.6V58.5c-131,0.2-237.3,106.1-238.1,237h174.4
                                C233.4,260.8,261.5,233,296.2,232.6z"></path>
                                <path id="XMLID_462_" fill="#E6E6E6" d="M233.1,296H57.7l0-0.5C58.1,232,83.1,172.4,128,127.6C173,82.8,232.8,58.1,296.2,58
                                l0.5,0v175.1l-0.5,0c-34.6,0.4-62.3,27.8-63.1,62.4L233.1,296z M58.7,295h173.4c0.5-16.7,7.3-32.4,19.2-44.1
                                c11.9-11.8,27.7-18.4,44.4-18.7V59C166,59.5,59.8,165.2,58.7,295z"></path>
                            </g>
                            <g id="XMLID_461_">
                                <path id="XMLID_18_" fill="#FFFFFF" d="M361.3,297c0,35.6-28.8,64.4-64.4,64.4c-0.2,0-0.5,0-0.7,0v174.1c0.1,0,0.3,0,0.4,0
                                c131.7,0,238.5-106.8,238.5-238.5c0-0.5,0-1,0-1.5H361.3C361.3,296,361.3,296.5,361.3,297z"></path>
                                <path id="XMLID_458_" fill="#E6E6E6" d="M296.7,536l-0.9,0V360.8l0.8,0c0.1,0,0.2,0,0.4,0c35.2,0,63.9-28.7,63.9-63.9
                                c0-0.3,0-0.6,0-0.9l0-1.1h174.9l0,1.2c0,0.3,0,0.6,0,0.8c0,63.8-24.9,123.9-70,169C420.5,511.1,360.5,536,296.7,536z
                                 M296.7,361.8V535c131.2,0,237.9-106.8,237.9-238c0-0.3,0-0.5,0-0.8l0-0.2H361.8v0c0,0.3,0,0.6,0,1c0,35.8-29.1,64.9-64.9,64.9
                                C296.9,361.8,296.8,361.8,296.7,361.8z"></path>
                            </g>
                            <g id="XMLID_457_">
                                <path id="XMLID_23_" fill="#FFFFFF" d="M296.7,58.5c-0.1,0-0.3,0-0.4,0v174.1c0.2,0,0.5,0,0.7,0c35.1,0,63.5,28,64.3,62.9h173.8
                                C534.3,164.4,427.9,58.5,296.7,58.5z"></path>
                                <path id="XMLID_104_" fill="#E6E6E6" d="M535.6,296H360.8l0-0.5c-0.8-34.4-29.4-62.4-63.8-62.4c-0.1,0-0.3,0-0.4,0l-0.8,0V58
                                l0.9,0c63.6,0,123.4,24.7,168.5,69.5c45.1,44.8,70.1,104.5,70.5,168L535.6,296z M361.8,295h172.9C533.6,164.8,427,59,296.7,59
                                v173.1c0.1,0,0.1,0,0.2,0c17.1,0,33.2,6.6,45.4,18.5C354.4,262.4,361.3,278.1,361.8,295z"></path>
                            </g>
                            <path id="XMLID_21_" fill="#FFFFFF" stroke="#6D6E71" stroke-miterlimit="10" d="M296.2,361.3"></path>
                            <path id="XMLID_22_" fill="#FFFFFF" stroke="#6D6E71" stroke-miterlimit="10" d="M232.6,295.5"></path>
                            <path id="XMLID_3_" fill="#FFFFFF" stroke="#6D6E71" stroke-miterlimit="10" d="M296.2,295.5"></path>
                            <g id="XMLID_103_">
                                <path id="XMLID_6_" fill="#FFFFFF" d="M297,232.6c-0.2,0-0.5,0-0.7,0"></path>
                                <path id="XMLID_29_" fill="#6D6E71" d="M296.3,233.1l0-1l0.3,0c0.1,0,0.3,0,0.4,0v1c-0.1,0-0.3,0-0.4,0L296.3,233.1z"></path>
                            </g>
                            <path id="XMLID_5_" fill="#FFFFFF" stroke="#6D6E71" stroke-miterlimit="10" d="M361.3,295.5"></path>
                            <path id="XMLID_20_" fill="#FFFFFF" stroke="#6D6E71" stroke-miterlimit="10" d="M296.2,295.5"></path>
                        </g>
                        <g id="XMLID_368_">
                            <path id="XMLID_369_" fill="#414042" d="M528.8,418.8l0.5,1.5c-1.4,0.2-2.8,1.1-3.6,2.6c-1,1.8-0.7,3.4,0.7,4.2
                            c1.6,0.9,2.9,0,4-1.9c0.3-0.5,0.6-1.1,0.7-1.3l1.4,0.8c-0.1,0.2-0.5,0.8-0.7,1.3c-0.9,1.6-1,3.3,0.5,4.1c1.4,0.8,2.9-0.1,3.7-1.6
                            c0.8-1.4,0.8-2.7,0.3-4.2l1.5-0.3c0.6,1.5,0.6,3.4-0.4,5.4c-1.3,2.4-3.6,3.6-5.7,2.4c-1.8-1-1.9-3-1.4-4.3
                            c-0.7,1.1-2.7,2.3-4.7,1.2c-2.1-1.2-2.7-3.7-1.1-6.5C525.4,420,527.2,419,528.8,418.8z"></path>
                            <path id="XMLID_371_" fill="#414042" d="M521.6,429.2c0.6,0.3,0.7,1.1,0.4,1.6c-0.3,0.6-1.1,0.7-1.6,0.4
                            c-0.6-0.3-0.7-1.1-0.4-1.6C520.3,429.1,521.1,428.9,521.6,429.2z"></path>
                            <path id="XMLID_373_" fill="#414042" d="M517.3,438.3l0.5,1.6c-1.4,0.2-3.1,0.9-4.2,2.6c-1.4,2.1-0.8,3.6,0.2,4.2
                            c1.4,1,2.8-0.2,4.3-1.5c1.8-1.6,3.8-3.3,6.1-1.8c2,1.3,2.1,4,0.5,6.3c-1.2,1.8-2.8,2.8-4.5,3.1l-0.5-1.6c1.6-0.2,2.9-1.2,3.8-2.5
                            c1-1.4,0.9-3-0.2-3.7c-1.2-0.8-2.5,0.3-3.9,1.6c-1.8,1.6-3.9,3.4-6.4,1.7c-1.7-1.2-2.7-3.5-0.5-6.8
                            C513.7,439.5,515.6,438.5,517.3,438.3z"></path>
                            <path id="XMLID_375_" fill="#414042" d="M508.5,450.3L504,447l1-1.3l11.5,8.6l-1,1.3l-1.2-0.9c0.4,1.2,0.2,2.7-0.7,3.9
                            c-1.6,2.2-4.4,2.5-7.1,0.5c-2.7-2-3.3-4.8-1.6-7C505.7,450.9,507,450.3,508.5,450.3z M507.5,457.7c1.8,1.4,4,1.4,5.2-0.3
                            c0.8-1,0.9-2.5,0.5-3.5l-3.8-2.8c-1.1-0.1-2.4,0.5-3.2,1.5C505,454.2,505.7,456.3,507.5,457.7z"></path>
                            <path id="XMLID_378_" fill="#414042" d="M500.8,457.8l8.1,6.5l-1,1.3l-1.3-1.1c0.3,1.3,0.2,2.8-0.7,3.9l-1.3-1
                            c0.2-0.1,0.3-0.3,0.5-0.5c0.6-0.8,0.8-2.3,0.5-3.2l-5.7-4.6L500.8,457.8z"></path>
                            <path id="XMLID_380_" fill="#414042" d="M496.3,463.2l8,6.7l-1,1.2l-8-6.7L496.3,463.2z M506.4,471.4c0.5,0.4,0.5,1.1,0.1,1.5
                            c-0.4,0.5-1.1,0.5-1.5,0.1c-0.5-0.4-0.5-1.1-0.1-1.5C505.3,471.1,506,471,506.4,471.4z"></path>
                            <path id="XMLID_383_" fill="#414042" d="M488.6,472.1l5.1,4.5c1.4,1.2,2.4,0.9,3.3-0.1c0.8-0.9,1.1-2.4,0.9-3.4l-5.7-5.1l1.1-1.2
                            l7.7,7l-1.1,1.2l-1.1-1c0.2,1.1-0.1,2.8-1.2,3.9c-1.5,1.6-3,1.7-4.7,0.2l-5.4-4.9L488.6,472.1z"></path>
                            <path id="XMLID_385_" fill="#414042" d="M486.7,477.3l4.9,4.7l1.2-1.2l1,1l-1.2,1.2l2,2l-1.1,1.2l-2-2l-1.5,1.5l-1-1l1.5-1.5
                            l-4.7-4.5c-0.6-0.5-1.2-0.7-1.7-0.2c-0.3,0.3-0.5,0.8-0.5,1.1l-1.2-0.5c0-0.6,0.2-1.2,0.9-1.9
                            C484.4,476.1,485.6,476.2,486.7,477.3z"></path>
                            <path id="XMLID_387_" fill="#414042" d="M477.1,483.9l9.8,10.5l-4.2,3.9c-2.1,2-4.6,1.6-6.2-0.1c-1.6-1.7-1.7-4.3,0.4-6.2
                            l2.9-2.7l-3.9-4.2L477.1,483.9z M477.7,497c1.1,1.2,2.7,1.2,4,0.1l2.8-2.6l-3.7-4L478,493C476.7,494.2,476.6,495.8,477.7,497z"></path>
                            <path id="XMLID_390_" fill="#414042" d="M467.9,492.3l9.4,10.9l-1.2,1.1l-9.4-10.9L467.9,492.3z"></path>
                            <path id="XMLID_392_" fill="#414042" d="M459.3,499.5l0.7,0.9c0.1-1.3,0.7-2.4,1.8-3.3c1.4-1.1,3.6-1.4,5,0.4
                            c1.5,1.8,0.7,3.8-0.7,5c-1.1,0.9-2.3,1.3-3.6,1.1l1.2,1.5c0.9,1.1,2.3,1,3.4,0c1-0.8,1.5-1.8,1.6-3.1l1.3,0.4
                            c-0.1,1.6-0.8,2.9-2.1,4c-1.7,1.4-3.8,1.8-5.3-0.2l-4.5-5.5L459.3,499.5z M460.6,501.2l1.2,1.5c1,0.3,2.2-0.1,3-0.8
                            c1.2-1,1.4-2.4,0.5-3.4c-0.8-1-2.2-1.1-3.4-0.1C461.2,499.1,460.6,500.1,460.6,501.2z"></path>
                            <path id="XMLID_395_" fill="#414042" d="M450,506.6l4.1,5.5c1.1,1.5,2.2,1.4,3.3,0.5c1-0.8,1.5-2.1,1.5-3.1l-4.5-6.1l1.3-1
                            l6.2,8.3l-1.3,1l-0.9-1.2c-0.1,1.1-0.7,2.7-1.9,3.6c-1.7,1.3-3.3,1.1-4.7-0.7l-4.4-5.9L450,506.6z"></path>
                            <path id="XMLID_397_" fill="#414042" d="M440.3,513.3l3.8,5.6c1,1.5,2.1,1.5,3.3,0.7c1.1-0.7,1.6-2,1.6-3.1l-4.3-6.3l1.3-0.9
                            l5.8,8.6l-1.3,0.9l-0.8-1.2c-0.1,1.1-0.8,2.7-2.1,3.5c-1.8,1.2-3.4,0.9-4.6-0.9l-4.1-6.1L440.3,513.3z"></path>
                            <path id="XMLID_399_" fill="#414042" d="M436.3,515.8l5.6,8.8l-1.4,0.9l-5.6-8.8L436.3,515.8z M443.5,526.6
                            c0.3,0.5,0.2,1.2-0.3,1.5c-0.5,0.3-1.2,0.2-1.5-0.3c-0.3-0.5-0.2-1.2,0.4-1.5C442.5,526,443.2,526.1,443.5,526.6z"></path>
                            <path id="XMLID_402_" fill="#414042" d="M426.3,521.9l3.4,5.9c0.9,1.6,2,1.6,3.2,0.9c1.1-0.6,1.7-1.9,1.8-3l-3.9-6.6l1.4-0.8
                            l5.3,9l-1.4,0.8l-0.8-1.3c-0.2,1.1-1,2.6-2.3,3.4c-1.9,1.1-3.4,0.7-4.6-1.2l-3.7-6.3L426.3,521.9z"></path>
                            <path id="XMLID_404_" fill="#414042" d="M421,521.8l-0.2,1.4c-1.2-0.5-2.3-0.4-3.7,0.3c-1.5,0.8-2.6,2.3-1.5,4.3l0.7,1.3
                            c0.2-1.3,0.9-2.6,2.2-3.3c2.4-1.3,5-0.4,6.6,2.6c1.6,3,0.8,5.7-1.6,6.9c-1.2,0.7-2.7,0.6-3.9,0l0.7,1.4l-1.4,0.8l-4.7-8.9
                            c-1.6-3.1,0-5.1,2.3-6.3C418.1,521.6,419.3,521.3,421,521.8z M416.9,530.4l2.1,4c0.9,0.5,2.4,0.6,3.5,0c1.8-1,2.2-3,1.1-5.1
                            c-1.1-2-3-2.9-4.8-2C417.8,528,417,529.3,416.9,530.4z"></path>
                            <path id="XMLID_407_" fill="#414042" d="M168.7,521.9l-1.8,3.2l5.9,3.4l-0.7,1.3l-9.9,5l-2.1-1.2l4.5-8l-1.8-1l0.8-1.4l1.8,1
                            l1.8-3.2L168.7,521.9z M162.5,532.9l8.1-4l-4.4-2.5L162.5,532.9z"></path>
                            <path id="XMLID_410_" fill="#414042" d="M163.3,519.8c-0.3,0.6-1.1,0.7-1.6,0.4c-0.6-0.3-0.7-1.1-0.4-1.6
                            c0.3-0.6,1.1-0.7,1.6-0.4C163.5,518.5,163.7,519.3,163.3,519.8z"></path>
                            <path id="XMLID_412_" fill="#414042" d="M154.3,515.5l-1.6,0.5c-0.2-1.4-0.9-3.1-2.6-4.2c-2.1-1.5-3.6-0.8-4.2,0.1
                            c-1,1.4,0.2,2.8,1.5,4.3c1.6,1.8,3.3,3.8,1.7,6.1c-1.3,2-4,2.1-6.3,0.5c-1.8-1.2-2.8-2.8-3.1-4.5l1.6-0.5
                            c0.2,1.6,1.2,2.9,2.5,3.8c1.4,1,3,0.9,3.7-0.2c0.8-1.2-0.3-2.5-1.5-3.9c-1.6-1.8-3.4-4-1.7-6.4c1.2-1.7,3.6-2.7,6.8-0.5
                            C153.1,511.9,154.1,513.7,154.3,515.5z"></path>
                            <path id="XMLID_414_" fill="#414042" d="M142.4,506.5l3.3-4.4l1.3,1l-8.7,11.4l-1.3-1l0.9-1.2c-1.2,0.4-2.7,0.2-3.9-0.7
                            c-2.2-1.7-2.4-4.4-0.4-7.1c2-2.7,4.8-3.2,7-1.6C141.8,503.8,142.4,505.1,142.4,506.5z M135,505.5c-1.4,1.8-1.4,3.9,0.2,5.2
                            c1,0.8,2.5,0.9,3.5,0.6l2.8-3.7c0.1-1.1-0.4-2.4-1.5-3.2C138.5,503.1,136.4,503.7,135,505.5z"></path>
                            <path id="XMLID_417_" fill="#414042" d="M135,498.8l-6.6,8.1l-1.3-1l1.1-1.3c-1.3,0.3-2.8,0.2-3.9-0.7l1.1-1.3
                            c0.1,0.2,0.3,0.3,0.5,0.5c0.8,0.6,2.3,0.9,3.2,0.5l4.7-5.7L135,498.8z"></path>
                            <path id="XMLID_419_" fill="#414042" d="M121.3,504.3c-0.4,0.5-1.1,0.5-1.5,0.1c-0.5-0.4-0.5-1.1-0.1-1.5
                            c0.4-0.5,1.1-0.5,1.5-0.1C121.6,503.2,121.7,503.9,121.3,504.3z M129.5,494.3l-6.8,7.9l-1.2-1l6.8-7.9L129.5,494.3z"></path>
                            <path id="XMLID_422_" fill="#414042" d="M120.8,486.5l-4.6,5c-1.2,1.4-0.9,2.4,0.1,3.4c0.9,0.9,2.4,1.1,3.4,0.9l5.1-5.6l1.2,1.1
                            l-7,7.7l-1.2-1.1l1-1.1c-1.1,0.1-2.8-0.1-3.9-1.2c-1.6-1.5-1.7-3-0.2-4.7l4.9-5.4L120.8,486.5z"></path>
                            <path id="XMLID_424_" fill="#414042" d="M115.6,484.6l-4.8,4.9l1.2,1.2l-1,1l-1.2-1.2l-2,2l-1.2-1.1l2-2l-1.5-1.5l1-1l1.5,1.5
                            l4.5-4.6c0.5-0.6,0.7-1.2,0.2-1.7c-0.3-0.3-0.8-0.5-1.1-0.5l0.5-1.2c0.6,0,1.2,0.2,1.9,0.9C116.7,482.3,116.7,483.4,115.6,484.6z
                            "></path>
                            <path id="XMLID_426_" fill="#414042" d="M106.6,472.3l-9.4,8.6l3.1,3.4l-1.2,1.1l-7.4-8.1l1.2-1.1l3.1,3.4l9.4-8.6L106.6,472.3z"></path>
                            <path id="XMLID_428_" fill="#414042" d="M102.1,467.3l-7.9,6.8l-1.1-1.2l1.3-1.1c-1.4,0.1-2.8-0.3-3.7-1.4l1.3-1.1
                            c0.1,0.2,0.2,0.3,0.4,0.5c0.6,0.8,2.2,1.2,3.1,1.1l5.6-4.8L102.1,467.3z"></path>
                            <path id="XMLID_430_" fill="#414042" d="M93.5,456.9l-0.9,0.7c1.3,0.1,2.4,0.7,3.3,1.8c1.1,1.4,1.4,3.6-0.4,5
                            c-1.8,1.5-3.8,0.7-4.9-0.7c-0.9-1.1-1.3-2.3-1-3.6l-1.5,1.2c-1.1,0.9-1,2.2-0.1,3.4c0.8,1,1.8,1.5,3.1,1.6l-0.4,1.3
                            c-1.6-0.1-2.9-0.8-3.9-2.2c-1.4-1.7-1.7-3.8,0.2-5.3l5.6-4.5L93.5,456.9z M91.8,458.3l-1.5,1.2c-0.3,1,0.1,2.2,0.8,3
                            c0.9,1.2,2.3,1.4,3.4,0.6c1-0.8,1.1-2.2,0.2-3.4C93.9,458.8,92.9,458.2,91.8,458.3z"></path>
                            <path id="XMLID_433_" fill="#414042" d="M86.8,456.8c-2.5,1.8-5.6,1.5-7.5-1c-1.1-1.5-1.2-2.9-0.8-4.1l1.4,0.3
                            c-0.4,1.1-0.2,2.1,0.5,2.9c1.3,1.8,3.5,1.9,5.4,0.5c1.9-1.4,2.4-3.5,1.1-5.3c-0.6-0.9-1.5-1.3-2.7-1.3l0.2-1.5
                            c1.3,0,2.6,0.5,3.7,2C90,451.8,89.3,454.9,86.8,456.8z"></path>
                            <path id="XMLID_435_" fill="#414042" d="M80.5,438.8l-1.8,5.7l2.4,0.5l2.5-1.7l0.9,1.3l-11.9,8l-0.9-1.3l7.9-5.3l-7.7-1.4
                            l-1.1-1.7l6.5,1.2l2.1-7L80.5,438.8z"></path>
                            <path id="XMLID_437_" fill="#414042" d="M67.6,442.7c-0.5,0.3-1.2,0.2-1.5-0.3c-0.3-0.5-0.2-1.2,0.3-1.5c0.5-0.3,1.2-0.2,1.5,0.4
                            C68.2,441.7,68.1,442.4,67.6,442.7z M78.4,435.5l-8.8,5.5l-0.9-1.4l8.8-5.5L78.4,435.5z"></path>
                            <path id="XMLID_440_" fill="#414042" d="M72.3,425.5l-5.9,3.4c-1.6,0.9-1.6,2-0.9,3.2c0.6,1.1,1.9,1.7,3,1.8l6.6-3.8l0.8,1.4
                            l-9,5.3l-0.8-1.4l1.3-0.8c-1.1-0.2-2.6-1-3.4-2.3c-1.1-1.9-0.7-3.4,1.2-4.6l6.3-3.7L72.3,425.5z"></path>
                            <path id="XMLID_442_" fill="#414042" d="M72.4,420.2l-1.4-0.2c0.5-1.2,0.4-2.3-0.3-3.7c-0.8-1.5-2.3-2.6-4.3-1.5l-1.3,0.7
                            c1.3,0.2,2.6,0.9,3.3,2.2c1.3,2.4,0.4,5-2.6,6.6c-3,1.6-5.7,0.8-6.9-1.6c-0.7-1.2-0.6-2.7,0-3.9l-1.4,0.7l-0.8-1.4l8.9-4.7
                            c3.1-1.6,5.1,0,6.3,2.3C72.6,417.3,72.9,418.5,72.4,420.2z M63.9,416.1l-4,2.1c-0.5,0.9-0.6,2.4,0,3.5c1,1.8,3,2.2,5.1,1.1
                            c2-1.1,2.9-3,2-4.8C66.2,416.9,64.9,416.2,63.9,416.1z"></path>
                        </g>
                        <g id="XMLID_283_">
                            <path id="XMLID_284_" fill="#414042" d="M71.5,169.6L61,163.7l1,3.2l-1.5,0.4l-1.5-5l0.8-1.4l12.5,7.1L71.5,169.6z"></path>
                            <path id="XMLID_286_" fill="#414042" d="M73,165c-0.6-0.3-0.7-1.1-0.4-1.6c0.3-0.6,1.1-0.7,1.6-0.4c0.6,0.3,0.7,1.1,0.4,1.6
                            C74.3,165.2,73.5,165.3,73,165z"></path>
                            <path id="XMLID_288_" fill="#414042" d="M79.4,156.4l-11.9-8l3.2-4.8c1.6-2.4,4.2-2.6,6.1-1.3c2,1.3,2.7,3.8,1.1,6.2l-2.2,3.3
                            l4.7,3.2L79.4,156.4z M75.7,143.8c-1.3-0.9-2.9-0.6-3.9,0.9l-2.1,3.1l4.5,3l2.1-3.1C77.4,146.3,77.1,144.7,75.7,143.8z"></path>
                            <path id="XMLID_291_" fill="#414042" d="M86.4,146.1L78,140l0.9-1.3l1.4,1c-0.4-1.3-0.3-2.8,0.5-3.9l1.3,1
                            c-0.1,0.1-0.3,0.3-0.4,0.5c-0.6,0.8-0.7,2.4-0.3,3.3l6,4.3L86.4,146.1z"></path>
                            <path id="XMLID_293_" fill="#414042" d="M86.1,137.7c-2.4-1.9-3.1-4.9-1.1-7.4s5.1-2.5,7.5-0.7c2.4,1.9,3.1,4.9,1.1,7.4
                            S88.4,139.6,86.1,137.7z M91.4,131c-1.7-1.3-3.9-1.5-5.3,0.2c-1.4,1.7-0.6,3.9,1,5.2c1.7,1.3,3.9,1.5,5.3-0.2
                            C93.8,134.4,93,132.3,91.4,131z"></path>
                            <path id="XMLID_296_" fill="#414042" d="M103,125.2l-1.2-1c0.3,1.3,0,2.7-0.9,3.8c-1.8,2.1-4.6,2.2-7.2-0.1
                            c-2.5-2.2-2.9-5-1.1-7.1c0.9-1.1,2.3-1.6,3.7-1.5l-4.2-3.6l1.1-1.2l10.9,9.4L103,125.2z M100.9,123.4l-3.6-3.1
                            c-1.1-0.2-2.5,0.3-3.3,1.3c-1.4,1.6-0.9,3.7,0.9,5.2s3.9,1.7,5.2,0.1C100.9,125.8,101.2,124.4,100.9,123.4z"></path>
                            <path id="XMLID_299_" fill="#414042" d="M111,116.6l-1.1-1c0.1,1.2-0.2,2.8-1.3,3.9c-1.5,1.6-3,1.7-4.7,0.1l-5.3-5l1.1-1.2l5,4.7
                            c1.3,1.3,2.4,1,3.3-0.1c0.9-0.9,1.2-2.3,1-3.3l-5.6-5.3l1.1-1.2l7.6,7.1L111,116.6z"></path>
                            <path id="XMLID_301_" fill="#414042" d="M110.2,109.9c-2.1-2.2-2.3-5.4-0.1-7.5c1.4-1.3,2.7-1.5,4-1.4l-0.1,1.5
                            c-1.2-0.2-2.1,0.1-2.8,0.9c-1.6,1.5-1.4,3.7,0.2,5.4c1.6,1.7,3.8,1.9,5.4,0.4c0.8-0.8,1.1-1.7,1-2.8l1.5,0c0.1,1.3-0.1,2.6-1.5,4
                            C115.5,112.4,112.3,112.2,110.2,109.9z"></path>
                            <path id="XMLID_303_" fill="#414042" d="M120.6,104.2l-4.6-5l-1.3,1.2l-1-1.1l1.3-1.2l-1.9-2.1l1.2-1.1l1.9,2.1l1.6-1.4l1,1.1
                            l-1.6,1.4l4.4,4.8c0.5,0.6,1.2,0.8,1.7,0.3c0.3-0.3,0.5-0.7,0.5-1.1l1.2,0.6c0,0.6-0.3,1.2-1,1.8
                            C122.8,105.4,121.7,105.3,120.6,104.2z"></path>
                            <path id="XMLID_305_" fill="#414042" d="M130.6,98.2L121.6,87l4.5-3.6c2.2-1.8,4.7-1.3,6.2,0.5c1.5,1.8,1.4,4.4-0.8,6.2l-3.1,2.5
                            l3.6,4.4L130.6,98.2z M130.9,85.1c-1-1.3-2.6-1.4-3.9-0.3l-2.9,2.4l3.4,4.2l2.9-2.4C131.7,87.9,131.9,86.3,130.9,85.1z"></path>
                            <path id="XMLID_308_" fill="#414042" d="M140.4,90.4L131.7,79l1.3-1l8.7,11.5L140.4,90.4z"></path>
                            <path id="XMLID_310_" fill="#414042" d="M149.5,83.8l-0.7-1c-0.2,1.3-0.8,2.4-2,3.2c-1.4,1-3.7,1.1-5-0.7c-1.3-1.9-0.5-3.9,1-4.9
                            c1.2-0.8,2.4-1.1,3.6-0.8l-1.1-1.5c-0.8-1.1-2.2-1.1-3.4-0.3c-1,0.7-1.6,1.7-1.8,3l-1.3-0.5c0.2-1.6,1-2.8,2.4-3.8
                            c1.8-1.3,3.9-1.5,5.3,0.5l4.1,5.8L149.5,83.8z M148.2,82l-1.1-1.6c-1-0.3-2.2-0.1-3.1,0.6c-1.2,0.9-1.5,2.3-0.8,3.3
                            c0.8,1.1,2.2,1.2,3.4,0.4C147.5,84.1,148.2,83.1,148.2,82z"></path>
                            <path id="XMLID_313_" fill="#414042" d="M159.2,77.3l-3.7-5.7c-1-1.5-2.1-1.5-3.3-0.8c-1.1,0.7-1.7,2-1.7,3l4.1,6.4l-1.4,0.9
                            l-5.6-8.7l1.4-0.9l0.8,1.3c0.1-1.1,0.8-2.7,2.2-3.5c1.8-1.2,3.4-0.9,4.6,1l4,6.1L159.2,77.3z"></path>
                            <path id="XMLID_315_" fill="#414042" d="M169.3,71.3l-3.4-5.9c-0.9-1.6-2-1.6-3.2-0.9c-1.1,0.6-1.7,1.9-1.8,2.9l3.8,6.6l-1.4,0.8
                            l-5.2-9l1.4-0.8l0.8,1.3c0.2-1.1,1-2.6,2.3-3.4c1.9-1.1,3.4-0.7,4.6,1.2l3.7,6.3L169.3,71.3z"></path>
                            <path id="XMLID_317_" fill="#414042" d="M167,57.7c-0.3-0.5-0.1-1.2,0.4-1.5c0.5-0.3,1.2-0.1,1.5,0.4c0.3,0.5,0.1,1.2-0.5,1.5
                            C167.9,58.4,167.3,58.2,167,57.7z M173.4,69l-5-9.1l1.4-0.8l5,9.1L173.4,69z"></path>
                            <path id="XMLID_320_" fill="#414042" d="M183.8,63.6l-3.1-6.1c-0.8-1.6-1.9-1.7-3.2-1.1c-1.1,0.6-1.9,1.8-2,2.8L179,66l-1.4,0.7
                            l-4.7-9.3l1.4-0.7l0.7,1.3c0.3-1.1,1.1-2.5,2.5-3.2c1.9-1,3.5-0.5,4.5,1.5l3.3,6.5L183.8,63.6z"></path>
                            <path id="XMLID_322_" fill="#414042" d="M189.1,64l0.3-1.4c1.2,0.6,2.3,0.5,3.7-0.1c1.6-0.7,2.7-2.1,1.8-4.2l-0.6-1.4
                            c-0.3,1.3-1.1,2.5-2.4,3.1c-2.5,1.1-5.1,0-6.4-3c-1.4-3.1-0.5-5.7,2-6.8c1.3-0.6,2.7-0.4,3.9,0.3l-0.6-1.4l1.5-0.7l4.1,9.2
                            c1.4,3.2-0.4,5.1-2.7,6.1C192,64.4,190.8,64.6,189.1,64z M193.7,55.7l-1.8-4.2c-0.9-0.6-2.3-0.8-3.5-0.3c-1.9,0.8-2.4,2.9-1.4,5
                            c0.9,2.1,2.8,3.1,4.7,2.3C192.8,58,193.6,56.8,193.7,55.7z"></path>
                            <path id="XMLID_325_" fill="#414042" d="M406.7,61.4l0.6-1.3c6.4-1.4,10-2.3,11.1-4.5c0.8-1.6-0.2-3-1.5-3.7
                            c-1.5-0.8-3.1-0.7-4.3-0.1l-0.5-1.5c1.6-0.8,3.7-0.7,5.5,0.2c2.1,1.1,3.7,3.3,2.5,5.9c-1.3,2.6-5.1,3.7-10.1,4.8l6.3,3.1
                            l-0.7,1.4L406.7,61.4z"></path>
                            <path id="XMLID_327_" fill="#414042" d="M418.4,66.2c0.3-0.6,1-0.8,1.6-0.5c0.6,0.3,0.8,1,0.5,1.6c-0.3,0.6-1,0.8-1.6,0.5
                            C418.4,67.5,418.1,66.8,418.4,66.2z"></path>
                            <path id="XMLID_329_" fill="#414042" d="M434.5,76.5l-0.2-6.8l-2.5-1.5l-3,4.9l-1.5-0.9l7.4-12.3l4.9,3c2.2,1.4,3,3.8,1.6,6
                            c-1.3,2.2-3.6,2.5-5.4,1.6l0.3,7L434.5,76.5z M439.8,67.9c0.8-1.4,0.4-2.9-1-3.8l-3.2-2l-2.8,4.7l3.2,1.9
                            C437.3,69.7,438.9,69.3,439.8,67.9z"></path>
                            <path id="XMLID_332_" fill="#414042" d="M440.2,73.9c1.7-2.5,4.8-3.3,7.3-1.7c2.6,1.7,2.8,4.8,1.1,7.4l-0.2,0.3l-7-4.7
                            c-1,1.7-0.7,3.8,1.1,5.1c1,0.7,2.3,0.9,3.4,0.7l0.1,1.3c-1.4,0.3-2.9,0-4.3-1C439,79.7,438.4,76.6,440.2,73.9z M446.7,73.3
                            c-1.8-1.2-3.8-0.3-4.8,1l5.7,3.8C448.5,76.8,448.7,74.6,446.7,73.3z"></path>
                            <path id="XMLID_335_" fill="#414042" d="M447.7,85.3l8.4-11.7l1.3,0.9L449,86.3L447.7,85.3z"></path>
                            <path id="XMLID_337_" fill="#414042" d="M454.3,83.6c1.8-2.4,5-3,7.4-1.2c2.5,1.9,2.5,5,0.6,7.5l-0.2,0.3l-6.7-5.1
                            c-1.1,1.6-1,3.8,0.8,5.1c0.9,0.7,2.2,1.1,3.3,0.9l0,1.3c-1.5,0.2-2.9-0.2-4.3-1.3C452.7,89.3,452.3,86.2,454.3,83.6z M460.8,83.4
                            c-1.8-1.4-3.8-0.5-4.8,0.7l5.4,4.2C462.4,87,462.7,84.9,460.8,83.4z"></path>
                            <path id="XMLID_340_" fill="#414042" d="M466,99.6l0.8-0.9c-1.3,0.2-2.5-0.2-3.6-1.1c-1.4-1.1-2-3.2-0.6-5c1.5-1.8,3.6-1.4,5-0.3
                            c1.1,0.9,1.7,2,1.7,3.3l1.2-1.4c0.9-1.1,0.6-2.4-0.6-3.4c-1-0.8-2-1.1-3.4-0.9l0.1-1.3c1.6-0.2,3,0.3,4.3,1.4
                            c1.7,1.4,2.5,3.4,0.8,5.3l-4.6,5.5L466,99.6z M467.4,97.9l1.3-1.5c0.1-1.1-0.5-2.1-1.4-2.8c-1.2-1-2.6-0.9-3.4,0.1
                            c-0.8,1-0.6,2.4,0.5,3.4C465.3,97.8,466.4,98.2,467.4,97.9z"></path>
                            <path id="XMLID_343_" fill="#414042" d="M469.8,101.1l1.4-0.3c0,1.1,0.5,2.4,1.5,3.4c1.2,1.1,2.3,1.1,3,0.3
                            c0.8-0.8,0-1.9-0.8-3.1c-1-1.5-2.2-3.2-0.7-4.8c1.1-1.2,3.1-1.3,4.9,0.4c1.3,1.2,1.8,2.5,1.8,3.7l-1.3,0.3c0-1-0.4-2.2-1.4-3.1
                            c-1.1-1-2.2-1-2.8-0.3c-0.7,0.7,0,1.7,0.8,2.9c1.1,1.5,2.3,3.3,0.7,5c-1.2,1.3-3.2,1.4-5.1-0.4
                            C470.6,104,469.9,102.6,469.8,101.1z"></path>
                            <path id="XMLID_345_" fill="#414042" d="M480.1,105.2c2.1-2.1,5.4-2.4,7.5-0.3c2.2,2.2,1.8,5.3-0.4,7.5l-0.3,0.3l-6-5.9
                            c-1.3,1.5-1.5,3.6,0.1,5.2c0.8,0.8,2,1.3,3.2,1.3l-0.2,1.3c-1.5,0-2.9-0.6-4.1-1.8C477.8,110.7,477.8,107.6,480.1,105.2z
                             M486.6,105.9c-1.6-1.6-3.7-1-4.9,0.1l4.9,4.8C487.7,109.7,488.3,107.6,486.6,105.9z"></path>
                            <path id="XMLID_348_" fill="#414042" d="M489.1,121.9l10.8-9.5l3.8,4.3c1.9,2.2,1.5,4.7-0.3,6.2c-1.8,1.6-4.3,1.6-6.2-0.5l-2.6-3
                            l-4.3,3.8L489.1,121.9z M502.2,121.6c1.2-1.1,1.3-2.7,0.2-4l-2.5-2.8l-4.1,3.6l2.5,2.8C499.4,122.5,501,122.6,502.2,121.6z"></path>
                            <path id="XMLID_351_" fill="#414042" d="M497.2,131.3l11.1-9.1l1,1.2l-11.1,9.1L497.2,131.3z"></path>
                            <path id="XMLID_353_" fill="#414042" d="M504.2,140.1l0.9-0.7c-1.3-0.1-2.4-0.7-3.3-1.8c-1.1-1.4-1.3-3.6,0.5-5
                            c1.8-1.4,3.9-0.6,4.9,0.8c0.9,1.1,1.2,2.3,1,3.6l1.5-1.1c1.1-0.9,1.1-2.2,0.1-3.4c-0.8-1-1.7-1.5-3.1-1.6l0.4-1.3
                            c1.6,0.2,2.9,0.9,3.9,2.2c1.3,1.7,1.7,3.8-0.3,5.3l-5.7,4.4L504.2,140.1z M506,138.8l1.6-1.2c0.3-1,0-2.2-0.7-3.1
                            c-0.9-1.2-2.3-1.4-3.4-0.6c-1,0.8-1.1,2.2-0.2,3.4C503.9,138.2,504.9,138.8,506,138.8z"></path>
                            <path id="XMLID_356_" fill="#414042" d="M511.1,149.6l5.6-3.9c1.5-1.1,1.4-2.1,0.6-3.3c-0.7-1-2.1-1.6-3.1-1.6l-6.2,4.4l-0.9-1.3
                            l8.5-6l0.9,1.3l-1.2,0.9c1.1,0.1,2.7,0.7,3.6,2c1.3,1.8,1,3.3-0.8,4.6l-6,4.2L511.1,149.6z"></path>
                            <path id="XMLID_358_" fill="#414042" d="M517.5,159.4l5.7-3.7c1.5-1,1.5-2.1,0.8-3.3c-0.7-1.1-2-1.7-3-1.7l-6.4,4.1l-0.9-1.4
                            l8.8-5.6l0.9,1.4l-1.3,0.8c1.1,0.2,2.7,0.9,3.5,2.2c1.2,1.8,0.9,3.4-1,4.6l-6.2,3.9L517.5,159.4z"></path>
                            <path id="XMLID_360_" fill="#414042" d="M520,163.4l8.9-5.3l0.8,1.4l-8.9,5.3L520,163.4z M531,156.6c0.5-0.3,1.2-0.1,1.5,0.4
                            c0.3,0.5,0.1,1.2-0.4,1.5c-0.5,0.3-1.2,0.1-1.5-0.4C530.3,157.5,530.5,156.9,531,156.6z"></path>
                            <path id="XMLID_363_" fill="#414042" d="M525.8,173.6l6-3.3c1.6-0.9,1.7-2,1-3.2c-0.6-1.1-1.9-1.8-2.9-1.9l-6.7,3.7l-0.8-1.4
                            l9.1-5l0.8,1.4l-1.3,0.7c1.1,0.2,2.6,1,3.3,2.4c1.1,1.9,0.6,3.4-1.3,4.5l-6.4,3.5L525.8,173.6z"></path>
                            <path id="XMLID_365_" fill="#414042" d="M525.6,178.9l1.4,0.2c-0.5,1.2-0.4,2.3,0.2,3.7c0.8,1.5,2.2,2.6,4.3,1.6l1.3-0.7
                            c-1.3-0.2-2.6-1-3.2-2.3c-1.2-2.4-0.2-5,2.8-6.5c3-1.5,5.7-0.7,6.9,1.8c0.6,1.3,0.6,2.7-0.1,3.9l1.4-0.7l0.7,1.4l-9.1,4.5
                            c-3.1,1.5-5.1-0.2-6.3-2.4C525.3,181.8,525.1,180.6,525.6,178.9z M534.1,183.2l4.1-2c0.6-0.9,0.7-2.4,0.1-3.5
                            c-0.9-1.9-3-2.3-5-1.2c-2,1-3,2.9-2.1,4.8C531.7,182.3,533,183.1,534.1,183.2z"></path>
                        </g>
                        <g id="Layer_3">
                            <g id="XMLID_256_">
                                <path id="XMLID_257_" d="M175.2,155.3l-1.3-3.3h-7.3l-1.3,3.3h-2.1l5.9-14.7h2.3l5.9,14.7H175.2z M170.2,142.5l-3.1,7.9h6.2
                                L170.2,142.5z"></path>
                                <path id="XMLID_260_" d="M186.8,155.3v-1.6c-0.8,1.1-2.1,1.8-3.6,1.8c-2.8,0-4.7-2.1-4.7-5.6c0-3.4,1.9-5.6,4.7-5.6
                                c1.4,0,2.7,0.7,3.6,1.9v-5.7h1.6v14.7H186.8z M186.8,152.4v-4.8c-0.6-0.9-1.9-1.7-3.2-1.7c-2.1,0-3.4,1.8-3.4,4.1
                                s1.3,4.1,3.4,4.1C184.9,154.1,186.2,153.4,186.8,152.4z"></path>
                                <path id="XMLID_263_" d="M199.5,155.3v-1.6c-0.8,1.1-2.1,1.8-3.6,1.8c-2.8,0-4.7-2.1-4.7-5.6c0-3.4,1.9-5.6,4.7-5.6
                                c1.4,0,2.7,0.7,3.6,1.9v-5.7h1.6v14.7H199.5z M199.5,152.4v-4.8c-0.6-0.9-1.9-1.7-3.2-1.7c-2.1,0-3.4,1.8-3.4,4.1
                                s1.3,4.1,3.4,4.1C197.6,154.1,198.9,153.4,199.5,152.4z"></path>
                                <path id="XMLID_266_" d="M153.2,179.7l1.1-1.4c1,1.1,2.6,2.1,4.6,2.1c2.6,0,3.5-1.4,3.5-2.5c0-1.8-1.8-2.2-3.7-2.8
                                c-2.4-0.6-5-1.3-5-4.2c0-2.4,2.2-4.1,5-4.1c2.2,0,4,0.7,5.2,2l-1.1,1.3c-1.1-1.2-2.6-1.8-4.2-1.8c-1.8,0-3,0.9-3,2.3
                                c0,1.5,1.7,1.9,3.6,2.4c2.4,0.6,5.1,1.4,5.1,4.5c0,2.1-1.5,4.3-5.4,4.3C156.2,182,154.4,181,153.2,179.7z"></path>
                                <path id="XMLID_268_" d="M167.2,179.5v-7h-1.8v-1.5h1.8v-2.9h1.6v2.9h2.2v1.5h-2.2v6.6c0,0.8,0.4,1.4,1.1,1.4
                                c0.5,0,0.9-0.2,1.1-0.4l0.5,1.2c-0.4,0.4-1,0.7-2,0.7C168,182,167.2,181.1,167.2,179.5z"></path>
                                <path id="XMLID_270_" d="M172.7,176.4c0-3.1,2-5.6,5.3-5.6s5.3,2.5,5.3,5.6s-2,5.6-5.3,5.6S172.7,179.5,172.7,176.4z
                                 M181.5,176.4c0-2.2-1.3-4.1-3.5-4.1c-2.3,0-3.5,1.9-3.5,4.1c0,2.2,1.3,4.1,3.5,4.1C180.2,180.5,181.5,178.6,181.5,176.4z"></path>
                                <path id="XMLID_273_" d="M185.9,181.7v-10.6h1.6v1.7c0.9-1.1,2.1-1.9,3.5-1.9v1.7c-0.2,0-0.4-0.1-0.7-0.1c-1,0-2.4,0.8-2.9,1.7
                                v7.5H185.9z"></path>
                                <path id="XMLID_275_" d="M192.9,168.5c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1c0,0.6-0.5,1.1-1.1,1.1
                                C193.4,169.6,192.9,169.1,192.9,168.5z M193.2,181.7v-10.6h1.6v10.6H193.2z"></path>
                                <path id="XMLID_278_" d="M197.5,176.4c0-3.1,2.2-5.6,5.2-5.6c3.2,0,5.1,2.5,5.1,5.7v0.4h-8.6c0.1,2,1.5,3.7,3.8,3.7
                                c1.2,0,2.4-0.5,3.3-1.3l0.8,1.1c-1.1,1.1-2.5,1.6-4.2,1.6C199.8,182,197.5,179.7,197.5,176.4z M202.7,172.2
                                c-2.3,0-3.4,1.9-3.5,3.5h7C206.2,174.2,205.2,172.2,202.7,172.2z"></path>
                                <path id="XMLID_281_" d="M209.6,180.3l0.9-1.2c0.7,0.8,2,1.5,3.5,1.5c1.6,0,2.5-0.8,2.5-1.8c0-1.2-1.3-1.5-2.7-1.8
                                c-1.8-0.4-3.9-0.9-3.9-3.1c0-1.7,1.4-3.1,4-3.1c1.8,0,3.1,0.7,3.9,1.5l-0.8,1.1c-0.6-0.8-1.8-1.3-3.2-1.3
                                c-1.5,0-2.4,0.7-2.4,1.7c0,1,1.2,1.3,2.6,1.6c1.8,0.4,4,0.9,4,3.3c0,1.8-1.4,3.2-4.2,3.2C212.1,182,210.7,181.5,209.6,180.3z"></path>
                            </g>
                            <g id="XMLID_185_">
                                <path id="XMLID_186_" d="M325.1,150c0-4.5,3.3-7.6,7.5-7.6c2.6,0,4.4,1.3,5.6,2.9l-1.5,0.9c-0.8-1.2-2.4-2.1-4-2.1
                                c-3.2,0-5.7,2.5-5.7,6c0,3.5,2.4,6,5.7,6c1.7,0,3.2-0.9,4-2.1l1.6,0.9c-1.2,1.7-3,2.9-5.6,2.9
                                C328.4,157.6,325.1,154.5,325.1,150z"></path>
                                <path id="XMLID_188_" d="M340.5,157.3v-10.6h1.7v1.7c0.9-1.1,2.1-1.9,3.5-1.9v1.7c-0.2,0-0.4-0.1-0.7-0.1c-1,0-2.4,0.8-2.9,1.7
                                v7.5H340.5z"></path>
                                <path id="XMLID_190_" d="M347.1,152c0-3.1,2.2-5.6,5.2-5.6c3.2,0,5.1,2.5,5.1,5.7v0.4h-8.6c0.1,2,1.5,3.7,3.8,3.7
                                c1.2,0,2.4-0.5,3.3-1.3l0.8,1.1c-1.1,1.1-2.5,1.6-4.2,1.6C349.4,157.6,347.1,155.3,347.1,152z M352.3,147.8
                                c-2.3,0-3.4,1.9-3.5,3.5h7C355.8,149.8,354.8,147.8,352.3,147.8z"></path>
                                <path id="XMLID_193_" d="M366.8,157.3v-1.2c-0.9,1-2.1,1.5-3.5,1.5c-1.8,0-3.7-1.2-3.7-3.5c0-2.4,1.9-3.5,3.7-3.5
                                c1.5,0,2.7,0.5,3.5,1.5v-1.9c0-1.4-1.1-2.2-2.7-2.2c-1.3,0-2.3,0.5-3.3,1.5l-0.8-1.1c1.1-1.2,2.5-1.8,4.2-1.8
                                c2.2,0,4.1,1,4.1,3.6v7.3H366.8z M366.8,155.1v-2c-0.6-0.9-1.8-1.3-2.9-1.3c-1.5,0-2.6,1-2.6,2.3c0,1.3,1.1,2.3,2.6,2.3
                                C365,156.4,366.1,156,366.8,155.1z"></path>
                                <path id="XMLID_196_" d="M372,155.1v-7h-1.8v-1.5h1.8v-2.9h1.7v2.9h2.2v1.5h-2.2v6.6c0,0.8,0.4,1.4,1.1,1.4
                                c0.5,0,0.9-0.2,1.1-0.4l0.5,1.2c-0.4,0.4-1,0.7-2,0.7C372.8,157.6,372,156.7,372,155.1z"></path>
                                <path id="XMLID_198_" d="M377.6,152c0-3.1,2.2-5.6,5.2-5.6c3.2,0,5.1,2.5,5.1,5.7v0.4h-8.6c0.1,2,1.5,3.7,3.8,3.7
                                c1.2,0,2.4-0.5,3.3-1.3l0.8,1.1c-1.1,1.1-2.5,1.6-4.2,1.6C379.8,157.6,377.6,155.3,377.6,152z M382.8,147.8
                                c-2.3,0-3.4,1.9-3.5,3.5h7C386.3,149.8,385.2,147.8,382.8,147.8z"></path>
                                <path id="XMLID_201_" d="M405.8,157.3c-0.4-0.4-1-0.9-1.6-1.5c-1.1,1.1-2.4,1.8-4.2,1.8c-2.5,0-4.6-1.4-4.6-4.1
                                c0-2.3,1.5-3.5,3.2-4.4c-0.7-1.1-1.1-2.3-1.1-3.3c0-2,1.6-3.4,3.7-3.4c1.9,0,3.3,1,3.3,2.9c0,2.2-1.9,3.2-3.7,4.1
                                c0.6,0.8,1.2,1.5,1.7,2.1c0.6,0.7,1.2,1.3,1.7,2c0.8-1.2,1.3-2.5,1.6-3.3l1.4,0.6c-0.5,1.2-1.1,2.6-2,3.8
                                c0.9,0.9,1.8,1.8,2.9,2.8H405.8z M403.2,154.8c-0.8-0.9-1.6-1.7-2-2.2c-0.6-0.7-1.3-1.5-1.8-2.3c-1.2,0.7-2.2,1.6-2.2,3.2
                                c0,1.8,1.4,2.8,2.9,2.8C401.3,156.2,402.4,155.6,403.2,154.8z M400.1,148.4c1.6-0.8,2.9-1.6,2.9-3c0-1.1-0.7-1.7-1.7-1.7
                                c-1.1,0-2,0.9-2,2.1C399.2,146.6,399.6,147.5,400.1,148.4z"></path>
                                <path id="XMLID_205_" d="M416.1,157.3v-14.7h5.9c2.9,0,4.6,2,4.6,4.4s-1.7,4.4-4.6,4.4h-4.1v5.8H416.1z M424.7,147.1
                                c0-1.7-1.2-2.8-2.9-2.8h-3.9v5.6h3.9C423.5,149.9,424.7,148.7,424.7,147.1z"></path>
                                <path id="XMLID_209_" d="M428.9,157.3v-14.7h1.7v14.7H428.9z"></path>
                                <path id="XMLID_211_" d="M440.5,157.3v-1.2c-0.9,1-2.1,1.5-3.5,1.5c-1.8,0-3.7-1.2-3.7-3.5c0-2.4,1.9-3.5,3.7-3.5
                                c1.5,0,2.7,0.5,3.5,1.5v-1.9c0-1.4-1.1-2.2-2.7-2.2c-1.3,0-2.3,0.5-3.3,1.5l-0.8-1.1c1.1-1.2,2.5-1.8,4.2-1.8
                                c2.2,0,4.1,1,4.1,3.6v7.3H440.5z M440.5,155.1v-2c-0.6-0.9-1.8-1.3-2.9-1.3c-1.5,0-2.6,1-2.6,2.3c0,1.3,1.1,2.3,2.6,2.3
                                C438.8,156.4,439.9,156,440.5,155.1z"></path>
                                <path id="XMLID_214_" d="M452.6,157.3v-7c0-1.9-0.9-2.5-2.4-2.5c-1.3,0-2.5,0.8-3.1,1.7v7.8h-1.7v-10.6h1.7v1.5
                                c0.7-0.9,2.2-1.8,3.8-1.8c2.2,0,3.4,1.1,3.4,3.4v7.5H452.6z"></path>
                                <path id="XMLID_216_" d="M364.7,183.7l-3.7-5.8H358v5.8h-1.8v-14.7h5.9c2.7,0,4.6,1.7,4.6,4.4c0,2.6-1.8,4.1-3.8,4.3l4,6H364.7z
                                 M364.8,173.5c0-1.7-1.2-2.8-2.9-2.8H358v5.6h3.9C363.6,176.3,364.8,175.1,364.8,173.5z"></path>
                                <path id="XMLID_219_" d="M368.2,178.4c0-3.1,2.2-5.6,5.2-5.6c3.2,0,5.1,2.5,5.1,5.7v0.4H370c0.1,2,1.5,3.7,3.8,3.7
                                c1.2,0,2.4-0.5,3.3-1.3l0.8,1.1c-1.1,1.1-2.5,1.6-4.2,1.6C370.5,184,368.2,181.7,368.2,178.4z M373.4,174.2
                                c-2.3,0-3.4,1.9-3.5,3.5h7C376.9,176.2,375.8,174.2,373.4,174.2z"></path>
                                <path id="XMLID_222_" d="M381.2,183.7v-14.7h1.7v14.7H381.2z"></path>
                                <path id="XMLID_224_" d="M385.5,178.4c0-3.1,2.2-5.6,5.2-5.6c3.2,0,5.1,2.5,5.1,5.7v0.4h-8.6c0.1,2,1.5,3.7,3.8,3.7
                                c1.2,0,2.4-0.5,3.3-1.3l0.8,1.1c-1.1,1.1-2.5,1.6-4.2,1.6C387.8,184,385.5,181.7,385.5,178.4z M390.8,174.2
                                c-2.3,0-3.4,1.9-3.5,3.5h7C394.2,176.2,393.2,174.2,390.8,174.2z"></path>
                                <path id="XMLID_227_" d="M405.2,183.7v-1.2c-0.9,1-2.1,1.5-3.5,1.5c-1.8,0-3.7-1.2-3.7-3.5c0-2.4,1.9-3.5,3.7-3.5
                                c1.5,0,2.7,0.5,3.5,1.5v-1.9c0-1.4-1.1-2.2-2.7-2.2c-1.3,0-2.3,0.5-3.3,1.5l-0.8-1.1c1.1-1.2,2.5-1.8,4.2-1.8
                                c2.2,0,4.1,1,4.1,3.6v7.3H405.2z M405.2,181.5v-2c-0.6-0.9-1.8-1.3-2.9-1.3c-1.5,0-2.6,1-2.6,2.3c0,1.3,1.1,2.3,2.6,2.3
                                C403.4,182.8,404.6,182.4,405.2,181.5z"></path>
                                <path id="XMLID_251_" d="M409.2,182.3l0.9-1.2c0.7,0.8,2,1.5,3.5,1.5c1.6,0,2.5-0.8,2.5-1.8c0-1.2-1.3-1.5-2.7-1.8
                                c-1.8-0.4-3.9-0.9-3.9-3.1c0-1.7,1.4-3.1,4-3.1c1.8,0,3.1,0.7,3.9,1.5l-0.8,1.1c-0.6-0.8-1.8-1.3-3.2-1.3
                                c-1.5,0-2.4,0.7-2.4,1.7c0,1,1.2,1.3,2.6,1.6c1.8,0.4,4,0.9,4,3.3c0,1.8-1.4,3.2-4.2,3.2C411.8,184,410.3,183.5,409.2,182.3z"></path>
                                <path id="XMLID_253_" d="M419.8,178.4c0-3.1,2.2-5.6,5.2-5.6c3.2,0,5.1,2.5,5.1,5.7v0.4h-8.6c0.1,2,1.5,3.7,3.8,3.7
                                c1.2,0,2.4-0.5,3.3-1.3l0.8,1.1c-1.1,1.1-2.5,1.6-4.2,1.6C422,184,419.8,181.7,419.8,178.4z M425,174.2c-2.3,0-3.4,1.9-3.5,3.5
                                h7C428.5,176.2,427.4,174.2,425,174.2z"></path>
                            </g>
                            <g id="XMLID_140_">
                                <path id="XMLID_141_" d="M139.1,374.7v-13h-4.6V360h11.1v1.6h-4.7v13H139.1z"></path>
                                <path id="XMLID_143_" d="M146.1,374.7v-10.6h1.6v1.7c0.9-1.1,2.1-1.9,3.5-1.9v1.7c-0.2,0-0.4-0.1-0.7-0.1c-1,0-2.4,0.8-2.9,1.7
                                v7.5H146.1z"></path>
                                <path id="XMLID_145_" d="M160,374.7v-1.2c-0.9,1-2.1,1.5-3.5,1.5c-1.8,0-3.7-1.2-3.7-3.5c0-2.4,1.9-3.5,3.7-3.5
                                c1.5,0,2.7,0.5,3.5,1.5v-1.9c0-1.4-1.1-2.2-2.7-2.2c-1.3,0-2.3,0.5-3.3,1.5l-0.8-1.1c1.1-1.2,2.5-1.8,4.2-1.8
                                c2.2,0,4.1,1,4.1,3.6v7.3H160z M160,372.4v-2c-0.6-0.9-1.8-1.3-2.9-1.3c-1.5,0-2.6,1-2.6,2.3c0,1.3,1.1,2.3,2.6,2.3
                                C158.2,373.8,159.3,373.3,160,372.4z"></path>
                                <path id="XMLID_148_" d="M164.3,369.4c0-3.1,2.1-5.6,5.3-5.6c1.9,0,3.1,0.8,3.9,1.8l-1.1,1c-0.7-1-1.6-1.4-2.7-1.4
                                c-2.3,0-3.7,1.7-3.7,4.1s1.4,4.1,3.7,4.1c1.1,0,2-0.4,2.7-1.4l1.1,1c-0.8,1.1-2,1.8-3.9,1.8
                                C166.5,374.9,164.3,372.5,164.3,369.4z"></path>
                                <path id="XMLID_150_" d="M183.1,374.7l-3.8-4.8l-1.8,1.7v3h-1.6V360h1.6v9.7l5.6-5.7h2.1l-4.7,4.8l4.7,5.8H183.1z"></path>
                                <path id="XMLID_152_" d="M192.9,374.7V360h5c4.6,0,7.5,3.2,7.5,7.3c0,4.2-3,7.3-7.5,7.3H192.9z M203.5,367.4
                                c0-3.2-2-5.7-5.6-5.7h-3.2v11.4h3.2C201.5,373.1,203.5,370.5,203.5,367.4z"></path>
                                <path id="XMLID_155_" d="M214.9,374.7v-1.2c-0.9,1-2.1,1.5-3.5,1.5c-1.8,0-3.7-1.2-3.7-3.5c0-2.4,1.9-3.5,3.7-3.5
                                c1.5,0,2.7,0.5,3.5,1.5v-1.9c0-1.4-1.1-2.2-2.7-2.2c-1.3,0-2.3,0.5-3.3,1.5l-0.8-1.1c1.1-1.2,2.5-1.8,4.2-1.8
                                c2.2,0,4.1,1,4.1,3.6v7.3H214.9z M214.9,372.4v-2c-0.6-0.9-1.8-1.3-2.9-1.3c-1.5,0-2.6,1-2.6,2.3c0,1.3,1.1,2.3,2.6,2.3
                                C213.1,373.8,214.2,373.3,214.9,372.4z"></path>
                                <path id="XMLID_158_" d="M219.5,361.4c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1s-0.5,1.1-1.1,1.1
                                C220,362.5,219.5,362.1,219.5,361.4z M219.8,374.7v-10.6h1.6v10.6H219.8z"></path>
                                <path id="XMLID_161_" d="M224.8,374.7V360h1.6v14.7H224.8z"></path>
                                <path id="XMLID_163_" d="M229.4,377.3c0.2,0.1,0.6,0.2,0.9,0.2c0.7,0,1.2-0.2,1.6-1.1l0.7-1.6l-4.4-10.7h1.8l3.5,8.7l3.5-8.7
                                h1.8l-5.3,12.8c-0.6,1.5-1.7,2.1-3.1,2.2c-0.4,0-0.9-0.1-1.2-0.2L229.4,377.3z"></path>
                                <path id="XMLID_165_" d="M148.1,401.1v-14.7h5.9c2.9,0,4.6,2,4.6,4.4s-1.7,4.4-4.6,4.4h-4.1v5.8H148.1z M156.7,390.8
                                c0-1.7-1.2-2.8-2.9-2.8h-3.9v5.6h3.9C155.5,393.6,156.7,392.5,156.7,390.8z"></path>
                                <path id="XMLID_168_" d="M161,401.1v-10.6h1.6v1.7c0.9-1.1,2.1-1.9,3.5-1.9v1.7c-0.2,0-0.4-0.1-0.7-0.1c-1,0-2.4,0.8-2.9,1.7
                                v7.5H161z"></path>
                                <path id="XMLID_170_" d="M167.6,395.8c0-3.1,2-5.6,5.3-5.6s5.3,2.5,5.3,5.6c0,3.1-2,5.6-5.3,5.6S167.6,398.8,167.6,395.8z
                                 M176.4,395.8c0-2.2-1.3-4.1-3.5-4.1c-2.3,0-3.5,1.9-3.5,4.1c0,2.2,1.3,4.1,3.5,4.1C175.1,399.9,176.4,397.9,176.4,395.8z"></path>
                                <path id="XMLID_173_" d="M180.8,403.8l0.8-1.2c0.9,1.1,1.9,1.5,3.5,1.5c1.8,0,3.4-0.9,3.4-3.2v-1.5c-0.8,1.1-2.1,1.9-3.6,1.9
                                c-2.8,0-4.7-2.1-4.7-5.5c0-3.4,1.9-5.5,4.7-5.5c1.4,0,2.7,0.7,3.6,1.9v-1.6h1.6v10.3c0,3.5-2.5,4.6-5.1,4.6
                                C183.3,405.4,182,405,180.8,403.8z M188.5,398v-4.6c-0.6-0.9-1.8-1.7-3.2-1.7c-2.1,0-3.4,1.7-3.4,4c0,2.3,1.3,4,3.4,4
                                C186.6,399.8,187.9,399,188.5,398z"></path>
                                <path id="XMLID_176_" d="M193.4,401.1v-10.6h1.6v1.7c0.9-1.1,2.1-1.9,3.5-1.9v1.7c-0.2,0-0.4-0.1-0.7-0.1c-1,0-2.4,0.8-2.9,1.7
                                v7.5H193.4z"></path>
                                <path id="XMLID_178_" d="M200.1,395.8c0-3.1,2.2-5.6,5.2-5.6c3.2,0,5.1,2.5,5.1,5.7v0.4h-8.6c0.1,2,1.5,3.7,3.8,3.7
                                c1.2,0,2.4-0.5,3.3-1.3l0.8,1.1c-1.1,1.1-2.5,1.6-4.2,1.6C202.3,401.3,200.1,399.1,200.1,395.8z M205.3,391.6
                                c-2.3,0-3.4,1.9-3.5,3.5h7C208.8,393.5,207.7,391.6,205.3,391.6z"></path>
                                <path id="XMLID_181_" d="M212.1,399.7l0.9-1.2c0.7,0.8,2,1.5,3.5,1.5c1.6,0,2.5-0.8,2.5-1.8c0-1.2-1.3-1.5-2.7-1.8
                                c-1.8-0.4-3.9-0.9-3.9-3.1c0-1.7,1.4-3.1,4-3.1c1.8,0,3.1,0.7,3.9,1.5l-0.8,1.1c-0.6-0.8-1.8-1.3-3.2-1.3
                                c-1.5,0-2.4,0.7-2.4,1.7c0,1,1.2,1.3,2.6,1.6c1.8,0.4,4,0.9,4,3.3c0,1.8-1.4,3.2-4.2,3.2C214.7,401.3,213.2,400.8,212.1,399.7z"></path>
                                <path id="XMLID_183_" d="M222.4,399.7l0.9-1.2c0.7,0.8,2,1.5,3.5,1.5c1.6,0,2.5-0.8,2.5-1.8c0-1.2-1.3-1.5-2.7-1.8
                                c-1.8-0.4-3.9-0.9-3.9-3.1c0-1.7,1.4-3.1,4-3.1c1.8,0,3.1,0.7,3.9,1.5l-0.8,1.1c-0.6-0.8-1.8-1.3-3.2-1.3
                                c-1.5,0-2.4,0.7-2.4,1.7c0,1,1.2,1.3,2.6,1.6c1.8,0.4,4,0.9,4,3.3c0,1.8-1.4,3.2-4.2,3.2C224.9,401.3,223.5,400.8,222.4,399.7z"></path>
                            </g>
                            <g id="XMLID_102_">
                                <path id="XMLID_106_" d="M377.4,368l1.1-1.4c1,1.1,2.6,2.1,4.6,2.1c2.6,0,3.5-1.4,3.5-2.5c0-1.8-1.8-2.2-3.7-2.8
                                c-2.4-0.6-5-1.3-5-4.2c0-2.4,2.2-4.1,5-4.1c2.2,0,4,0.7,5.2,2l-1.1,1.3c-1.1-1.2-2.6-1.8-4.2-1.8c-1.8,0-3,0.9-3,2.3
                                c0,1.5,1.7,1.9,3.6,2.4c2.4,0.6,5.1,1.4,5.1,4.5c0,2.1-1.5,4.3-5.4,4.3C380.5,370.3,378.6,369.3,377.4,368z"></path>
                                <path id="XMLID_108_" d="M392.8,368.4v5.7h-1.7v-14.7h1.7v1.6c0.8-1.1,2.1-1.8,3.6-1.8c2.8,0,4.7,2.1,4.7,5.6
                                c0,3.4-1.9,5.6-4.7,5.6C394.9,370.3,393.6,369.6,392.8,368.4z M399.4,364.7c0-2.4-1.3-4.1-3.4-4.1c-1.3,0-2.6,0.8-3.2,1.7v4.8
                                c0.6,0.9,1.9,1.7,3.2,1.7C398.1,368.8,399.4,367.1,399.4,364.7z"></path>
                                <path id="XMLID_111_" d="M403.7,370v-10.6h1.6v1.7c0.9-1.1,2.1-1.9,3.5-1.9v1.7c-0.2,0-0.4-0.1-0.7-0.1c-1,0-2.4,0.8-2.9,1.7
                                v7.5H403.7z"></path>
                                <path id="XMLID_113_" d="M410.7,356.8c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1c0,0.6-0.5,1.1-1.1,1.1
                                C411.2,357.9,410.7,357.4,410.7,356.8z M411,370v-10.6h1.6V370H411z"></path>
                                <path id="XMLID_116_" d="M423.1,370v-7c0-1.9-0.9-2.5-2.4-2.5c-1.3,0-2.5,0.8-3.1,1.7v7.8h-1.7v-10.6h1.7v1.5
                                c0.7-0.9,2.2-1.8,3.8-1.8c2.2,0,3.4,1.1,3.4,3.4v7.5H423.1z"></path>
                                <path id="XMLID_118_" d="M428.4,367.8v-7h-1.8v-1.5h1.8v-2.9h1.7v2.9h2.2v1.5H430v6.6c0,0.8,0.4,1.4,1.1,1.4
                                c0.5,0,0.9-0.2,1.1-0.4l0.5,1.2c-0.4,0.4-1,0.7-2,0.7C429.2,370.3,428.4,369.4,428.4,367.8z"></path>
                                <path id="XMLID_120_" d="M367.6,396.4v-14.7h5.9c2.9,0,4.6,2,4.6,4.4s-1.7,4.4-4.6,4.4h-4.1v5.8H367.6z M376.2,386.2
                                c0-1.7-1.2-2.8-2.9-2.8h-3.9v5.6h3.9C375,389,376.2,387.8,376.2,386.2z"></path>
                                <path id="XMLID_123_" d="M380.4,396.4v-14.7h1.7v14.7H380.4z"></path>
                                <path id="XMLID_125_" d="M392,396.4v-1.2c-0.9,1-2.1,1.5-3.5,1.5c-1.8,0-3.7-1.2-3.7-3.5c0-2.4,1.9-3.5,3.7-3.5
                                c1.5,0,2.7,0.5,3.5,1.5v-1.9c0-1.4-1.1-2.2-2.7-2.2c-1.3,0-2.3,0.5-3.3,1.5l-0.8-1.1c1.1-1.2,2.5-1.8,4.2-1.8
                                c2.2,0,4.1,1,4.1,3.6v7.3H392z M392,394.2v-2c-0.6-0.9-1.8-1.3-2.9-1.3c-1.5,0-2.6,1-2.6,2.3c0,1.3,1.1,2.3,2.6,2.3
                                C390.3,395.5,391.4,395.1,392,394.2z"></path>
                                <path id="XMLID_128_" d="M404.1,396.4v-7c0-1.9-0.9-2.5-2.4-2.5c-1.3,0-2.5,0.8-3.1,1.7v7.8H397v-10.6h1.7v1.5
                                c0.7-0.9,2.2-1.8,3.8-1.8c2.2,0,3.4,1.1,3.4,3.4v7.5H404.1z"></path>
                                <path id="XMLID_130_" d="M416.3,396.4v-7c0-1.9-0.9-2.5-2.4-2.5c-1.3,0-2.5,0.8-3.1,1.7v7.8h-1.6v-10.6h1.6v1.5
                                c0.7-0.9,2.2-1.8,3.8-1.8c2.2,0,3.4,1.1,3.4,3.4v7.5H416.3z"></path>
                                <path id="XMLID_132_" d="M420.9,383.2c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1s-0.5,1.1-1.1,1.1
                                C421.4,384.3,420.9,383.8,420.9,383.2z M421.2,396.4v-10.6h1.6v10.6H421.2z"></path>
                                <path id="XMLID_135_" d="M433.3,396.4v-7c0-1.9-0.9-2.5-2.4-2.5c-1.3,0-2.5,0.8-3.1,1.7v7.8h-1.7v-10.6h1.7v1.5
                                c0.7-0.9,2.2-1.8,3.8-1.8c2.2,0,3.4,1.1,3.4,3.4v7.5H433.3z"></path>
                                <path id="XMLID_137_" d="M438.3,399.2l0.8-1.2c0.9,1.1,1.9,1.5,3.5,1.5c1.8,0,3.4-0.9,3.4-3.2v-1.5c-0.8,1.1-2.1,1.9-3.6,1.9
                                c-2.8,0-4.7-2.1-4.7-5.5c0-3.4,1.9-5.5,4.7-5.5c1.4,0,2.7,0.7,3.6,1.9v-1.6h1.6v10.3c0,3.5-2.5,4.6-5.1,4.6
                                C440.7,400.7,439.5,400.4,438.3,399.2z M446,393.4v-4.6c-0.6-0.9-1.8-1.7-3.2-1.7c-2.1,0-3.4,1.7-3.4,4c0,2.3,1.3,4,3.4,4
                                C444.1,395.1,445.4,394.3,446,393.4z"></path>
                            </g>
                        </g>
                        <g id="Layer_6">
                            <g id="XMLID_65_">
                                <g id="XMLID_66_">
                                    <path id="XMLID_67_" fill="#CBDB7C" d="M420.3,210.2h-2.6c-0.3,0-0.3-0.4-0.3-0.4v-1.3c0-1.7-1.4-3.1-3.1-3.1h-21.5
                                    c-0.1,0-0.3-0.3-0.6-0.9c0-0.1-0.1-0.2-0.1-0.3c-1.1-2.1-2.5-2.5-3.5-2.5h-5.6c-1.4,0-2.3,0.8-3.3,2.4
                                    c-0.1,0.1-0.1,0.2-0.2,0.3c-0.3,0.5-0.6,0.9-0.7,0.9H377c-1.7,0-3.1,1.4-3.1,3.1v26.1c0,1.5,1.1,2.8,2.6,3.1
                                    c0.2,0.1,0.4,0.1,0.6,0.1h38.3c1,0,2-0.8,2.2-1.9l4.2-23.8C422,211,421.3,210.2,420.3,210.2L420.3,210.2z M377,207.8h1.6
                                    c1.6,0,2.4-1.3,2.9-2.2c0.1-0.1,0.1-0.2,0.2-0.3c0.5-0.8,0.9-1.2,1.4-1.2h5.3c0.2,0,0.7,0,1.3,1.2c0,0.1,0.1,0.2,0.1,0.2
                                    c0.5,0.9,1.2,2.3,2.9,2.3h21.5c0.3,0,0.6,0.3,0.6,0.6v1.3c0,0,0,0.5-0.3,0.5h-32.7c-1,0-2,0.8-2.2,1.9l-3.2,17.7
                                    c0,0-0.2,0.6-0.2-0.5v-20.8C376.4,208.1,376.7,207.8,377,207.8L377,207.8z M377,207.8"></path>
                                </g>
                            </g>
                            <g id="XMLID_36_">
                                <g id="XMLID_228_">
                                    <path id="XMLID_246_" fill="#B4A4A1" d="M198.2,225.2c0,0.4-0.4,0.8-0.8,0.8h-26.9c-0.4,0-0.8-0.4-0.8-0.8v-0.5
                                    c0-0.4,0.4-0.8,0.8-0.8h26.9c0.4,0,0.8,0.4,0.8,0.8V225.2z M198.2,225.2"></path>
                                    <path id="XMLID_243_" fill="#B4A4A1" d="M181.7,203.1c0,0.4-0.4,0.8-0.8,0.8h-10.4c-0.4,0-0.8-0.4-0.8-0.8v-0.5
                                    c0-0.4,0.4-0.8,0.8-0.8h10.4c0.4,0,0.8,0.4,0.8,0.8V203.1z M181.7,203.1"></path>
                                    <path id="XMLID_240_" fill="#B4A4A1" d="M197.8,232.5c0,0.4-0.4,0.8-0.8,0.8h-10.4c-0.4,0-0.8-0.4-0.8-0.8V232
                                    c0-0.4,0.4-0.8,0.8-0.8H197c0.4,0,0.8,0.4,0.8,0.8V232.5z M197.8,232.5"></path>
                                    <path id="XMLID_237_" fill="#B4A4A1" d="M187.3,210.5c0,0.4-0.4,0.8-0.8,0.8h-16c-0.4,0-0.8-0.4-0.8-0.8v-0.5
                                    c0-0.4,0.4-0.8,0.8-0.8h16c0.4,0,0.8,0.4,0.8,0.8V210.5z M187.3,210.5"></path>
                                    <path id="XMLID_234_" fill="#B4A4A1" d="M198.2,217.8c0,0.4-0.4,0.8-0.8,0.8h-26.9c-0.4,0-0.8-0.4-0.8-0.8v-0.5
                                    c0-0.4,0.4-0.8,0.8-0.8h26.9c0.4,0,0.8,0.4,0.8,0.8V217.8z M198.2,217.8"></path>
                                    <path id="XMLID_229_" fill="#B4A4A1" d="M203.2,204.7l-12.9-10.6c-1.1-0.9-2.9-1.5-4.3-1.5h-20.3c-1.8,0-3.2,1.4-3.2,3.2v43.6
                                    c0,3.3,3.1,3.3,3.2,3.3H202c1.8,0,3.2-1.4,3.2-3.2v-30.6C205.2,207.4,204.4,205.6,203.2,204.7L203.2,204.7z M189.5,197.3
                                    c0-0.8,0.6-0.2,0.6-0.2l10.2,8.7c0,0,0.7,0.6-0.5,0.6h-9.6c-0.5,0-0.8-0.4-0.8-0.8V197.3z M202,240.2h-36.5
                                    c-0.1,0-0.6,0-0.6-0.7v-43.8c0-0.4,0.4-0.8,0.8-0.8h20.3c0.3,0,1.1,0.2,1.1,1.2v9.6c0,1.8,1.4,3.2,3.2,3.2h12
                                    c0.2,0,0.6,0.2,0.6,0.7l0,29.9C202.9,239.8,202.5,240.2,202,240.2L202,240.2z M202,240.2"></path>
                                </g>
                            </g>
                            <g id="XMLID_57_">
                                <g id="XMLID_61_">
                                    <path id="XMLID_62_" fill="#F4D173" d="M411.4,443.4c-0.7,0.7-1.8,0.8-2.6,0.2l-12.4-9.2c-0.8-0.6-0.9-1.7-0.4-2.5l1.6-2.1
                                    c0.6-0.8,1.7-0.9,2.5-0.4l8,5.8c0.8,0.6,2,0.5,2.6-0.3l17.8-19.1c0.7-0.7,1.8-0.8,2.5-0.1l1.9,1.8c0.7,0.7,0.8,1.8,0.1,2.5
                                    L411.4,443.4z M411.4,443.4"></path>
                                </g>
                                <path id="XMLID_58_" fill="#F4D173" d="M424.4,435.7v10.4c0,1.5-1.2,2.7-2.7,2.7h-33.7c-1.5,0-2.7-1.2-2.7-2.7v-32.5
                                c0-1.5,1.2-2.7,2.7-2.7h33.7c1.5,0,2.7,1.2,2.7,2.7v1.6c0,1,0.7,0.2,0.7,0.2s1.4-1.3,1.7-1.6c0.1-0.1,0.5-0.4,0.5-1v-2.1
                                c0-1.5-1.2-2.7-2.7-2.7h-39.5c-1.5,0-2.7,1.2-2.7,2.7V449c0,1.5,1.2,2.7,2.7,2.7h39.5c1.5,0,2.7-1.2,2.7-2.7v-16.4
                                c0-0.8-0.6-0.1-0.6-0.1s-1.2,1.4-1.8,1.9C424.6,434.7,424.4,435,424.4,435.7L424.4,435.7z M424.4,435.7"></path>
                            </g>
                            <g id="XMLID_34_">
                                <path id="XMLID_75_" fill="#E88272" d="M199.9,449.6c0,0.8-0.2,1.4-1.2,1.4h-35.2c-1,0-1.8-0.8-1.8-1.8v-33.1
                                c0-1,0.6-1.2,1.4-1.2c0.8,0,1.4,0.2,1.4,1.2v30.3c0,1,0.8,1.8,1.8,1.8h32.3C199.7,448.1,199.9,448.8,199.9,449.6L199.9,449.6z
                                 M199.9,449.6"></path>
                                <path id="XMLID_35_" fill="#E88272" d="M196.5,427.2c0.4,0.3,0.7,0.1,0.7-0.4l-0.7-7.6c0-0.5-0.5-0.7-0.9-0.6l-7.2,2.7
                                c-0.5,0.2-0.5,0.5-0.1,0.8l1.3,0.8c0.4,0.3,0.5,0.8,0.3,1.2l-5.4,8.2c-0.3,0.4-0.9,0.6-1.3,0.4l-8.4-3.2c-0.5-0.2-1.1,0-1.4,0.4
                                l-5.3,6.4c-0.3,0.4-0.6,1.1-0.6,1.6l0,5.2c0,0.5,0.3,0.6,0.6,0.2l6.8-8c0.3-0.4,1-0.5,1.4-0.4l8.5,3.2c0.5,0.2,1.1,0,1.3-0.4
                                l7.6-11.4c0.3-0.4,0.8-0.5,1.2-0.3L196.5,427.2z M196.5,427.2"></path>
                            </g>
                        </g>
                    </g>
                    <g id="Layer_7">
                        <g id="XMLID_451_">
                            <polygon id="XMLID_452_" fill="#8ED3D4" points="331.4,29.1 313.6,39.4 295.7,49.7 295.7,29.1 295.7,8.6 313.6,18.8"></polygon>
                            <path id="XMLID_453_" fill="#8ED3D4" d="M295.8,57.7c-1.4,0-2.8-0.4-4-1.1c-2.5-1.4-4-4.1-4-6.9V8.6c0-2.9,1.5-5.5,4-6.9
                            c2.5-1.4,5.5-1.4,8,0l35.6,20.6c2.5,1.4,4,4.1,4,6.9s-1.5,5.5-4,6.9l-35.6,20.6C298.5,57.3,297.1,57.7,295.8,57.7z M303.8,22.4
                            v13.4l11.6-6.7L303.8,22.4z"></path>
                        </g>
                        <g id="XMLID_450_">
                            <polygon id="XMLID_32_" fill="#CBDB7C" points="564.8,333 554.1,314.5 543.4,296 564.8,296 586.2,296 575.5,314.5"></polygon>
                            <path id="XMLID_447_" fill="#CBDB7C" d="M564.8,341c-2.9,0-5.5-1.5-6.9-4L536.4,300c-1.4-2.5-1.4-5.5,0-8c1.4-2.5,4.1-4,6.9-4
                            h42.8c2.9,0,5.5,1.5,6.9,4c1.4,2.5,1.4,5.5,0,8L571.7,337C570.3,339.5,567.6,341,564.8,341z M557.2,304l7.6,13.1l7.6-13.1H557.2z
                            "></path>
                        </g>
                        <g id="XMLID_446_">
                            <polygon id="XMLID_9_" fill="#F4D173" points="264.9,565.1 282.8,554.8 300.8,544.4 300.8,565.1 300.8,585.9 282.8,575.5"></polygon>
                            <path id="XMLID_31_" fill="#F4D173" d="M300.8,593.9c-1.4,0-2.8-0.4-4-1.1l-35.9-20.7c-2.5-1.4-4-4.1-4-6.9s1.5-5.5,4-6.9
                            l35.9-20.7c2.5-1.4,5.5-1.4,8,0c2.5,1.4,4,4.1,4,6.9v41.5c0,2.9-1.5,5.5-4,6.9C303.5,593.5,302.2,593.9,300.8,593.9z
                             M280.9,565.1l11.9,6.9v-13.8L280.9,565.1z"></path>
                        </g>
                        <g id="XMLID_8_">
                            <polygon id="XMLID_1_" fill="#E88272" points="28.9,261.8 38.9,279.2 48.9,296.5 28.9,296.5 8.9,296.5 18.9,279.2"></polygon>
                            <path id="XMLID_2_" fill="#E88272" d="M48.9,304.5h-40c-2.9,0-5.5-1.5-6.9-4s-1.4-5.5,0-8l20-34.6c1.4-2.5,4.1-4,6.9-4
                            s5.5,1.5,6.9,4l20,34.6c1.4,2.5,1.4,5.5,0,8S51.7,304.5,48.9,304.5z M22.7,288.5H35l-6.1-10.6L22.7,288.5z"></path>
                        </g>
                    </g>
                </g>
                <g id="Layer_5">
                    <g id="Layer_4">
                        <circle id="XMLID_19_" fill="#E6E6E6" cx="296.5" cy="296.8" r="65.8"></circle>
                    </g>
                    <g id="XMLID_28_" transform="translate(0, -10)">
                        <path id="XMLID_72_" d="M265.1,306.9v-11.6h4.6c2.3,0,3.6,1.6,3.6,3.5s-1.3,3.5-3.6,3.5h-3.2v4.6H265.1z M271.8,298.8
                        c0-1.3-0.9-2.2-2.3-2.2h-3v4.4h3C270.9,301,271.8,300.1,271.8,298.8z"></path>
                        <path id="XMLID_79_" d="M275.2,306.9v-8.4h1.3v1.4c0.7-0.9,1.6-1.5,2.8-1.5v1.3c-0.2,0-0.3-0.1-0.5-0.1c-0.8,0-1.9,0.7-2.3,1.3
                        v5.9H275.2z"></path>
                        <path id="XMLID_81_" d="M280.4,302.7c0-2.4,1.6-4.4,4.1-4.4s4.1,2,4.1,4.4s-1.6,4.4-4.1,4.4S280.4,305.1,280.4,302.7z
                         M287.3,302.7c0-1.7-1-3.2-2.8-3.2s-2.8,1.5-2.8,3.2c0,1.7,1,3.2,2.8,3.2S287.3,304.4,287.3,302.7z"></path>
                        <path id="XMLID_84_" d="M290.3,302.7c0-2.5,1.7-4.4,4.2-4.4c1.5,0,2.4,0.6,3.1,1.5l-0.9,0.8c-0.6-0.8-1.3-1.1-2.1-1.1
                        c-1.8,0-2.9,1.4-2.9,3.2s1.1,3.2,2.9,3.2c0.9,0,1.6-0.3,2.1-1.1l0.9,0.8c-0.6,0.8-1.5,1.5-3.1,1.5
                        C292,307.1,290.3,305.2,290.3,302.7z"></path>
                        <path id="XMLID_86_" d="M298.9,302.7c0-2.4,1.7-4.4,4.1-4.4c2.5,0,4,2,4,4.5v0.3h-6.8c0.1,1.6,1.2,2.9,3,2.9c1,0,1.9-0.4,2.6-1.1
                        l0.6,0.8c-0.8,0.8-2,1.3-3.3,1.3C300.7,307.1,298.9,305.3,298.9,302.7z M303,299.4c-1.8,0-2.7,1.5-2.7,2.8h5.5
                        C305.8,300.9,304.9,299.4,303,299.4z"></path>
                        <path id="XMLID_89_" d="M308.4,305.8l0.7-0.9c0.6,0.6,1.6,1.2,2.8,1.2c1.3,0,2-0.6,2-1.4c0-0.9-1-1.2-2.1-1.4
                        c-1.4-0.3-3-0.7-3-2.4c0-1.3,1.1-2.4,3.1-2.4c1.4,0,2.4,0.5,3.1,1.2l-0.6,0.9c-0.5-0.6-1.4-1.1-2.5-1.1c-1.1,0-1.9,0.6-1.9,1.3
                        c0,0.8,1,1,2,1.3c1.5,0.3,3.1,0.7,3.1,2.6c0,1.4-1.1,2.5-3.3,2.5C310.4,307.1,309.3,306.7,308.4,305.8z"></path>
                        <path id="XMLID_91_" d="M316.5,305.8l0.7-0.9c0.6,0.6,1.6,1.2,2.8,1.2c1.3,0,2-0.6,2-1.4c0-0.9-1-1.2-2.1-1.4
                        c-1.4-0.3-3-0.7-3-2.4c0-1.3,1.1-2.4,3.1-2.4c1.4,0,2.4,0.5,3.1,1.2l-0.6,0.9c-0.5-0.6-1.4-1.1-2.5-1.1c-1.1,0-1.9,0.6-1.9,1.3
                        c0,0.8,1,1,2,1.3c1.5,0.3,3.1,0.7,3.1,2.6c0,1.4-1.1,2.5-3.3,2.5C318.5,307.1,317.3,306.7,316.5,305.8z"></path>
                        <path id="XMLID_93_" d="M279.4,327.7v-11.6h7.6v1.3h-6.1v3.7h6v1.3h-6v5.3H279.4z"></path>
                        <path id="XMLID_95_" d="M288.9,327.7v-11.6h1.3v11.6H288.9z"></path>
                        <path id="XMLID_97_" d="M292.3,323.5c0-2.4,1.6-4.4,4.1-4.4s4.1,2,4.1,4.4c0,2.4-1.6,4.4-4.1,4.4S292.3,325.9,292.3,323.5z
                         M299.2,323.5c0-1.7-1-3.2-2.8-3.2s-2.8,1.5-2.8,3.2c0,1.7,1,3.2,2.8,3.2S299.2,325.2,299.2,323.5z"></path>
                        <path id="XMLID_100_" d="M309.9,327.7l-2.2-6.7l-2.2,6.7h-1.3l-2.7-8.4h1.4l2,6.7l2.2-6.7h1.1l2.2,6.7l2-6.7h1.4l-2.7,8.4H309.9z"></path>
                    </g>
                </g>
            </svg>
        </div>';

        return($tag);
    }

}

class LoginHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("Login", "user");
    }

    protected function addDashboard()
    {
        $tag = '<div class="login-main display-table">';
        $tag .= '<div class="login display-table-cell">' . $this->EOF_LINE;
        $tag .= '    <div id="login-sub">' . $this->EOF_LINE;
        $tag .= '        <h3>Log In</h3>' . $this->EOF_LINE;
        $tag .= '        <form id="login-form" class="login-form" method="post">' . $this->EOF_LINE;
        $tag .= '            <input type="hidden" name="page" id="page" value="login" class="retro-style">' . $this->EOF_LINE;
        $tag .= '            <input type="hidden" name="redirect" id="redirect" value=' . (isset($_GET['redirect']) ? $_GET['redirect'] : '') . '>' . $this->EOF_LINE;
        $tag .=              addInputTag('input', 'text', 'username', 'Username', '', '') . $this->EOF_LINE;
        $tag .=              addInputTag('input', 'password', 'password', 'Password', '', '') . $this->EOF_LINE;
        $tag .= '            <div class="retro-style-form-element">' . $this->EOF_LINE;
        $tag .= '                <input id="signIn" name="signIn" type="submit" value="Sign in" class="retro-style blue">' . $this->EOF_LINE;
        $tag .= '            </div>' . $this->EOF_LINE;
        $tag .= '        </form>' . $this->EOF_LINE;
        $tag .= '        <span><a id="link-forgot-passwd" href="recovery.php">Can&#39;t access your account?</a></span>' . $this->EOF_LINE;
        $tag .= '    </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;
        $tag .= '<div id="signup-nav display-table-cell">' . $this->EOF_LINE;
        $tag .= '    <div id="signup-nav-sub">' . $this->EOF_LINE;
        $tag .= '        <h3>Don\'t have an account?</h3>' . $this->EOF_LINE;
        $tag .= '        <p><a href="signUp.php">Sign up</a> today.</p>' . $this->EOF_LINE;
        $tag .= '    </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;
        $tag .='</div>' . $this->EOF_LINE;

        return($tag);
    }
}

class SignupHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("Sign Up", "user");
    }

    protected function addDashboard()
    {
        $tag = '';
        $tag .= '<div id="signup-main" class="signup-main display-table">' . $this->EOF_LINE;

        $tag .= '<div class="signup display-table-cell">' . $this->EOF_LINE;
        $tag .= '    <h3>Sign Up</h3>' . $this->EOF_LINE;
        $tag .= '    <form class="createaccount-form" id="createaccount" name="createaccount" method="post">' . $this->EOF_LINE;
        $tag .= '        <input class="retro-style" type="hidden" name="page" id="page" value="signup">' . $this->EOF_LINE;
        $tag .= '        <div class="retro-style-form-element multi-field name" id="name-form-element">' . $this->EOF_LINE;
        $tag .= '            <fieldset>' . $this->EOF_LINE;
        $tag .= '                <legend><strong>Name</strong></legend>' . $this->EOF_LINE;
        $tag .= '                <label id="firstname-label" class="firstname">' . $this->EOF_LINE;
        $tag .= '                    <strong>First name</strong>' . $this->EOF_LINE;
        $tag .= '                    <input type="text" value="" class="retro-style" name="firstName" id="firstName-input" onblur="javascript:showErrorMsg(\'firstName\', \'input\', \'name\')" placeholder="First" spellcheck="false">' . $this->EOF_LINE;
        $tag .= '                </label>' . $this->EOF_LINE;
        $tag .= '                <label id="lastname-label" class="lastname">' . $this->EOF_LINE;
        $tag .= '                    <strong>Last name</strong>' . $this->EOF_LINE;
        $tag .= '                    <input type="text" class="retro-style" value="" name="lastName" id="lastName-input" onblur="javascript:showErrorMsg(\'lastName\', \'input\', \'name\')" placeholder="Last" spellcheck="false">' . $this->EOF_LINE;
        $tag .= '                </label>' . $this->EOF_LINE;
        $tag .= '            </fieldset>' . $this->EOF_LINE;
        $tag .= '            <div class="retro-style-errmsg" id="name-errmsg"></div>' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= addInputTag('input', 'text', 'username', 'Choose your username', 'onblur="javascript:showErrorMsg(\'username\', \'input\', \'\')"', "");
        $tag .= addInputTag('input', 'password', 'password', 'Create a password', 'onblur="javascript:showErrorMsg(\'password\', \'input\', \'\')"', "");
        $tag .= addInputTag('input', 'password', 'confirm-password', 'Confirm your password', 'onblur="javascript:showErrorMsg(\'confirm-password\', \'input\', \'\')"', "");
        $tag .= addGenderTag('onblur="javascript:showErrorMsg(\'gender\', \'select\', \'\')"');
        $tag .= addTitleTag('onblur="javascript:showErrorMsg(\'title\', \'select\', \'\')"');
        $tag .= addDepertmentTag('onblur="javascript:showErrorMsg(\'department\', \'select\', \'\')"');
        $tag .= addInputTag('input', 'text', 'manager', 'Manager', 'onblur="javascript:showErrorMsg(\'manager\', \'input\', \'\')"', "");
        $tag .= addInputTag('input', 'text', 'email', 'Your current email address', 'onblur="javascript:showErrorMsg(\'email\', \'input\', \'\')"', "");
        $tag .= addInputTag('input', 'text', 'altEmail', 'Your alternative email address(optional)', '', "");
        $tag .= '        <div class="retro-style-form-element">' . $this->EOF_LINE;
        $tag .= '            <input id="submitbutton" name="submitbutton" type="submit" value="Submit" class="retro-style blue">' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= '    </form>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        $tag .= '   <div id="login-nav" class="login display-table-cell">' . $this->EOF_LINE;
        $tag .= '       <div id="login-nav-sub">' . $this->EOF_LINE;
        $tag .= '           <h3>Have an Account?</h3>' . $this->EOF_LINE;
        $tag .= '           <p>If you already have a password, please <a href="login.php">Login</a>.</p>' . $this->EOF_LINE;
        $tag .= '       </div>' . $this->EOF_LINE;
        $tag .= '   </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }
}

class RecoveryHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("Login", "user");
    }

    protected function addDashboard()
    {
        $tag = '<div id="recovery-main" class="login-main side-align">' . $this->EOF_LINE;
        $tag .= '    <h1>Having trouble loging in?</h1>' . $this->EOF_LINE;
        $tag .= '    <form id="recovery-form" class="login-form" method="post">' . $this->EOF_LINE;
        $tag .= '        <input type="hidden" name="page" id="page" value="recovery">' . $this->EOF_LINE;
        $tag .= '        <div class="retro-style-errmsg" id="recovery-errmsg"></div>' . $this->EOF_LINE;

        //<!-- 1. I don't know my password (radio) and hidden input/text to provide username-->
        $tag .= '        <div id="password-radio-contaioner" class="recovery-radio-container">' . $this->EOF_LINE;
        $tag .= '            <input type="radio" class="retro-style" name="recovery" value="password" onclick="recoveryOptionSelected(\'password-radio-input-container\')">' . $this->EOF_LINE;
        $tag .= '            <label id="password-radio-label" for="password">I don\'t know my password</label>' . $this->EOF_LINE;
        $tag .= '            <div id="password-radio-input-container" class="recovery-radio-input-container">' . $this->EOF_LINE;
        $tag .= '                <label id="username-radio-input-label">To reset your password, enter the username you use to sign in.</label>' . $this->EOF_LINE;
        $tag .=                  addInputTag('input', 'text', 'username', 'username', '', '');
        $tag .= '            </div>' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;

        //<!-- 2. I don't know my username (radio) and hidden input/text to provide email id-->
        $tag .= '        <div id="username-radio-contaioner" class="recovery-radio-container">' . $this->EOF_LINE;
        $tag .= '            <input type="radio" class="retro-style" name="recovery" value="username" onclick="recoveryOptionSelected(\'username-radio-input-container\')">' . $this->EOF_LINE;
        $tag .= '            <label id="username-radio-label" for="username">I don\'t know my username</label>' . $this->EOF_LINE;
        $tag .= '            <div id="username-radio-input-container" class="recovery-radio-input-container">' . $this->EOF_LINE;
        $tag .= '                <label id="email-radio-input-label">To know your username, enter the email address associated with your account.</label>' . $this->EOF_LINE;
        $tag .=                  addInputTag('input', 'text', 'email', 'e-mail', '', '');
        $tag .= '            </div>' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;

        $tag .= '        <div style="margin-top:20px">' . $this->EOF_LINE;
        $tag .= '            <input id="continue" class="retro-style blue" name="continue" type="submit" value="Continue">' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= '    </form>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;
        $tag .= '<div id="recovery-msg-container" class="side-align" style="display:none">' . $this->EOF_LINE;
        $tag .= '    <p id="recovery-p"></p>' . $this->EOF_LINE;
        $tag .= '    <p>To log in, please click <a href="login.php">here</a>.</p>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }
}

abstract class SPRTrackHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct($curNav, $curDir, $enableNav);
    }

    protected function getTabMenu($currentTab)
    {
        $tag = '<div class="main-article-nav-container display-table-row">' . $this->EOF_LINE;
        $tag .= '    <ul class="float-box-nav main-article-nav">' . $this->EOF_LINE;
        $tag .= '        <li><a ' . (($currentTab == "Dashboard") ? 'class="selected-tab"' : '') . 'href="dashboard.php" target="_top">Dashboard</a></li>' . $this->EOF_LINE;
        $tag .= '        <li><a ' . (($currentTab == "Submission Status") ? 'class="selected-tab"' : '') . 'href="submit_status.php" target="_top">Submission Status</a></li>' . $this->EOF_LINE;
        $tag .= '        <li><a ' . (($currentTab == "Report") ? 'class="selected-tab"' : '') . 'href="report.php" target="_top">Report</a></li>' . $this->EOF_LINE;
        $tag .= '        <li><a ' . (($currentTab == "Import") ? 'class="selected-tab"' : '') . 'href="spr_import.php" target="_top">Import</a></li>' . $this->EOF_LINE;
        $tag .= '    </ul>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }
}

class SPRTrackDashboardHTML extends SPRTrackHTML
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("SPR Tracking-Dashboard", "spr_tracking", true);
    }

    protected function addDashboard()
    {
        $tag = '';
        $tag .= '<div class="main-article display-table">' . $this->EOF_LINE;

        $tag .= parent::getTabMenu("Dashboard");

        $tag .= '<div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
        $tag .= '    <div class="main-article-tab-info-container">' . $this->EOF_LINE;
        $tag .= '        <div class="main-article-info-header">' . $this->EOF_LINE;
        $tag .= '            <div class="header-tag">' . $this->EOF_LINE;
        $tag .= '                <h1>SPR Tracking Dashboard</h1>' . $this->EOF_LINE;
        $tag .= '            </div>' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= '        <div class="project-backlog-container">' . $this->EOF_LINE;
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

        $tag .= $this->createDasboardTable();

        $tag .= '           </div>' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= '    </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function createDasboardTable()
    {
        global $conn;

        $year = getCurrentSession();
        $qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session  FROM `spr_tracking` WHERE user_name = '". $_SESSION['project-managment-username'] ."' and session='" . $year . "'";

        $str = "";

        $Table = new HTMLTable("spr-tracking-dashboard-table", "grippy-table spr-tracking-dashboard-table");
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

        /// SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session FROM `spr_tracking`
        $rows = $conn->result_fetch_array($qry);
        if(!empty($rows))
        {
            // loop over the result and fill the rows
            foreach($rows as $row)
            {
                $Table->tr(null, null, null, "align=\"center\"");
                    $Table->td(getGreppyDotTag(), "{$row[0]}-greppy", "hasGrippy", "text-align:center;");
                    $Table->td(getSPRString($row[1], $row[0]), "{$row[0]}-spr-no", null, null, "width=\"12%\"");
                    $Table->td("{$row[1]}", "{$row[0]}-type", null, null, "width=\"10%\"");
                    $Table->td("{$row[2]}", "{$row[0]}-status", null, "background-color:" . getSPRTrackingStatusColor($row[2]) . ";", "width=\"15%\"", "ondblclick=\"javascript:showSPREditTag('" . $row[0] . "-status', 'select', true)\"");
                    $Table->td("{$row[3]}", "{$row[0]}-build-version", null, null, "width=\"12%\"", "ondblclick=\"javascript:showSPREditTag('" . $row[0] . "-build-version', 'input', true)\"");
                    $Table->td("{$row[4]}", "{$row[0]}-commit-build", null, null, "width=\"12%\"", "ondblclick=\"javascript:showSPREditTag('" . $row[0] . "-commit-build', 'input', true)\"");
                    $Table->td("{$row[5]}", "{$row[0]}-respond-by", null, null, "width=\"12%\""," ondblclick=\"javascript:showSPREditTag('" . $row[0] . "-respond-by', 'input', true)\"");
                    $Table->td(shortDescription($row[6], 25), "{$row[0]}-comment", null, null, null, "ondblclick=\"javascript:showSPREditTag('" . $row[0] . "-comment', 'textarea', true)\"                                                                               onmouseover=\"javascript:showFullComment('" . $row[0] . "', true)\"                                                                                onblur=\"javascript:showFullComment('" . $row[0] . "', false)\"");
                    $Table->td("{$row[6]}", "{$row[0]}-comment-full", null, "display: none;");
                    $Table->td("{$row[7]}", "{$row[0]}-session", null, null, "width=\"8%\"", "ondblclick=\"javascript:showSPREditTag('" . $row[0] . "-session', 'input', true)\"");
                    //$Table->td(getQuickActionBtn("{$row[0]}-edit-btn", "spr-tracking-tbl-td-btn", "spr-tracking-dashboard-table-dropdown"), "{$row[0]}-edit", null, null, "width=\"2%\"");
            }
        }
        else
        {
            $Table->tr(null, null, null, "align=\"center\"");
                $Table->td("<p>No result !!!</p>", "no-result", null, null, null);
        }

        return(utf8_encode($Table->toHTML()));
    }
}

class SPRTrackSubmitStatusHTML extends SPRTrackHTML
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("SPR Tracking-Submit Status", "spr_tracking", true);
    }

    protected function addDashboard()
    {
        $tag = '';
        $tag .= '<div class="main-article display-table">' . $this->EOF_LINE;

        $tag .= parent::getTabMenu("Submission Status");

        $tag .= '<div class="main-article-tab-container display-table-row">' . $this->EOF_LINE;
        $tag .= '    <div class="main-article-tab-info-container">' . $this->EOF_LINE;
        $tag .= '        <div class="main-article-info-header">' . $this->EOF_LINE;
        $tag .= '            <div class="header-tag">' . $this->EOF_LINE;
        $tag .= '                <h1>SPR Submit Status</h1>' . $this->EOF_LINE;
        $tag .= '            </div>' . $this->EOF_LINE;
        $tag .= '        </div>' . $this->EOF_LINE;
        $tag .= '        <div class="project-backlog-container">' . $this->EOF_LINE;
        $tag .= '                <div class="spr-tracking-menu-container">' . $this->EOF_LINE;
        $tag .= '                    <div id="session-btn" class="session-btn">' . $this->EOF_LINE;
        $tag .= '                        <select id="session-select" class="retro-style session-select" onchange="javascript:showDashboardAccdSession(\'submission-status-tbody\', \'fillSPRTrackingSubmissionStatusRow\')">' . $this->EOF_LINE;
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
        $tag .= '                            <span>Add SPR to update Submission Status</span>' . $this->EOF_LINE;
        $tag .= '                        </button>' . $this->EOF_LINE;
        $tag .= '                        <button class="retro-style red" type="button">' . $this->EOF_LINE;
        $tag .= '                            <span>Delete SPR Submission Status(s)</span>' . $this->EOF_LINE;
        $tag .= '                        </button>' . $this->EOF_LINE;
        $tag .= '                    </div>' . $this->EOF_LINE;
        $tag .= '                </div>' . $this->EOF_LINE;
        $tag .= '                <div id="spr-tracking-table-dropdown" class="dropdown-content">' . $this->EOF_LINE;
        $tag .= '                    <a href="#">Save</a>' . $this->EOF_LINE;
        $tag .= '                    <a href="#">Cancel</a>' . $this->EOF_LINE;
        $tag .= '                </div>' . $this->EOF_LINE;
        $tag .= '                <div class="spr-tracking-table-container">' . $this->EOF_LINE;

        $tag .= $this->createDasboardTable();

        $tag .= '                </div>' . $this->EOF_LINE;
        $tag .= '       </div>' . $this->EOF_LINE;
        $tag .= '    </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function createDasboardTable()
    {
        global $conn;
        $tag = "";

        $colNames = getColumnName('spr_submission');
        if(!empty($colNames))
        {

            $cnt = count($colNames);

            $qry = "SELECT ";
            for($i = 0; $i < $cnt; ++$i)
            {
                $qry .= "spr_submission.".$colNames[$i];
                if($i != ($cnt - 1))
                    $qry .=",";
                $qry .= " ";
            }

            $qry .= ", spr_tracking.type FROM spr_submission INNER JOIN spr_tracking ON spr_submission.spr_no=spr_tracking.spr_no
                    and spr_tracking.user_name='". $_SESSION['project-managment-username'] ."' and spr_tracking.session='". getCurrentSession() ."'";

            // Create table
            $Table = new HTMLTable("submission-status-table", "grippy-table spr-tracking-dashboard-table");

            // add table header
            $Table->thead("submission-status-thead");
                $Table->th("&nbsp;", null, null, null, null);
                $Table->th("SPR Number", null, null, null, "data-sort=\"int\"");
                $Table->th("l03", null,  null, null, "data-sort=\"string\"");
                $Table->th("p10", null, null, null, "data-sort=\"string\"");
                $Table->th("p20", null, null, null, "data-sort=\"string\"");
                $Table->th("p30", null, null, null, "data-sort=\"string\"");
                $Table->th("Comment", null, null, null);
                //$Table->th("&nbsp;", null, null, null, null);

            // add Table body
            $Table->tbody("submission-status-tbody");

            $rows = $conn->result_fetch_array($qry);
            if(!empty($rows))
            {
                // loop over the result and fill the rows
                foreach($rows as $row)
                {
                    $Table->tr(null, null, null, "align=\"center\"");
                        $Table->td(getGreppyDotTag(), "{$row[0]}-greppy", "hasGrippy", null, "width=\"2%\"");
                        $Table->td(getSPRString($row[1], $row[0]), "{$row[0]}-spr-no", null, null, "width=\"12%\"");

                        $Table->td("{$row[1]}", "{$row[0]}-L03", null, "background-color:" . getSPRTrackingStatusColor($row[1]) . ";", "width=\"12%\"", "ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-L03', 'select', true)\"");

                        $Table->td("{$row[2]}", "{$row[0]}-P10", null, "background-color:" . getSPRTrackingStatusColor($row[2]) . ";", "width=\"12%\"", "ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-P10', 'select', true)\"");

                        $Table->td("{$row[3]}", "{$row[0]}-P20", null, "background-color:" . getSPRTrackingStatusColor($row[3]) . ";", "width=\"12%\"", "ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-P20', 'select', true)\"");

                        $Table->td("{$row[4]}", "{$row[0]}-P30", null, "background-color:" . getSPRTrackingStatusColor($row[1]) . ";", "width=\"12%\"", "ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-P30', 'select', true)\"");

                        $Table->td("{$row[5]}", "{$row[0]}-comment", null, null, "width=\"12%\""," ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-comment', 'textarea', true)\"");
                }
            }
            else
            {
                $Table->tr(null, null, null, "align=\"center\"");
                    $Table->td(" ", "no-result", null, null, null);
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
    }
}

class SprintTrackDetailHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
    parent::__construct("Scrum-Sprint-Tracking-Taskboard", "scrum", true);
    }

    protected function addDashboard()
    {
        $tag = '';
        $tag .= '<div class="main-article display-table">' . $this->EOF_LINE;
        $tag .= '   <p>#Sprint Tracking Detail - Artcle block</p>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }
}

class SprintTrackTaskboardHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
    parent::__construct("Scrum-Sprint-Tracking-Taskboard", "scrum", true);
    }

    protected function addDashboard()
    {
        $tag = '';
        $tag .= '<div class="main-article display-table">' . $this->EOF_LINE;
        $tag .= '   <p>#Sprint Tracking Taskboard - Artcle block</p>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }
}

?>
