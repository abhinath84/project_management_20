/*--
    File    : js/shield.js
    Author  : Abhishek Nath
    Date    : 10-Jan-2016
    Desc    : file to handle functionality to shield container and related stuff.
--*/

/*--
    10-Jan-17   V1-01-00   abhishek   $$1   Created.
--*/


var sprintScheduleInfo = {
    title: "",
    len: "1",
    len_unit: "days",
    gap: "0",
    gap_unit: "days",
    description: "",
    setDefult: function() {
        this.title = "";
        this.len = "1";
        this.len_unit = "days";
        this.gap = "0";
        this.gap_unit = "days";
        this.description = "";
    }
};

function createSprintScheduleDialog() {

    var tag = '';

    tag += '<div class="toolbar">';
    tag += '    <span class="icon"></span>';
    tag += '    <h2>Sprint Schedule</h2>';
    tag += '    <div class="submit-btns">'
    tag += '        <button class="retro-style red add-spr" type="button" onclick="shield.show(false)">';
    tag += '            <span>Cancel</span>';
    tag += '        </button>';
    tag += '    </div>';
    tag += '    <div class="submit-btns">'
    tag += '        <button class="retro-style green-bg add-spr" type="button" onclick="shield.show(false)">';
    tag += '            <span>Save & New</span>';
    tag += '        </button>';
    tag += '    </div>';
    tag += '    <div class="submit-btns">'
    tag += '        <button class="retro-style green-bg add-spr" type="button" onclick="shield.show(false)">';
    tag += '            <span>Save</span>';
    tag += '        </button>';
    tag += '    </div>';
    tag += '</div>';

    // Added field for input for Sprint Schedule.
    tag += '<div class="sprint-schedule-form line">';
    tag += '    <table class="sprint-schedule-table">';
    tag += '        <tr>';
    tag += '            <td class="label-td"><label for="title">Title:</label></td>';
    tag += '            <td class="text-td"><input style="width: 400px;" type="text" name="title" value="' + sprintScheduleInfo.title + '"/><span class="red-asterisk">*</span></td>';
    tag += '        </tr>';
    tag += '        <tr>';
    tag += '            <td class="label-td"><label for="len">Sprint Length:</label></td>';
    tag += '            <td class="text-td">';
    tag += '                <input style="width: 50px;" type="text" name="len" value="' + sprintScheduleInfo.len + '" />';
    tag += '                <select id="len-unit-select" class="retro-style unit-select">';
    tag += '                    <option value="days"'+ ((sprintScheduleInfo.len_unit == "days") ? 'selected' : '') +'>days</option>';
    tag += '                    <option value="weeks"'+ ((sprintScheduleInfo.len_unit == "weeks") ? 'selected' : '') +'>weeks</option>';
    tag += '                    <option value="months"'+ ((sprintScheduleInfo.len_unit == "months") ? 'selected' : '') +'>months</option>';
    tag += '                </select>'
    tag += '            </td>';
    tag += '        </tr>';
    tag += '        <tr>';
    tag += '            <td class="label-td"><label for="gap">Sprint Gap:</label></td>';
    tag += '            <td class="text-td">';
    tag += '                <input style="width: 50px;" type="text" name="gap" value="' + sprintScheduleInfo.gap + '" />';
    tag += '                <select id="gap-unit-select" class="retro-style unit-select">';
    tag += '                    <option value="days"'+ ((sprintScheduleInfo.gap_unit == "days") ? 'selected' : '') +'>days</option>';
    tag += '                    <option value="weeks"'+ ((sprintScheduleInfo.gap_unit == "weeks") ? 'selected' : '') +'>weeks</option>';
    tag += '                    <option value="months"'+ ((sprintScheduleInfo.gap_unit == "months") ? 'selected' : '') +'>months</option>';
    tag += '                </select>'
    tag += '            </td>';
    tag += '        </tr>';
    tag += '        <tr>';
    tag += '            <td class="label-td"><label for="description">Description:</label></td>';
    tag += '            <td class="text-td">';
    tag += '                <textarea name="description" rows="15" cols="120" spellcheck="false">' + sprintScheduleInfo.description + '</textarea>';
    tag += '            </td>';
    tag += '        </tr>';
    tag += '    </table>';
    tag += '</div>';
    //tag += '<span class="close-icon close" onclick="showShield(false)"></span>';

    return(tag);
}

