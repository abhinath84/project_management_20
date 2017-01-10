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
    }
};
