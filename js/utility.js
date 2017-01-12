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

    updateDashboradTable: function (tagBodyId, fillTableFunc) {
        var formData = {
            'fillTableFunc' : fillTableFunc,
        };

        document.getElementById(tagBodyId).innerHTML = getServerResponseViaAJAX("../ajax/default.php", "updateDashboradTablecallback", formData, "");
    },

    executeFunctionByName: function (functionName, context /*, args */) {
            var args = [].slice.call(arguments).splice(2);
            var namespaces = functionName.split(".");
            var func = namespaces.pop();
            for(var i = 0; i < namespaces.length; i++) {
            context = context[namespaces[i]];
        }

        return context[func].apply(context, args);
    }
};
