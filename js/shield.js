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
        title       : "",
        len         : "1",
        len_unit    : "days",
        gap         : "0",
        gap_unit    : "days",
        description : "",

        setDefult: function() {
            this.title = "";
            this.length = "1";
            this.length_unit = "days";
            this.gap = "0";
            this.gap_unit = "days";
            this.description = "";
        }
    },

    editDropdown: function() {
        var list = [
                    ['Edit', '', 'onclick="shieldSprintSchedule.openEditDialog(this, \'sprint-schedule-tbody\', true)"'],
                    ['Delete', '', 'onclick="shieldSprintSchedule.delete(this, \'sprint-schedule-tbody\')"']
                   ];

        return(list);
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
        shieldDialog.formTable.enable = true;
        shieldDialog.formTable.clear();

        //, owner, begin_date, end_date, sprint_schedule, parent, description, status, target_estimate, test_suit, target_swag, reference

        inputTag += '<input style="width: 400px;" type="text" id="title-input" name="title" value="' + this.info.title + '"/>';
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="title-errmsg"></div>';
        shieldDialog.formTable.add('Title', inputTag);

        inputTag = ' <input style="width: 50px;" type="text" id="length-input" name="len" value="' + this.info.length + '" />';
        inputTag += '<select id="length_unit-select" class="retro-style unit-select">';
        inputTag += '   <option value="days"'+ ((this.info.length_unit == "days") ? 'selected' : '') +'>days</option>';
        inputTag += '   <option value="weeks"'+ ((this.info.length_unit == "weeks") ? 'selected' : '') +'>weeks</option>';
        inputTag += '   <option value="months"'+ ((this.info.length_unit == "months") ? 'selected' : '') +'>months</option>';
        inputTag += '</select>';
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="len-errmsg"></div>';
        shieldDialog.formTable.add('Sprint Length', inputTag);

        inputTag = ' <input style="width: 50px;" type="text" id="gap-input" name="gap" value="' + this.info.gap + '" />';
        inputTag += '<select id="gap_unit-select" class="retro-style unit-select">';
        inputTag += '   <option value="days"'+ ((this.info.gap_unit == "days") ? 'selected' : '') +'>days</option>';
        inputTag += '   <option value="weeks"'+ ((this.info.gap_unit == "weeks") ? 'selected' : '') +'>weeks</option>';
        inputTag += '   <option value="months"'+ ((this.info.gap_unit == "months") ? 'selected' : '') +'>months</option>';
        inputTag += '</select>';
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="gap-errmsg"></div>';
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

    onclickSaveSuccessFunc: function (data) {
        // hide the dialog
        shield.show(false);

        // update sprint schedule list.
        utility.updateDashboradTable('sprint-schedule-tbody', 'getTableBodyElement', 'SprintScheduleHTML', '');
    },

    onclickSaveErrorFunc: function (data) {
        // reset error msg container.
        utility.clearTag('title-errmsg');
        utility.clearTag('len-errmsg');
        utility.clearTag('gap-errmsg');

        // wrtie title error
        if((data['errors']['title'] != null) && (data['errors']['title'] != ''))
        {
            var titleErrMsg = document.getElementById('title-errmsg');
            titleErrMsg.innerHTML = '<span>'+ data['errors']['title'] +'</span>' + utility.EOF_LINE;
            titleErrMsg.style.display = 'block';
        }

        // wrtie len error
        if((data['errors']['len'] != null) && (data['errors']['len'] != ''))
        {
            var lenErrMsg = document.getElementById('len-errmsg');
            lenErrMsg.innerHTML = '<span>'+ data['errors']['len'] +'</span>' + utility.EOF_LINE;
            lenErrMsg.style.display = 'block';
        }

        // wrtie gap error
        if((data['errors']['gap'] != null) && (data['errors']['gap'] != ''))
        {
            var gapErrMsg = document.getElementById('gap-errmsg');
            gapErrMsg.innerHTML = '<span>'+ data['errors']['gap'] +'</span>' + utility.EOF_LINE;
            gapErrMsg.style.display = 'block';
        }
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

    onclickSaveNew: function (tbodyId) {
        var formData = this.getFormData();

        var data = {
            url             : "../ajax/default.php",
            callbackFunc    : "addSprintScheduleCallback",
            formData        : formData,
            successFunc     : function () {
                // reset error msg container.
                utility.clearTag('title-errmsg');
                utility.clearTag('len-errmsg');
                utility.clearTag('gap-errmsg');

                $("#title-input").val(shieldSprintSchedule.info.title);
                $("#length-input").val(shieldSprintSchedule.info.length);
                $("#length_unit-select").val(shieldSprintSchedule.info.length_unit);
                $("#gap-input").val(shieldSprintSchedule.info.gap);
                $("#gap_unit-select").val(shieldSprintSchedule.info.gap_unit);
                $("#description-textarea").val(shieldSprintSchedule.info.description);
            },
            errorFunc       : shieldSprintSchedule.onclickSaveErrorFunc,
            failFunc        : null
        };

        // Update DB according to the input and also update sprint schedule list in the display.
        utility.ajax.serverRespond(data);
    },

    onclickCancel: function (tbodyId) {
        this.onclickSaveSuccessFunc();
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

    openEditDialog: function (selObj, tbodyId, isCallingFromDropMenu) {
        var key = $(selObj).data('parent-id');

        // hide dropdown menu.
        if(isCallingFromDropMenu) {
            $('#' + key + '-sprint-schedule-edit-btn-dropdown').css('display', 'none');
        }

        if((key != null) && (key != "")) {
            // update this.info and open dialog with updated infomsg
            this.info.title = document.getElementById(key + "-title").innerHTML;

            var lenStr = document.getElementById(key + "-length").innerHTML;
            var res = lenStr.split(" ");
            this.info.length = res[0]; //"1";
            this.info.length_unit = res[1];

            lenStr = document.getElementById(key + "-gap").innerHTML;
            res = lenStr.split(" ");
            this.info.gap = res[0]; //"2";
            this.info.gap_unit = res[1];
            this.info.description = document.getElementById(key + "-description").innerHTML; //"Base Sprint Schedule";

            // reset and add button for dialog
            shieldDialog.toolBar.toolbarBtns.clear();
            shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style red add-spr', 'Cancel', 'onclick="shieldSprintSchedule.onclickCancel(\''+ tbodyId +'\')"');
            shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style green-bg add-spr', 'Save', 'onclick="shieldSprintSchedule.onclickSave(\''+ tbodyId +'\')"');

            // fill all infomation of dialog title(toolBar).
            this.fillTitle();

            // fill all infomation of dialog form.
            this.fillTable();

            shield.openDialog(this, false, 'sprint-schedule-add-form-container', this.createDialog);
        }
    },

    delete: function(selObj, tbodyId) {
        var key = $(selObj).data('parent-id');
        var title = document.getElementById(key + "-title").innerHTML;

        // hide dropdown menu.
        $('#' + key + '-sprint-schedule-edit-btn-dropdown').css('display', 'none');

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
                    utility.updateDashboradTable('sprint-schedule-tbody', 'getTableBodyElement', 'SprintScheduleHTML', '');
                },
                errorFunc       : null,
                failFunc        : null
            };

            // Update DB according to the input and also update sprint schedule list in the display.
            utility.ajax.serverRespond(data);
        }
    }
};

