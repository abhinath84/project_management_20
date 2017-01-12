/*--
    File    : js/shield.js
    Author  : Abhishek Nath
    Date    : 10-Jan-2016
    Desc    : file to handle functionality to shield container and related stuff.
--*/

/*--
    10-Jan-17   V1-01-00   abhishek   $$1   Created.
--*/

var shieldSprintSchedule = {
    info : {
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
    },

    createDialog: function () {
        return(shieldDialog.getTag());
    },

    fillTitle: function () {
        // fill all the toolbar related infomation before move on.
        shieldDialog.toolBar.title = "Sprint Schedule";
        shieldDialog.toolBar.imgIconClass = "sprint-schedule-icon-img";
    },

    fillTable: function () {
        var inputTag = '';

        // fill all infomation of dialog form.
        // reset before use.
        shieldDialog.formTable.clear();

        inputTag += '<input style="width: 400px;" type="text" id="title-input" name="title" value="' + this.info.title + '"/>';
        inputTag += '<span class="red-asterisk">*</span>';
        shieldDialog.formTable.add('Title', inputTag);

        inputTag = '                <input style="width: 50px;" type="text" id="length-input" name="len" value="' + this.info.len + '" />';
        inputTag += '                <select id="length_unit-select" class="retro-style unit-select">';
        inputTag += '                    <option value="days"'+ ((this.info.len_unit == "days") ? 'selected' : '') +'>days</option>';
        inputTag += '                    <option value="weeks"'+ ((this.info.len_unit == "weeks") ? 'selected' : '') +'>weeks</option>';
        inputTag += '                    <option value="months"'+ ((this.info.len_unit == "months") ? 'selected' : '') +'>months</option>';
        inputTag += '                </select>';
        shieldDialog.formTable.add('Sprint Length', inputTag);

        inputTag = '                <input style="width: 50px;" type="text" id="gap-input" name="gap" value="' + this.info.gap + '" />';
        inputTag += '                <select id="gap_unit-select" class="retro-style unit-select">';
        inputTag += '                    <option value="days"'+ ((this.info.gap_unit == "days") ? 'selected' : '') +'>days</option>';
        inputTag += '                    <option value="weeks"'+ ((this.info.gap_unit == "weeks") ? 'selected' : '') +'>weeks</option>';
        inputTag += '                    <option value="months"'+ ((this.info.gap_unit == "months") ? 'selected' : '') +'>months</option>';
        inputTag += '                </select>';
        shieldDialog.formTable.add('Sprint Gap', inputTag);

        inputTag = '<textarea id="description-textarea" name="description" rows="15" cols="120" spellcheck="false">' + this.info.description + '</textarea>';
        shieldDialog.formTable.add('Description', inputTag);
    },

    getFormData: function () {
        return({
                'title'         : $('#title-input').val(),
                'length'        : $('#length-input').val(),
                'length_unit'   : $('#length_unit-select').val(),
                'gap'           : $('#gap-input').val(),
                'gap_unit'      : $('#gap_unit-select').val(),
                'description'   : $('#description-textarea').val(),
                'key_title'     : this.info.title
        });
    },

    getServerResponseSprintSchedule: function () {
        //var retdata;
        var formData = this.getFormData();

        utility.ajax.serverRespond( {
            url             : "../ajax/default.php",
            callbackFunc    : "addSprintScheduleCallback",
            formData        : formData,
            successFunc     : shieldSprintSchedule.onclickSaveSuccessFunc,
            errorFunc       : null,
            failFunc        : null
        });

        // call AJAX function
        /*$.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '../ajax/default.php?f=addSprintScheduleCallback', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        })

        // handle error
        .done(function(data) {

            var formData = {
                'fillTableFunc' : 'fillSprintSheduleTable',
            };

            document.getElementById('sprint-schedule-tbody').innerHTML = getServerResponseViaAJAX("../ajax/default.php", "updateDashboradTablecallback", formData, "");

            // log data to the console so we can see
            console.log(data);

            // here we will handle errors and validation messages
            if ( ! data.success) {
                /*for(var inx in data.errors) {
                    var id_errmsg = "";
                    if((data.errors[inx][0] == "firstName") || (data.errors[inx][0] == "lastName"))
                        id_errmsg = "name";
                    else
                        id_errmsg = data.errors[inx][0];

                    $('#'+data.errors[inx][0]+'-input').addClass('form-error');
                    $('#'+id_errmsg+'-errmsg').css( "display", "block" );
                    $('#'+id_errmsg+'-errmsg').text( data.errors[inx][1] );
                }*/
                /*console.log(data);

            } else {
                console.log(data);
                // usually after form submission, you'll want to redirect
                //window.location = '../result.php?user=created';
            }
        })

        // using the fail promise callback
        .fail(function(data) {

            // show any errors
            // best to remove for production
            console.log(data);

            var formData = {
                'fillTableFunc' : 'fillSprintSheduleTable',
            };

            document.getElementById('sprint-schedule-tbody').innerHTML = getServerResponseViaAJAX("../ajax/default.php", "updateDashboradTablecallback", formData, "");
        })

        return(retdata);*/
    },

    onclickCancel: function (tbodyId) {
        // hide shield dialog.
        shield.show(false);

        // update
    },

    onclickSaveNew: function (tbodyId) {
        shield.show(false);
    },

    onclickSaveSuccessFunc: function (data) {
        // hide the dialog
        shield.show(false);

        // update sprint schedule list.
        utility.updateDashboradTable('sprint-schedule-tbody', 'fillSprintSheduleTable');
    },

    onclickSaveErrorFunc: function (data) {
        // update sprint schedule list.
        //utility.updateDashboradTable('sprint-schedule-tbody', 'fillSprintSheduleTable');
    },

    onclickSave: function (tbodyId) {
        var formData = this.getFormData();

        var data = {
            url             : "../ajax/default.php",
            callbackFunc    : "",
            formData        : formData,
            successFunc     : shieldSprintSchedule.onclickSaveSuccessFunc,
            errorFunc       : shieldSprintSchedule.onclickSaveErrorFunc,
            failFunc        : null
        };


        if(this.info.title == "") {
            // Add new entry.
            data.callbackFunc = "addSprintScheduleCallback";
        } else {
            // Edit existing sprint schedule.
            data.callbackFunc = "updateSprintScheduleCallback";
        }

        // Update DB according to the input and also update sprint schedule list in the display.
        utility.ajax.serverRespond(data);
    },

    openAddDialog: function (tbodyId) {
        this.info.setDefult();

        // fill all infomation of dialog title(toolBar).
        this.fillTitle();

        // reset and add button for dialog
        shieldDialog.toolBar.toolbarBtns.clear();
        shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style red add-spr', 'Cancel', 'onclick="shieldSprintSchedule.onclickCancel(\''+ tbodyId +'\')"');
        shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style green-bg add-spr', 'Save & New', 'onclick="shieldSprintSchedule.onclickSaveNew(\''+ tbodyId +'\')"');
        shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style green-bg add-spr', 'Save', 'onclick="shieldSprintSchedule.onclickSave(\''+ tbodyId +'\')"');

        // fill all infomation of dialog form.
        this.fillTable();

        shield.openDialog(this, false, 'sprint-schedule-add-form-container', this.createDialog);
    },

    openEditDialog: function (Id, tbodyId, isCallingFromDropMenu) {

        var key = '';
        var divObj = document.getElementById(Id).children[0];

        if(isCallingFromDropMenu) {
            key = document.getElementById(divObj.innerHTML).children[0].innerHTML;
            document.getElementById(Id).style.display = "none";
        } else {
            key = divObj.innerHTML;
        }

        if((key != null) && (key != "")) {
            // update this.info and open dialog with updated infomsg
            this.info.title = document.getElementById(key + "-title").innerHTML; //"Base Sprint Schedule";

            var lenStr = document.getElementById(key + "-length").innerHTML;
            var res = lenStr.split(" ");
            this.info.len = res[0]; //"1";
            this.info.len_unit = res[1];

            lenStr = document.getElementById(key + "-gap").innerHTML;
            res = lenStr.split(" ");
            this.info.gap = res[0]; //"2";
            this.info.gap_unit = res[1];
            this.info.description = document.getElementById(key + "-description").innerHTML; //"Base Sprint Schedule";

            // fill all infomation of dialog title(toolBar).
            this.fillTitle();

            // reset and add button for dialog
            shieldDialog.toolBar.toolbarBtns.clear();
            shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style red add-spr', 'Cancel', 'onclick="shieldSprintSchedule.onclickCancel(\''+ tbodyId +'\')"');
            shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style green-bg add-spr', 'Save', 'onclick="shieldSprintSchedule.onclickSave(\''+ tbodyId +'\')"');

            // fill all infomation of dialog form.
            this.fillTable();

            shield.openDialog(this, false, 'sprint-schedule-add-form-container', this.createDialog);
        }
    },

    delete: function(Id, tbodyId) {

        var key = '';
        var title = '';
        var divObj = document.getElementById(Id).children[0];

        key = document.getElementById(divObj.innerHTML).children[0].innerHTML;
        title = document.getElementById(key + "-title").innerHTML;
        document.getElementById(Id).style.display = "none";

        var r = confirm("Do you want to delete '" + title + "' ?");
        if (r == true) {
            var formData = {
                    'clause'    : "title = '" + title + "'"
            };

            var data = {
                url             : "../ajax/default.php",
                callbackFunc    : "deleteSprintScheduleCallback",
                formData        : formData,
                successFunc     : function () {
                    utility.updateDashboradTable('sprint-schedule-tbody', 'fillSprintSheduleTable');
                },
                errorFunc       : shieldSprintSchedule.onclickSaveErrorFunc,
                failFunc        : null
            };

            // Update DB according to the input and also update sprint schedule list in the display.
            utility.ajax.serverRespond(data);
        }
    }
};

