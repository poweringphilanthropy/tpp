jQuery(function ($) {
    var timerID;

    $(document)
        .on('keyup change', '[data-om-cc-map-address]', function () {
            var $this = $(this),
                value = $this.val(),
                map = $this.data('map');

            clearTimeout(timerID);

            if ('map' && value.length > 2) {
                timerID = setTimeout(function () {
                    var geocoder = $this.data('geocoder'),
                        marker = $this.data('marker'),
                        $field = $this.data('$field'),
                        $map = $this.data('$map').css('opacity', 0.5);

                    geocoder.geocode({ 'address': value }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var location = results[0].geometry.location;

                            map.setCenter(location);
                            marker.setPosition(location);
                            $field.val(location.lat() + ',' + location.lng() + '|' + value);
                            $map.css('opacity', 1);
                        } else {
                            //alert('Geocode was not successful for the following reason: ' + status);
                        }
                    });

                }, 2000);
            }
        })
        .ajaxComplete(function () {
            $('[data-om-cc-map-address]').each(function () {
                var $this = $(this);

                if (!$this.data('map')) {
                    var $map = $('<div/>').css({ 'height': 200, 'margin-top': 10 }),
                        geocoder = new google.maps.Geocoder(),
                        options = {
                            zoom: 8,
                            panControl: false,
                            mapTypeControl: false,
                            scaleControl: false,
                            streetViewControl: false
                        },
                        ll = $this.data('om-cc-map-address'),
                        map,
                        marker;

                    $this.parent().append($map);

                    if (ll) {
                        ll = ll.split(',');
                        ll = [parseFloat(ll[0]), parseFloat(ll[1])];
                    } else {
                        ll = [40.689375, -74.044508];
                    }

                    options.center = new google.maps.LatLng(ll[0], ll[1]);

                    map = new google.maps.Map($map.get(0), options);
                    marker = new google.maps.Marker({ map: map, position: options.center });

                    $this.data('map', map);
                    $this.data('$map', $map);
                    $this.data('geocoder', geocoder);
                    $this.data('marker', marker);
                    $this.data('$field', $this.prev());
                }
            });
        });
});