var shieldProject = {
    info : {
        title               : "",
        parent              : "",
        sprint_schedule     : "",
        description         : "",
        begin_date          : "",
        end_date            : "",
        owner               : "",
        status              : "",
        target_estimate     : "",
        test_suit           : "",
        target_swag         : "",
        reference           : "",

        setDefult: function() {
            this.title              = "";
            this.parent             = "";
            this.sprint_schedule    = "Base Sprint Schedule";
            this.description        = "";
            this.begin_date         = utility.getCurrentDate('-');
            this.end_date           = "";
            this.owner              = utility.getSessionValue('project-managment-username');
            this.status             = "Open";
            this.target_estimate    = "";
            this.test_suit          = "";
            this.target_swag        = "";
            this.reference          = "";
        }
    },

    projectDropdown: function() {
        var list = [
                    ['Edit', '', 'onclick="shieldProject.openEditDialog(this, \'\', \'project-tbody\', true)"'],
                    ['Delete', '', 'onclick="shieldProject.delete(this)"']
                   ];

        return(list);
    },

    clearErrMsgs: function() {
        // reset error msg container.
        utility.clearTag('title-errmsg');
        utility.clearTag('sprint_schedule-errmsg');
        utility.clearTag('begin_date-errmsg');
        utility.clearTag('end_date-errmsg');
        utility.clearTag('owner-errmsg');
    },

    fillInfo: function (key, isAdding = true) {
        if((key != null) && (key != "")) {
            if(isAdding == true) {
                this.info.parent             = 'System(All Projects)';
                this.info.begin_date         = utility.getCurrentDate('-');
                this.info.owner              = utility.getSessionValue('project-managment-username');
            } else {
                this.info.title              = document.getElementById(key + "-title").innerHTML;
                this.info.parent             = document.getElementById(key + "-parent").innerHTML;
                this.info.sprint_schedule    = document.getElementById(key + "-sprint_schedule").innerHTML;
                this.info.description        = document.getElementById(key + "-description").innerHTML;
                this.info.begin_date         = document.getElementById(key + "-begin_date").innerHTML;
                this.info.end_date           = document.getElementById(key + "-end_date").innerHTML;
                this.info.owner              = document.getElementById(key + "-owner").innerHTML;
                this.info.status             = document.getElementById(key + "-status").innerHTML;
                this.info.target_estimate    = document.getElementById(key + "-target_estimate").innerHTML;
                this.info.test_suit          = document.getElementById(key + "-test_suit").innerHTML;
                this.info.target_swag        = document.getElementById(key + "-target_swag").innerHTML;
                this.info.reference          = document.getElementById(key + "-reference").innerHTML;
            }
        }
    },

    fillTitle: function () {
        // fill all the toolbar related infomation before move on.
        shieldDialog.toolBar.title = "Projects";
        shieldDialog.toolBar.imgIconClass = "project-icon-img";
    },

    fillTable: function () {
        var inputTag = '';

        // fill all infomation of dialog form.
        // reset before use.
        shieldDialog.formTable.enable = true;
        shieldDialog.formTable.clear();

        // Title
        inputTag += '<input style="width: 400px;" type="text" id="title-input" name="title" value="' + this.info.title + '"' + ((this.info.title != '') ? 'disabled' : '') + '/>';
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="title-errmsg"></div>';
        shieldDialog.formTable.add('Title', inputTag);

        // Sprint Schedule
        inputTag = utility.getSprintScheduleSelect(this.info.sprint_schedule);
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="sprint_schedule-errmsg"></div>';
        shieldDialog.formTable.add('Sprint Schedule', inputTag);

        // Description
        inputTag = '<textarea id="description-textarea" name="description" rows="10" cols="100" spellcheck="false">' + this.info.description + '</textarea>';
        shieldDialog.formTable.add('Description', inputTag);

        // Begin Date
        inputTag = '<input type="date" id="begin_date-input" name="begin_date" value="' + this.info.begin_date + '"/>';
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="begin_date-errmsg"></div>';
        shieldDialog.formTable.add('Begin Date', inputTag);

        // End Date
        inputTag = '<input type="date" id="end_date-input" name="end_date" value="' + this.info.end_date + '"/>';
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="end_date-errmsg"></div>';
        shieldDialog.formTable.add('End Date', inputTag);

        // Status
        inputTag = ' <select id="status-select" style="height: 25px;">';
        inputTag += '   <option value="Open" ' + ((this.info.status === 'Open') ? 'selected' : '') + '>Open</option>';
        inputTag += '   <option value="In-progress" ' + ((this.info.status === 'In-progress') ? 'selected' : '') + '>In-progress</option>';
        inputTag += '   <option value="Closed" ' + ((this.info.status === 'Closed') ? 'selected' : '') + '>Closed</option>';
        inputTag += '</select>';
        shieldDialog.formTable.add('Status', inputTag);

        // Owner
        inputTag = '<input type="text" id="owner-input" name="owner" value="' + this.info.owner + '" ' + ((this.info.title != '') ? 'disabled' : '') + '/>';
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="owner-errmsg"></div>';
        inputTag += '<div id="owner-list-container"></div>';
        shieldDialog.formTable.add('Owner', inputTag);

        // Target Estimate Pts.
        inputTag = '<input type="text" id="target_estimate-input" name="target_estimate" value="' + this.info.target_estimate + '"/>';
        /*inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="target_estimate-errmsg"></div>';*/
        shieldDialog.formTable.add('Target Estimate Pts.', inputTag);

        // Target Swag
        inputTag = '<input type="text" id="target_swag-input" name="target_estimate" value="' + this.info.target_swag + '"/>';
        shieldDialog.formTable.add('Target Swag', inputTag);
    },

    getFormData: function () {
        return({
            'title'               : $('#title-input').val(),
            'parent'              : this.info.parent,
            'sprint_schedule'     : $('#sprint_schedule-select').val(),
            'description'         : $('#description-textarea').val(),
            'begin_date'          : $('#begin_date-input').val(),
            'end_date'            : $('#end_date-input').val(),
            'owner'               : $('#owner-input').val(),
            'status'              : $('#status-select').val(),
            'target_estimate'     : $('#target_estimate-input').val(),
            'test_suit'           : '',
            'target_swag'         : $('#target_swag-input').val(),
            'reference'           : '',
            'key_title'           : this.info.title
        });
    },

    onclickSaveSuccessFunc: function (data) {
        // hide the dialog
        shield.show(false);

        // update sprint schedule list.
        utility.updateDashboradTable('project-tbody', 'getTableBodyElement', 'ProjectsHTML', '');
    },

    onclickSaveErrorFunc: function (data) {
        // reset error msg container.
        this.clearErrMsgs();

        // wrtie title error
        if((data['errors']['title'] != null) && (data['errors']['title'] != ''))
        {
            var titleErrMsg = document.getElementById('title-errmsg');
            titleErrMsg.innerHTML = '<span>'+ data['errors']['title'] +'</span>' + utility.EOF_LINE;
            titleErrMsg.style.display = 'block';
        }

        // wrtie sprint_schedule error
        if((data['errors']['sprint_schedule'] != null) && (data['errors']['sprint_schedule'] != ''))
        {
            var lenErrMsg = document.getElementById('sprint_schedule-errmsg');
            lenErrMsg.innerHTML = '<span>'+ data['errors']['sprint_schedule'] +'</span>' + utility.EOF_LINE;
            lenErrMsg.style.display = 'block';
        }

        // wrtie begin_date error
        if((data['errors']['begin_date'] != null) && (data['errors']['begin_date'] != ''))
        {
            var gapErrMsg = document.getElementById('begin_date-errmsg');
            gapErrMsg.innerHTML = '<span>'+ data['errors']['begin_date'] +'</span>' + utility.EOF_LINE;
            gapErrMsg.style.display = 'block';
        }

        // wrtie end_date error
        if((data['errors']['end_date'] != null) && (data['errors']['end_date'] != ''))
        {
            var gapErrMsg = document.getElementById('end_date-errmsg');
            gapErrMsg.innerHTML = '<span>'+ data['errors']['end_date'] +'</span>' + utility.EOF_LINE;
            gapErrMsg.style.display = 'block';
        }

        // wrtie owner error
        if((data['errors']['owner'] != null) && (data['errors']['owner'] != ''))
        {
            var gapErrMsg = document.getElementById('owner-errmsg');
            gapErrMsg.innerHTML = '<span>'+ data['errors']['owner'] +'</span>' + utility.EOF_LINE;
            gapErrMsg.style.display = 'block';
        }
    },

    onclickSave: function (tbodyId) {
        var formData = this.getFormData();

        var data = {
            url             : "../ajax/default.php",
            callbackFunc    : "",
            formData        : formData,
            successFunc     : shieldProject.onclickSaveSuccessFunc.bind(this),
            errorFunc       : shieldProject.onclickSaveErrorFunc.bind(this),
            failFunc        : null
        };


        if(this.info.title == "") {
            // Add new entry.
            data.callbackFunc = "addProjectCallback";
        } else {
            // Edit existing sprint schedule.
            data.callbackFunc = "updateProjectCallback";
        }

        // Update DB according to the input and also update sprint schedule list in the display.
        utility.ajax.serverRespond(data);
    },

    onclickSaveNew: function (tbodyId) {
        var formData = this.getFormData();

        var data = {
            url             : "../ajax/default.php",
            callbackFunc    : "addProjectCallback",
            formData        : formData,
            successFunc     : shieldProject.successSaveNewFunc.bind(this),
            errorFunc       : shieldProject.onclickSaveErrorFunc.bind(this),
            failFunc        : null
        };

        // Update DB according to the input and also update sprint schedule list in the display.
        utility.ajax.serverRespond(data);
    },

    successSaveNewFunc: function () {
        // reset error msg container.
        this.clearErrMsgs();

        // reset field values
        $("#title-input").val(this.info.title);
        $("#sprint_schedule-select").val(this.info.sprint_schedule);
        $("#description-textarea").val(this.info.description);
        $("#begin_date-input").val(this.info.begin_date);
        $("#end_date-input").val(this.info.end_date);
        $("#status-select").val(this.info.status);
        $("#owner-input").val(this.info.owner);
        $("#target_estimate-input").val(this.info.target_estimate);
        $("#target_swag-input").val(this.info.target_swag);
    },

    onclickCancel: function (tbodyId) {
        this.onclickSaveSuccessFunc();
    },

    openDialog: function (Id, tbodyId, isCallingFromDropMenu, isAdding) {
        this.info.setDefult();

        var key = '';

        if(Id == '')
            key = 'System(All Projects)';
        else
            key = Id;

        if((key != null) && (key != "")) {
            this.fillInfo(key, isAdding);

            // fill all infomation of dialog title(toolBar).
            this.fillTitle();
            // fill all infomation of dialog form.
            this.fillTable();

            // reset and add button for dialog
            shieldDialog.toolBar.toolbarBtns.clear();

            shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style red add-spr', 'Cancel', 'onclick="shieldProject.onclickCancel(\''+ tbodyId +'\')"');

            if(isAdding)
                shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style green-bg add-spr', 'Save & New', 'onclick="shieldProject.onclickSaveNew(\''+ tbodyId +'\')"');

            shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style green-bg add-spr', 'Save', 'onclick="shieldProject.onclickSave(\''+ tbodyId +'\')"');

            shield.openDialog(this, false, 'project-add-form-container', shieldDialog.getTag);
        }
    },

    openAddDialog: function(Id, tbodyId, isCallingFromDropMenu) {
        this.openDialog(Id, tbodyId, isCallingFromDropMenu, true);
    },

    openEditDialog: function(selObj, tbodyId, isCallingFromDropMenu) {
        var Id = $(selObj).data('parent-id');
        //if(Id == '')
            //Id = document.getElementById('project-table-dropdown').children[0].innerHTML;

        this.openDialog(Id, tbodyId, isCallingFromDropMenu, false);

        // hide project-table-dropdown' menu if it's open.
        if(isCallingFromDropMenu)
            document.getElementById(Id + '-project-edit-btn-dropdown').style.display = "none";
    },

    delete: function(selObj) {
        var key     = $(selObj).data('parent-id');
        var title   = document.getElementById(key + "-title").innerHTML;
        var parent  = document.getElementById(key + "-parent").innerHTML;

        document.getElementById(key + '-project-edit-btn-dropdown').style.display = "none";

        var r = confirm("Do you want to delete '" + title + "' ?");
        if (r == true) {
            var formData = {
                'project'     : title,
                'parent'    : parent
                //    'clause'    : "title = '" + title + "' and parent = '" + parent + "'"
            };

            var data = {
                url             : "../ajax/default.php",
                callbackFunc    : "deleteProjectCallback",
                formData        : formData,
                successFunc     : function () {
                    utility.updateDashboradTable('project-tbody', 'getTableBodyElement', 'ProjectsHTML', '');
                },
                errorFunc       : null,
                failFunc        : null
            };

            // Update DB according to the input and also update sprint schedule list in the display.
            utility.ajax.serverRespond(data);
        }
    }
};

