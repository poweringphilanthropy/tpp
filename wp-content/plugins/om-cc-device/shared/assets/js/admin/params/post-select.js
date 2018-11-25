;(function ($, window, document) {

    "use strict";

    var pluginName = "ccPostSelect",
        postsUrl = "/wp-json/om-cc/posts/";

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.$element = $(element);
        this.settings = $.extend({}, options);
        this.initValues = [];
        this.apiHost = this.$element.data('omCcApiHost');
        this.posttypeSelector = this.$element.data('omCcPostSelect');
        this.placeholder = this.$element.data('omCcPlaceholder');
        if (this.posttypeSelector && this.apiHost) {
            this.init();
        } else if (!this.apiHost) {
            console.error('No API host for ' + pluginName + ' plugin')
        }
    }

    $.extend(Plugin.prototype, {
        init: function () {
            var $selectPosttype = $(this.posttypeSelector);

            $selectPosttype.on('change', this.getPosts.bind(this));
            this.$selectPosttype = $selectPosttype;

            $.map(this.$element.val().split(','), function (postId) {
                this.initValues.push(+postId)
            }.bind(this));

            this.getPosts()
        },
        getPosts: function () {
            var posttype = this.$selectPosttype.val();

            $.get(this.apiHost + postsUrl, {type: posttype}, function (data) {
                this.renderPosts(data)
            }.bind(this))
        },
        renderPosts: function (posts) {
            if (!this.$selectPosts) {
                this.$selectPosts = $('<select multiple>').attr("data-placeholder", this.placeholder).insertAfter(this.$element)
            } else {
                this.$selectPosts.chosen('destroy').empty()
            }
            $.map(posts, function (post) {
                var selected = this.initValues && this.initValues.indexOf(post.id) > -1
                    ? 'selected'
                    : '';

                $('<option value="' + post.id + '" ' + selected + '>').text(post.title).appendTo(this.$selectPosts)
            }.bind(this));
            this.$selectPosts.chosen({width: "100%"}).on('change', this.result.bind(this))
            this.result()
        },
        result: function () {
            var values = this.$selectPosts.val();

            if (this.initValues) this.initValues = null;// don't need init values anymore

            this.$element.val(values ? values.join(',') : '')
        }
    });

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" +
                    pluginName, new Plugin(this, options));
            }
        });
    };

    $(function () {
        $('[data-om-cc-post-select]')[pluginName]()
    })

})(jQuery, window, document);