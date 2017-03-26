/*--
    File    : js/functions.js
    Author  : Abhishek Nath
    Date    : 01-Jan-2015
    Desc    : Common js functions.
--*/

/*--
    01-Jan-15   V1-01-00   abhishek   $$1   Created.
    17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
    23-Jul-15   V1-01-00   abhishek   $$3   add functionality to open popup dialog for build_version,
                                            commit_build while adding new SPR for tracking.
--*/

/* Global Variables */
var spr_tracking_status_list            = ['NONE', 'INVESTIGATING','NOT AN ISSUE','SUBMITTED',
                                            'RESOLVED','PASS FOR TESTING','CLOSED','ON HOLD',
                                            'TESTING COMPLETE', 'PASS TO CORRESPONDING GROUP',
                                            'NEED MORE INFO', 'OTHERS'];
var spr_tracking_type_list              = ['SPR','INTEGRITY SPR','REGRESSION','OTHERS'];
var submission_status_list              = ['NO', 'YES', 'N/A', 'IDLING', 'REOPENED'];
var work_tracker_list                   = ['SPR', 'REG FIX', 'REGRESSION TEST', 'SF', 'REG CLEAN-UP',
                                            'CONSULTATION', 'PROJECT', 'MISC', 'OTHERS'];
var spr_tracking_report_search_sub_list = [['Commit Build',['All', 'Having Commit Build',
                                            'Without Commit Build']],['Respond By', ['All']]];

var spr_tracking_report_search_sub_list = [['Commit Build',['All', 'Having Commit Build', 'Without Commit Build']],
                                          ['Respond By', ['All']]];


/**************************************
*           Helper functions          *
***************************************/
function visible(obj, makeVisible) {
    if (makeVisible)
        obj.style.visibility = "visible";
    else
        obj.style.visibility = "hidden";
}

function setDisplay(tagId, value) {
    var tag = document.getElementById(tagId);
    tag.style.display = value;
}

function setWidth(tagId, value) {
    // Resize div tag
    var entryMainTag = document.getElementById(tagId);
    entryMainTag.style.width = value;
}

function addOptionTag(val, text) {
    return('<option value="'+ val +'">'+ text +'</option>'+"\n");
}

function addOptions(optionValues, optionCaptions) {
    var optionTag = "";
    var len = optionValues.length;
    for(var i = 0; i< len; ++i)
    {
        optionTag += addOptionTag(optionValues[i], optionCaptions[i]);
    }

    return(optionTag);
}

function addEventHandler(elem, eventType, handler) {
    if (elem.addEventListener)
        elem.addEventListener (eventType,handler,false);
    else if (elem.attachEvent)
        elem.attachEvent ('on'+eventType,handler);
}

/* Use additional space to organize output in the html file */
function create_select_tag(id, caption, options, event) {
    var tag = ' <div class="form-element" id="'+id+'-form-element">';
        tag += '    <label id="'+id+'-label">';
        tag += '        <strong id="'+id+'Label">'+caption+'</strong>';
        tag += '    </label>';
        tag += '    <div id="'+id+'Holder" >';
        tag += '        <select id="'+id+'" name="'+id+'"';

        if(event !="")
            tag += ' '+event;

        tag += '>';
        tag += '            <option value="">Select ...</option>'+"\n";

        for (var i = 0; i < options.length; i++)
            tag +='         <option value="'+options[i]+'" >'+options[i]+'</option>'+"\n";

        tag += '        </select>';
        tag += '    </div>';
        tag += '        <span role="alert" class="errormsg" id="errormsg_0_'+id+'">';
        tag += '        </span>';
        tag += '    </div>';

    return(tag);
}

function addInputTag(name, caption, type) {
    var tag = '                         <div class="form-element" id="'+name+'-form-element">';
        tag += '                            <label id="'+name+'-label">';
        tag += '                                <strong>'+caption+'</strong>';
        tag += '                                <input type="'+type+'" maxlength="30" autocomplete="off" name="'+name+'" id="'+name+'" value="" spellcheck="false">';
        tag += '                            </label>';
        tag += '                            <div class="infomsg" id="'+name+'-infomessage">You can use letters, numbers, and periods.</div>';
        tag += '                            <div id="'+name+'-errormsg-and-suggestions">';
        tag += '                                <span role="alert" class="errormsg" id="errormsg_0_'+name+'"></span>';
        tag += '                                <div id=\''+name+'ExistsError\' style="display: none">';
        tag += '                                This username address already corresponds to an Account. Please <a href="#">reset it</a>.';
        tag += '                                </div>';
        tag += '                                <div class="'+name+'-suggestions" id="'+name+'-suggestions">';
        tag += '                                </div>';
        tag += '                            </div>';
        tag += '                        </div>';

    return(tag);
}

function showHideTag(tagId, flag) {
    var tagObj = document.getElementById(tagId);
    if(flag)
        tagObj.style.display = "block";
    else
        tagObj.style.display = "none";
}

// show -> flag - true
// hide -> flag - false
function showHideInfo(tagId, flag) {
    showHideTag(tagId + '-infomessage', flag);
}

function debug(str) {
    alert("output : " + str);
}

function getServerResponseViaAJAX(urlPath, func, formData, params) {
    var returnval;
    var par = "";

    if(params != "")
        par = "&" + params;

    $.ajax({
                type: 'POST',
                url: urlPath + "?f="+ func + par,
                // call php function , phpFunction=function Name , x= parameter
                async       : false,
                data        : formData,
                dataType    : 'json', // what type of data do we expect back from the server
                encode      : true,
                success: function(data) {
                    returnval = data;
                },
                error:function(data){
                    console.log(data);
                }
            });

    return(returnval);
}

function serverRespondViaAJAX(url, callback, formData) {
    // call AJAX function
    $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : url + '?f=' + callback, // the url where we want to POST
        data        : formData, // our data object
        dataType    : 'json', // what type of data do we expect back from the server
        encode      : true
    })

    // handle error
    .done(function(data) {

        // log data to the console so we can see
        console.log(data);
    })

    // using the fail promise callback
    .fail(function(data) {

        // show any errors
        // best to remove for production
        console.log(data);
    })
}