var shieldMembers = {
    info : {
        member_id       : "",
        privilage       : "",
        privilageList   : Array(),
        projects        : "",

        setDefult: function() {
            this.member_id      = "";
            this.privilage      = "Team Member";
            this.projects       = "";

            this.privilageList.length = 0;
            var enums = utility.getEnumOptions('scrum_member', 'privilage');
            for(var i in enums)
                this.privilageList.push([enums[i], enums[i]]);
        }
    },

    getUsers: function(inputObj) {
        if(inputObj != null) {
            var memberListContainer = document.getElementById('member-list-container');
            if(memberListContainer != null) {
                document.getElementById('member-form-container').onclick= function() { shieldMembers.showHideMemberList(false); }

                // reset div values.
                utility.clearTag('member-list-container');

                // show the div in proper place.
                memberListContainer.style.position = "absolute";
                memberListContainer.style.display = "none";
                memberListContainer.style.backgroundColor = "white";
                memberListContainer.style.width = "150px";

                // get input value
                var userName = inputObj.value;
                if((userName != null) && (userName != '')) {
                    // get users from server.
                    var users = utility.getUsers(userName);
                    if((users != null) && (users.length > 0)) {
                        var userListTag = '';

                        // update user display div
                        userListTag += '<ul>' + utility.EOF_LINE;
                        for(var i in users)
                            userListTag += '<li onclick="shieldMembers.setUserFromOptions(this)">' + users[i] + '</li>' + utility.EOF_LINE;
                        userListTag += '</ul>' + utility.EOF_LINE;

                        memberListContainer.innerHTML = userListTag;
                        // show the div in proper place.
                        memberListContainer.style.display = "block";
                    }
                }
            }
        }
    },

    showHideMemberList: function(show) {
        /// show/hide member list container
        if(show)
            document.getElementById('member-list-container').style.display = "block";
        else
            document.getElementById('member-list-container').style.display = "none";
    },

    setUserFromOptions: function(selectOptionsObj) {
        if(selectOptionsObj != null) {

            var userName = selectOptionsObj.innerHTML;
            if((userName != null) && (userName != '')) {
                document.getElementById('member_id-input').value = userName;
            }

            /// hide member list container.
            this.showHideMemberList(false);
        }
    },

    fillTitle: function () {
        // fill all the toolbar related infomation before move on.
        shieldDialog.toolBar.title = "Members";
        shieldDialog.toolBar.imgIconClass = "sprint-schedule-icon-img";
    },

    fillTable: function () {
        var inputTag = '';

        // fill all infomation of dialog form.
        // reset before use.
        shieldDialog.formTable.enable = true;
        shieldDialog.formTable.clear();

        /// Member field
        inputTag += '<input style="width: 400px;" type="text" id="member_id-input" name="member_id" value="' + this.info.member_id + '" onkeyup="shieldMembers.getUsers(this)"/>';
        inputTag += '<span class="red-asterisk">*</span>';
        inputTag += '<div class="retro-style-errmsg" id="member_id-errmsg"></div>';
        inputTag += '<div id="member-list-container"></div>';
        shieldDialog.formTable.add('Member', inputTag);

        /// Assign member to Project field
        var selectProjects = Array();
        selectProjects.push(['', '']);

        var projects = utility.getScrumProject();
        for(var i in projects){
            if(projects[i] != 'System(All Projects)')
                selectProjects.push([projects[i], projects[i]]);
        }

        inputTag = utility.getRetroSelect('project-select', selectProjects, this.info.privilage, '', 'session-select', 'session-container');
        shieldDialog.formTable.add('Assign member to Project', inputTag);

        /// Admin Privilage field
        inputTag = utility.getRetroSelect('privilage-select', this.info.privilageList, this.info.privilage, '', 'session-select', 'session-container');
        shieldDialog.formTable.add('Admin Privilage', inputTag);
    },

    getFormData: function () {
        return({
                'member_id'     : $('#member_id-input').val(),
                'project'       : $('#project-select').val(),
                'privilage'     : $('#privilage-select').val()
        });
    },

    onclickSaveSuccessFunc: function (data) {
        // hide the dialog
        shield.show(false);

        // update sprint schedule list.
        utility.updateDashboradTable('member-tbody', 'fillMembersTable', '', '');
    },

    onclickSaveErrorFunc: function (data) {
        // reset error msg container.
        utility.clearTag('member_id-errmsg');

        var memberErrMsg = document.getElementById('member_id-errmsg');
        memberErrMsg.innerHTML = '<span>'+ data['errors'][0] +'</span>' + utility.EOF_LINE;
        memberErrMsg.style.display = 'block';
    },

    onclickSave: function (tbodyId) {
        var memberErrMsg = document.getElementById('member_id-errmsg');
        if(memberErrMsg != null) {
            // reset 'member_id-errmsg' div.
            utility.clearTag('member_id-errmsg');
            memberErrMsg.style.display = 'none';

            // check member field is blank or not?
            var memberId = document.getElementById('member_id-input').value;
            if((memberId != null) && (memberId != '')) {

                var formData = this.getFormData();
                var data = {
                    url             : "../ajax/default.php",
                    callbackFunc    : 'addMemberCallback',
                    formData        : formData,
                    successFunc     : shieldMembers.onclickSaveSuccessFunc,
                    errorFunc       : shieldMembers.onclickSaveErrorFunc,
                    failFunc        : null
                };

                // Update DB according to the input and also update sprint schedule list in the display.
                utility.ajax.serverRespond(data);
            }
            else {
                memberErrMsg.innerHTML = '<span>Please enter member.</span>' + utility.EOF_LINE;
                memberErrMsg.style.display = 'block';
            }
        }
    },

    onclickCancel: function() {
        shield.show(false);
    },

    openAddDialog: function (tbodyId) {
        this.info.setDefult();

        // fill all infomation of dialog title(toolBar).
        this.fillTitle();

        // reset and add button for dialog
        shieldDialog.toolBar.toolbarBtns.clear();
        shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style red add-spr', 'Cancel', 'onclick="shieldMembers.onclickCancel()"');
        shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style green-bg add-spr', 'Save', 'onclick="shieldMembers.onclickSave()"');

        // fill all infomation of dialog form.
        this.fillTable();

        shield.openDialog(this, false, 'member-form-container', shieldDialog.getTag);
    },

    openEditDialog: function (Id, tbodyId, isCallingFromDropMenu) {
            shield.openDialog(this, false, 'member-form-container', this.createDialog);
        //}
    }
}

