// Depends on ScrollMagic

!function ($, window) {
    'use strict';

    var $window = $(window),
        mobile = /mobi/i.test(navigator.userAgent);

    function parseScrollAnimate(controller, $element) {
        var element = $element.get(0);

        return function (item, key) {
            var matches = key.match(/^(100|[0-9]{1,2})(_(100|[0-9]{1,2}))?$/),
                scene,
                hook,
                offset;

            if (matches) {
                hook = 1 - (matches[3] ? parseInt(matches[3]) : 0) / 100;
                offset = parseInt(matches[1]) / 100 * $element.height();

                scene = new ScrollMagic.Scene({
                    triggerElement: element,
                    triggerHook: hook,
                    offset: offset
                })
                    .addTo(controller);

                scene.data = matches[0];

                return scene;
            }
        };
    }

    function getOrderScenes(vh) {
        return function orderScenes(a, b) {
            a = a.triggerPosition() - a.triggerHook() * vh;
            b = b.triggerPosition() - b.triggerHook() * vh;

            return a > b ? 1 : a < b ? -1 : 0;
        };
    }

    function format(string, args) {
        return string.replace(/\$(\d)/g, function (match, number) {
            return typeof args[--number] != 'undefined' ? args[number] : match;
        });
    }

    function parseAnimateStyles(string) {
        var strings = string.split(';'),
            param,
            styles = {},
            index;

        for (index = 0; index < strings.length; index++) {
            param = strings[index].split(':');
            styles[param[0]] = param[1] || '$1';
        }

        return styles;
    }

    function parseAnimateValues(styles, string) {
        var strings = string.toString().split(';'),
            values = {},
            key,
            index = 0;

        for (key in styles) {
            if (styles.hasOwnProperty(key)) {
                values[key] = $.map(strings[index].split(','), parseFloat);
                index++;
            }
        }

        return values;
    }

    function getArrayProgress(progress, start, end) {
        var index = start.length,
            values = [];

        while (index--) {
            values[index] = end[index] * progress + start[index] * (1 - progress);
        }

        return values;
    }

    function onAnimateProgress($element, styles, start, end) {
        return function (event) {
            var progress = event.progress,
                key,
                values = {};

            for (key in styles) {
                if (styles.hasOwnProperty(key)) {
                    values[key] = format(styles[key], getArrayProgress(progress, start[key], end[key]));
                }
            }

            $element.css(values);
        };
    }

    $(function () {
        var controller = new ScrollMagic.Controller();

        /*controller.scrollTo(function (position, instant) {
            if (instant) {
                $window.scrollTop(position);
            } else {
                $scrollable.stop().animate({scrollTop: position}, '400', 'swing');
            }
        });*/

        if (!mobile) {
            $('[data-om-cc-scroll-animate]').each(function () {
                var $this = $(this),
                    initiated = $this.data('scroll-animate-init');

                if(!initiated) {
                    $this.data('scroll-animate-init', true);

                    var triggerSelector = $this.data('scroll-trigger'),
                        $triggerElement = triggerSelector ? $this.parents(triggerSelector).first() : $this,
                        scenes = $.map($this.data(), parseScrollAnimate(controller, $triggerElement)),
                        scene,
                        nextScene,
                        index = scenes.length,
                        styles,
                        values = [],
                        vh;

                    if (index) {
                        vh = $window.height();

                        scenes = scenes.sort(getOrderScenes(vh));
                        styles = parseAnimateStyles($this.data('om-cc-scroll-animate'));

                        while (index--) {
                            values[index] = parseAnimateValues(styles, $this.data(scenes[index].data));
                        }

                        index = scenes.length - 1;

                        while (index--) {
                            scene = scenes[index];
                            nextScene = scenes[index + 1];
                            scene.duration(nextScene.triggerPosition() - nextScene.triggerHook() * vh - scene.triggerPosition() + scene.triggerHook() * vh);

                            scene.on('progress', onAnimateProgress($this, styles, values[index], values[index + 1]));
                        }
                    }
                }

            });
        }
    });
}(jQuery, window);