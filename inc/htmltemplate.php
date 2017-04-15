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
require_once ('utility.php');
require_once ('navigator.php');
require_once ('htmltable.php');
require_once ('grippytable.php');
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
    /******************************************
        Member Variables Block
    ******************************************/
    private $currentDir     = null;
    private $enableNav      = null;
    private $currentNav     = null;
    private $sideNavItems       = null;
    private $currentSideNav     = null;

    protected $EOF_LINE     = "\n";


    /******************************************
        Constructor/Destructor block
    ******************************************/
    public function __construct($curNav = null, $curDir = null, $enableNav = false, $sideNavItems = null, $currentSideNav = null)
    {
        $this->currentDir   = $curDir;
        $this->enableNav    = $enableNav;
        $this->currentNav   = $curNav;
        $this->sideNavItems     = $sideNavItems;
        $this->currentSideNav   = $currentSideNav;
    }


    /******************************************
        Public methods block
    ******************************************/
    public function generateBody()
    {
        $tag = "";

        if(($this->currentDir != null) && ($this->currentNav != null))
        {
            $tag = '';

            $tag .= '<div class="display-flex-row">' . $this->EOF_LINE;
            $tag .=     $this->addHeader($this->currentDir, $this->currentNav, $this->enableNav) . $this->EOF_LINE;
            $tag .=     $this->addSideNav() . $this->EOF_LINE;
            $tag .=     $this->addArticle() . $this->EOF_LINE;
            $tag .=     $this->addFooter($this->currentDir) . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
        }

        return($tag);
    }

    public function setTabItems($sideNavItems)
    {
        $this->sideNavItems = $sideNavItems;
    }

    public function setCurrentTab($curTab)
    {
        $this->currentSideNav = $curTab;
    }

    public function getTabMenu()
    {
        $tag = '';
        if(($this->sideNavItems != null) && (count($this->sideNavItems) > 0) &&
            ($this->currentSideNav != null) && ($this->currentSideNav != ''))
        {
            $tag .= '<div class="main-article-nav-container display-table-row">' . $this->EOF_LINE;
            $tag .= '    <ul class="float-box-nav main-article-nav">' . $this->EOF_LINE;

            foreach($this->sideNavItems as $tab)
            {
                $tag .= '        <li><a ' . (($this->currentSideNav === $tab[0]) ? 'class="selected-tab"' : '') . 'href="'. $tab[1] .'" target="_top">'. $tab[0] .'</a></li>' . $this->EOF_LINE;
            }

            $tag .= '    </ul>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
        }

        return($tag);
    }


    /******************************************
        Protected methods block
    ******************************************/
    abstract protected function addDashboard();

    protected function getWidgetboxContent() { return(''); }
    protected function getWidgetTitlebarContent() { return(''); }

    protected function getWidgetbox()
    {
        $tag = '';

        if(($this->currentSideNav != null) && ($this->currentSideNav != ''))
        {
            $tag .= '<div class="widgetbox">'. $this->EOF_LINE;
            $tag .= '   <div class="titlebar">'. $this->EOF_LINE;
            $tag .= '       <h1>'. $this->EOF_LINE;
            $tag .= '           <span class="title">'. $this->currentSideNav .'</span>'. $this->EOF_LINE;
            $tag .=             $this->getWidgetTitlebarContent();
            $tag .= '       </h1>'. $this->EOF_LINE;
            $tag .= '   </div>'. $this->EOF_LINE;

            $tag .= '   <div class="content">'. $this->EOF_LINE;
            $tag .=         $this->getWidgetboxContent();
            $tag .= '   </div>'. $this->EOF_LINE;
            $tag .= '</div>'. $this->EOF_LINE;
        }

        return($tag);
    }


    /******************************************
        Private methods block
    *******************************************/

    /**
    * Add header block.
    *
    * Add header block including header caption and navigator.
    *
    * @return string $tag
    *   header tag string.
    */
    private function addHeader($currentDir, $selNav, $enableNav)
    {
        $tag = '';

        $nav = new Navigator();
        if($nav != null)
        {
            $tag .= '<header class="flex-full">' . EOF_LINE;
            $tag .=     $nav->header_new($currentDir, $selNav, $enableNav);
            $tag .= '</header>' . EOF_LINE;
        }

        return($tag);
    }

    private function addSideNav()
    {
        $tag = '';

        if((count($this->sideNavItems) > 0) && ($this->enableNav))
        {
            $tag .= '<nav class="side-nav">' . $this->EOF_LINE;
            $tag .= '   <ul>';
            foreach($this->sideNavItems as $item)
            {
                $tag .= '       <li ' .(($item[0] == $this->currentSideNav) ? 'class="selected"' : '') .' tooltip="'. $item[0] .'" flow="right">';
                $tag .= '           <a href="'. $item[1] .'">';

                /* display the svg icon */
                if($item[2] != null)
                    $tag .=                 $item[2];

                $tag .= '           </a>';
                $tag .= '       </li>';
            }
            $tag .= '   </ul>';
            $tag .= '</nav>' . $this->EOF_LINE;
        }

        return($tag);
    }

    private function addArticle()
    {
        $tag = "";

        $tag = '<article>' . $this->EOF_LINE;
        $tag .=     $this->addDashboard() . $this->EOF_LINE;
        $tag .= '</article>' . $this->EOF_LINE;

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
    private function addFooter($currentDir, $hrTagFlag = true)
    {
        $tag = '';

        $nav = new Navigator();
        if($nav != null)
        {
            $tag .= '<footer class="flex-full">' . EOF_LINE;
            $tag .=     $nav->footer($currentDir, $hrTagFlag);
            $tag .= '</footer>' . EOF_LINE;
        }

        return($tag);
    }
}

