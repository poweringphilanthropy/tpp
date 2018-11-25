;(function ($, window, document) {

    "use strict";

    var pluginName = "ccPosttypePostfields",
        metakeysUrl = "/wp-json/om-cc/posts/post-fields/";

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.$element = $(element);
        this.settings = $.extend({}, options);
        this.initValue = '';
        this.apiHost = this.$element.data('omCcApiHost');
        this.posttypeSelector = this.$element.data('omCcPosttypePostfields');
        this.titleSourceMeta = this.$element.data('omCcTitleSourceMeta');
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

            this.initValue = this.$element.val();

            this.getPosts()
        },
        getPosts: function () {
            var posttype = this.$selectPosttype.val();

            $.get(this.apiHost + metakeysUrl, {type: posttype, ismetakeys: this.titleSourceMeta || undefined}, function (data) {
                this.renderMetakeys(data)
            }.bind(this))
        },
        renderMetakeys: function (metakeys) {
            if (!this.$selectMetakeys) {
                this.$selectMetakeys = $('<select>').insertAfter(this.$element)
            } else {
                this.$selectMetakeys.empty()
            }
            $('<option value="">').text('-').appendTo(this.$selectMetakeys);

            $.map(metakeys, function (metakey) {
                var selected = this.initValue == metakey.key
                    ? 'selected'
                    : '';

                $('<option value="' + metakey.key + '" ' + selected + '>').text(metakey.title).appendTo(this.$selectMetakeys)
            }.bind(this));
            this.$selectMetakeys.on('change', this.result.bind(this));
            this.result()
        },
        result: function () {
            if (this.initValue) this.initValue = null; // don't need init values anymore

            this.$element.val(this.$selectMetakeys.val())
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
        $('[data-om-cc-posttype-postfields]')[pluginName]()
    })

})(jQuery, window, document);