var shieldProject = {

};

/* object to create dialog for shield popup. */
var shieldDialog = {
    toolBar : {
        title           : "",
        imgIconClass    : "",
        toolbarBtns     : {
        buttons         : [],

        setDefult: function() {
            this.title = "";
            this.imgIconClass = "";
            this.toolbarBtns.clear();
        },

        add: function (divClass, btnClass, btnTitle, btnEvent) {
              this.buttons.push({
                divClass: divClass,
                btnClass: btnClass,
                btnTitle: btnTitle,
                btnEvent: btnEvent
              });
            },

        clear: function () {
                while(this.buttons.length > 0) {
                    this.buttons.pop();
                }
            }
        },

        getTitle: function () {
            var tag = '';

            tag += '<span class="icon ' + this.imgIconClass + '"></span>';
            tag += '<h2>' + this.title + '</h2>';

            return(tag);
        },

        getButtons: function () {
            var tag = '';

            // Add buttons
            for(var inx in this.toolbarBtns.buttons) {
                tag += '<div class="' + this.toolbarBtns.buttons[inx].divClass + '">'
                tag += '    <button class="' + this.toolbarBtns.buttons[inx].btnClass + '" type="button" ' + this.toolbarBtns.buttons[inx].btnEvent + '">';
                tag += '    <span>' + this.toolbarBtns.buttons[inx].btnTitle + '</span>';
                tag += '    </button>';
                tag += '</div>';
            }

            return(tag);
        },

        /*  top header block.
            consists:
            - Icon of the dialog content, like if dialog is for project then Icon of the project.
            - Title of the dialog.
            - Buttons for the dialog. ie, cancel, save, edit etc button.
                - Note: pass button in reverse order than it display in the dialog. Because button are floating
                right using float: right property.
        */
        getTag: function () {
            var tag = '';

            tag += '<div class="toolbar">';
            // Add title for toolbar(dialog)
            tag +=      this.getTitle();
            // Add buttons for toolbar(dialog)
            tag +=      this.getButtons();
            tag += '</div>';

            return(tag);
        }
    },

    formTable : {
        tr: [],

        add: function(label, inputTag) {
            this.tr.push({
                label: label,
                input: inputTag
            })
        },

        clear: function() {
            while(this.tr.length > 0) {
                this.tr.pop();
            }
        },

        getLabelTD: function (title) {
            var tag = '';

            tag += '            <td class="label-td">';
            tag += '                <label for="title">' + title + ':</label>';
            tag += '            </td>';

            return(tag);
        },

        getInputTD: function (input) {
            tag = '';

            tag += '            <td class="text-td">';
            tag +=                  input;
            tag += '            </td>';

            return(tag);
        },

        getTR: function (title, input) {
            var tag = '';

            tag += '        <tr>';
            tag +=              this.getLabelTD(title);
            tag +=              this.getInputTD(input);
            tag += '        </tr>';

            return(tag);
        },

        getTag: function () {
            var tag = '';

            tag += '<div class="shield-dialog-form line">';
            tag += '    <table class="shield-dialog-table">';

            // Add rows according to the input (formTable.tr).
            for(var inx in this.tr) {
                tag += this.getTR(this.tr[inx].label, this.tr[inx].input);
            }

            tag += '    </table>';
            tag += '</div>';

            return(tag);
        }
    },

    getTag: function () {
        var tag = '';

        /*  top header block.
            consists:
            - Icon of the dialog content, like if dialog is for project then Icon of the project.
            - Title of the dialog.
            - Buttons for the dialog. ie, cancel, save, edit etc button.
                - Note: pass button in reverse order than it display in the dialog. Because button are floating
                right using float: right property.
        */
        tag += shieldDialog.toolBar.getTag();

        //  Added field for input.
        tag += shieldDialog.formTable.getTag();

        return(tag);
    }
};

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
