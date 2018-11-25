;(function ($, window, document) {

    "use strict";

    var pluginName = "ccPosttypeTerms",
        taxonomiesUrl = "/wp-json/om-cc/taxonomies/",
        SEPARATORS = {
            TAXONOMY: ';',
            TAXONOMYNAME: ':',
            TERM: ','
        };
    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.$element = $(element);
        this.settings = $.extend({}, options);
        this.emptyText = this.$element.data('omCcEmpty');
        this.initValues = {};
        this.apiHost = this.$element.data('omCcApiHost');
        this.posttypeSelector = this.$element.data('omCcPosttypeTerms');
        this.placeholder = this.$element.data('omCcPlaceholder');
        if (this.posttypeSelector && this.apiHost) {
            this.init();
        } else if (!this.apiHost) {
            console.error('No API host for ' + pluginName + ' plugin');
        }
    }

    $.extend(Plugin.prototype, {
        init: function () {
            var $selectPosttype = $(this.posttypeSelector);
            $selectPosttype.on('change', this.getTaxonomies.bind(this));
            this.$selectPosttype = $selectPosttype;

            $.each(this.$element.val().split(SEPARATORS.TAXONOMY), function (index, taxonomy) {
                if (taxonomy.length > 0) {
                    taxonomy = taxonomy.split(SEPARATORS.TAXONOMYNAME);
                    var taxonomyName = taxonomy.shift(),
                        terms = taxonomy[0].split(SEPARATORS.TERM);

                    this.initValues[taxonomyName] = {};

                    $.each(terms, function (index, term) {
                        this.initValues[taxonomyName][term] = true;
                    }.bind(this))

                }
            }.bind(this));

            this.getTaxonomies();
        },
        getTaxonomies: function () {
            var posttype = this.$selectPosttype.val();

            $.get(this.apiHost + taxonomiesUrl, {type: posttype}, function (data) {
                this.renderTaxanomies(data);
            }.bind(this));
        },
        renderTaxanomies: function (taxanomies) {
            this.clearTaxanomies();
            if (taxanomies.length > 0) {
                this.multiselects = $.map(taxanomies, function (taxonomy) {
                    var multiselect = {};
                    multiselect.$select = $('<select multiple class="chosen-select" name="' + taxonomy.id + '">').attr("data-placeholder", this.placeholder);

                    multiselect.options = $.map(taxonomy.terms, function (term) {
                        var selected = this.initValues && this.initValues[taxonomy.id] && this.initValues[taxonomy.id][term.slug]
                            ? 'selected'
                            : '';

                        return $('<option value="' + term.slug + '" ' + selected + '>')
                            .text(term.name)
                            .appendTo(multiselect.$select);
                    }.bind(this));

                    multiselect.$label = $('<label>')
                        .text(taxonomy.name)
                        .append(multiselect.$select);

                    this.$element.after(multiselect.$label);
                    multiselect.$select.on('change', this.result.bind(this));
                    if ($.fn.chosen) multiselect.$select.chosen({width: '100%'});
                    return multiselect;
                }.bind(this));
            } else {
                this.$empty = $('<div>').text(this.emptyText).addClass('vc_description').insertAfter(this.$element);
            }
            this.result();
        },
        clearTaxanomies: function () {
            if (this.multiselects && this.multiselects.length > 0) {
                $.each(this.multiselects, function (index, multiselect) {
                    if ($.fn.chosen) multiselect.$select.chosen('destroy');
                    multiselect.$label.remove();
                });
                this.multiselects = [];
            }
            if (this.$empty) this.$empty.remove();
        },
        result: function () {
            var result = '';

            if (this.initValues) this.initValues = null; // don't need init values anymore

            $.each(this.multiselects, function (index, taxonomy) {
                var selectTerms = taxonomy.$select.val();
                if (selectTerms && selectTerms.length > 0) {
                    result += taxonomy.$select.attr('name') + SEPARATORS.TAXONOMYNAME + selectTerms.join(SEPARATORS.TERM) + SEPARATORS.TAXONOMY;
                }
            });
            this.$element.val(result);
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
        $('[data-om-cc-posttype-terms]')[pluginName]();
    })

})(jQuery, window, document);