function getSessionList() {
    var dt          = new Date();

    var curMonth    = dt.getMonth();
    var curYear     = (curMonth >= 10) ? (dt.getFullYear() + 1) : dt.getFullYear();
    var sessionList = [];
    var stYear      = 2000;
    var yr;

    for(yr = curYear; yr >= stYear; yr--)
        sessionList.push(yr.toString());

    return(sessionList);
}



/**************************************
*           Specific functions          *
***************************************/
function showShield(tagId, type) {
    var obj = document.getElementById(tagId);

    // call ajax to fill the data.
    processXMLHttpRequest("inc/shield.php?shield_type=" + type, accessAJAX, "get", "");

    // Access data from AJAX.
    function accessAJAX(xhr) {
        obj.innerHTML = xhr.responseText;

        // Add event to close-icon
        closeShield(type + '-shield-close', 'click', function () {visible(obj, false)});

        // Add event to shield div
        closeShield(type + '-shield', 'click', function () {visible(obj, false)});

        visible(obj, true);
    }
}

function closeShield(sourceTagId, event, func) {
    // Add event to close-icon
    closeIconObj = document.getElementById(sourceTagId);
    addEventHandler(closeIconObj, event, func);
}

function insertOption(serachOptionTagId, searchOptionDivTagId, optionValues, optionCaptions) {
    var serachOptionTag = document.getElementById(serachOptionTagId);

    serachOptionTag.innerHTML = addOptions(optionValues, optionCaptions);
    setDisplay(searchOptionDivTagId, 'block');
}

function getTagBGColor(tagId, callback, val) {
    document.getElementById(tagId).style.backgroundColor = getServerResponseViaAJAX("../ajax/default.php", "getTagBGColorCallback", {},
                                                                "func="+callback+"&val="+val);
}

function getSelectTag(id, callback, option_list, current_val) {
    tag = '<select id="'+id+'-select"';
    if(callback != "")
        tag += ' '+callback;
    tag += '>';

    // Check for the selected item.
    var x;
    for (x in option_list) {
        if(option_list[x] == current_val)
            tag += '    <option value="'+option_list[x]+'" selected>'+option_list[x]+'</option>';
        else
            tag += '      <option value="'+option_list[x]+'">'+option_list[x]+'</option>';
    }

    tag += '</select>';

    return(tag);
}

function getTagAccdType(id, type, event, value, select_list) {
    var tag = "";

    if(type == 'textarea') {
        tag += '<'+type+' rows="6" cols="50" id="'+id+'-'+type+'" spellcheck="false"';
        if(event != "")
            tag += ' ' + event;
        tag += '">'+value+'</'+type+'>';
    }
    else if(type == 'input') {
        tag += '<input type="text" id="'+id+'-input" value="'+value+'"';
        if(event != "")
            tag += ' ' + event;
        tag += '>';
    }
    else if(type == 'select')
        tag += getSelectTag(id, event, select_list, value);

    return(tag);
}

function getGreppyDotTag() {
    var tag = '<i class="grippy">';
    tag += '    <i class="dot"></i>';
    tag += '    <i class="dot"></i>';
    tag += '    <i class="dot"></i>';
    tag += '    <i class="dot"></i>';
    tag += '    <i class="dot"></i>';
    tag += '</i>';

    return(tag);
}

function getQuickActionBtn(Id, Class, dropdownId) {
    var tag = '<div id="'+ Id +'" class="quick-action-btn '+ Class +'">';
    tag += '    <a class="quick-action-text" href="popup:Widgets/Details/QuickEditStory">Save</a>';
    tag += '    <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu(\'show\', \''+ Id +'\', \''+ dropdownId +'\')" onblur="showHideEditMenu(\'hide\', \''+ Id +'\', \''+ dropdownId +'\')">';
    tag += '        <span>';
    tag += '            <svg width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">';
    tag += '                <g>';
    tag += '                    <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>';
    tag += '                </g>';
    tag += '            </svg>';
    tag += '        </span>';
    tag += '    </a>';
    tag += '</div>';

    return(tag);
}

function showSPREditTag(id, type, flag) {
    var tag = "";
    var parTag = document.getElementById(id);
    var spr_no = id.split("-");

    // hide full comment block
    if(spr_no[1] == "comment")
        showFullComment(id, false);

    if(flag) {
        var currentValue = ((spr_no[1] == "comment") ? document.getElementById(id + "-full").innerHTML : parTag.innerHTML);
        var data_list = ((spr_no[1]=="type") ? spr_tracking_type_list : spr_tracking_status_list);
        var event = 'onblur="javascript:showSPREditTag(\''+id+'\', \''+type+'\', false)"';

        tag += getTagAccdType(id, type, event,
                                currentValue, data_list);
    } else {
        var element = document.getElementById(id+'-'+type);
        tag += element.value;

        // call server site function to save updated data into database.
        var bgColor = getServerResponseViaAJAX("../ajax/default.php", "updateSPRTrackingCallback", {},
                                    "spr_no="+spr_no[0]+"&field="+spr_no[1]+"&value="+tag);

        // If spr status is submitted, then add/update spr_submission table.
        if(tag == "SUBMITTED") {
            // get the type of the spr.
            var spr_type = document.getElementById(spr_no[0] + '-type').innerHTML;

            // if it's SPR/Integrity SPR then open prompt for submission
            if((spr_type != "REGRESSION") && (spr_type != "OTHERS")) {
                var version = prompt("Enter the Submission version", "p10");
                if ((version != null) && (version != "") &&
                        ((version =="l03") || (version == "p10") || (version == "p20") || (version == "p30")))
                {
                    getServerResponseViaAJAX("../ajax/default.php", "addUpdateSPRSubmissionStatusCallback", {},
                                        "spr_no="+spr_no[0]+"&version="+version);
                }
            }
        }

        // Get short Description for Comment field.
        if(spr_no[1] == "comment") {
            // update '*-comment-full' with latest value.
            document.getElementById(id + "-full").innerHTML = tag;

            tag = getServerResponseViaAJAX("../ajax/default.php", "shortDescriptionCallback", {},
                                    "comment="+element.value+"&len=25");
        }

        // change back-ground for 'Status' corresponding to their type.
        if (bgColor != "")
            document.getElementById(id).style.backgroundColor = bgColor;
    }

    parTag.innerHTML = tag;
    document.getElementById(id+'-'+type).focus();
}