function addSprintScheduleDialog() {
    sprintScheduleInfo.setDefult();

    shield.openDialog(this, false, 'sprint-schedule-add-form-container', createSprintScheduleDialog);
}

function editSprintScheduleDialog(Id, isCallingFromDropMenu) {

    var key = '';
    var divObj = document.getElementById(Id).children[0];

    if(isCallingFromDropMenu) {
        key = document.getElementById(divObj.innerHTML).children[0].innerHTML;
    } else {
        key = divObj.innerHTML;
    }
    if((key != null) && (key != "")) {
        // update sprintScheduleInfo and open dialog with updated infomsg
        sprintScheduleInfo.title = document.getElementById(key + "-title").innerHTML; //"Base Sprint Schedule";

        var lenStr = document.getElementById(key + "-length").innerHTML;
        var res = lenStr.split(" ");
        sprintScheduleInfo.len = res[0]; //"1";
        sprintScheduleInfo.len_unit = res[1];

        lenStr = document.getElementById(key + "-gap").innerHTML;
        res = lenStr.split(" ");
        sprintScheduleInfo.gap = res[0]; //"2";
        sprintScheduleInfo.gap_unit = res[1];
        sprintScheduleInfo.description = document.getElementById(key + "-desc").innerHTML; //"Base Sprint Schedule";

        shield.openDialog(this, false, 'sprint-schedule-add-form-container', createSprintScheduleDialog);
    }
}


/* Create namespace Using Object Literal Notation */
var shield = {

    /**
    * This function show or hide shield container depending upon 'mode'.
    * @namespace    shield
    * @method       show
    * @param        {bool} mode - show or hide shield container
    */
    show: function (mode) {
        if(mode != null) {
            obj = document.getElementById('shield-wrapper');
            if(mode)
                obj.style.visibility = 'visible';
            else
                obj.style.visibility = 'hidden';
        }
    },

    /**
    * This is a description
    * @namespace    shield
    * @method       openDialog
    * @param        {Object} selObj - object for which we will set dialog position.
    * @param        {requestCallback} dialogCreateFunc - The callback that handles the response.
    *                                                   Means will create inner component of the dialog.
    * @param        {String} str - some string

    * @return {bool} some bool
    */
    openDialog: function (selObj, takeSelPos, destId, dialogCreateFunc) {
        var o_top = 0;
        var o_left = 0;

        if((selObj != null) && (takeSelPos != null) && (destId != "") && (dialogCreateFunc != "")) {
            // collect the position of the dialog to be appired.
            if(takeSelPos == true) {
                var offsets = $(selObj).position();

                o_top = offsets.top + 22;
                o_left = offsets.left;

            } else {
                // get top 1/4 position of the screen to display the dialog.
                var width = utility.getWindowWidth();
                var height = utility.getWindowHeight();

                o_top = height/4;
                o_left = width/4;
            }

            // create the dialog container and components before displaying them.
            var tag = '<div id="shield-wrapper" class="shield-wrapper">';
            tag += '    <div class="shield" onclick="shield.show(false)"></div>';
            tag += '    <div id="shield-form-window" class="shield-window shield-window-position">';
            tag +=          dialogCreateFunc();     // user specific function which creates inner component of the dialog.
            tag += '    </div>';
            tag += '</div>';

            $("#" + destId).html(tag);
            //$('#shield-form-window').offset({ top: o_top, left: o_left});

            shield.show(true);
        }
    }
};
