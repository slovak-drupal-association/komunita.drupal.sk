$(function () {
    var baseUrl = 'http://dsk.dd:8083'; // Define your Drupal base ULR without trailing slash.
    var appUrl = 'http://dsk.dd:8083'; // Define your app base ULR without trailing slash.
    var apiVersion = 1;

    // Models
    var Slide = Backbone.Model.extend({
        idAttribute: 'nid'
    });

    var Blog = Backbone.Model.extend({
        idAttribute: 'nid'
    })

    // Collections
    var SlideCollection = Backbone.Collection.extend({
        model: Slide,
        url: baseUrl + '/api/v' + apiVersion + '/slides'
    });

    var BlogCollection = Backbone.Collection.extend({
        model: Blog,
        url: baseUrl + '/api/v' + apiVersion + '/blogs'
    });

    // Views
    var SlideListView = Backbone.View.extend({
        tagName: 'ul',

        initialize: function () {
            this.model.bind('reset', this.render, this);
        },

        render: function (event) {
            _.each(this.model.models, function (slide) {
                this.$el.append(new SlideItemView({model: slide}).render().el);
            }, this);

            return this;
        }
    });

    var SlideItemView = Backbone.View.extend({
        tagName: 'li',

        render: function (event) {
            this.template_external = _.template(App.getTemplate(appUrl + '/app/templates/slide_w_external_path.html'));
            $(this.$el).html(this.template_external(this.model.toJSON()));
            return this;

        }
    });

    // Router
    var AppRouter = Backbone.Router.extend({

        routes: {
            '': 'home',
        },

        home: function () {
            App.home();
        }

    });


    // Application
    var App = {

        getTemplate: function (url) {
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
        },

        home: function () {
            this.slider = new SlideCollection();
            var self = this;
            this.slider.fetch({
                success: function (response) {
                    self.slideListView = new SlideListView({model: self.slider});
                    $('#main-container').html(self.slideListView.render().el);
                }
            });

        }

    }


    var router = new AppRouter;
    Backbone.history.start();

});