function showSPRTrackingSubmissionEdit(id, type, flag) {
    var tag = "";
    var parTag = document.getElementById(id);

    if(flag) {
        var currentValue = parTag.innerHTML;

        tag += getTagAccdType(id, type, 'onblur="javascript:showSPRTrackingSubmissionEdit(\''+id+'\', \''+type+'\', false)"',
                                currentValue, submission_status_list);
    } else {
        var element = document.getElementById(id+'-'+type);
        tag += element.value;

        // call server site function to save updated data into database.
        var spr_no = id.split("-");
        var bgColor = getServerResponseViaAJAX("../ajax/default.php", "updateSPRTrackingSubmissionCallback", {},
                                    "spr_no="+spr_no[0]+"&field="+spr_no[1]+"&value="+tag);

        // change back-ground for 'l03, p10, p20' corresponding to their type.
        if (bgColor != "")
            document.getElementById(id).style.backgroundColor = bgColor;
    }

    parTag.innerHTML = tag;
    document.getElementById(id+'-'+type).focus();
}

function showWorkTrackerEdit(id, type, flag) {
    var tag = "";
    var parTag = document.getElementById(id);

    if(flag) {
        var currentValue = parTag.innerHTML;

        tag += getTagAccdType(id, type, 'onblur="javascript:showWorkTrackerEdit(\''+id+'\', \''+type+'\', false)"',
                                currentValue, work_tracker_list);
    } else {
        var element = document.getElementById(id+'-'+type);
        tag += element.value;

        // call server site function to save updated data into database.
        var workTrackerId = id.split("-");
        getServerResponseViaAJAX("../ajax/default.php", "updateWorkTrackerCallback", {},
                                    "key="+workTrackerId[0]+"&field="+workTrackerId[1]+"&value="+tag);
    }

    parTag.innerHTML = tag;
    document.getElementById(id+'-'+type).focus();
}

function getSelElementVal(inputArr, element) {
    var val = "";

    for (var i in inputArr) {
        if(inputArr[i][0] == element)
            val = inputArr[i][1];
    }

    return(val);
}

function addDashboardRow(id, inputList) {
    /*
     * Each Tag = [<type>, <id>, <Property Array>, <Value Array>]
     * Property Array     = [[<field_1>, <value_1>], ..., [<field_n>, <value_n>]]
     * Value Array        = [<value_1>, ..., <value_n>]
     * */

    // Add new row to existing dashboard table to put user input.
    //if($('#add-cancel-button-container').css('display') == "none") {
        var table_rows = "";

        if((typeof inputList != "undefined") && (inputList != null) && (inputList.length > 0)) {

            var tableTH = $('#' + id + "-table").find('th');
            last_col = (tableTH.length - 1);

            // add 'TH' element
            tableTH.eq(last_col).after('<th>Edit</th>');

            // add 'TD' element
            $('#'+ id + "-table").find('tr').each(function () {
                //if(typeof(col) === 'undefined')
                    $(this).find('td').last().after('<td> &nbsp; </td>');
                /*else
                    $(this).find('td').eq(col).after('<td> &nbsp; </td>');*/
            });

            table_rows += '<tr>';
            table_rows += ' <td id="add-greppy" class="hasGrippy", style="text-align:center;">';
            table_rows +=       getGreppyDotTag();
            table_rows += ' </td>';

            for (var i in inputList) {
                if(inputList[i][0] == "input") {
                    table_rows += '<td><input type="text" id="'+inputList[i][1]+'-input"';
                    /* set Properties */
                    for (var iin in inputList[i][2])
                        table_rows += ' ' + inputList[i][2][iin][0] + '="' + inputList[i][2][iin][1] + '"';

                    /* set Values */
                    if((typeof inputList[i][3] != "undefined") && (inputList[i][3] != null) && (inputList[i][3].length > 0))
                    {
                        var values = "";
                        for (var k in inputList[i][3])
                            values += ' ' + inputList[i][3][k];

                        table_rows += ' value = ' + values;
                    }
                    table_rows += '></td>';
                } else if(inputList[i][0] == "select") {
                    var current_val = getSelElementVal(inputList[i][2], 'sel');
                    table_rows += '<td>'+ getSelectTag(inputList[i][1], '', inputList[i][3], current_val) +'</td>';
                } else if(inputList[i][0] == "textarea") {
                    table_rows += '<td><textarea id="'+inputList[i][1]+'-textarea"';

                    /* set Properties */
                    for(var ita in inputList[i][2])
                        table_rows += ' ' + inputList[i][2][ita][0] + '="' + inputList[i][2][ita][1] + '"';

                    /* set Values */

                    table_rows += '></textarea></td>';
                }
            }

            table_rows += ' <td id="add-btn" style="width: 2%">';
            table_rows +=       getQuickActionBtn("edit-btn", "spr-tracking-tbl-td-btn", "spr-tracking-dashboard-table-dropdown");
            table_rows += ' </td>';

            table_rows += '</tr>';

            if($('#'+id + '-tbody').find('tr').length > 0)
                $('#'+id + '-tbody').find('tr:first').before(table_rows);
            else
                $('#'+id + '-tbody').html(table_rows);

            // show both the button
            setAddDeleteContainer(id, 'Add', 'inline-block');
            $('#session-select').prop('disabled', true);
        }
    //}
}

