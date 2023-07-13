// IIFE - Immediately Invoked Function Expression
(function (pluginNamePublic) {
    // The global jQuery object is passed as a parameter
    pluginNamePublic(window.jQuery, window, document);
})(function ($, window, document) {
    ("use strict");

    // The $ is now locally scoped

    // o---------------------------------o
    // |                                 |
    // |    The DOM may not be ready.    |
    // |                                 |
    // o---------------------------------o

    // Listen for the jQuery ready event on the document
    $(function () {
        // o-------------------------o
        // |                         |
        // |    The DOM is ready!    |
        // |                         |
        // o-------------------------o
    });
});