var shieldProductBacklog = {
    member : {
        selObject   : null
    },

    moveToProjectDropdown: function() {
        var list = [
                    ['Move To Project', '', 'onclick="shieldProductBacklog.moveToProjectDialog(this, true)"'],
                    ['Move To Sprint', 'dashed-bottom-border', 'onclick="shieldProductBacklog.moveToSprint(this)"'],
                    ['Quick Close', '', 'onclick="shieldProductBacklog.quickClose(this)"'],
                    ['Close', 'dashed-bottom-border', 'onclick="shieldProductBacklog.close(this)"'],
                    ['Reopen', '', 'onclick="shieldProductBacklog.reOpen(this)"'],
                    ['Delete', '', 'onclick="shieldProductBacklog.delete(this)"']
                   ];

        return(list);
    },

    storyInlineDropdown: function() {
        var list = [
                    ['Add Story Inline', '', 'onclick="shieldProductBacklog.addStoryInline(this, true)"'],
                    ['Add Story', '', 'onclick="shieldProductBacklog.addStory(this)"'],
                    ['Add Defect Inline', '', 'onclick="shieldProductBacklog.addDefectInline(this)"'],
                    ['Add Defect', '', 'onclick="shieldProductBacklog.addDefect(this)"']
                   ];

        return(list);
    },

    editDropdown: function() {
        var list = [
                    ['Edit', 'dashed-bottom-border', 'onclick="shieldProductBacklog.editDialog(this, false)"'],
                    ['Plan Story', '', 'onclick="shieldProductBacklog.planStory(this)"'],
                    ['Add Task', 'dashed-bottom-border', 'onclick="shieldProductBacklog.addTask(this)"'],
                    ['Quick Close', '', 'onclick="shieldProductBacklog.quickClose(this)"'],
                    ['Close', 'dashed-bottom-border', 'onclick="shieldProductBacklog.close(this)"'],
                    ['Delete', '', 'onclick="shieldProductBacklog.delete(this)"']
                   ];

        return(list);
    },

    fillTitle: function () {
        // fill all the toolbar related infomation before move on.
        shieldDialog.toolBar.title = "Projects";
        shieldDialog.toolBar.imgIconClass = "project-icon-img";
    },

    fillDiv: function() {
        // fill all infomation of dialog form.
        // reset before use.
        shieldDialog.formDiv.enable = true;
        shieldDialog.formDiv.clear();

        var inputTag = '';

        inputTag += '<input type="hidden" id="selected-project-input" name="selected-project-input" value="' + this.member.selObject.innerHTML + '"/>';
        shieldDialog.formDiv.add(inputTag);

        // Title
        inputTag = '<div style="margin-bottom: 20px;">';
        inputTag += '   <span>Select Project from following list:</span>';
        inputTag += '</div>';
        shieldDialog.formDiv.add(inputTag);

        // get project list and put them into list to display.
        var projects = utility.getScrumProject();
        for(var i in projects){
                if(projects[i] != 'System(All Projects)') {
                inputTag = '<div class="project-backlog-project">';
                inputTag += '   <a class="project-backlog-project-anchor '+ ((this.member.selObject.innerHTML === projects[i]) ? 'selected' : '') +'" style="text-transform: uppercase;" onclick="shieldProductBacklog.onSelect(this)">'+ projects[i] +'</a>';
                inputTag += '</div>';
                shieldDialog.formDiv.add(inputTag);
            }
        }
    },

    onclickApply: function() {
        var selProject = document.getElementById('selected-project-input').value;
        // change the project and backlog table elements.
        document.getElementById('project-title').innerHTML = selProject;

        // Update backlog table according to project..
        utility.updateDashboradTable('project-backlog-tbody', 'getTableBodyElement', 'ProductBacklogHTML', 'WHERE project="'+ selProject +'"');

        // hide the dialog
        shield.show(false);
    },

    onclickCancel: function() {
        // hide the dialog
        shield.show(false);
    },

    onSelect: function(obj) {
        // reset all project.
        var anchorTag = document.getElementsByClassName('project-backlog-project-anchor');
        for (i = 0; i < anchorTag.length; i++) {
            anchorTag[i].className = "project-backlog-project-anchor";
        }
        // set class for selected item
        obj.className = "project-backlog-project-anchor selected";

        // fill hidden text with selected project title.
        document.getElementById('selected-project-input').value = obj.innerHTML;
    },

    showProject: function(obj) {
        //var selObj = document.getElementsByClassName('titlebar')[0];
        this.member.selObject = obj;

        // fill all infomation of dialog title(toolBar).
        this.fillTitle();

        // reset and add button for dialog
        shieldDialog.toolBar.toolbarBtns.clear();
        shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style red add-spr', 'Cancel', 'onclick="shieldProductBacklog.onclickCancel()"');
        shieldDialog.toolBar.toolbarBtns.add('submit-btns', 'retro-style green-bg add-spr', 'Apply', 'onclick="shieldProductBacklog.onclickApply()"');

        // fill all infomation of dialog form.
        this.fillDiv();

        shield.openDialog(this.member.selObject, true, 'project-form-container', shieldDialog.getTag);
    }
};