function deleteDashboardRow(id) {
    if($('#add-cancel-button-container').css('display') == "none") {
        addTableCol(id + '-table', '<input type="checkbox"></input>');
        addTableCol('fixed_table', '<input type="checkbox"></input>');

        setThWidth(id + '-table', 'fixed_table');

        setAddDeleteContainer(id, 'Delete', 'inline-block');
        $('#session-select').prop('disabled', true);
    }
}

function addSPRTrackingDashboardRow() {
    var current_session = "";
    if($('#session-select').val() != "All")
        current_session = $('#session-select').val();

    /*
     * Each Tag = [<type>, <id>, <Property Array>, <Value Array>]
     * Property Array     = [[<field_1>, <value_1>], ..., [<field_n>, <value_n>]]
     * Value Array        = [<value_1>, ..., <value_n>]
     * */

    var inputList = [
                        ["input", "spr_no", [["size", "15"]], null],
                        ["select", "type", null, spr_tracking_type_list],
                        ["select", "status", null, spr_tracking_status_list],
                        ["input", "build_version", [["size", "15"],["placeholder", "Ex: L03,P10,P20"], ["onfocus", "openPopupDialog(this, getBuildVersionDialog);"]], null],
                        ["input", "commit_build", [["size", "15"], ["placeholder", "X-XX-XX"], ["onfocus", "openPopupDialog(this, getCommitBuildDialog);"]], null],
                        ["input", "respond_by_date", [["size", "15"], ["placeholder", "YYYY-MM-DD"]], null],
                        ["textarea", "comment", [["spellcheck", "false"], ["rows", "4"], ["cols","25"], ["maxlength", "500"]], null],
                        ["select", "session", [['sel', current_session]], getSessionList()]
                    ];

    addDashboardRow("spr-tracking-dashboard", inputList);
    //$("table").fixMe();
}

function addSPRTrackingSubmissionStatusRow() {
    /*
     * Each Tag = [<type>, <id>, <Property Array>, <Value Array>]
     * Property Array     = [[<field_1>, <value_1>], ..., [<field_n>, <value_n>]]
     * Value Array        = [<value_1>, ..., <value_n>]
     * */

    var inputList = [
                        ["input", "spr_no", [["size", "15"]], null],
                        ["select", "L03", null, submission_status_list],
                        ["select", "P10", null, submission_status_list],
                        ["select", "P20", null, submission_status_list],
                        ["select", "P30", null, submission_status_list],
                        ["textarea", "comment", [["spellcheck", "false"], ["rows", "6"], ["cols","50"], ["maxlength", "500"]], null]
                    ];

    addDashboardRow("submission-status", inputList);
}

function addWorkTrackerDashboardRow() {
    /*
     * Each Tag = [<type>, <id>, <Property Array>, <Value Array>]
     * Property Array     = [[<field_1>, <value_1>], ..., [<field_n>, <value_n>]]
     * Value Array        = [<value_1>, ..., <value_n>]
     * */

    var inputList = [
                        ["input", "day", [["size", "15"], ["placeholder", "YYYY-MM-DD"]], null],
                        ["input", "task", [["size", "20"]], null],
                        ["select", "category", null, work_tracker_list],
                        ["input", "time", [["size", "15"], ["placeholder", "HH OR HH.MM"]], null],
                        ["textarea", "comment", [["spellcheck", "false"], ["rows", "6"], ["cols","60"], ["maxlength", "500"]], null]
                    ];

    addDashboardRow("work-tracker-dashboard", inputList);
}

function deleteSPRTrackingDashboardRow() {
    deleteDashboardRow('spr-tracking-dashboard');
}

function deleteSPRTrackingSubmissionStatusRow() {
    deleteDashboardRow('submission-status');
}

function deleteWorkTrackerDashboardRow() {
    deleteDashboardRow('work-tracker-dashboard');
}

function showDashboardAccdSession(tagId, func) {
    var formData = {
        'func'        : func,
        'session'     : $('#session-select').val()
    };

    document.getElementById(tagId).innerHTML = getServerResponseViaAJAX("../ajax/default.php", "showDashboardAccdSessionCallback", formData, "");
}

function addSPRTrackingDashboard(flag) {
    var errMsg = "";

    // Add new SPR number for tracking
    if(flag == true) {
        var spr_no = document.getElementById('spr_no-input').value.trim();
        var type = document.getElementById('type-select').value;
        var status = document.getElementById('status-select').value;
        var build_version = document.getElementById('build_version-input').value;
        var commit_build = document.getElementById('commit_build-input').value;
        var respond_by_date = document.getElementById('respond_by_date-input').value;
        var comment = document.getElementById('comment-textarea').value.trim();
        var session = document.getElementById('session-input').value;

        if(spr_no != "") {
            // call server side function via AJAX
            errMsg = getServerResponseViaAJAX("../ajax/default.php", "addSPRTrackingDashboardCallback", {},
                        "spr_no="+spr_no+"&type="+type+"&status="+status+"&build_version="+build_version+"&commit_build="+commit_build+
                        "&respond_by_date="+respond_by_date+"&comment="+comment+"&session="+session);
        }
        else
            errMsg ="Please enter SPR number to track.";
    }
alert("alert");
    if(errMsg != "")
        alert(errMsg);
    else {
        showDashboardAccdSession("spr-tracking-dashboard-tbody", "fillSPRTrackingDashboardRow");

        // hide button container
        document.getElementById('add-cancel-button-container').style.display = "none";
    }
}

function addDeleteSPRTrackingDashboard(flag) {
    if($("#spr-tracking-dashboard-add-button").html() == 'Add') {
        var formData = {
            'spr_no'            : $('#spr_no-input').val(),
            'type'                : $('#type-select').val(),
            'status'            : $('#status-select').val(),
            'build_version'        : $('#build_version-input').val(),
            'commit_build'        : $('#commit_build-input').val(),
            'respond_by_date'    : $('#respond_by_date-input').val(),
            'comment'            : $('#comment-textarea').val(),
            'session'            : $('#session-select').val()
        };
        addToDBTable('spr-tracking-dashboard', 'addSPRTrackingDashboardCallback', formData, "fillSPRTrackingDashboardRow", flag);
    }
    else
        deleteFromDBTable('spr-tracking-dashboard', 'fillSPRTrackingDashboardRow', 'deleteSPRTrackingDashboardCallback', 'spr_no', flag);
}

