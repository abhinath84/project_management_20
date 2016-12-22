<?php
    /*ini_set('display_errors', 'On');
    error_reporting(E_ALL);*/

    require_once ('../inc/functions.inc.php');
    require_once ('../inc/mysql_functions.inc.php');
    require_once ('../inc/htmltemplate.php');

    // Create Database and required tables
    build_db();

    // Initialize session data
    session_start();

    // if not log in then redirect to login page.
    //if(!isset($_SESSION['project-managment-username']))
    //    header("Location: ../user/login.php?redirect=../spr_tracking/dashboard.php");
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <title>PTC Project Management</title>
        <link rel="stylesheet" type="text/css" href="../css/retro_style.css">
        <link rel="stylesheet" type="text/css" href="../css/grippy_table.css">
        <link rel="stylesheet" type="text/css" href="../css/global.css">
        <link rel="stylesheet" type="text/css" href="../css/spr_tracking_dashboard.css">
        <script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="../js/stupidtable.min.js?dev"></script>
        <script type="text/javascript" src="../js/functions.js"></script>
        <script type="text/javascript" src="../js/jqry.js"></script>
        <script type="text/javascript" src="../js/addtable.js"></script>
        <script>
            $(document).ready(function(){
               $("table").fixMe();
               $(".up").click(function() {
                  $('html, body').animate({
                  scrollTop: 0
              }, 3000);
             });
            });

        </script>
    </head>
    <?php
        $htmlBody = new SPRTrackingHTML();

        echo $htmlBody->generateBody();
    ?>
    <!--<body>
        <div class="wrapper display-table">
            <div class="header display-table-row">
                <div class="banner display-table">
                    <div class="logo display-table-cell">
                        <a href="../index.php">
                            <img src="../images/ptc-master-color_small.png" alt="ptc.com"/>
                        </a>
                    </div>
                    <div class="title display-table-cell">
                        <h1>PROJECT MANAGEMENT</h1>
                    </div>
                    <div class="admin-nav display-table-cell">
                        <ul>
                            <li><a href="#" target="_top">ADMIN</a></li>
                            <li>
                                <a href="#" target="_top">Abhishek Nath &#9660;</a>
                                <ul style="text-align: left;">
                                    <li><a href="#" target="_top">Profile</a></li>
                                    <li><a href="#" target="_top">Change Password</a></li>
                                    <li><a href="#" target="_top">Logout</a></li>
                                </ul>
                            </li>
                            <li><a href="#" target="_top">HELP</a></li>
                        </ul>
                    </div>
                </div>
                <div class="nav display-table">
                    <div class="main-nav display-table-row">
                        <ul class ="float-box-nav">
                            <li><a href="../index.php" target="_top">HOME</a></li>
                            <li>
                                <a class="selected" href="">SPR Tracking</a>
                                <ul>
                                    <li><a href="#" target="_top">Dashboard</a></li>
                                    <li><a href="#" target="_top">Submit Status</a></li>
                                    <li><a href="#" target="_top">Report</a></li>
                                </ul>
                            </li>
                            <li><a href="#" target="_top">Work Tracker</a></li>
                            <li>
                                <a href="">Scrum</a>
                                <ul>
                                    <li><a href="../scrum/product_plan_backlog.php" target="_top">Product Planning</a></li>
                                    <li><a href="#" target="_top">Release Planning</a></li>
                                    <li><a href="#" target="_top">Sprint Planning</a></li>
                                    <li><a href="../scrum/sprint_plan_taskboard.php" target="_top">Sprint Tracking</a></li>
                                    <li><a href="#" target="_top">Sprint Review</a></li>
                                </ul>
                            </li>
                            <li><a href="" target="_top">About</a></li>
                            <li><a href="" target="_top">Contact us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="article display-table-row">
                <div class="display-table article-container spr-tracking-dashboard">
                    <div class="main-article display-table">
                        <div class="main-article-nav-container display-table-row">
                            <ul class="float-box-nav main-article-nav">
                                <li><a class="selected-tab" href="#" target="_top">Dashboard</a></li>
                                <li><a href="#" target="_top">Submission Status</a></li>
                                <li><a href="#" target="_top">Report</a></li>
                                <li><a href="#" target="_top">Import</a></li>
                            </ul>
                        </div>
                        <div class="main-article-tab-container display-table-row">
                            <div class="main-article-tab-info-container">
                                <div class="main-article-info-header">
                                    <div class="header-tag">
                                        <h1>SPR Tracking Dashboard</h1>
                                    </div>
                                </div>
                                <div class="article-container">
                                    <div class="project-backlog-container">
                                        <div class="spr-tracking-menu-container">
                                            <div id="session-btn" class="session-btn">
                                                <select id="session-select" class="retro-style session-select" onchange="javascript:showDashboardAccdSession('spr-tracking-dashboard-tbody', 'fillSPRTrackingDashboardRow')">
                                                    <option value="All">All</option>
                                                    <option value="2017" selected="">2017</option>
                                                    <option value="2016">2016</option>
                                                    <option value="2015">2015</option>
                                                    <option value="2014">2014</option>
                                                    <option value="2013">2013</option>
                                                    <option value="2012">2012</option>
                                                    <option value="2011">2011</option>
                                                    <option value="2010">2010</option>
                                                    <option value="2009">2009</option>
                                                    <option value="2008">2008</option>
                                                    <option value="2007">2007</option>
                                                    <option value="2006">2006</option>
                                                    <option value="2005">2005</option>
                                                    <option value="2004">2004</option>
                                                    <option value="2003">2003</option>
                                                    <option value="2002">2002</option>
                                                    <option value="2001">2001</option>
                                                    <option value="2000">2000</option>
                                                </select>
                                            </div>
                                            <div style="float: right; margin-right: 10px;">
                                                <button class="retro-style green add-spr" type="button">
                                                    <span>Add SPR to Track</span>
                                                </button>
                                                <button class="retro-style red" type="button">
                                                    <span>Delete SPR(s)</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="spr-tracking-table-dropdown" class="dropdown-content">
                                            <a href="#">Save</a>
                                            <a href="#">Cancel</a>
                                        </div>
                                        <div class="spr-tracking-table-container">
                                            <table id="spr-tracking-table" class="grippy-table spr-tracking-table">
                                                <thead id="spr-tracking-thead">
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th data-sort="int">Item Number</th>
                                                        <th data-sort="string">Type</th>
                                                        <th data-sort="string">Status</th>
                                                        <th data-sort="string">Build Version</th>
                                                        <th data-sort="string">Commit Build</th>
                                                        <th data-sort="string">Respond By</th>
                                                        <th>Comment</th>
                                                        <th data-sort="string">Session</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="spr-tracking-tbody">
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:10%;">INTEGRITY SPR</td>
                                                        <td style="width:15%; background-color:#5CD82F">SUBMITTED</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                        <td>
                                                            <div id="spr-tracking-table-btn-1" class="quick-action-btn">
                                                                <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Save</a>
                                                                <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'spr-tracking-table-btn-1', 'spr-tracking-table-dropdown')" onblur="showHideEditMenu('hide', 'spr-tracking-table-btn-1', 'spr-tracking-table-dropdown')">
                                                                    <span>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                            <g>
                                                                                <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%; background-color: rgb(238, 129, 45);">NOT AN ISSUE</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align:center; width:2%;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="width:12%;"><a href="#">6197350</a></td>
                                                        <td style="width:8%;">INTEGRITY SPR</td>
                                                        <td style="width:15%;">INVESTIGATING</td>
                                                        <td style="width:12%;">P10,P20,P30</td>
                                                        <td style="width:12%;">P-10-42</td>
                                                        <td style="width:12%;">2016-11-06</td>
                                                        <td></td>
                                                        <td style="width:8%;">2017</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer display-table-row">
                <div class="banner display-table">
                    <div class="logo display-table-cell">
                        <a id="footer-logo" class="footer-logo" href="index.php">
                            <img src="../images/ptc-master-color_small.png" alt="ptc.com">
                        </a>
                    </div>
                    <div class="footer-nav display-table-cell">
                        <ul>
                            <li><a href="index.php">HOME</a> | </li>
                            <li><a class="up">TOP</a> | </li>
                            <li><a href="">ABOUT</a></li>
                        </ul>
                    </div>
                </div>
                <div class="copyright">
                    PTC project management is optimized for SPR tracking, Scrum.
                    Its help user to maintain SPR records and scrum methodology.<br>
                    While using this site, you agree to have read and accepted our
                    <a href="about/copyright.php">terms of use</a> and <a href="about/privacy.php">privacy policy</a>.<br>
                    <a href="about/copyright.php">Copyright 1999-2016</a> by Abhishek Nath. All Rights Reserved.<br><br>
                </div>
            </div>
        </div>
    </body>-->
</html>
