require.config({
    paths: {
        'underscore': 'lib/underscore',
        'backbone': 'lib/backbone',
        'jquery': 'lib/jquery',
    },

    shim: {
        'underscore': {
            exports: '_'
        },
        'lib/backbone': {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        }
    },

    deps: ["main"]
});