function addDeleteSPRTrackingSubmissionStatus(flag) {
    if($("#submission-status-add-button").html() == 'Add') {
        var formData = {
            'spr_no'    : $('#spr_no-input').val(),
            'L03'        : $('#L03-select').val(),
            'P10'        : $('#P10-select').val(),
            'P20'        : $('#P20-select').val(),
            'P30'        : $('#P30-select').val(),
            'comment'    : $('#comment-textarea').val()
        };
        addToDBTable('submission-status', 'addSPRSubmissionStatusCallback', formData, "fillSPRTrackingSubmissionStatusRow", flag);
    }
    else
        deleteFromDBTable('submission-status', 'fillSPRTrackingSubmissionStatusRow', 'deleteSPRTrackingSubmissionStatusCallback', 'spr_no', flag);
}

function addToDBTable(id, addRowCallback, formData, fillDashdoardRowFunc, flag) {
    var data;

    if(flag == true)
        data = getServerResponseViaAJAX("../ajax/default.php", addRowCallback, formData, "");

    if(typeof(data) === 'undefined' || data.success == true) {
        $('#session-select').prop('disabled', false);
        showDashboardAccdSession(id + "-tbody", fillDashdoardRowFunc);

        // hide button container
        document.getElementById('add-cancel-button-container').style.display = "none";
    } else {
        var errMsg = "";
        for(var inx in data.errors)
            errMsg += data.errors[inx][1] + "\n";

        if(errMsg != "")
            alert(errMsg);
    }
}

function deleteFromDBTable(id, fillDashboardRow, deleteRowCallback, key, flag) {
    var errMsg = "";

    if(flag == true) {
        // delete permission .
        var r = confirm("Do you want to delete selected element(s)?");
        if (r == true) {
            var where = "";
            var count = 0;

            // loop over table
            $('#'+id+'-table').find('tr').each(function () {
                if($(this).find('td').last().find('input').prop("checked") == true)
                {
                    if(count > 0)
                        where +=" OR ";
                    where += key + "='" + $(this).find('td:first').attr('id').split("-")[0]  + "'";

                    count++;
                }
            });

            // evaluate select query atleast one row is selected.
            if(count > 0) {
                // collect data from page
                var formData = {
                    'where'        : where
                };

                getServerResponseViaAJAX('../ajax/default.php', deleteRowCallback, formData, "");
            }
            else
                errMsg = "Please select Row(s) to delete.";
        }
    }

    if(errMsg != "")
        alert(errMsg);
    else {
        $('#session-select').prop('disabled', false);

        showDashboardAccdSession(id + "-tbody", fillDashboardRow);

        deleteTableCol(id+'-table');
        deleteTableCol('fixed_table');
        setThWidth(id+'-table', 'fixed_table');

        document.getElementById('add-cancel-button-container').style.display = "none";
    }
}

function cancelEditTable(id, fillDashboardRow)
{
    $('#session-select').prop('disabled', false);

    var editMenuElem = document.getElementById('spr-tracking-dashboard-table-dropdown');
    editMenuElem.style.display = "none";

    showDashboardAccdSession(id + "-tbody", fillDashboardRow);

    // delete 'TH' element
    var tableTH = $('#'+ id+'-table').find('th');
    tableTH.eq(tableTH.length - 1).remove();

    setThWidth(id+'-table', 'fixed_table');
}

function addDeleteWorkTracker(flag) {
    if($("#work-tracker-dashboard-add-button").html() == 'Add') {
        // collect data from page
        var formData = {
            'day'        : $('#day-input').val(),
            'task'        : $('#task-input').val(),
            'category'    : $('#category-select').val(),
            'time'        : $('#time-input').val(),
            'comment'    : $('#comment-textarea').val()
        };
        addToDBTable('work-tracker-dashboard', 'addWorkTrackerCallback', formData, "fillWorkTrackerDashboardRow", flag);
    }
    else
        deleteFromDBTable('work-tracker-dashboard', 'fillWorkTrackerDashboardRow', 'deleteWorkTrackerCallback', 'id', flag);
}

function loginSubmit() {
    // set/reset to default style
    $('input').removeClass('form-error');
    $('.errmsg').css( "display", "none" );

    // collect data from page
    var formData = {
        'username'  : $('input[name=username]').val(),
        'password'  : $('input[name=password]').val()
    };

    // call AJAX function
    $.ajax({
        type         : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : '../ajax/default.php?f=loginSubmitCallback', // the url where we want to POST
        data         : formData, // our data object
        dataType     : 'json', // what type of data do we expect back from the server
        encode         : true
    })

    // handle error
    .done(function(data) {

        // log data to the console so we can see
        console.log(data);

        // here we will handle errors and validation messages
        if ( ! data.success) {
            if(data.errors.username) {
                $('#username-input').addClass('form-error');
                $('#username-errmsg').css( "display", "block" );
                $('#username-errmsg').text( data.errors.username );
            }

            if (data.errors.password) {
                $('#password-input').addClass('form-error');
                $('#password-errmsg').css( "display", "block" );
                $('#password-errmsg').text( data.errors.password );
            }

        } else {
            // usually after form submission, you'll want to redirect
            var redirect = $('input[name=redirect]').val();
            if(redirect != "")
                window.location = redirect;
            else
                window.location = '../index.php';
        }
    })

    // using the fail promise callback
    .fail(function(data) {
        // show any errors
        // best to remove for production
        console.log(data);
    })
}

function recoveryOptionSelected(tagId) {
    // set/reset to default properties
    $('.recovery-radio-input-container').css( "display", "none" );

    // visible input field for selected radio button
    $('#' + tagId).css( "display", "block" );
}