abstract class SPRTrackHTML extends HTMLTemplate
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false, $currentSideNav = null)
    {
        $tabs = array
                      (
                        array('Dashboard', 'dashboard.php', SVG::getSPRTrack()),
                        array('Submission Status', 'submit_status.php', SVG::getSPRTrack()),
                        array('Report', 'report.php', SVG::getSPRTrack()),
                        array('Import', 'spr_import.php', SVG::getSPRTrack())
                      );

        parent::__construct($curNav, $curDir, $enableNav, $tabs, $currentSideNav);
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
        $tag = '';

        if((isset($_SESSION["project-managment-username"])) && ($_SESSION["project-managment-username"] != ""))
            $tag .= $this->getUserInfoContainer();
        else
            $tag .= $this->getProjectManagementInfo();

        return($tag);
    }

    private function getUserInfoContainer()
    {
        $tag = "";
        $nc = getNextCutOff('config/ptc_info.xml');

        if(!empty($nc))
        {
            $tag .= '<div class="user-info-container">' . $this->EOF_LINE;
            $tag .= '   <div class="spr-info-container">' . $this->EOF_LINE;
            $tag .= '       <div id="user-spr-container" style="flex: 2; margin: 0 20px;">' . $this->EOF_LINE;

            // get upcoming Respond by date (next 2 months).
            $tag .=             $this->getRespondByDiv();

            // get upcoming Commit Build information.
            $tag .=             $this->getCommitBuildDiv([$nc[0][0], $nc[1][0], $nc[2][0], $nc[3][0]]);

            // get Submission Status Information.
            $tag .=                 $this->getSubmissionStatusDiv();

            $tag .= '       </div>' . $this->EOF_LINE;

            // get Next Cutoff info.
            $tag .= '       <div id="nextCutOff-container" style="flex: 1; margin-right: 25px;">' . $this->EOF_LINE;
            $tag .=             $this->getNextCutOffDiv($nc);
            $tag .= '       </div>' . $this->EOF_LINE;

            $tag .= '   </div>' . $this->EOF_LINE;
            $tag .= '   <div id="scrum-container" class="display-flex" style="margin-left: 25px;">' . $this->EOF_LINE;
            $tag .= '       <p>#Scrum Section</p>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
        }

        return(utf8_encode($tag));
    }

    private function getTitlebar($title)
    {
        $tag = '';

        if(($title != null) && ($title != ''))
        {
            $tag .= '<div class="titlebar">' . $this->EOF_LINE;
            $tag .= '   <h1>' . $this->EOF_LINE;
            $tag .= '       <span class="title">'.$title.'</span>' . $this->EOF_LINE;
            $tag .= '   </h1>' . $this->EOF_LINE;
            $tag .= '</div>' . $this->EOF_LINE;
        }

        return($tag);
    }

    private function getContentbar($content)
    {
        $tag = '';

        if(($content != null) && ($content != ''))
        {
            $tag .= '   <div class="contentbar">' . $this->EOF_LINE;
            $tag .= '       <label>' . $this->EOF_LINE;
            $tag .=             $content . $this->EOF_LINE;
            $tag .= '       </label>' . $this->EOF_LINE;
            $tag .= '   </div>' . $this->EOF_LINE;
        }

        return($tag);
    }

    private function getRespondByDiv()
    {
        $NearByDate = 20;
        $tag = '';
        $content = '';

        $tag .= '<div id="respond-by-container" class="widgetbox no-widgetbox" style="margin-bottom: 30px;">' . $this->EOF_LINE;
        $tag .= $this->getTitlebar('Respond By');

        $respond_by = $this->getSPRHavingNextRespondBy();
        if(count($respond_by) > 0)
        {
            foreach($respond_by as $each)
            {
                $content = '<a href="'.getSPRLink($each[0], $each[2]).'" target="_blank">'.$each[0].'</a>' . $this->EOF_LINE;
                $content .= '<span> : </span><span style="text-align:center; color: '.((isNearByDate($each[1], $NearByDate) == true) ? "red" : "black").'">'.$each[1].'</span>' . $this->EOF_LINE;

                $tag .= $this->getContentbar($content);
            }
        }
        else
        {
            $content = '<span>No upcoming SPR to set Commit Build.</span>';
            $tag .= $this->getContentbar($content);
        }
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
        $tag = '';
        $content = '';

        $tag .= '<div id="commit-build-container" class="widgetbox no-widgetbox" style="margin-bottom: 30px;">' . $this->EOF_LINE;
        $tag .= $this->getTitlebar('SPR Having Commit Build');

        if(!empty($buildVersions))
        {
            foreach($buildVersions as $each)
            {
                // get SPRs having Commit build equals to current build.
                $tag .= $this->getCommitBuildRows(getPrevBuildVersion($each));

                // get SPRs having Commit build equals to next build.
                $tag .= $this->getCommitBuildRows($each);
            }
        }
        else
        {
            $content = '<span>No Committed SPR for up-coming build</span>';
            $tag .= $this->getContentbar($content);
        }
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getCommitBuildRows($version)
    {
        $tag = '';
        $content = '';

        if($version != '')
        {
            $sprList = getSPRsHavingCommitBuild($version);

            if(!empty($sprList))
            {
                $content = '       <span>'. $version .'</span><span> : </span>' . $this->EOF_LINE;
                foreach($sprList as $each)
                {
                    $content .= '   <a href="'.getSPRLink($each[0], $each[1]).'" target="_blank">'.$each[0].'</a>&nbsp;&nbsp;';
                }

                $tag .= $this->getContentbar($content);
            }
        }

        return($tag);
    }

    private function getSubmissionStatusDiv()
    {
        $tag = '';
        $content = '';

        $tag .= '<div id="port-container" class="widgetbox no-widgetbox" style="margin-bottom: 30px;">' . $this->EOF_LINE;
        $tag .= $this->getTitlebar('Submission / Port');

        $submission_status = getSubmissionStatus();
        if(count($submission_status) > 0)
        {
            foreach($submission_status as $each)
            {
                $content = '<a href="'.getSPRLink($each[0], $each[2]).'" target="_blank">'.$each[0].'</a>' . $this->EOF_LINE;
                $content .= '<span> : </span><span>'. $each[1] .'</span>' . $this->EOF_LINE;

                $tag .= $this->getContentbar($content);
            }
        }
        else
        {
            $content = '<span>No SPR Porting is left.</span>';
            $tag .= $this->getContentbar($content);
        }
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getNextCutOffDiv($nc)
    {
        $tag = "";
        $nearByDateRange = 15;

        if(!empty($nc))
        {
            $tag .= '<table>' . $this->EOF_LINE;
            $tag .= '   <thead style="background-color: #444;">' . $this->EOF_LINE;
            $tag .= '       <tr>' . $this->EOF_LINE;
            $tag .= '           <th colspan="2">' . $this->EOF_LINE;
            $tag .= '               <h1 style="text-align:center; font-weight: 300; font-size: 1.714em; letter-spacing: -.025em; margin: 0; color: #9dce0a; padding: 10px .5em;"><span style="text-transform: uppercase;">Build Status</span></h1>' . $this->EOF_LINE;
            $tag .= '           </th>' . $this->EOF_LINE;
            $tag .= '       </tr>' . $this->EOF_LINE;
            $tag .= '   </thead>' . $this->EOF_LINE;

            $tag .= '   <tbody style="background-color: #80a219;">' . $this->EOF_LINE;
            foreach($nc as $each)
            {
                $tag .= '       <tr>' . $this->EOF_LINE;
                $tag .= '           <td style="text-align: right;">' . $this->EOF_LINE;
                $tag .= '               <p style="margin:5px;"><strong>'.$each[0].'</strong> :</p>' . $this->EOF_LINE;
                $tag .= '           </td>' . $this->EOF_LINE;
                $tag .= '           <td style="text-align: left;">' . $this->EOF_LINE;
                $tag .= '               <p style="margin:5px;">' . $this->EOF_LINE;
                $tag .= '                   <label style="color: '.((isNearByDate($each[1], $nearByDateRange) == true) ? "red" : "black").';">'.((isBuildReleased($each[1]) == true) ? "<strong>Releashed</strong>" : $each[1]).'</label>' . $this->EOF_LINE;
                $tag .= '               </p>' . $this->EOF_LINE;
                $tag .= '           </td>' . $this->EOF_LINE;
                $tag .= '       </tr>' . $this->EOF_LINE;
            }
            $tag .= '   </tbody>' . $this->EOF_LINE;
            $tag .= '</table>' . $this->EOF_LINE;
        }

        return($tag);
    }

    private function getProjectManagementInfo()
    {
        $tag = '';

        $tag .= '   <div class="display-table">' . $this->EOF_LINE;
        $tag .= '       <div class="display-table-row pm-info-container">' . $this->EOF_LINE;
        $tag .=             $this->getPMShortDesc();
        $tag .= '       </div>' . $this->EOF_LINE;
        $tag .= '       <div class="display-table-row">' . $this->EOF_LINE;
        $tag .= '           <div class="display-table" style="width: 100%; height: 100%">' . $this->EOF_LINE;
        $tag .= '               <div class="display-table-cell spr-article-table-cell">' . $this->EOF_LINE;
        $tag .=                     $this->getSPRTrackInfoContainer();
        $tag .= '               </div>' . $this->EOF_LINE;
        $tag .= '               <div class="display-table-cell scrum-article-table-cell">' . $this->EOF_LINE;
        $tag .=                     $this->getScrumInfoContainer();
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
        $tag .= '                Tool help user to organize SPRs assign to them. </br> It will help to track SPR status, submission status and many other facilities. It also track user\'s daily work. It will generate weekly, monthly work reports. </br> It also manages <strong id="desc-strong">Scrum Methodology</strong>.' . $this->EOF_LINE;
        $tag .= '   </label>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getSPRTrackInfoContainer()
    {
        $imagesPath = "images";

        $sprTrackURL = "spr_tracking/dashboard.php";
        $sprTrackImage = $imagesPath .'/spr_tracking_screen_new.png';
        $sprTrackTitle = "SPR Tracking";
        $sprTrackDesc = "Help user to manage SPRs those are assign to them.";

        $sprSubmissionURL = "spr_tracking/submit_status.php";
        $sprSubmissionImage = $imagesPath .'/spr_submission_status_screen_new.png';
        $sprSubmissionTitle = "SPR Submission Status";
        $sprSubmissionDesc = "Help user to manage Submission status of SPRs.";

        $workTrackURL = "work_tracker/dashboard.php";
        $workTrackImage = $imagesPath .'/work_tracker_screen_.png';
        $workTrackTitle = "Work Tracker";
        $workTrackDesc = "Help user to track their daily work. It will help them to understand how much time they spend for an SPR. This will help them in future for project planning purpose.";

        $tag  = '<div id="spr-track-article-container" class="spr-track-article-container">' . $this->EOF_LINE;

        $tag .=     $this->getSPRInfoDiv("spr-tracking-container", $sprTrackURL, $sprTrackImage, $sprTrackTitle, $sprTrackDesc);
        $tag .=     $this->getSPRInfoDiv("spr-submission-article-container", $sprSubmissionURL, $sprSubmissionImage, $sprSubmissionTitle, $sprSubmissionDesc);
        $tag .=     $this->getSPRInfoDiv("work-tracker-article-container", $workTrackURL, $workTrackImage, $workTrackTitle, $workTrackDesc);

        $tag  .= '</div>' . $this->EOF_LINE;

        return(utf8_encode($tag));
    }

    private function getSPRInfoDiv($divId, $url, $img, $headerStr, $desc)
    {
        $tag  = '  <div id="' . $divId . '" class="info-container">' . $this->EOF_LINE;
        $tag  .= '      <a href="'. $url .'">' . $this->EOF_LINE;
        $tag  .= '          <img src="'. $img .'" alt="' . $headerStr . '" />' . $this->EOF_LINE;
        $tag  .= '      </a>' . $this->EOF_LINE;
        $tag  .= '      <h4>' . $this->EOF_LINE;
        $tag  .= '          <a href="'. $url .'">' . $headerStr . '</a>' . $this->EOF_LINE;
        $tag  .= '      </h4>' . $this->EOF_LINE;
        $tag  .= '      <p>' . $desc . '</p>' . $this->EOF_LINE;
        $tag  .= '  </div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getScrumInfoContainer()
    {
        $tag = "";

        $tag .= '<div id="scrum-process-flow-svg-container" class="scrum-process-flow-container">' . $this->EOF_LINE;
        $tag .=     SVG::getScrumProcessFlow();
        $tag .= '</div>' . $this->EOF_LINE;
        $tag .= '<div id="scrum-process-link" class="display-table scrum-process-flow-container scrum-process-link">' . $this->EOF_LINE;
        $tag .=     $this->getScrumProcessLink();
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getScrumProcessLink()
    {
        $tag = '';

        $tag .= '<div class="title">' . $this->EOF_LINE;
        $tag .= '   Links' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        $tag .= $this->getScrumProcessLinkItem("Product Planning", "scrum/product_plan_backlog.php");
        $tag .= $this->getScrumProcessLinkItem("Release Planning", "scrum/release_plan_dashboard.php");
        $tag .= $this->getScrumProcessLinkItem("Sprint Planning", "scrum/sprint_plan_dashboard.php");
        $tag .= $this->getScrumProcessLinkItem("Sprint Tracking", "scrum/sprint_track_detail.php");

        return($tag);
    }

    private function getScrumProcessLinkItem($title, $url)
    {
        $tag = '<div class="link-item">' . $this->EOF_LINE;
        $tag .='    <a href="'. $url .'" class="tip" data-tip-text="' . $title . '">' . $this->EOF_LINE;
        $tag .='        <img src="images/burndown-chart.png" width="33" height="24" alt="' . $title . '">' . $this->EOF_LINE;
        $tag .='        <br>' . $this->EOF_LINE;
        $tag .='        ' . $title . $this->EOF_LINE;
        $tag .='    </a>' . $this->EOF_LINE;
        $tag .='</div>' . $this->EOF_LINE;

        return($tag);
    }
}

class SPRTrackDashboardHTML extends SPRTrackHTML
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("SPR Tracking-Dashboard", "spr_tracking", true, "Dashboard");
    }

    protected function addDashboard()
    {
        return( parent::getWidgetbox() );
    }

    protected function getWidgetboxContent()
    {
        $tag = '';

        $tag .= '<div style="margin-bottom: 50px;">' . $this->EOF_LINE;
        $tag .=     $this->getWidgetContent();
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getWidgetContent()
    {
        $selectOptions = array
                            (
                                array('All', 'All'),
                                array('2017', '2017'), array('2016', '2016'), array('2015', '2015'), array('2014', '2014'),
                                array('2013', '2013'), array('2012', '2012'), array('2011', '2011'), array('2010', '2010'),
                                array('2009', '2009'), array('2008', '2008'), array('2007', '2007'), array('2006', '2006'),
                                array('2005', '2005'), array('2004', '2004'), array('2003', '2003'), array('2002', '2002'),
                                array('2001', '2001'), array('2000', '2000')
                            );

        $addSPRDropdownList = array
                                (
                                    array('Save', 'onclick=""'),
                                    array('Cancel', 'onclick="cancelEditTable(\'spr-tracking-dashboard\', \'fillSPRTrackingDashboardRow\')"')
                                );


        $tag = '<div class="spr-tracking-menu-container">' . $this->EOF_LINE;
        $tag .=     Utility::getRetroSelect('session-select', $selectOptions, '2017', 'onchange="javascript:showDashboardAccdSession(\'spr-tracking-dashboard-tbody\', \'fillSPRTrackingDashboardRow\')"', 'session-select', 'session-container');
        $tag .= '   <div style="float: right; margin-right: 10px;">' . $this->EOF_LINE;
        $tag .=         Utility::getRetroButton('Add SPR to Track', 'add-spr-btn', 'green add-spr', 'onclick="javascript:addSPRTrackingDashboardRow()"');
        $tag .=         Utility::getRetroButton('Delete SPR(s)', 'delete-spr-btn', 'red', 'onclick=""');
        $tag .= '   </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        $tag .= Utility::getQuickActionBtnDropdown('spr-tracking-dashboard-table-dropdown', $addSPRDropdownList);
        $tag .= $this->createDasboardTable();

        return($tag);
    }

    private function createDasboardTable()
    {
        global $conn;

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
        $qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session  FROM `spr_tracking` WHERE user_name = '". $_SESSION['project-managment-username'] ."' and session='" . getCurrentSession() . "'";

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

        $tag = '<div class="spr-tracking-table-container">' . $this->EOF_LINE;
        $tag .=     $Table->toHTML();
        $tag .= '</div>' . $this->EOF_LINE;

        return(utf8_encode($tag));
    }
}

class SPRTrackSubmitStatusHTML extends SPRTrackHTML
{
    public function __construct($curNav = null, $curDir = null, $enableNav = false)
    {
        parent::__construct("SPR Tracking-Submit Status", "spr_tracking", true, "Submission Status");
    }

    protected function addDashboard()
    {
        return( parent::getWidgetbox() );
    }

    protected function getWidgetboxContent()
    {
        $tag = '';

        $tag .= '<div style="margin-bottom: 50px;">' . $this->EOF_LINE;
        $tag .=     $this->getWidgetContent();
        $tag .= '</div>' . $this->EOF_LINE;

        return($tag);
    }

    private function getWidgetContent()
    {
        $selectOptions = array
                            (
                                array('All', 'All'),
                                array('2017', '2017'), array('2016', '2016'), array('2015', '2015'), array('2014', '2014'),
                                array('2013', '2013'), array('2012', '2012'), array('2011', '2011'), array('2010', '2010'),
                                array('2009', '2009'), array('2008', '2008'), array('2007', '2007'), array('2006', '2006'),
                                array('2005', '2005'), array('2004', '2004'), array('2003', '2003'), array('2002', '2002'),
                                array('2001', '2001'), array('2000', '2000')
                            );

        $addSPRDropdownList = array
                                (
                                    array('Save', 'onclick=""'),
                                    array('Cancel', 'onclick=""')
                                );

        $tag = '';

        $tag .= '<div class="spr-tracking-menu-container">' . $this->EOF_LINE;
        $tag .=     Utility::getRetroSelect('session-select', $selectOptions, '2017', 'onchange="showDashboardAccdSession(\'submission-status-tbody\', \'fillSPRTrackingSubmissionStatusRow\')"', 'session-select', 'session-container');

        $tag .= '   <div style="float: right; margin-right: 10px;">' . $this->EOF_LINE;
        $tag .=         Utility::getRetroButton('Add SPR to update Submission Status', 'add-spr-btn', 'green add-spr', 'onclick=""');
        $tag .=         Utility::getRetroButton('Delete SPR Submission Status(s)', 'delete-spr-btn', 'red', 'onclick=""');
        $tag .= '   </div>' . $this->EOF_LINE;
        $tag .= '</div>' . $this->EOF_LINE;

        $tag .= Utility::getQuickActionBtnDropdown('submission-status-table-dropdown', $addSPRDropdownList);
        $tag .= $this->createDasboardTable();

        return($tag);
    }

    private function createDasboardTable()
    {
        global $conn;

        $str = '';
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
                        $Table->td(getSPRString($row[1], $row[0]), "{$row[0]}-spr-no", null, null, "width=\"20%\"");

                        $Table->td("{$row[1]}", "{$row[0]}-L03", null, "background-color:" . getSPRSubmissionColor($row[1]) . ";", "width=\"12%\"", "ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-L03', 'select', true)\"");

                        $Table->td("{$row[2]}", "{$row[0]}-P10", null, "background-color:" . getSPRSubmissionColor($row[2]) . ";", "width=\"12%\"", "ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-P10', 'select', true)\"");

                        $Table->td("{$row[3]}", "{$row[0]}-P20", null, "background-color:" . getSPRSubmissionColor($row[3]) . ";", "width=\"12%\"", "ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-P20', 'select', true)\"");

                        $Table->td("{$row[4]}", "{$row[0]}-P30", null, "background-color:" . getSPRSubmissionColor($row[1]) . ";", "width=\"12%\"", "ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-P30', 'select', true)\"");

                        $Table->td("{$row[5]}", "{$row[0]}-comment", null, null, "width=\"38%\""," ondblclick=\"javascript:showSPRTrackingSubmissionEdit('" . $row[0] . "-comment', 'textarea', true)\"");
                }
            }
            else
            {
                $Table->tr(null, null, null, "align=\"center\"");
                    $Table->td("<p>No result !!!</p>", "no-result", null, null, null);
            }

            $str .= '<div class="spr-tracking-table-container">' . $this->EOF_LINE;
            $str .=     $Table->toHTML();
            $str .= '</div>' . $this->EOF_LINE;
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

?>
