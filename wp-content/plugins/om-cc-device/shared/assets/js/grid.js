!function ($) {
    'use strict';

    var $window = $(window);

    function layout(data, instant, duration) {
        var count = 4,
            timer = data.timer,
            masonry = data.masonry;

        if (timer) {
            clearInterval(timer);
        }

        if (instant) {
            masonry.options.transitionDuration = duration !== undefined ? duration : '0.4s';
            masonry.layout();
        }

        timer = setInterval(function () {
            masonry.options.transitionDuration = 0;
            masonry.layout();
            count--;

            if (!count && timer) {
                clearInterval(timer);
            }
        }, 500);

        data.timer = timer;
    }

    function filter(data, value) {
        var masonry = data.masonry,
            $items = $(masonry.element).children('.grid-item'),
            $item,
            item,
            hide = [],
            reveal = [],
            index;

        for (index = 0; index < $items.length; index++) {
            $item = $items.eq(index);
            item = masonry.getItem($item.get(0));

            if (value === '*' || $.inArray(value, $item.data('filter').split(',')) >= 0) {
                if ($item.css('display') === 'none') reveal.push(item);
            } else {
                hide.push(item);
            }
        }
        masonry.hide(hide);
        masonry.reveal(reveal);
        //masonry.reloadItems();

        layout(data, true);
    }

    function onFilter(e) {
        var value = this.value,
            data = e.data,
            $select = data.$select;

        if ($select.val() !== value) $select.val(value);

        filter(data.data, value);
    }

    function onSelect(e) {
        e.data.$radios.filter('[value="' + this.value + '"]').parent().button('toggle');
    }

    $(function () {
        $('[data-om-cc-grid]').each(function () {
            var $this = $(this),
                data,
                $filtersContainer = $this.prev('.grid-filters'),
                $radios = $filtersContainer.find('input'),
                $select = $filtersContainer.find('select'),
                timer,
                init = function () {
                    if (timer) {
                        clearTimeout(timer);
                        timer = false;
                    } else {
                        return;
                    }

                    $this.masonry({
                        columnWidth: '.grid-sizer',
                        itemSelector: '.grid-item',
                        percentPosition: true
                    });

                    data = $this.data();

                    layout(data);

                    $radios.on('change', { data: data, $select: $select }, onFilter);
                    $select.on('change', { $radios: $radios }, onSelect);
                    $window.on('resize', function () { layout(data, true, 0); });
                };

            timer = setTimeout(init, 1e4)
            $this.imagesLoaded(init);

            //$this.masonry();
        });
    });

}(jQuery);