function recoverySubmit() {
    // set/reset to default option.
    $('input').removeClass('form-error');
    $('.errmsg').css( "display", "none" );
    $('#recovery-main').css( "display", "block" );
    $('#recovery-msg-container').css( "display", "none" );

    // collect input data
    var formData = {
        'recovery'        : $('input[name=recovery]:checked').val(),
        'username'         : $('input[name=username]').val(),
        'email'         : $('input[name=email]').val()
    };

    // call AJAX function
    $.ajax({
        type         : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : '../ajax/default.php?f=recoverySubmitCallback', // the url where we want to POST
        data         : formData, // our data object
        dataType     : 'json', // what type of data do we expect back from the server
        encode         : true
    })

    // if error handle it
    .done(function(data) {

        // log data to the console so we can see
        console.log(data);

        // here we will handle errors and validation messages
        if ( ! data.success) {

            if(data.errors.recovery) {
                $('#recovery-errmsg').css( "display", "block" );
                $('#recovery-errmsg').text( data.errors.recovery );
            } else if (data.errors.username) {
                $('#recovery-errmsg').css( "display", "block" );
                $('#recovery-errmsg').text( data.errors.username );
            } else if (data.errors.email) {
                $('#recovery-errmsg').css( "display", "block" );
                $('#recovery-errmsg').text( data.errors.email );
            }

        } else {

            $('#recovery-main').css( "display", "none" );
            $('#recovery-msg-container').css( "display", "block");
            $('#recovery-p').text( data.msg );
        }
    })

    // using the fail promise callback
    .fail(function(data) {
        // show any errors
        // best to remove for production
        console.log(data);
    })
}

function signupSubmit() {
    // set/reset to default style
    $('input').removeClass('form-error');
    $('select').removeClass('form-error');
    $('.errmsg').css( "display", "none" );

    // collect data from page
    var formData = {
        'firstName'            : $('input[name=firstName]').val(),
        'lastName'            : $('input[name=lastName]').val(),
        'username'            : $('input[name=username]').val(),
        'password'            : $('input[name=password]').val(),
        'confirm-password'    : $('input[name=confirm-password]').val(),
        'gender'            : $('select[name=gender]').val(),
        'title'                : $('select[name=title]').val(),
        'department'        : $('select[name=department]').val(),
        'manager'            : $('input[name=manager]').val(),
        'email'             : $('input[name=email]').val(),
        'altEmail'             : $('input[name=altEmail]').val()
    };

    // call AJAX function
    $.ajax({
        type         : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : '../ajax/default.php?f=signupSubmitCallback', // the url where we want to POST
        data         : formData, // our data object
        dataType     : 'json', // what type of data do we expect back from the server
        encode         : true
    })

    // handle error
    .done(function(data) {

        // log data to the console so we can see
        console.log(data);

        // here we will handle errors and validation messages
        if ( ! data.success) {

            for(var inx in data.errors) {
                var id_errmsg = "";
                if((data.errors[inx][0] == "firstName") || (data.errors[inx][0] == "lastName"))
                    id_errmsg = "name";
                else
                    id_errmsg = data.errors[inx][0];

                $('#'+data.errors[inx][0]+'-input').addClass('form-error');
                $('#'+id_errmsg+'-errmsg').css( "display", "block" );
                $('#'+id_errmsg+'-errmsg').text( data.errors[inx][1] );
            }

        } else {

            // usually after form submission, you'll want to redirect
            window.location = '../result.php?user=created';
        }
    })

    // using the fail promise callback
    .fail(function(data) {

        // show any errors
        // best to remove for production
        console.log(data);
    })
}

function showErrorMsg(tagId, type, errmsgId) {
    if(errmsgId == "")
        errmsgId = tagId;

    // set/reset default properties
    $('#'+tagId+'-'+type).removeClass('form-error');
    $('#'+errmsgId+'-errmsg').css( "display", "none" );

    // collect data from page
    var formData = {
        'tag_id'            : tagId,
        'tag_val'            : $('#'+tagId+'-'+type).val(),
        'password_val'        : $('#password-input').val()
    };

    // call AJAX function
    $.ajax({
        type         : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : '../ajax/default.php?f=showErrorMsgCallback', // the url where we want to POST
        data         : formData, // our data object
        dataType     : 'json', // what type of data do we expect back from the server
        encode         : true
    })

    // handle error
    .done(function(data) {

        // log data to the console so we can see
        console.log(data);

        // here we will handle errors and validation messages
        if ( ! data.success) {

            $('#'+tagId+'-'+type).addClass('form-error');
            $('#'+errmsgId+'-errmsg').css( "display", "block" );
            $('#'+errmsgId+'-errmsg').text( data.errors[tagId] );
        }
    })

    // using the fail promise callback
    .fail(function(data) {

        // show any errors
        // best to remove for production
        console.log(data);
    })
}

function setAddDeleteContainer(id, val, status) {
    $("#" + id + "-add-button").html(val);
    $('#add-cancel-button-container').css("display", status);
}

function setThWidth(sTableId, dTableId) {
    var sTableTH = $('#'+sTableId).find('th');
    var dTableTH = $('#'+dTableId).find('th');

    for ( var i = 0; i < dTableTH.length; i++ ) {
        dTableTH.eq(i).css("width",(sTableTH.eq(i).outerWidth() - 2)+"px");
    }
}

function addTableCol(tabId, val, col) {
    var tableTH = $('#'+tabId).find('th');
    last_col = (typeof(col) !== 'undefined') ? col : (tableTH.length - 1);

    // add 'TH' element
    tableTH.eq(last_col).after('<th>Delete</th>');

    // add 'TD' element
    $('#'+tabId).find('tr').each(function () {
        if(typeof(col) === 'undefined')
            $(this).find('td').last().after('<td>'+val+'</td>');
        else
            $(this).find('td').eq(col).after('<td>'+val+'</td>');
    });
}

