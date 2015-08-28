define(function (require, exports, module) {
    "use strict";

    exports.baseUrl = 'http://dsk.dd:8083'; // Define your Drupal base ULR without trailing slash.
    exports.appUrl = 'http://dsk.dd:8083'; // Define your app base ULR without trailing slash.
    exports.apiVersion = 1;

    // The root path to run the application through.
    exports.getTemplate = function (url) {
        var data = "<h1> failed to load url : " + url + "</h1>";
        $.ajax({
            async: false,
            dataType: "text",
            url: url,
            success: function (response) {
                data = response;
            }
        });
        return data;
    };
});