jQuery(function ($) {
    'use strict';

    var $body = $('body');

    //$body.on('click', '[data-om-cc-image-preview] i.remove, [data-om-cc-image-preview] span.remove-img', function (event) {
    $body.on('click', '[data-om-cc-image-preview] i.remove', function (event) {
        event.preventDefault();

        var $self = $(this);
        var _input = $self.parents('[data-om-cc-image-preview]').find('input[type="hidden"]');
        var _preview = $self.parents('[data-om-cc-image-preview]').find('div.thumbnail');
        //var _url = $self.parents('[data-om-cc-image-preview]').find('a.remove-img');

        _preview.find('img').remove().end().find('i').remove();
        //_url.remove();
        _preview.html('<span class="add-img">Add image</span>');
        _input.val('').trigger('change');

        return false;
    });

    $body.on('click', '[data-om-cc-image-preview] .thumbnail span.add-img, [data-om-cc-image-preview] .thumbnail img', function (event) {
        event.preventDefault();

        var $self = $(this);

        if ($self.is('.thumbnail')) {
            if ($self.parents('[data-om-cc-image-preview]').find('img').length != 0) {
                $self.parents('[data-om-cc-image-preview]').find('img').trigger('click');
                return true;
            }
        }

        var _input      = $self.parents('[data-om-cc-image-preview]').find('input[type="hidden"]');
        var _preview    = $self.parents('[data-om-cc-image-preview]').find('div.thumbnail');
        var _old_add    = $self.parents('[data-om-cc-image-preview]').find('.add-img');
        //var _old_remove = $self.parents('[data-om-cc-image-preview]').find('.remove-img');

        // uploader frame properties
        var frame = wp.media({
            title: "Select Image",
            multiple: false,
            library: {type: 'image'},
            button: {text: "Use image"}
        });

        frame.on('select', function () {
            var selection = frame.state().get('selection');

            selection.each(function (attachment) {
                if (_input.length > 0) {
                    _input.val(attachment.id);
                }

                if (_preview.length > 0) {
                    // remove current preview
                    if (_preview.find('img').length > 0) {
                        _preview.find('img').remove();
                    }
                    if (_preview.find('i.remove').length > 0) {
                        _preview.find('i.remove').remove();
                    }

                    // Get the preview image
                    var image = attachment.attributes.sizes.full;
                    if (typeof attachment.attributes.sizes.thumbnail != 'undefined') {
                        image = attachment.attributes.sizes.thumbnail;
                    }

                    _preview.css({width: image.width});

                    $("<img src='" + image.url + "'/>")
                        .css({width: image.width, height: image.height})
                        .appendTo(_preview);
                    $("<i class='dashicons dashicons-no-alt remove'></i>").prependTo(_preview);

                    // if (_old_remove.is('.remove-img')) {
                    //     _old_remove.remove();
                    //     $('<span class="remove-img">Remove second thumbnail</span>').appendTo(_preview);
                    // }
                }
                _input.trigger('change');
                _old_add.remove();
            });

            frame.off('select');
        });

        frame.open();

        return false;
    });

});