function deleteTableCol(tabId, col) {
    // delete 'TH' element
    var tableTH = $('#'+tabId).find('th');
    col = (typeof(col) !== 'undefined') ? col : (tableTH.length - 1);

    tableTH.eq(col).remove();

    // delete 'TD' element
    $('#'+tabId).find('tr').each(function () {
        $(this).find('td').eq(col).remove();
    });
}

function importCSV() {
    // open file selector dialog
    var doImport = false;

    var csv_file = 'High_customer_SPRs_of_Nath_Abhishek.csv';
    // check file (only CSV file accepted)
    var pieces = csv_file.split('.');
    var extn = pieces[pieces.length - 1];
    while(extn != "csv") {
        // open file selector dialog
        csv_file = 'High_customer_SPRs_of_Nath_Abhishek.csv';
        pieces = csv_file.split('.');
        extn = pieces[pieces.length - 1];
    }

    doImport = true;
    if(doImport == true) {
        // Do AJAX call
        // collect data from page
        var formData = {
            'type'            : 'Import',
            'filePath'        : csv_file
        };

        // call AJAX function
        $.ajax({
            type         : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '../ajax/default.php?f=importExportCSVCallback', // the url where we want to POST
            data         : formData, // our data object
            dataType     : 'json', // what type of data do we expect back from the server
            encode         : true
        })

        // handle error
        .done(function(data) {

            // log data to the console so we can see
            console.log(data);

            if ( ! data.success) {
                alert(data.errors.misc);
            }
            else
                alert("Import successfully.");
        })

        // using the fail promise callback
        .fail(function(data) {

            // show any errors
            // best to remove for production
            console.log(data);
        })
    }
}

function importExportCSV(id, fillDashdoardRowFunc) {
    var misc_type = $('#misc-select').val();

    // Disable 'Session' option untill Import/Export is done.
    $('#session-select').prop('disabled', true);

    // Import
    if(misc_type == 'Import') {
        importCSV();

        // update data table in screen
        showDashboardAccdSession(id + "-tbody", fillDashdoardRowFunc);
    }

    // Export
    else if(misc_type == 'Export') {
        // open folder selector dialog
            // check file with same name exists or not.
            // if exists then show error msg
                // if user wants to delet the existing file then delete it.
                // if not then create file with _1,_2, ..., _n format.
            // collect data from database.
            // create csv file with those collected data.
    }

    // Enable 'Session' option after Import/Export is done.
    $('#session-select').prop('disabled', false);
}

function showFullComment(id, flag) {
    var tag = "";

    // flag = true     -> display comment block
    if(flag) {
        var element = document.getElementById(id+'-comment');
        //openPopupDialog(element, getCommitBuildDialog);
    } else {
        // flag = false    -> hide comment block
        //show(false);
    }
}

function showSPRTrackingReportSearchSubOptions(Obj) {
    var sub_search_container = document.getElementById('sub-search-container');
    var sub_search_select = document.getElementById('sub-search-select');

    /// reset 'sub-search-container' and select tag
    sub_search_select.options[0].selected = true;
    sub_search_container.style.display = 'none';

    /// get selected search option.
    var main_search_option = Obj.options[Obj.selectedIndex].value;

    /// collect all the sub search option for the selected search option
    for(var i = 0; i < spr_tracking_report_search_sub_list.length; i++) {
        if(spr_tracking_report_search_sub_list[i][0] == main_search_option) {
            /// reset all options of the sub-search-select.
            for(var j = sub_search_select.options.length - 1; j>=0; j--) {
                sub_search_select.remove(j);
            }

            /// Add 'Blank' option.
            sub_search_select.options[0] = new Option(' ', 'Blank');

            /// and add them in the sub search list.
            /// visible the sub search options.
            for(var k = 0; k < spr_tracking_report_search_sub_list[i][1].length; k++) {
                sub_search_select.options[k+1] = new Option(spr_tracking_report_search_sub_list[i][1][k],
                                                            spr_tracking_report_search_sub_list[i][1][k]);
            }

            sub_search_select.options[0].selected = true;
            sub_search_container.style.display = 'block';
        }
    }
    /// if no sub option found then don't do any thing.
}

function showSPRTrackingReportSearchResult() {
    /// reset all
    var search_result_container = document.getElementById('search-result-container');
    search_result_container.style.display = 'none';

    /// check for the black field.
    ///    'Search for' is empty?
    var main_search_select = document.getElementById('main-search-select');
    var main_search_select_string = main_search_select.options[main_search_select.selectedIndex].text;
    if(main_search_select_string != "") {
        /// 'Condition' is empty?
        var sub_search_select = document.getElementById('sub-search-select');
        var sub_search_select_string = sub_search_select.options[sub_search_select.selectedIndex].text;
        if(sub_search_select_string != "") {
            /// reset report table.
            /// get selected value from all 3 select box.
            /// make query according to the input.
            /// display result.
            var formData = {
                                'session'            : $('#search-session-select').val(),
                                'main_search'        : main_search_select_string,
                                'sub_search'        : sub_search_select_string
                           };
            search_result_container.innerHTML = getServerResponseViaAJAX("../ajax/default.php", "showSPRTrackingReportCallback", formData, "");
            search_result_container.style.display = 'block';

            $("table").fixMe();
        }
        else
            alert("Please select item from 'Condition' option!");
    }
    else
        alert("Please select item from 'Search for' option!");
}

