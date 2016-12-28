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
    if(!isset($_SESSION['project-managment-username']))
        header("Location: ../user/login.php?redirect=../scrum/product_plan_backlog.php");
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Scrum-Product Planning</title>
        <link rel="stylesheet" type="text/css" href="../css/global.css">
        <link rel="stylesheet" type="text/css" href="../css/product_backlog.css">
        <script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="../js/stupidtable.min.js?dev"></script>
        <script type="text/javascript" src="../js/jqry.js"></script>
        <script type="text/javascript" src="../js/addtable.js"></script>
        <script>
            $(document).ready(function(){
               $("table").fixMe();
               $(".up").click(function() {
                  $('html, body').animate({
                  scrollTop: 0
               }, 2000);
             });
            });

        </script>
    </head>
    <body>
        <?php
            $htmlBody = new ScrumPPBHTML();

            echo $htmlBody->generateBody();
        ?>
    <!--<body>
        <div class="wrapper display-table">
            <div class="article display-table-row">
                <div class="article-container display-table">
                    <div class="project-item display-table">
                        <button class="project-selector asset-hover" type="button">
                            <h3>Product Backlog</h3>
                            <span class="ps-icon">
                                <svg class="project-selector-icon" viewBox="0 0 250 250" width="20" height="20">
                                  <rect x="50" y="20" rx="10" ry="10" width="60" height="60"
                                  	style="fill:black;stroke:#00a9e0;stroke-width:25;" />
                                  <line x1="80" y1="80" x2="80" y2="150"
                                      stroke-width="25" stroke="#00a9e0"/>
                                  <line x1="67" y1="150" x2="150" y2="150"
                                      stroke-width="25" stroke="#00a9e0"/>
                                  <rect x="150" y="120" rx="10" ry="10" width="60" height="60"
                                  	style="fill:black;stroke:#00a9e0;stroke-width:25;" />
                                Sorry, your browser does not support inline SVG.
                                </svg>
                            </span>
                        </button>
                    </div>
                    <div class="main-article display-table">
                        <div class="main-article-nav-container display-table-row">
                            <ul class="float-box-nav main-article-nav">
                                <li><a class="selected-tab" href="#" target="_top">Backlog</a></li>
                                <li><a href="#" target="_top">Import</a></li>
                            </ul>
                        </div>
                        <div class="main-article-tab-container display-table-row">
                            <div class="main-article-tab-info-container">
                                <div class="main-article-info-header">
                                    <div class="header-tag">
                                        <h1>Backlog</h1>
                                    </div>
                                    <div id="story-inline-btn" class="quick-action-btn story-inline-btn">
                                        <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Add to Story Inline</a>
                                        <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'story-inline-btn', 'story-inline-dropdown')" onblur="showHideEditMenu('hide', 'story-inline-btn', 'story-inline-dropdown')">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                    <g>
                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                    <div id="story-inline-dropdown" class="dropdown-content">
                                      <a href="#">Add Story Inline</a>
                                      <a href="#">Add Story</a>
                                      <a href="#">Add Defect Inline</a>
                                      <a href="#">Add Defect</a>
                                    </div>
                                </div>
                                <div class="article-container">
                                    <div class="project-backlog-container">
                                        <div id="move-to-project-btn" class="quick-action-btn move-to-project-btn">
                                            <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Move to Project</a>
                                            <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'move-to-project-btn', 'move-to-project-dropdown')" onblur="showHideEditMenu('hide', 'move-to-project-btn', 'move-to-project-dropdown')">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                        <g>
                                                            <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                        <div id="move-to-project-dropdown" class="dropdown-content">
                                            <a href="#">Move To Project</a>
                                            <a href="#">Move To Iteration</a>
                                            <a href="#">Quick Close</a>
                                            <a href="#">Close</a>
                                            <a href="#">Reopen</a>
                                            <a href="#">Delete</a>
                                            <a href="#">Rank</a>
                                        </div>
                                        <div id="backlog-table-dropdown" class="dropdown-content">
                                            <a class="dashed-bottom-border" href="#">Edit</a>
                                            <a href="#">Plan Story</a>
                                            <a href="#">Add Task</a>
                                            <a class="dashed-bottom-border" href="#">Copy</a>
                                            <a href="#">Quick Close</a>
                                            <a class="dashed-bottom-border" href="#">Close</a>
                                            <a href="#">Convert to Defect</a>
                                            <a href="#">Delete</a>
                                        </div>
                                        <div class="project-backlog-table-container">
                                            <table id="project-backlog-table" class="grippy-table">
                                                <thead id="project-backlog-thead">
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th style="text-align: center;">
                                                            <input id="_fjvevqi" _v1_multiselector="1" type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll('_fjvevqi');" name="selectAll">
                                                        </th>
                                                        <th data-sort="int">Order</th>
                                                        <th data-sort="string"><a class="sortable">Title</a></th>
                                                        <th data-sort="string"><a class="sortable">ID</a></th>
                                                        <th data-sort="string"><a class="sortable">Owner</a></th>
                                                        <th data-sort="string"><a class="sortable">Priority</a></th>
                                                        <th data-sort="string"><a class="sortable">Estimate Pts</a></th>
                                                        <th data-sort="string"><a class="sortable">Project</a></th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="project-backlog-tbody">
                                                    <tr align="center">
                                                        <td class="hasGrippy" style="text-align: center;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input _v1_multiselector="1" type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll('_fjvevqi');" name="selectAll">
                                                        </td>
                                                        <td>1</td>
                                                        <td>
                                                            <div class="backlog-title-container">
                                                                <span class="backlog-image-container">
                                                                    <img alt="12345" src="../../images/Defect-Icon.gif" title="12345">
                                                                </span>
                                                                <a>6534211</a>
                                                            </div>
                                                        </td>
                                                        <td>s00001</td>
                                                        <td>Pooja</td>
                                                        <td>High</td>
                                                        <td>15.0</td>
                                                        <td>Release 1.0</td>
                                                        <td class="gg" align="right" style="text-align: right;">
                                                            <div id="backlog-table-btn-1" class="quick-action-btn backlog-table-btn">
                                                                <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Edit</a>
                                                                <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'backlog-table-btn-1', 'backlog-table-dropdown')" onblur="showHideEditMenu('hide', 'backlog-table-btn-1', 'backlog-table-dropdown')">
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
                                                        <td class="hasGrippy" style="text-align: center;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input id="_fjvevqi" _v1_multiselector="1" type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll('_fjvevqi');" name="selectAll">
                                                        </td>
                                                        <td>2</td>
                                                        <td>
                                                            <div class="backlog-title-container">
                                                                <span class="backlog-image-container">
                                                                    <img alt="12345" src="../../images/Defect-Icon.gif" title="12345">
                                                                </span>
                                                                <a>6534215</a>
                                                            </div>
                                                        </td>
                                                        <td>s00002</td>
                                                        <td>Pooja</td>
                                                        <td>Low</td>
                                                        <td>10.0</td>
                                                        <td>Release 1.0</td>
                                                        <td class="gg" align="right" style="text-align: right;">
                                                            <div id="backlog-table-btn-2" class="quick-action-btn backlog-table-btn">
                                                                <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Edit</a>
                                                                <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'backlog-table-btn-2', 'backlog-table-dropdown')" onblur="showHideEditMenu('hide', 'backlog-table-btn-2', 'backlog-table-dropdown')">
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
                                                        <td class="hasGrippy" style="text-align: center;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input id="_fjvevqi" _v1_multiselector="1" type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll('_fjvevqi');" name="selectAll">
                                                        </td>
                                                        <td>3</td>
                                                        <td>
                                                            <div class="backlog-title-container">
                                                                <span class="backlog-image-container">
                                                                    <img alt="12345" src="../../images/Defect-Icon.gif" title="12345">
                                                                </span>
                                                                <a>6534221</a>
                                                            </div>
                                                        </td>
                                                        <td>s00003</td>
                                                        <td>Abhishek</td>
                                                        <td>High</td>
                                                        <td>10.0</td>
                                                        <td>Release 1.0</td>
                                                        <td class="gg" align="right" style="text-align: right;">
                                                            <div id="backlog-table-btn-3" class="quick-action-btn backlog-table-btn">
                                                                <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Edit</a>
                                                                <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'backlog-table-btn-3', 'backlog-table-dropdown')" onblur="showHideEditMenu('hide', 'backlog-table-btn-3', 'backlog-table-dropdown')">
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
                                                        <td class="hasGrippy" style="text-align: center;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input id="_fjvevqi" _v1_multiselector="1" type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll('_fjvevqi');" name="selectAll">
                                                        </td>
                                                        <td>4</td>
                                                        <td>
                                                            <div class="backlog-title-container">
                                                                <span class="backlog-image-container">
                                                                    <img alt="12345" src="../../images/Defect-Icon.gif" title="12345">
                                                                </span>
                                                                <a>6534225</a>
                                                            </div>
                                                        </td>
                                                        <td>s00004</td>
                                                        <td>Abhishek</td>
                                                        <td>High</td>
                                                        <td>10.0</td>
                                                        <td>Release 1.0</td>
                                                        <td class="gg" align="right" style="text-align: right;">
                                                            <div id="backlog-table-btn-4" class="quick-action-btn backlog-table-btn">
                                                                <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Edit</a>
                                                                <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'backlog-table-btn-4', 'backlog-table-dropdown')" onblur="showHideEditMenu('hide', 'backlog-table-btn-4', 'backlog-table-dropdown')">
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
                                                        <td class="hasGrippy" style="text-align: center;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input id="_fjvevqi" _v1_multiselector="1" type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll('_fjvevqi');" name="selectAll">
                                                        </td>
                                                        <td>5</td>
                                                        <td>
                                                            <div class="backlog-title-container">
                                                                <span class="backlog-image-container">
                                                                    <img alt="12345" src="../../images/Defect-Icon.gif" title="12345">
                                                                </span>
                                                                <a>6534228</a>
                                                            </div>
                                                        </td>
                                                        <td>s00005</td>
                                                        <td>Abhishek</td>
                                                        <td>high</td>
                                                        <td>10.0</td>
                                                        <td>Release 1.0</td>
                                                        <td class="gg" align="right" style="text-align: right;">
                                                            <div id="backlog-table-btn-5" class="quick-action-btn backlog-table-btn">
                                                                <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Edit</a>
                                                                <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'backlog-table-btn-5', 'backlog-table-dropdown')" onblur="showHideEditMenu('hide', 'backlog-table-btn-5', 'backlog-table-dropdown')">
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
                                                        <td class="hasGrippy" style="text-align: center;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input id="_fjvevqi" _v1_multiselector="1" type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll('_fjvevqi');" name="selectAll">
                                                        </td>
                                                        <td>6</td>
                                                        <td>
                                                            <div class="backlog-title-container">
                                                                <span class="backlog-image-container">
                                                                    <img alt="12345" src="../../images/Defect-Icon.gif" title="12345">
                                                                </span>
                                                                <a>6534231</a>
                                                            </div>
                                                        </td>
                                                        <td>s00006</td>
                                                        <td>Abhishek</td>
                                                        <td>High</td>
                                                        <td>10.0</td>
                                                        <td>Release 1.0</td>
                                                        <td class="gg" align="right" style="text-align: right;">
                                                            <div id="backlog-table-btn-6" class="quick-action-btn backlog-table-btn">
                                                                <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Edit</a>
                                                                <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'backlog-table-btn-6', 'backlog-table-dropdown')" onblur="showHideEditMenu('hide', 'backlog-table-btn-6', 'backlog-table-dropdown')">
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
                                                        <td class="hasGrippy" style="text-align: center;">
                                                            <i class="grippy">
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                                <i class="dot"></i>
                                                            </i>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input id="_fjvevqi" _v1_multiselector="1" type="checkbox" clickhandler="V1.Gadgets.Grid.MultiSelect.ToggleAll('_fjvevqi');" name="selectAll">
                                                        </td>
                                                        <td>7</td>
                                                        <td>
                                                            <div class="backlog-title-container">
                                                                <span class="backlog-image-container">
                                                                    <img alt="12345" src="../../images/Feature-Icon.gif" title="12345">
                                                                </span>
                                                                <a>Scrum Web-page Design</a>
                                                            </div>
                                                        </td>
                                                        <td>s00007</td>
                                                        <td>Abhishek</td>
                                                        <td>High</td>
                                                        <td>10.0</td>
                                                        <td>Release 1.0</td>
                                                        <td class="gg" align="right" style="text-align: right;">
                                                            <div id="backlog-table-btn-7" class="quick-action-btn backlog-table-btn">
                                                                <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Edit</a>
                                                                <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu('show', 'backlog-table-btn-7', 'backlog-table-dropdown')" onblur="showHideEditMenu('hide', 'backlog-table-btn-7', 'backlog-table-dropdown')">
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
        </div>
    </body>-->
    </body>
</html>