/* object to create dialog for shield popup. */
var shieldDialog = {
    toolBar : {
        title           : "",
        imgIconClass    : "",
        toolbarBtns     : {
            buttons : [],

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
        enable  : false,
        tr      : [],

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

    formDiv : {
        enable      : false,
        elemList    :[],

        add: function(tag) {
            this.elemList.push(tag);
        },

        clear: function() {
            while(this.elemList.length > 0) {
                this.elemList.pop();
            }
        },

        getTag: function() {
            var tag = '';

            tag += '<div class="shield-dialog-div">';

            // Add rows according to the input (formTable.tr).
            for(var inx in this.elemList) {
                tag += this.elemList[inx];
            }

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

        if(shieldDialog.formTable.enable) {
            //  Added field for input.
            tag += shieldDialog.formTable.getTag();
        }
        else if(shieldDialog.formDiv.enable) {
            tag += shieldDialog.formDiv.getTag();
        }

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

        // create the dialog container and components before displaying them.
        var tag = '<div id="shield-wrapper" class="shield-wrapper">';
        tag += '    <div class="shield" onclick="shield.show(false)"></div>';
        tag += '    <div id="shield-form-window" class="shield-window shield-window-position">';
        tag +=          dialogCreateFunc();     // user specific function which creates inner component of the dialog.
        tag += '    </div>';
        tag += '</div>';

        $("#" + destId).html(tag);

        // calculate shield dialog's location and locate it.
        var $shieldFormWindow = $('#shield-form-window');
        if((selObj != null) && (takeSelPos != null) && (destId != "") && (dialogCreateFunc != "")) {
            // collect the position of the dialog to be appired.
            if(takeSelPos == true) {
                var offsets = $(selObj).position();
                o_top = offsets.top + selObj.offsetHeight + 2; //getBoundingClientRect().height;
                o_left = offsets.left;
            } else {
                // display shield dialog at the center of the window.
                o_top = (utility.getWindowHeight() - $shieldFormWindow.height())/2;
                o_left = (utility.getWindowWidth() - $shieldFormWindow.width())/2;
            }
            $shieldFormWindow.offset({ top: o_top, left: o_left});

            shield.show(true);
        }
    }
};
