<?php
    /*
        File    : functions.inc.php
        Author  : Abhishek Nath
        Date    : 01-Jan-2015
        Desc    : Common functions (php) to use in server-side files.
    */

    /*
        01-Jan-15   V1-01-00   abhishek   $$1   Created.
        17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
        14-Aug-15   V1-01-00   abhishek   $$3   Change BG color for 'RESOLVED' and 'CLOSED' status.
        14-Aug-15   V1-01-00   abhishek   $$3   Write getNavURL() function and updated calling files.
        22-Aug-16   V1-01-00   abhishek   $$4   Removed unwanted functions.
    */

    /* include header file */
    require_once ('variables.inc.php');
    require_once ('navigator.php');
    require_once ('htmltable.php');
    require_once ('adminhtml.php');



    /**
    * add select tag.
    *
    * add select tag according to input options.
    *
    * @param string $id
    *   select tag id.
    * @param string $caption
    *   select tag caption.
    * @param Array $options
    *   options of select tag.
    * @param event $event
    *   event of select tag.
    *
    * @return string $tag
    *   select tag string.
    */
    function addSelectTag($id, $caption, $options, $event)
    {
        $tag = '<div class="retro-style-form-element" id="'.$id.'-form-element">
                <label id="'.$id.'-label">
                <strong id="'.$id.'Label">'.$caption.'</strong>
                </label>
                <div id="'.$id.'Holder" >
                <select id="'.$id.'-select" class="retro-style" name="'.$id.'"';

        if($event !="")
            $tag .= ' '.$event;

        $tag .= '>';
        $tag .= '        <option value="">Select ...</option>'."\n";

        foreach($options as $option)
            $tag .='<option value="'.$option.'" >'.$option.'</option>'."\n";

        $tag .= '</select>
                </div>
                <div class="retro-style-errmsg" id="'.$id.'-errmsg"></div>
                </div>'."\n";

        return($tag);
    }

    /**
    * add Input tag.
    *
    * add Input tag according to input.
    *
    * @param string $tagName
    *   tag name.
    * @param string $type
    *   tag type.
    * @param Array $options
    *   options of select tag.
    * @param event $event
    *   event of select tag.
    *
    * @return string $tag
    *   select tag string.
    */
    function addInputTag($tagName, $type, $name, $caption, $event, $placeholder)
    {
        $tagStr = '<div class="retro-style-form-element" id="'.$name.'-form-element">';

        $tagStr .= '                <label id="'.$name.'-label">
                                <strong>'.$caption.'</strong>
                            </label>';

        if(($tagName <> "") && ($type <> ""))
        {
            $tagStr .= '                <'.$tagName.' class="retro-style" type="'.$type.'"';

            if(($tagName == "input") && ($placeholder))
                $tagStr .= '  placeholder="'.$placeholder.'"';

            if($event != "")
                $tagStr .= ' '. $event;

            $tagStr .= ' maxlength="75" autocomplete="off" name="'.$name.'" id="'.$name.'-'.$tagName.'" value="" spellcheck="false">
                            <div class="retro-style-errmsg" id="'.$name.'-errmsg"></div>';
        }
        $tagStr .= '</div>'."\n";

        return($tagStr);
    }

    function getGreppyDotTag()
    {
        $tag = '<i class="grippy">' . EOF_LINE;
        $tag .= '    <i class="dot"></i>' . EOF_LINE;
        $tag .= '    <i class="dot"></i>' . EOF_LINE;
        $tag .= '    <i class="dot"></i>' . EOF_LINE;
        $tag .= '    <i class="dot"></i>' . EOF_LINE;
        $tag .= '    <i class="dot"></i>' . EOF_LINE;
        $tag .= '</i>' . EOF_LINE;

        return($tag);
    }

    function getSPRString($spr_type, $spr_no)
    {
        $tag = "";

        if($spr_type == "SPR")
        {
            $tag .= '<a href="http://rdweb.ptc.com/WebSiebel/report.php?value='.$spr_no.
                                '+&spr_no='.$spr_no.'+&call_no=&mode=details&form=spr&do_not_check_ver=1" target="_blank">'.$spr_no.'</a>';
        }
        else if($spr_type == "REGRESSION")
        {
            $tag .= '<a href="http://regdb.ptc.com/regdb/servlet/Index?regbugid='.$spr_no.'&mode=basic" target="_blank">'.$spr_no.'</a>';
        }
        else if($spr_type == "INTEGRITY SPR")
        {
            $tag .= '<a href="http://integrity.ptc.com:7001/im/viewissue?selection='.$spr_no.'" target="_blank">'.$spr_no.'</a>';
        }
        else
            $tag .= $spr_no;


        return($tag);
    }

    function getSessionList()
    {
        $start_year        = 2000;
        $current_year     = intval(getCurrentSession());
        $sessionList        = array();

        for($yr = $current_year; $yr >= $start_year; $yr--)
            array_push($sessionList, strval($yr));

        return($sessionList);

    }

    function getSessionSelectTag($current_session, $func_name)
    {
        $tag = "";
        $sessions = getSessionList();
        array_unshift($sessions, "All");

        if(($current_session != "") && ($func_name != ""))
        {
            $tag .= '<div id="session-container" style="display: inline-block; *display: inline; zoom: 1;
                    vertical-align: top; font-size: 12px; width: 17%;">
                    <label id="session-label">
                    <strong id="session-strong">Session </strong>
                    </label>'."\n";
            $tag .= '<select id="session-select" onchange="javascript:'.$func_name.'">'."\n";

            foreach($sessions as $session)
            {
                if($session == $current_session)
                    $tag .='<option value="'.$session.'" selected>'.$session.'</option>'."\n";
                else
                    $tag .='<option value="'.$session.'">'.$session.'</option>'."\n";
            }

            $tag .= '</select>'."\n";
            $tag .= '</div>'."\n";
        }

        return($tag);
    }

    function addGenderTag($event)
    {
        $gender = array('Female', 'Male');

        return(addSelectTag('gender', 'Gender', $gender, $event));
    }

    function addDepertmentTag($event)
    {
        $dept = array('Assmbly', 'Drawing', 'QA', 'Technical Writer', 'Manufacturing', 'Others');

        return(addSelectTag('department', 'Department', $dept, $event));
    }

    function addTitleTag($event)
    {
        $titles = array('Software Specialist', 'Senior Software Specialist', 'Tech Lead', 'Senior Tech Lead', 'Technical Consultant', 'Senior Technical Consultant',
                            'QA', 'Senior QA', 'Tech Lead QA', 'Senior Tech Lead QA', 'Consultant QA', 'Others');

        return(addSelectTag('title', 'Title', $titles, $event));
    }

    function appendClause(&$clause, $element, $value)
    {
        if((isset($value)) && ($value != "" || $value != "Select..."))
        {
            if($clause != "")
                $clause .=" AND ";
            $clause .= $element . " LIKE '%" . $value . "%'";
        }
    }

    function appendClauseArr(&$clause, $clauseArr)
    {
        foreach($clauseArr as $each)
            appendClause($clause, $each[0], $each[1]);
    }

    function addEntryCaption($element, $type)
    {
        $tag = "";

        if((isset($element)) && ($element != ""))
            $tag .= "<h1>Edit ". $type ." Information</h1>";
        else
            $tag .= "<h1>Add ". $type ." Information</h1>";

        $tag .= "<hr id='entry-form-hr' class='entry-form-hr'>";

        return($tag);
    }

    function getCurrentSession()
    {
        $date = date("m-Y");
        list($month, $year) = explode('-', $date);

        return(($month >= 10) ? ($year + 1) : $year);
    }

    function getNextCutOff($file)
    {
        $build_info = array();

        $doc = new DOMDocument();
        $doc->load( $file );

        $next_build = $doc->getElementsByTagName( "NEXT_BUILD" );
        if(!empty($next_build))
        {
            $builds = $next_build->item(0)->getElementsByTagName( "BUILD" );
            foreach($builds as $build)
            {
                $version     = $build->getElementsByTagName( "VERSION" )->item(0)->nodeValue;
                $date        = $build->getElementsByTagName( "DATE" )->item(0)->nodeValue;

                array_push($build_info, [$version, $date]);
            }
        }

        return($build_info);
    }


    // *********************************************************** //

    /**
    * Add header block.
    *
    * Add header block including header caption and navigator.
    *
    * @return string $tag
    *   header tag string.
    */
    /*function addHeader($selNav, $enableNav, $currentDir)
    {
        $nav = new Navigator();
        $tag = $nav->header($currentDir, $selNav, $enableNav);

        return($tag);
    }*/

    /**
    * Add footer block for this app.
    *
    * Add footer block including footer caption and navigator.
    *
    * @return string $tag
    *   header tag string.
    */
    /*function addFooter($currentDir, $hrTagFlag = true)
    {
        $nav = new Navigator();
        $tag = $nav->footer($currentDir, $hrTagFlag);

        return($tag);
    }*/

    function addSPRTrackingInfo($spr_no, $user)
    {
        global $conn;
        $tag = "";

        // fatch value from database.
        try
        {
            $qry = "SELECT spr_no, status, comment, session, build_version, submit_version, prev_status FROM spr_tracking
                    WHERE spr_no='" . $spr_no . "'"." AND user_name='". $user ."';";

            //echo $qry;
            $result = $conn->result_fetch_array($qry);
            if(!empty($result))
            {
                $tag = '<div id="spr-tracking-info-div" style="display:none;">'."\n";
                $tag .= '<label id="spr-no-lbl">'. $result[0][0] .'</label>'."\n";
                $tag .= '<label id="status-lbl">'. $result[0][1] .'</label>'."\n";
                $tag .= '<label id="session-lbl">'. $result[0][3] .'</label>'."\n";
                $tag .= '<label id="build-version-lbl">'. $result[0][4] .'</label>'."\n";
                $tag .= '<label id="submit-version-lbl">'. $result[0][5] .'</label>'."\n";
                $tag .= '<label id="comment-lbl">'. $result[0][2] .'</label>'."\n";
                $tag .='</div>'."\n";
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }

        echo $tag;
    }

    function getSPRSubmissionColor($val)
    {
        $color = "";

        switch ($val)
        {
            case "YES":
                $color = "#5CD82F";
                break;
            case "N/A":
                $color = "#00FF00";
                break;
            case "REOPENED":
                $color = "#EE812D";
                break;
            default:
                $color = "white";
        }

        return($color);
    }

    function getSPRTrackingStatusColor($val)
    {
        $color = "white";

        switch ($val)
        {
            case "SUBMITTED":
                $color = "#5CD82F";
                break;
            case "NOT AN ISSUE":
                $color = "#EE812D";
                break;
            case "CLOSED":
            case "RESOLVED":
                $color = "#58A3F7";
                break;
            case "PASS FOR TESTING":
                $color = "#E2E331";
                break;
            default:
                $color = "white";
        }

        return($color);
    }

    function shortDescription($desc, $len = 0)
    {
        $shortDesc = "";
        $split_pos = 0;

        if(($len > 0) && ($desc <> ""))
        {
            // get first ' ' position
            $pos = strpos($desc, ' ');
            if ($pos !== false)
                $split_pos = (($pos < ($len/2)) ? $len : $pos);
            else
                $split_pos = $len;

            // split string
            $shortDesc = substr($desc, 0, $split_pos);

            // add '...'
            $shortDesc .= ' ...';
        }
        else
            $shortDesc = $desc;

        return($shortDesc);
    }

    function fillSPRTrackingDashboardRow($session)
    {
        global $conn;
        $tag = "";

        $qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session  FROM `spr_tracking` "; /*WHERE user_name = '". $_SESSION['project-managment-username'] ."'";*/
        if($session <> "All")
            $qry .= "WHERE session='" . $session . "'";

        $rows = $conn->result_fetch_array($qry);
        if(!empty($rows))
        {
            // loop over the result and show the element
            foreach($rows as $row)
            {
                $tag .= '            <tr>'."\n";
                $tag .= '              <td id="'. $row[0]. '-greppy" class="hasGrippy", style="text-align:center;">';
                $tag .= getGreppyDotTag();
                $tag .= '              </td>';

                $tag .= '              <td id="'.$row[0].'-spr-no" width = 12%>';
                $tag .= '                   <a href="#">' . getSPRString($row[1], $row[0]) . '</a>';
                $tag .= '              </td>'."\n";

                $tag .= '              <td id="'.$row[0].'-type" width = 10%>' . $row[1] . '</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-status" style="background-color:'.getSPRTrackingStatusColor($row[2]).'" width = 15% ondblclick="javascript:showSPREditTag(\''.$row[0].'-status\', \'select\', true)">'.$row[2].'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-build-version" width = 12% ondblclick="javascript:showSPREditTag(\''.$row[0].'-build-version\', \'input\', true)">'.$row[3].'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-commit-build" width = 12% ondblclick="javascript:showSPREditTag(\''.$row[0].'-commit-build\', \'input\', true)">'.$row[4].'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-respond-by" width = 12% ondblclick="javascript:showSPREditTag(\''.$row[0].'-respond-by\', \'input\', true)">'.$row[5].'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-comment" ondblclick="javascript:showSPREditTag(\''.$row[0].'-comment\', \'textarea\', true)"
                                                                    onmouseover="javascript:showFullComment(\''.$row[0].'\', true)"
                                                                    onblur="javascript:showFullComment(\''.$row[0].'\', false)">'.shortDescription($row[6], 25).'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-comment-full" style="display: none;">'.$row[6].'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-session" width = 8% ondblclick="javascript:showSPREditTag(\''.$row[0].'-session\', \'input\', true)">'.$row[7].'</td>'."\n";
                $tag .= '            </tr>'."\n";
            }
        }

        return(utf8_encode($tag));
    }

    function fillSPRTrackingSubmissionStatusRow($session)
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

            $qry .= ", spr_tracking.type FROM spr_submission INNER JOIN spr_tracking
                    ON spr_submission.spr_no=spr_tracking.spr_no and spr_tracking.user_name='". $_SESSION['project-managment-username'] ."'";
            if($session <> "All")
                $qry .= " and spr_tracking.session='". $session ."'";

            $rows = $conn->result_fetch_array($qry);
            if(!empty($rows))
            {
                // loop over the result and show the element
                foreach($rows as $row)
                {
                    $tag .= '            <tr>'."\n";

                    $tag .= '              <td id="'. $row[0]. '-greppy" class="hasGrippy" style="width:2%;">';
                    $tag .=                     getGreppyDotTag();
                    $tag .= '              </td>';

                    $tag .= '              <td id="'.$row[0].'-spr-no" width = 12%>';
                    $tag .= '                   <a href="#">' . getSPRString($row[1], $row[0]) . '</a>';
                    $tag .= '              </td>'."\n";

                    $tag .= '              <td id="'.$row[0].'-L03" style="background-color:'.getSPRSubmissionColor($row[1]).'" width = 12% ondblclick="javascript:showSPRTrackingSubmissionEdit(\''.$row[0].'-L03\', \'select\', true)">'.$row[1].'</td>'."\n";
                    $tag .= '              <td id="'.$row[0].'-P10" style="background-color:'.getSPRSubmissionColor($row[2]).'" width = 12% ondblclick="javascript:showSPRTrackingSubmissionEdit(\''.$row[0].'-P10\', \'select\', true)">'.$row[2].'</td>'."\n";
                    $tag .= '              <td id="'.$row[0].'-P20" style="background-color:'.getSPRSubmissionColor($row[3]).'" width = 12% ondblclick="javascript:showSPRTrackingSubmissionEdit(\''.$row[0].'-P20\', \'select\', true)">'.$row[3].'</td>'."\n";
                    $tag .= '              <td id="'.$row[0].'-P30" style="background-color:'.getSPRSubmissionColor($row[4]).'" width = 12% ondblclick="javascript:showSPRTrackingSubmissionEdit(\''.$row[0].'-P30\', \'select\', true)">'.$row[4].'</td>'."\n";
                    $tag .= '              <td id="'.$row[0].'-comment" style="width:38%;" ondblclick="javascript:showSPRTrackingSubmissionEdit(\''.$row[0].'-comment\', \'textarea\', true)">'.$row[5].'</td>'."\n";
                    $tag .= '            </tr>'."\n";
                }
            }
        }

        return(utf8_encode($tag));
    }

    function fillMembersTable()
    {
        $table = new HTMLTable("member-table", "grippy-table");
        $table->tbody("member-tbody");

        $rows = getScrumMembers('');
        if(!empty($rows))
        {
            // loop over the result and fill the rows
            $inx = 1;
            foreach($rows as $row)
            {
                $name = $row[0] . ' ' .$row[1];
                $table->tr("{$inx}-project-tr");
                    $table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                    $table->td($name, "{$inx}-name", "project-title-td", null, "width=\"34%\"");
                    $table->td($row[2], "{$inx}-username", null, null, "width=\"20%\"");
                    $table->td("{$row[4]}", "{$inx}-privilage", null, null, "width=\"20%\"");
                    $table->td($row[3], "{$inx}-end_date", null, null, "width=\"20%\"");
                    $table->td('&nbsp;', "{$inx}-member-edit", null, null, "width=\"2%\"");
                    //$table->td(Utility::getQuickActionBtn("{$inx}-member-edit-btn", "Edit", "project-td-btn", "onclick=\"shieldProject.openEditDialog('{$inx}-member-edit-btn', 'member-tbody', false)\"", "{$inx}", "member-edit-dropdown"), "{$inx}-member-edit", null, null, "width=\"2%\"");

                $inx++;
            }
        }
        else
        {
            $table->tr(null, null, null, "align=\"center\"");
                $table->td("<p>No result !!!</p>", "no-result", null, null, null);
        }

        return(utf8_encode($table->getTBodyElementHTML()));
    }

    function getWorkTrackerCount($day)
    {
        global $conn;
        $qry = "SELECT COUNT( id ) FROM work_tracker
                WHERE user_name =  '". $_SESSION['project-managment-username'] ."' AND day =  '".$day."'";

        $rows = $conn->result_fetch_row($qry);

        return($rows[0][0]);
    }

    function fillWorkTrackerDashboardRow($session)
    {
        global $conn;
        $current_day = "";
        $tag = "";

        $qry = "SELECT id, day, task, category, time, comment
                FROM work_tracker WHERE user_name = '". $_SESSION['project-managment-username'] ."'";

        if($session <> "All")
            $qry .= " and day LIKE  '%". $session ."%'";

        $qry .=" ORDER BY day ASC";

        $rows = $conn->result_fetch_array($qry);
        if(!empty($rows))
        {
            // loop over the result and show the element
            foreach($rows as $row)
            {
                $tag .= '            <tr>'."\n";
                if($current_day != $row[1])
                    $tag .= '              <td id="'.$row[0].'-day" width = 12% rowspan="'.getWorkTrackerCount($row[1]).'">'.$row[1].'</td>'."\n";

                $tag .= '              <td id="'.$row[0].'-task" width = 15% ondblclick="javascript:showWorkTrackerEdit(\''.$row[0].'-task\', \'input\', true)">'.$row[2].'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-category" width = 12% ondblclick="javascript:showWorkTrackerEdit(\''.$row[0].'-category\', \'select\', true)">'.$row[3].'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-time" width = 12% ondblclick="javascript:showWorkTrackerEdit(\''.$row[0].'-time\', \'input\', true)">'.$row[4].'</td>'."\n";
                $tag .= '              <td id="'.$row[0].'-comment" ondblclick="javascript:showWorkTrackerEdit(\''.$row[0].'-comment\', \'textarea\', true)">'.$row[5].'</td>'."\n";
                $tag .= '            </tr>'."\n";

                $current_day = $row[1];
            }
        }

        return(utf8_encode($tag));
    }

    function getDashboardAddButton($id, $buttonList)
    {
        $tag = "";

        if(($id <> "") && (!empty($buttonList)))
        {
            $tag .= '<div id="'.$id.'-top-button-container" style="display: inline-block; *display: inline;
                        zoom: 1; vertical-align: top; font-size: 12px; width: 75%;">'."\n";

            if(!empty($buttonList))
            {
                foreach($buttonList as $button)
                {
                    $tag .= '    <button type="button" class="b-btn '.$button[2].'" style = "margin-right:15px"
                                onclick="javascript:'.$button[0].'()">'.$button[1].'</button>'."\n";
                }
            }

            $tag .= '</div>'."\n";
        }

        return($tag);
    }

    function getDashboardInputTag($id, $inputList)
    {
        $tag = "";
        $year = getCurrentSession();

        if(($id <> "") && (!empty($inputList)))
        {
            $tag.='<div id="'.$id.'-input-container" style="width:100%; font-size:0; padding-top:10px">';

            // Show session combobox
            //$tag .= getSessionSelectTag($year, $inputList[0]);

            // Show Add button
            $buttonList = array();
            $tag .= getDashboardAddButton($id, $inputList[1]);

            if(!empty($inputList[2]))
            {

                $tag .= '<div style="font-size: 12px; float: right; width: 150px;">';
                $tag .= '            <select id="misc-select" style="margin: 0; margin-top: 8px; height: 35px; width: 150px"';

                if($inputList[2][1] != 'undefined')
                    $tag .= ' ' . $inputList[2][1];
                $tag .= '>';

                foreach($inputList[2][0] as $option)
                    $tag .= '                <option>'.$option.'</option>';

                $tag .= '            </select>';
                $tag .= '        </div>';
                $tag .= '<div class="clear"></div>';
            }

            $tag.='    </div>';
        }

        return($tag);
    }

    function showDashboardTable($id, $year, $tableList)
    {
        $tag = "";

        if(!empty($tableList))
        {
            $tag .= '<div>';
            $tag .= '<h2 align="center">'.$tableList[0].'</h2>';

            $tag .= '    <table id ="'.$id.'-table" class="blue">'."\n";

            $tag .= '            <thead id="'.$id.'-thead">'."\n";
            $tag .= '                <tr>'."\n";
            foreach($tableList[1] as $th)
            {
                $tag .= '                    <th';
                if(!empty($th[1]))
                    $tag .= ' data-sort="'.$th[1].'"';
                if($th[0] == "Comment-Full")
                    $tag .= ' style="display: none;"';
                $tag .= '>'.$th[0].'</th>'."\n";
            }
            $tag .= '                </tr>'."\n";
            $tag .= '            </thead>'."\n";

            $tag .= '            <tbody id="'.$id.'-tbody">'."\n";
            $tag .= $tableList[2]($year);
            $tag .= '            </tbody>'."\n";

            $tag .= '    </table>'."\n";
            $tag .= '</div>';
        }

        return(utf8_encode($tag));
    }

    function showDashboard($id, $inputList, $tableList, $addCancelList)
    {
        $tag = "";
        $year = getCurrentSession();

        $tag .='<div id="'.$id.'-container">'."\n";

        // input section
        if(!empty($inputList))
        {
            $tag .= getDashboardInputTag($id, $inputList);
            // Show session combobox
            $tag .= '<div style="border-top: 1px solid black; margin-top: 25px; padding-top: 15px;">';
            $tag .= getSessionSelectTag($year, $inputList[0]);
            $tag .= '</div>';
        }

        // dashboard table
        $tag .= showDashboardTable($id, $year, $tableList);

        // add-cancel button
        if(!empty($addCancelList))
        {
            $tag .= '    <div id="add-cancel-button-container" style="display:none; padding-top:15px;">'."\n";
            $tag .= '        <button type="button" id="'.$id.'-add-button" class="ent-button ent-button-submit"
                        onclick="javascript:'.$addCancelList[0].'(true)">Add</button>'."\n";
            $tag .= '        <button type="button" id="'.$id.'-cancel-button" class="ent-button ent-button-submit"
                        onclick="javascript:'.$addCancelList[0].'(false)">Cancel</button>'."\n";
            $tag .= '    </div>'."\n";
        }

        $tag .= '</div>'."\n";
        $tag .= '<div id="popup-div"></div>';

        return(utf8_encode($tag));
    }

    function showSPRTrackingReportSearchOptions()
    {
        $tag = "";
        $current_session = getCurrentSession();
        $sessions = getSessionList();
        array_unshift($sessions, "All");

        /// Add search option tag.
        $tag .= '<div id="main-search-container" style="margin-top: 20px;">'."\n";

        /// Add session tag
        $tag .='<div id="search-session-container" style="float: left; width: 15%;">';
        $tag .= '<label id="search-session-label">
                <strong id="search-session-strong">Session </strong>
                </label>'."\n";
        $tag .= '<select id="search-session-select">'."\n";
        foreach($sessions as $session)
        {
            if($session == $current_session)
                $tag .='<option value="'.$session.'" selected>'.$session.'</option>'."\n";
            else
                $tag .='<option value="'.$session.'">'.$session.'</option>'."\n";
        }
        $tag .= '</select>'."\n";
        $tag .='</div>';

        /// Add main search tag
        $tag .='<div id="main-search-container" style="float: left; width: 20%">';
        $tag .= '<label id="main-search-label">
                <strong id="main-search-strong">Search for </strong>
                </label>'."\n";
        $tag .='<select id="main-search-select" onchange="javascript:showSPRTrackingReportSearchSubOptions(this)">'."\n";
        $tag .='<option value="Blank" selected></option>'."\n";
        $tag .='<option value="Commit Build">Commit Build</option>'."\n";
        $tag .='<option value="Respond By">Respond By</option>'."\n";
        $tag .='</select>'."\n";
        $tag .='</div>';

        /// Add sub-search tag
        $tag .='<div id="sub-search-container" style="float: left; width: 25%; display: none;">';
        $tag .='<label id="sub-search-label">
                <strong id="sub-search-strong">Condition </strong>
                </label>'."\n";
        $tag .='<select id="sub-search-select">'."\n";
        $tag .='<option value="Blank" selected></option>'."\n";
        //$tag .='<option value="Having Commit Build">Having Commit Build</option>'."\n";
        //$tag .='<option value="Without Commit Build">Without Commit Build</option>'."\n";
        $tag .='</select>'."\n";
        $tag .='</div>';

        /// Add Search button tag
        $tag .='<div id="search-button-container" style="float: left; width: 10%">';
        $tag .= '    <button id="search-button" type="button" class="b-btn green" style = "margin-left:15px; margin-top: -3px;"
                                onclick="javascript:showSPRTrackingReportSearchResult()">Search</button>'."\n";
        $tag .='</div>'."\n";
        $tag .='<div class="clear"></div>';

        $tag .='</div>'."\n";

        $tag .='<div id="search-result-container" style="margin-top: 20px; display: none;">'."\n";
        $tag .='<p>Result is here</p>'."\n";
        $tag .='</div>'."\n";

        return(utf8_encode($tag));
    }

    function generateSPRTrackingReport($qry)
    {
        global $conn;
        $str = "";

        /// SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session FROM `spr_tracking`
        $rows = $conn->result_fetch_array($qry);
        if(!empty($rows))
        {
            $Table = new HTMLTable("spr-tracking-report-table", "blue");

            // add table header
            $Table->thead("spr-tracking-report-thead");

            $Table->th("Item number", null, null, null, "data-sort=\"int\"");
            $Table->th("Type", null,  null, null, "data-sort=\"string\"");
            $Table->th("Status", null, null, null, "data-sort=\"string\"");
            $Table->th("Build Version", null, null, null, "data-sort=\"string\"");
            $Table->th("Commit Build", null, null, null, "data-sort=\"string\"");
            $Table->th("Respond By", null, null, null, "data-sort=\"string\"");
            $Table->th("Comment", null, null, null);
            $Table->th("Session", null, null, null, "data-sort=\"string\"");

            // add Table body
            $Table->tbody("spr-tracking-dashboard-tbody");

            // loop over the result and fill the rows
            foreach($rows as $row)
            {

                $Table->tr();

                $Table->td(getSPRString($row[1], $row[0]), "{$row[0]}-spr-no", null, null, "width=\"12%\"");
                $Table->td("{$row[1]}", "{$row[0]}-type", null, null, "width=\"8%\"");
                $Table->td("{$row[2]}", "{$row[0]}-status", null, "background-color:'{getSPRTrackingStatusColor($row[2])}';", "width=\"15%\"");
                $Table->td("{$row[3]}", "{$row[0]}-build_version", null, null, "width=\"12%\"");
                $Table->td("{$row[4]}", "{$row[0]}-commit_build", null, null, "width=\"12%\"");
                $Table->td("{$row[5]}", "{$row[0]}-respond_by_date", null, null, "width=\"12%\"");
                $Table->td("{$row[6]}", "{$row[0]}-comment");
                $Table->td("{$row[7]}", "{$row[0]}-session", null, null, "width=\"8%\"");
            }

            $str = $Table->toHTML();
        }
        else
        {
            $str = "<p>No result !!!</p>";
        }


        return(utf8_encode($str));
    }

    function showSPRTrackingReportSearchResult()
    {
        $tableList = array("20 items found", array(array("Item Number", "int"), array("Status", "string"), array("Commit Build", "string")
                            , array("Respond By", "string"), array("Comment-Full", ""), array("Session", "string")), "fillSPRTrackingSearchReportRow");

        $tag = "";

        /// Have some style .... draw a line to seperate result.
        $tag .='<div id="spr-tracking-report-table-container" style="display: none;">';
        $tag .='<div style="border-top: 1px solid black; margin-top: 20px; padding-top: 15px;">';
        $tag .='</div>'."\n";
        $tag .= showDashboardTable("spr-tracking-report", $current_session, $tableList);
        $tag .='</div>'."\n";

        return(utf8_encode($tag));
    }

    function fillSPRTrackingSearchReportRow()
    {
        $tag = "";

        return(utf8_encode($tag));
    }

    function showSPRTrackingDashboard()
    {
        $id = "spr-tracking-dashboard";
        $inputList = array("showDashboardAccdSession('spr-tracking-dashboard-tbody', 'fillSPRTrackingDashboardRow')",
                        array(array("addSPRTrackingDashboardRow", "Add SPR to track", "green"), array("deleteSPRTrackingDashboardRow", "Delete SPR(s)", "red"),),
                        array(array("Select...","Import", "Export"), "onchange='javascript:importExportCSV(\"spr-tracking-dashboard\", \"fillSPRTrackingDashboardRow\")'"));
        $tableList = array("SPR Tracking Dashboard", array(array("Item Number", "int"), array("Type", "string"), array("Status", "string"), array("Build Version", "string"),
                            array("Commit Build", "string"), array("Respond By", "string"), array("Comment", ""), array("Comment-Full", ""), array("Session", "string")), "fillSPRTrackingDashboardRow");
        $addCancelList = array("addDeleteSPRTrackingDashboard");

        return(showDashboard($id, $inputList, $tableList, $addCancelList));
    }

    function showSPRTrackingSubmissionStatus()
    {
        $tag = "";
        $buildNames = getColumnName('spr_submission');

        if(!empty($buildNames))
        {
            $thInfo = array(array("SPR Number", "int"));
            foreach($buildNames as $bn)
            {
                if(($bn <> "spr_no") && ($bn <> "comment"))
                array_push($thInfo, array($bn, "string"));
            }
            array_push($thInfo, array("Comment", ""));

            $id = "submission-status";
            $inputList = array("showDashboardAccdSession('submission-status-tbody', 'fillSPRTrackingSubmissionStatusRow')",
                        array(array("addSPRTrackingSubmissionStatusRow", "Add SPR to update Submission Status", "green"), array("deleteSPRTrackingSubmissionStatusRow", "Delete SPR Submission Status(s)", "red")));
            $tableList = array("SPR Submission Status", $thInfo, "fillSPRTrackingSubmissionStatusRow");
            $addCancelList = array("addDeleteSPRTrackingSubmissionStatus");

            $tag = showDashboard($id, $inputList, $tableList, $addCancelList);
        }

        return($tag);
    }

    function showWorkTrackerDashboard()
    {
        $id = "work-tracker-dashboard";
        $inputList = array("showDashboardAccdSession('work-tracker-dashboard-tbody', 'fillWorkTrackerDashboardRow')",
                        array(array("addWorkTrackerDashboardRow", "Add Daily Work", "green"), array("deleteWorkTrackerDashboardRow", "Delete Daily Work", "red")));
        $tableList = array("Work Tracker Dashboard", array(array("Date", ""), array("Task", ""), array("Category", ""), array("Time(Hrs)", ""), array("Comment", "")), "fillWorkTrackerDashboardRow");
        $addCancelList = array("addDeleteWorkTracker");

        return(showDashboard($id, $inputList, $tableList, $addCancelList));
    }

    function getBuildVersion($commit_build)
    {
        $version = "";

        if(strstr($commit_build, "L-03"))
            $version = "l03";
        else if (strstr($commit_build, "P-10"))
            $version = "p10";
        else if (strstr($commit_build, "P-20"))
            $version = "p20";

        return($version);
    }

    function getSPRsHavingCommitBuild($commit_build)
    {
        global $conn;
        $sprs = array();

        if((isset($_SESSION["project-managment-username"])) && ($_SESSION["project-managment-username"] != ""))
        {
            // get all the spr_no having same commit build
            $qry = "SELECT spr_no, status, type
                    FROM  spr_tracking
                    WHERE user_name =  '".$_SESSION["project-managment-username"]."'
                    AND (type = 'SPR' OR type = 'INTEGRITY SPR')
                    AND (status <> 'NOT AN ISSUE' AND status <> 'RESOLVED' AND status <> 'CLOSED' AND status <> 'PASS TO CORRESPONDING GROUP')
                    AND commit_build LIKE  '%".$commit_build."%'";

            $rows = $conn->result_fetch_array($qry);
            if(!empty($rows))
            {
                foreach($rows as $row)
                {
                    // check for submission status.
                    if($row[1] == 'SUBMITTED')
                    {
                        $qry = "SELECT spr_submission.spr_no, spr_tracking.type
                                FROM spr_submission
                                INNER JOIN spr_tracking ON spr_submission.spr_no = spr_tracking.spr_no
                                AND spr_tracking.spr_no =  '".$row[0]."'
                                AND ( spr_submission.".getBuildVersion($commit_build)." = 'N/A' OR spr_submission.".getBuildVersion($commit_build)." = 'YES')";

                        $val = $conn->result_fetch_row($qry);
                        if(empty($val))
                            array_push($sprs, [$row[0], $row[2]]);
                    }
                    else
                        array_push($sprs, [$row[0], $row[2]]);
                }
            }
        }

        return($sprs);
    }

    function getCommitBuildInfo($versions)
    {
        $tag = "";
        $l03 = getSPRsHavingCommitBuild($versions[2]);
        $p10 = getSPRsHavingCommitBuild($versions[1]);
        $p20 = getSPRsHavingCommitBuild($versions[0]);

        if((!empty($l03)) || (!empty($p10)) || (!empty($p20)))
        {
            $tag = '<div id="commit-build-container" style="float:left; width:33%">
                        <h2 style="margin-left:30px; color:red">Commit Build</h2>
                        <hr>';

            if(!empty($p20))
                $tag .= getCommitBuildAccdVersion($versions[0], $p20);

            if(!empty($p10))
                $tag .= getCommitBuildAccdVersion($versions[1], $p10);

            if(!empty($l03))
                $tag .= getCommitBuildAccdVersion($versions[2], $l03);

            $tag .='</div>';
        }

        return($tag);
    }

    function getSubmissionStatus()
    {
        global $conn;
        $ss = array();

        if((isset($_SESSION["project-managment-username"])) && ($_SESSION["project-managment-username"] != ""))
        {
            $qry = "SELECT spr_submission.spr_no, spr_submission.L03, spr_submission.P10, spr_submission.P20, spr_submission.P30, spr_tracking.type
                    FROM spr_submission
                    INNER JOIN spr_tracking ON spr_submission.spr_no = spr_tracking.spr_no
                    AND
                    ((spr_submission.L03 <>  'N/A'
                    AND spr_submission.L03 <>  'YES')
                    OR (spr_submission.P10 <>  'N/A'
                    AND spr_submission.P10 <>  'YES')
                    OR (spr_submission.P20 <>  'N/A'
                    AND spr_submission.P20 <>  'YES')
                    OR (spr_submission.P30 <>  'N/A'
                    AND spr_submission.P30 <>  'YES'))
                    AND spr_tracking.user_name =  '".$_SESSION["project-managment-username"]."'";

            $rows = $conn->result_fetch_array($qry);
            if(!empty($rows))
            {
                foreach($rows as $row)
                {
                    $str = "";
                    if(($row[1] <> "N/A") && ($row[1] <> "YES"))
                        $str .= "L-03&nbsp;&nbsp;";
                    if(($row[2] <> "N/A") && ($row[2] <> "YES"))
                        $str .= "P-10&nbsp;&nbsp;";
                    if(($row[3] <> "N/A") && ($row[3] <> "YES"))
                        $str .= "P-20&nbsp;&nbsp;";
                    if(($row[4] <> "N/A") && ($row[4] <> "YES"))
                        $str .= "P-30&nbsp;&nbsp;";

                    array_push($ss, [$row[0], $str, $row[5]]);
                }
            }
        }

        return($ss);
    }

    function getSPRLink($sprNo, $type)
    {
        $tag = "";

        if($type == "SPR")
        {
            $tag .= 'http://rdweb.ptc.com/WebSiebel/report.php?value='.$sprNo.
                                                    '+&spr_no='.$sprNo.'+&call_no=&mode=details&form=spr&do_not_check_ver=1';
        }
        else if($type == "INTEGRITY SPR")
        {
            $tag .= 'http://integrity.ptc.com:7001/im/viewissue?selection='.$sprNo;
        }
        else
            $tag .= $sprNo;

        return($tag);
    }

    function getPrevBuildVersion($version)
    {
        $prev_version = "";

        if($version != "")
        {
            $pos = strrpos($version, '-');
            $no = substr($version, ($pos + 1));
            $rest = substr($version, 0, ($pos + 1));

            $prev_version = $rest . (intval($no < 10) ? "0".strval((intval($no)) - 1) : strval((intval($no)) - 1));
        }

        return($prev_version);
    }

    // $date : [yyyy-mm-dd], like [2015-06-27]
    function isNearByDate($date, $days)
    {
        $isNearBy = false;
        $curDate = date('Y-m-d');

        if(($date != "") && ($days > 0))
        {
            // get d, m, y from input string;
            list($year, $month, $day) = explode('-', $date);

            // get d, m, y from curDate.
            list($curYear, $curMonth, $curDay) = explode('-', $curDate);

            // add days to curDate.
            $addMon = ($days < 30 ? 0 : ($days/30));
            $addDay = ($days < 30 ? $days : ($days%30));
            $newDay = intval($curDay) + $addDay;
            $newMon = intval($curMonth) + $addMon;
            $newYear = intval($curYear);

            if($newDay > 30)
            {
                $newDay = $newDay - 30;
                $newMon = $newMon + 1;
            }
            if($newMon > 12)
            {
                $newMon = $newMon - 12;
                $newYear = $newYear + 1;
            }

            // compair them.
            if($newYear == intval($year))
            {
                if($newMon > intval($month))
                {
                    $isNearBy = true;
                }
                else if($newMon == intval($month))
                {
                    if($newDay >= intval($day))
                        $isNearBy = true;
                }
            }
            else if(($newYear - 1) == intval($year))
            {
                $isNearBy = true;
            }
        }

        return($isNearBy);
    }

    // $date : [yyyy-mm-dd], like [2015-06-27]
    function isBuildReleased($date)
    {
        $isReleased = false;
        $curDate = date('Y-m-d');

        if($date != "")
        {
            // get d, m, y from input string;
            list($year, $month, $day) = explode('-', $date);

            // get d, m, y from curDate.
            list($curYear, $curMonth, $curDay) = explode('-', $curDate);

            // check year.
            if(intval($curYear) > intval($year))
                $isReleased = true;
            else if((intval($curYear) == intval($year)) && (intval($curMonth) > intval($month)))
                $isReleased = true;
            else if((intval($curYear) == intval($year)) && (intval($curMonth) == intval($month)) && (intval($curDay) > intval($day)))
                $isReleased = true;
        }

        return($isReleased);
    }

    function addRightPointAngleQuotationSVG()
    {
        $tag = '                       <span class="rsaquo-span">' . EOF_LINE;
        $tag .= '                           <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 451.846 451.847" style="enable-background:new 0 0 451.846 451.847;" xml:space="preserve" fill="#FFFFFF">' . EOF_LINE;
        $tag .= '                               <g>' . EOF_LINE;
        $tag .= '                               <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744   L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284   c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"/>' . EOF_LINE;
        $tag .= '                               </g>' . EOF_LINE;
        $tag .= '                           </svg>' . EOF_LINE;
        $tag .= '                       </span>' . EOF_LINE;

        return($tag);
    }
?>
