;( function( $, window, document ) {

	"use strict";

	var pluginName = "ccDevicesSelect",
		typeNone   = 'none';

	// The actual plugin constructor
	function Plugin ( element, options ) {
		this.element     = element;
		this.$element    = $(element);
		this.settings    = $.extend( {}, options );
		this.initValue   = this.$element.val();
		this.devicesData = this.$element.data('omCcDeviceSelect');
		if (this.devicesData) {
			this.init();
		}
	}

	$.extend( Plugin.prototype, {
		init: function() {
			var $selects = {
				color:  $('<select >'),
				device: $('<select >'),
				type:   $('<select >')
			};
			this.initValues = this.initValue.split('|');

			$selects.type.on(   'change om-change', this.deviceOptions.bind(this) );
			$selects.device.on( 'change om-change', this.colorOptions.bind(this) );
			$selects.color.on(  'change om-change', this.result.bind(this) );

			this.$element.after($selects.type, $selects.device, $selects.color);

			this.$selects = $selects;
			this.typeOptions();
		},
		typeOptions: function() {
			var $selects = this.$selects,
				devicesData = this.devicesData;

			$selects.type.empty();
			$selects.device.empty();
			$selects.color.empty();

			$selects.type.append( $('<option value="' + typeNone + '">').text(typeNone) );

			$.each(devicesData, function( index, value ) {
				var selected = this.initValues && this.initValues[0] == value.type ? 'selected' : ''
				$selects.type.append( $('<option value="' + value.type + '" ' + selected + '>').text(value.name) );
			}.bind(this))

			$selects.type.trigger('om-change');
		},
		deviceOptions: function() {
			var $selects = this.$selects,
				devicesData = this.devicesData;

			$selects.device.empty();
			$selects.color.empty();

			var selectedType      = this.$selects.type.val(),
				selectedTypeIndex = this.findIndex(devicesData, 'type', selectedType);

			if (selectedTypeIndex > -1) {
				$.each(devicesData[selectedTypeIndex]['devices'], function( index, value ) {
					var selected = this.initValues && this.initValues[1] && this.initValues[1] == value.id ? 'selected' : ''
					$selects.device.append( $('<option value="' + value.id + '" ' + selected + '>').text(value.name) );
				}.bind(this))
			}

			$selects.device.trigger('om-change');
			$selects.device.toggle(selectedType != typeNone);
		},
		colorOptions: function() {
			var $selects = this.$selects,
				devicesData = this.devicesData,
				options;

			$selects.color.empty();

			var selectedType        = $selects.type.val(),
				selectedTypeIndex   = this.findIndex(devicesData, 'type', selectedType),
				selectedDevice      = $selects.device.val(),
				selectedDeviceIndex = selectedTypeIndex > -1
					? this.findIndex(devicesData[selectedTypeIndex]['devices'], 'id', selectedDevice)
					: -1;

			if (selectedTypeIndex > -1 && selectedDeviceIndex > -1) {
				options = devicesData[selectedTypeIndex]['devices'][selectedDeviceIndex]['colors'];
				if (options != undefined) {
					$.each(options, function( index, value ) {
						var selected = this.initValues && this.initValues[2] && this.initValues[2] == value.id ? 'selected' : ''
						$selects.color.append( $('<option value="' + value.id + '" ' + selected + '>').text(value.name) );
					}.bind(this))
				}
			}

			$selects.color.trigger('om-change');
			$selects.color.toggle( selectedType != typeNone && options != undefined );
		},
		result: function() {
			var $selects = this.$selects,
				result = $selects.type.val();

			if (this.initValues) this.initValues = null;
			if (result != typeNone) {
				result += '|' + $selects.device.val() + '|' + $selects.color.val();
			}

			this.$element.val( result );
		},
		findIndex: function( array, property, value ) {
			var length = array.length,
				i = 0;

			for (; i < length; i++) {
				if (array[i][property] && array[i][property] == value) return i;
			}

			return -1;
		}
	} );

	$.fn[ pluginName ] = function( options ) {
		return this.each( function() {
			if ( !$.data( this, "plugin_" + pluginName ) ) {
				$.data( this, "plugin_" +
					pluginName, new Plugin( this, options ) );
			}
		} );
	};

	$(function() {
		$('[data-om-cc-device-select]')[pluginName]();
	})

} )( jQuery, window, document );