var memberRoles = {
    info: {
        rowid           : '',
        projectTBody    : 'project-tbody',
        memberDiv       : 'member-div'
    },

    showMemberTable: function(rowId) {
        if((rowId != null) && (rowId != '')) {
            var isOpen = false;

            // check member table is already shown or not.
            // if not then open it.
            // else show warning and ask user whether they want's to change member table without saving current change.
            var memberDivDisplay = $('#' + this.info.memberDiv).css('display');
            if((memberDivDisplay != null) && (memberDivDisplay != '') & (memberDivDisplay != 'none')) {
                var r = confirm("Do you want to close unsaved Member Table?");
                if (r == false) {
                    isOpen = true;
                }
            }

            if(isOpen == false) {
                // change background-color of selected row of Project table.
                // reset bg-color for all the tr.
                $('#' + this.info.projectTBody).find('tr').each(function () {
                    $(this).removeClass('alice-blue-bg');
                });

                // set bg-color for selected color.
                $('#' + rowId + '-project-tr').addClass('alice-blue-bg');

                var projectName = document.getElementById(rowId + '-title').innerHTML;
                // get members for the selected project.
                utility.updateDashboradTable('member-tbody', 'getMemberTableElement', 'MemberRolesHTML', projectName);

                // display member for changing role.
                $('#' + this.info.memberDiv).css('display', 'block');
                this.info.rowid = rowId;
            }
        }
    },

    close: function() {
        // unselect the row by removing 'alice-blue-bg' from selected tr element.
        $('#' + this.info.rowid + '-project-tr').removeClass('alice-blue-bg');
        // hide member table.
        $('#' + this.info.memberDiv).css('display', 'none');
        // reset row id for further use.
        this.info.rowid = '';
    },

    save: function() {
        var memberTBody = document.getElementById("member-tbody");
        if(memberTBody != null) {
            var owner = document.getElementById(this.info.rowid + '-owner').innerHTML;

            var memberRoles = [];
            var childSize = memberTBody.childElementCount;
            // loop over all the members and save them into database.
            for(var i = 1; i <= childSize; ++i) {
                var memberId = document.getElementById(i + "-member_id").innerHTML;
                // check it's owner or not.
                if(memberId != owner) {
                    var newRole = $('#'+ i +'-privilage-select').val();
                    // If not then check 'New Project Role' is empty or not
                    if(newRole != 'none'){
                        var count = i-1;
                        memberRoles[count] = Array(memberId, newRole);
                    }
                }
            }

            var formData = {
                "memberRoles": JSON.stringify(memberRoles),
                "project": document.getElementById(this.info.rowid + '-title').innerHTML
            };

            // If not update database.
            var data = {
                url             : "../ajax/default.php",
                callbackFunc    : "updateMemberRolesCallback",
                formData        : formData,
                successFunc     : null,
                errorFunc       : null,
                failFunc        : null
            };

            // Update DB according to the input and also update sprint schedule list in the display.
            utility.ajax.serverRespond(data);
        }

        // hide member table
        this.close();
    }
};


var projectAssignment = {
    info : {
        memberTBodyId : 'member-tbody',
        selUserList : []
    },

    selectAllMembers: function() {
        // check check box is selected or not.
        var status = document.getElementById("member-th-checkbox").checked;

        // enable/disable 'assign-project-btn' button.
        $('#assign-project-btn').prop('disabled', ((status) ? false : true));

        // update all checkbox in the table according to the input.
        var len = document.getElementById(this.info.memberTBodyId).getElementsByTagName("tr").length;
        for(var i = 1; i <= len; i++) {
            document.getElementById(i + "-checkbox").checked = status;
        }
    },

    selectMember: function() {
        var check = true;
        var disable = true;

        // loop over all the checkbox of the table.
        var len = document.getElementById(this.info.memberTBodyId).getElementsByTagName("tr").length;
        // to improve execution time, check from both side.
        for(var i = 1, j=len; i <= j; i++, j--) {
            var iCheck = $('#' + i + '-checkbox').prop('checked');
            var jCheck = $('#' + j + '-checkbox').prop('checked');

            // check all the checkbox are checked or not.
            if((check) && (iCheck == false)) {
                check = false;
            }
            if((check) && (jCheck == false)) {
                check = false;
            }

            // check atleast one checkbox is checked or not.
            if((disable) && (iCheck == true)) {
                disable = false;
            }
            if((disable) && (jCheck == true)) {
                disable = false;
            }
        }

        // check/uncheck 'member-th-checkbox' checkbox
        $('#member-th-checkbox').prop('checked', check);
        // enable/disable 'assign-project-btn' button.
        $('#assign-project-btn').prop('disabled', disable);
    },

    assign: function() {
        // check button is enable or not
        if($('#assign-project-btn').prop('disabled') == false) {
            // collect all the selected user name in the list.
            // open shield having project list.
        }
    }
};

var projectRoles = {
    info: {
        rowid           : '',
        memberTBody     : 'member-tbody',
        projectDiv      : 'project-div'
    },

    showProjectTable: function(rowId) {
        if((rowId != null) && (rowId != '')) {
            var isOpen = false;

            // check member table is already shown or not.
            // if not then open it.
            // else show warning and ask user whether they want's to change member table without saving current change.
            var memberDivDisplay = $('#' + this.info.projectDiv).css('display');
            if((memberDivDisplay != null) && (memberDivDisplay != '') & (memberDivDisplay != 'none')) {
                var r = confirm("Do you want to close unsaved Member Table?");
                if (r == false) {
                    isOpen = true;
                }
            }

            if(isOpen == false) {
                // change background-color of selected row of Project table.
                // reset bg-color for all the tr.
                $('#' + this.info.memberTBody).find('tr').each(function () {
                    $(this).removeClass('alice-blue-bg');
                });

                // set bg-color for selected color.
                $('#' + rowId + '-project-tr').addClass('alice-blue-bg');

                var memberId = document.getElementById(rowId + '-member_id').innerHTML;
                // get members for the selected project.
                utility.updateDashboradTable('project-tbody', 'getProjectTableElement', 'ProjectRolesHTML', memberId);

                // display member for changing role.
                $('#' + this.info.projectDiv).css('display', 'block');
                this.info.rowid = rowId;
            }
        }
    },

    close: function() {
        // unselect the row by removing 'alice-blue-bg' from selected tr element.
        $('#' + this.info.rowid + '-project-tr').removeClass('alice-blue-bg');
        // hide member table.
        $('#' + this.info.projectDiv).css('display', 'none');
        // reset row id for further use.
        this.info.rowid = '';
    },

    save: function() {
        // check all the inputs are proper or not?
        // if not then display the error
        // otherwise save information and close the member table.
        this.close();
    }
};
