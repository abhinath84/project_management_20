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
        <title>HTML - Holy Grail Layout with Sticky Footer</title>
        <link rel="stylesheet" type="text/css" href="../css/global.css">
        <link rel="stylesheet" type="text/css" href="../css/sprint_taskboard.css">
        <script src="../js/addtable.js"></script>
    </head>
    <?php
        $htmlBody = new ScrumSTTaskboardHTML();

        echo $htmlBody->generateBody();
    ?>
    <!--<body>
        <div class="wrapper display-table">
            <div class="header display-table-row">
                <div class="banner display-table">
                    <div class="logo display-table-cell">
                        <a href="#">
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
                                <a href="">SPR Tracking</a>
                                <ul>
                                    <li><a href="../spr_tracking/dashboard.php" target="_top">Dashboard</a></li>
                                    <li><a href="#" target="_top">Submit Status</a></li>
                                    <li><a href="#" target="_top">Report</a></li>
                                </ul>
                            </li>
                            <li><a href="#" target="_top">Work Tracker</a></li>
                            <li>
                                <a class="selected" href="">Scrum</a>
                                <ul>
                                    <li><a href="#" target="_top">Product Planning</a></li>
                                    <li><a href="#" target="_top">Release Planning</a></li>
                                    <li><a href="#" target="_top">Sprint Planning</a></li>
                                    <li><a href="#" target="_top">Sprint Tracking</a></li>
                                    <li><a href="#" target="_top">Sprint Review</a></li>
                                </ul>
                            </li>
                            <li><a href="" target="_top">About</a></li>
                            <li><a href="" target="_top">Contact us</a></li>
                        </ul>
                    </div>
                    <div class="sub-nav-container display-table-row">
                        <ul class ="float-box-nav sub-nav">
                            <li>
                                <a href="#" target="_top">
                                    <span>Product Planning</span>
                                    <span class="rsaquo-span">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.846 451.847" style="enable-background:new 0 0 451.846 451.847;" xml:space="preserve" fill="#FFFFFF">
                                            <g>
                                                <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744   L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284   c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"/>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <ul>
                                    <li><a href="product_plan_backlog.php" target="_top">Backlog</a></li>
                                    <li><a href="#" target="_top">Import</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" target="_top">
                                    <span>Release Planning</span>
                                    <span class="rsaquo-span">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.846 451.847" style="enable-background:new 0 0 451.846 451.847;" xml:space="preserve" fill="#FFFFFF">
                                            <g>
                                                <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744   L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284   c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"/>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_top">
                                    <span>Sprint Planning</span>
                                    <span class="rsaquo-span">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.846 451.847" style="enable-background:new 0 0 451.846 451.847;" xml:space="preserve" fill="#FFFFFF">
                                            <g>
                                                <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744   L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284   c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"/>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="selected-after" href="#" target="_top">
                                    <span>Sprint Tracking</span>
                                    <span class="rsaquo-span">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.846 451.847" style="enable-background:new 0 0 451.846 451.847;" xml:space="preserve" fill="#FFFFFF">
                                            <g>
                                                <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744   L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284   c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"/>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <ul>
                                    <li><a href="#" target="_top">Detailed Tracking</a></li>
                                    <li><a href="#" target="_top">Taskboard</a></li>
                                    <li><a href="#" target="_top">Storyboard</a></li>
                                    <li><a href="#" target="_top">Testboard</a></li>
                                </ul>
                            </li>
                            <li><a href="#" target="_top">Sprint Review</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="article display-table-row">
                <div class="article-container display-table">
                    <div class="project-item display-table">
                        <button class="project-selector" type="button">
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
                                <li><a href="#" target="_top">Detailed Tracking</a></li>
                                <li><a href="#" target="_top">Storyboard</a></li>
                                <li><a class="selected-tab" href="#" target="_top">Taskboard</a></li>
                                <li><a href="#" target="_top">Testboard</a></li>
                            </ul>
                        </div>
                        <div class="main-article-tab-container display-table-row">
                            <div class="main-article-tab-info-container">
                                <div class="main-article-info-header">
                                    <h1>Taskboard</h1>
                                </div>
                                <div class="sprint-planning-container">
                                    <table id="addrow" class="sprint-taskboard-table">
                                        <thead>
                                            <tr>
                                                <th class="backlog-th">Backlog</th>
                                                <th>(None)</th>
                                                <th>Blocked</th>
                                                <th>In Progress</th>
                                                <th>Completed</th>
                                                <th class="summary-th">Summary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="backlog-td">
                                                    <div class="story-card-container">
                                                        <div class="identity">
                                                            <div class="identity-left">
                                                                <span class="number">s00001</span>
                                                            </div>
                                                            <div class="identity-right">
                                                                <span class="story-card-actions">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                        <g>
                                                                            <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                        </g>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="title">
                                                            <a href="#">6534211</a>
                                                        </div>
                                                        <div class="backlog-td-status" >In Progress</div>
                                                        <div class="backlog-td-bottom">
                                                            <span class="backlog-td-bottom-owner-name"><a href="#">Pooja Kumari</a></span>
                                                            <span class="backlog-td-bottom-time">15.00</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="status-td">
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Resolve</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">2.00</span>
                                                        </div>
                                                    </div>
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Integrate</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">2.00</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="status-td">
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Communication</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">1.00</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="status-td">
                                                </td>
                                                <td class="status-td">
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Analysis</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">2.00</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="summary-td">
                                                    <dl class="non-card">
                                                        <dt>
                                                            Test Results:
                                                        </dt>
                                                        <dd class="value">

                                                        </dd>
                                                        <dt>
                                                            To Do:
                                                        </dt>
                                                        <dd class="value">
                                                            12.00
                                                        </dd>
                                                    </dl>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="backlog-td story-card-container">
                                                    <div class="story-card-container">
                                                        <div class="identity">
                                                            <div class="identity-left">
                                                                <span class="number">s00002</span>
                                                            </div>
                                                            <div class="identity-right">
                                                                <span class="story-card-actions">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                        <g>
                                                                            <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                        </g>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="title">
                                                            <a href="#">6534215</a>
                                                        </div>
                                                        <div class="backlog-td-status" >In Progress</div>
                                                        <div class="backlog-td-bottom">
                                                            <span class="backlog-td-bottom-owner-name"><a href="#">Pooja Kumari</a></span>
                                                            <span class="backlog-td-bottom-time">15.00</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="status-td">
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Testing</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">2.00</span>
                                                        </div>
                                                    </div>
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Code Review</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">2.00</span>
                                                        </div>
                                                    </div>
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Integrate</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">2.00</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="status-td">
                                                </td>
                                                <td class="status-td">
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Communication</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">0.50</span>
                                                        </div>
                                                    </div>
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Resolve</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">1.00</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="status-td">
                                                    <div class="task-card">
                                                        <div class="task-card-title">
                                                            <span class="task-card-title-name">Analysis</span>
                                                            <span class="task-card-arrow" style="float:right;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">
                                                                    <g>
                                                                        <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="task-card-owner">
                                                            <span class="task-card-owner-name">Pooja Kumari</span>
                                                            <span class="task-card-owner-time" style="float:right">2.00</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="summary-td">
                                                    <dl class="non-card">
                                                        <dt>
                                                            Test Results:
                                                        </dt>
                                                        <dd class="value">

                                                        </dd>
                                                        <dt>
                                                            To Do:
                                                        </dt>
                                                        <dd class="value">
                                                            10.50
                                                        </dd>
                                                    </dl>
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
            <div class="footer display-table-row">
                <div class="banner display-table">
                    <div class="logo display-table-cell">
                        <a id="footer-logo" class="footer-logo" href="index.php">
                            <img src="../../images/ptc-master-color_small.png" alt="ptc.com">
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
