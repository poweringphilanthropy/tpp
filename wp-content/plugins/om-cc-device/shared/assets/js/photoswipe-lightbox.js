!function ($) {
    'use strict';

    var photoswipe,
        photoswipeElement,
        $photoswipe,
        options = {
            captionEl: true,
            shareEl: false,
            fullscreenEl: false,
            zoomEl: false
        },
        dragTimer;

    function create() {
        var div = '<div>',
            span = '<span>',
            index,

            topBtns = [
                { type: 'close', title: 'Close (Esc)', icon: 'ios-close' },
                { type: 'share', title: 'Share', icon: 'android-share-alt' },
                { type: 'fs', title: 'Toggle fullscreen', icon: 'ios-photos-outline' },
                { type: 'zoom', title: 'Zoom in/out', icon: 'ios-plus-empty' }
            ],
            btn,

           // $photoswipeContainer = $(div, { 'id': 'photoswipe' }),
            $photoswipe = $(div, {'id': 'om-cc-photoswipe', 'class': 'pswp', 'tabindex': '-1', 'role': 'dialog', 'aria-hidden': 'true' }),
            $wrap = $(div, { 'class': 'pswp__scroll-wrap' }).appendTo($photoswipe),
            $container = $(div, { 'class': 'pswp__container' }).appendTo($wrap),
            $ui = $(div, { 'class': 'pswp__ui pswp__ui--hidden' }).appendTo($wrap),
            $topBar = $(div, { 'class': 'pswp__top-bar' }).appendTo($ui),
            $shareModal = $(div, { 'class': 'pswp__share-modal pswp__share-modal--hidden pswp__single-tap' }).appendTo($ui),
            $caption = $(div, { 'class': 'pswp__caption' });

        $(div, { 'class': 'pswp__bg' }).prependTo($photoswipe);

        // container

        for (index = 0; index < 3; index++) {
            $('<div class="pswp__item">').appendTo($container);
        }

        // top bar

        $(div, { 'class': 'pswp__counter' }).prependTo($topBar);

        for (index = 0; index < topBtns.length; index++) {
            btn = topBtns[index];

            $('<button class="pswp__button">').addClass('pswp__button--' + btn.type + ' ion-' + btn.icon).attr('title', btn.title).appendTo($topBar);
        }

        $('<div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div>').appendTo($topBar);

        //other ui

        $(div, { 'class': 'pswp__share-tooltip' }).prependTo($shareModal);

        $('<button class="pswp__button">').addClass('pswp__button--arrow--left').attr('title', 'Previous').append($(span).addClass('om-cc-arrow-left')).appendTo($ui);
        $('<button class="pswp__button">').addClass('pswp__button--arrow--right').attr('title', 'Next').append($(span).addClass('om-cc-arrow-right')).appendTo($ui);

        $caption.appendTo($ui);

        $(div, { 'class': 'pswp__caption' }).appendTo($caption);

        $photoswipe.appendTo('body');

        return $photoswipe;
    }

    function init() {
        if (!photoswipeElement) {
            var $meta = $('meta[name="photoswipe"]'),
                share = [
                    { id: 'facebook', label: '<span class="ion-social-facebook"></span>', url: 'https://www.facebook.com/sharer/sharer.php?u={{url}}' },
                    { id: 'twitter', label: '<span class="ion-social-twitter"></span>', url: 'https://twitter.com/intent/tweet?text={{text}}&url={{url}}' },
                    { id: 'pinterest', label: '<span class="ion-social-pinterest"></span>', url: 'http://www.pinterest.com/pin/create/button/?url={{url}}&media={{image_url}}&description={{text}}' },
                    { id: 'tumblr', label: '<span class="ion-social-tumblr"></span>', url: 'http://tumblr.com/widgets/share/tool?canonicalUrl={{url}}' },
                    { id: 'google-plus', label: '<span class="ion-social-googleplus"></span>', url: 'https://plus.google.com/share?url={{url}}' },
                    { id: 'vk', label: '<span class="ion-speakerphone"></span>', url: 'http://vk.com/share.php?url={{url}}&image={{image_url}}' },
                    { id: 'reddit', label: '<span class="ion-social-reddit"></span>', url: 'https://www.reddit.com/submit?url={{url}}' },
                    { id: 'download', label: '<span class="ion-arrow-down-c"></span>', url: '{{raw_image_url}}', download: true }
                ],
                item,
                meta,
                index;

            if ($meta.length && (meta = $meta.prop('content'))) {
                meta = $.parseJSON(meta);

                options.fullscreenEl = meta.fullscreen ? !!meta.fullscreen : false;
                options.zoomEl = meta.zoom ? !!meta.zoom : false;

                if ($.isPlainObject(meta.share)) {
                    options.shareEl = true;
                    options.shareButtons = [];

                    for (index = 0; index < share.length; index++) {
                        item = share[index];

                        if (item.id in meta.share) {
                            item.label += meta.share[item.id];
                            options.shareButtons.push(item);
                        }

                    }
                }
            }

            $photoswipe = create();

            photoswipeElement = $photoswipe.get(0);
        }
    }

    function getTarget($item) {
        var selector = $item.data('selector'),
            $target = $item;

        if (selector) {
            selector = selector.split('|');
            $target = $item[selector[0]].apply($item, Array.prototype.slice.call(selector, 1));
        }

        return $target;
    }

    function itemToSlide (index, item) {
        var $item = $(item),
            size = $item.data('size').split('x'),
            width = parseInt(size[0]),
            height = parseInt(size[1]);

        $item.data('number', index);
        $item.data('ratio', width / height);

        return {
            src: $item.data('item'),
            w: width,
            h: height,
            title: $item.data('title')
        }
    }

    function show(uid, $items, $current) {
        var slides = $items.map(itemToSlide).get(),

            index = $current.data('number'),

            $target = getTarget($current),
            offset = $target.offset(),
            width = $target.width(),
            height = $target.height(),

            settings = $.extend({
                galleryUID: uid,
                index: index,
                getThumbBoundsFn: function () {
                    return { x: offset.left, y: offset.top, w: width };
                },
                showHideOpacity: Math.abs(width / height - $current.data('ratio')) > 0.03
            }, options);

        photoswipe = new PhotoSwipe(photoswipeElement, PhotoSwipeUI_Default, slides, settings);

        photoswipe.init();

        photoswipe.listen('preventDragEvent', function (e, isDown, preventObj) {
            clearTimeout(dragTimer);

            if (isDown) {
                $photoswipe.addClass('pswp-dragging');
            } else {
                dragTimer = setTimeout(function () {
                    $photoswipe.removeClass('pswp-dragging');
                }, 350);
            }
        });

        photoswipe.listen('beforeChange', function (e) {
            $photoswipe.removeClass('pswp-dragging');
        });
    }

    $(function () {
        $('[data-om-cc-photoswipe]').each(function (uid) {
            var $gallery = $(this);

            init();

            $gallery.on('click', '[data-item]', function (event) {
                event.preventDefault();

                show(uid + 1, $gallery.find('[data-item]'), $(this));
            });
        });

        $(document)
            .on('click', '[data-om-cc-photoswipe-group]', function (event) {
                event.preventDefault();

                init();

                var $this = $(this),
                    group = $this.data('om-cc-photoswipe-group');

                show('group-' + group, $('[data-om-cc-photoswipe-group="' + group + '"]'), $this);
            })
            .on('click', '.pswp__button--arrow--left,.pswp__button--arrow--right', function (event) {
                var $target = $(event.target);

                if (!$target.hasClass('.pswp__button')) {
                    $target.parent('.pswp__button').trigger('click');
                }
            });

        var $window = $(window),
            hash = location.hash,
            matches,
            uid,
            image,
            $images,
            $current;

        if (hash) {
            matches = hash.replace('#', '').match(/&gid=([^&]+)&pid=([^&]+)/)

            if ($.isArray(matches) && matches.length === 3) {
                uid = matches[1];
                image = matches[2];

                if (/^group-/.test(uid)) {
                    $images = $('[data-om-cc-photoswipe-group="' + uid.replace('group-', '') + '"]');
                } else if ($.isNumeric(uid) && uid >= 1) {
                    uid = parseInt(uid);

                    $images = $('[data-om-cc-photoswipe]:eq(' + (uid - 1) + ') [data-item]');
                }

                if ($images && $images.length >= image) {
                    init();

                    $current = $images.eq(image - 1);

                    $window.scrollTop($current.offset().top - $window.height() / 3);

                    show(uid, $images, $current);
                }
            }
        }
    });

}(jQuery);