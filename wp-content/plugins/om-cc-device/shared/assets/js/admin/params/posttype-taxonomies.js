;(function ($, window, document) {

    "use strict";

    var pluginName = "ccPosttypeTaxonomies",
        taxonomiesUrl = "/wp-json/om-cc/taxonomies/";

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.$element = $(element);
        this.settings = $.extend({}, options);
        this.initValue = '';
        this.apiHost = this.$element.data('omCcApiHost');
        this.posttypeSelector = this.$element.data('omCcPosttypeTaxonomies');
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

            $.get(this.apiHost + taxonomiesUrl, {type: posttype}, function (data) {
                this.renderMetakeys(data)
            }.bind(this))
        },
        renderMetakeys: function (taxonomies) {
            if (!this.$selectTaxanomies) {
                this.$selectTaxanomies = $('<select>').insertAfter(this.$element)
            } else {
                this.$selectTaxanomies.empty()
            }
            $('<option value="">').text('-').appendTo(this.$selectTaxanomies);

            $.map(taxonomies, function (taxonomy) {
                var selected = this.initValue == taxonomy.id
                    ? 'selected'
                    : '';

                $('<option value="' + taxonomy.id + '" ' + selected + '>').text(taxonomy.name).appendTo(this.$selectTaxanomies)
            }.bind(this));
            this.$selectTaxanomies.on('change', this.result.bind(this))
            this.result()
        },
        result: function () {
            if (this.initValue) this.initValue = null; // don't need init values anymore

            this.$element.val(this.$selectTaxanomies.val())
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
        $('[data-om-cc-posttype-taxonomies]')[pluginName]()
    })

})(jQuery, window, document);