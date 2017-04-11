/*--
    File    : js/shield.js
    Author  : Abhishek Nath
    Date    : 10-Jan-2016
    Desc    : file to handle functionality to shield container and related stuff.
--*/

/*--
    10-Jan-17   V1-01-00   abhishek   $$1   Created.
--*/

var utility = {
    /**************************************************
    *               Objects Section
    *
    *   All objects should be written in below section.
    ***************************************************/

    EOF_LINE : '\n',

    ajax : {
        input : {
            url             : "",
            callbackFunc    : "",
            formData        : null,
            successFunc     : null,
            errorFunc       : null,
            failFunc        : null
        },

        setData: function(url, callbackFunc, formData, successFunc, errorFunc, failFunc) {

            if((url != null) && (url != ""))
                this.input.url = url;

            if((callbackFunc != null) && (callbackFunc != ""))
                this.input.callbackFunc = callbackFunc;

            if(formData != null)
                this.input.formData = formData;

            this.input.successFunc = successFunc;
            this.input.errorFunc = errorFunc;
            this.input.failFunc = failFunc;
        },

        serverRespond: function (data = null) {

            if(data != null)
                this.setData(data.url, data.callbackFunc, data.formData, data.successFunc, data.errorFunc, data.failFunc);

            if((this.input.url != "") && (this.input.callbackFunc != "") && (this.input.formData != null)) {

                // call AJAX function
                $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : this.input.url + '?f=' + this.input.callbackFunc, // the url where we want to POST
                    data        : this.input.formData, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode      : true
                })

                // handle error
                .done(function(data) {
                    // log data to the console so we can see
                    console.log(data);

                    if ( data.success) {
                        // do stuff for success of AJAX operation.
                        if(utility.ajax.input.successFunc != null)
                            utility.ajax.input.successFunc(data);
                    } else {
                        // here we will handle errors and validation messages
                        if(utility.ajax.input.errorFunc != null)
                            utility.ajax.input.errorFunc(data);
                    }
                })

                // using the fail promise callback
                .fail(function(data) {
                    // show any errors
                    // best to remove for production
                    console.log(data);

                    if(utility.ajax.input.failFunc != null)
                        utility.ajax.input.failFunc(data);
                })
            }
        }
    },



    /**************************************************
    *               Methods Section
    *
    *   All methods should be written in below section.
    ***************************************************/

    getEnumOptions: function(tableName, colName){
        var enums = null;

        if(
            (tableName != null) && (tableName != "") &&
            (colName != null) && (colName != "")
          ) {
            var formData = {
              'tableName'   : tableName,
              'colName'     : colName
            }
            enums = getServerResponseViaAJAX("../ajax/default.php", "getEnumOptionsCallback", formData, "")
        }

        return(enums);
    },

    /**
    * This function get width of the window independent of browser.
    * @namespace    uitlity
    * @method       getWindowWidth
    * @return       {double} width of window.
    */
    getWindowWidth: function () {
        var actualWidth = window.innerWidth ||
                          document.documentElement.clientWidth ||
                          document.body.clientWidth ||
                          document.body.offsetWidth;

        return actualWidth;
    },

    /**
    * This function get height of the window independent of browser.
    * @namespace    uitlity
    * @method       getWindowHeight
    * @return       {double} height of window.
    */
    getWindowHeight: function () {
        var actualWidth = window.innerHeight ||
                          document.documentElement.clientHeight ||
                          document.body.clientHeight ||
                          document.body.offsetHeight;

        return actualWidth;
    },

    appendInputValidateErrorMsg : function(fieldName, errMsg) {
        if((fieldName != null) && (fieldName != '')) {

            // check any error msg already displayed or not.
            // if displayed then remove it.
            var errMsgId = fieldName +'-errmsg';
            var $errDiv = $('#' + errMsgId);
            if($errDiv.length)
                $errDiv.remove();

            // Create the error Div and append the error msg
            if((errMsg != null) && (errMsg != '')) {
                $errDiv = $('<div class="retro-style-errmsg" style="display: block" id="'+ errMsgId +'">');
                $errDiv.html('<span>' + errMsg + '</span>');

                $('#'+ fieldName).parent().append($errDiv);
            }
        }
    },

    getRetroSelect: function (selectId, selectOptions, selectedItem, selectEvent, selectClass, containerClass) {
        var tag ='';

        if(
            ((selectId != null) && (selectId != '')) &&
            ((selectOptions != null) && (selectOptions.length > 0)) &&
            ((selectedItem != null) && (selectedItem != ''))
          )
        {
            tag += '<div class="retro-style-select-container '+ containerClass +'">' + this.EOF_LINE;
            tag += '   <select id="'+ selectId +'" class="retro-style '+ selectClass +'" '+ selectEvent +'>' + this.EOF_LINE;

            for(var inx in selectOptions)
            {
                tag += '   <option value="'+ selectOptions[inx][0] +'" '+ ((selectOptions[inx][0] == selectedItem) ? 'selected' : '') +'>'+ selectOptions[inx][0] +'</option>' + this.EOF_LINE;
            }

            tag += '   </select>' + this.EOF_LINE;
            tag += '</div>' + this.EOF_LINE;
        }

        return(tag);
    },

    updateDashboradTable: function (tagBodyId, fillTableFunc, fillTableFuncClass, clause) {
        var formData = {
            'fillTableFunc'         : fillTableFunc,
            'fillTableFuncClass'    : fillTableFuncClass,
            'clause'                : clause
        };

        document.getElementById(tagBodyId).innerHTML = getServerResponseViaAJAX("../ajax/default.php", "updateDashboradTableCallback", formData, "");
    },

    getSprintScheduleSelect: function(selectedSchedule) {
        var formData = {
            'selectedSchedule' : selectedSchedule
        };

        return(getServerResponseViaAJAX("../ajax/default.php", "getSprintScheduleSelectCallback", formData, ""));
    },

    executeFunctionByName: function (functionName, context /*, args */) {
            var args = [].slice.call(arguments).splice(2);
            var namespaces = functionName.split(".");
            var func = namespaces.pop();
            for(var i = 0; i < namespaces.length; i++) {
                context = context[namespaces[i]];
            }

        return context[func].apply(context, args);
    },

    getCurrentDate: function (seperator) {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!

        var yyyy = today.getFullYear();
        if(dd<10){
            dd='0' + dd;
        }

        if(mm<10){
            mm='0' + mm;
        }

        return(yyyy + seperator + mm + seperator + dd);
    },

    getSessionValue: function (sessionId) {
        var formData = {
            'sessionId': sessionId
        }

        return(getServerResponseViaAJAX("../ajax/default.php", "getSessionValueCallback", formData, ""));
    },

    getUsers: function(hints) {
        var formData = {
          'user_hints'  : hints
        }

        return(getServerResponseViaAJAX("../ajax/default.php", "getUsersCallback", formData, ""));
    },

    getScrumProject: function() {
        return(getServerResponseViaAJAX("../ajax/default.php", "getProjectListCallback", null, ""));
    },

    clearTag: function(tagId) {
        var status = false;

        if((tagId != null) && (tagId != '')) {
            var obj = document.getElementById(tagId);
            if(obj != null) {
                while (obj.firstChild) {
                    obj.removeChild(obj.firstChild);
                }

                obj.style.display = 'none';
                status = true;
            }
        }

        return(status);
    }
};
