;( function( $, window, document ) {

	"use strict";

	var pluginName = "ccBreakpointSelect",
		possibleBreakpoints = [
			{
				id: 'lg',
				icon: 'ion-monitor'
			},
			{
				id: 'md',
				icon: 'ion-laptop'
			},
			{
				id: 'sm',
				icon: 'ion-ipad'
			},
			{
				id: 'xs',
				icon: 'ion-iphone'
			}
		],
		CSS_CLASSES = {
			GROUP:    'cc-breakpoint-select-group',
			BUTTON:   'button',
			SELECTED: 'active'
		};

	// The actual plugin constructor
	function Plugin ( element, options ) {
		this.element    = element;
		this.$element   = $(element);
		this.settings   = $.extend( {}, options );
		this.initValue  = this.$element.val();
		this.breakpointsData = this.$element.data('omCcBreakpointSelect')
		this.init();
	}

	$.extend( Plugin.prototype, {
		init: function() {
			this.initValues = this.initValue.split(',');
			var result = this.result.bind(this),
				$buttonsHost = $('<span>').addClass(CSS_CLASSES.GROUP).insertAfter(this.$element),
				presentBreakpoints = this.breakpointsData
					? $.grep(possibleBreakpoints, function( breakpoint ) {
						return this.breakpointsData.indexOf(breakpoint.id) > -1
					}.bind(this))  
					: possibleBreakpoints

			this.$buttons = $.map(presentBreakpoints, function( breakpoint ) {
				var isSelected = this.initValues.indexOf(breakpoint.id) > -1;

				return $('<a>')
					.addClass(CSS_CLASSES.BUTTON)
					.data('breakpoint', breakpoint.id)
					.data('breakpointSelected', isSelected)
					.toggleClass(CSS_CLASSES.SELECTED, isSelected)
					.on('click', function() {
						var $this = $(this),
							newState = !$this.data('breakpointSelected');

						$this.data('breakpointSelected', newState).toggleClass(CSS_CLASSES.SELECTED, newState)

						result()
					})
					.appendTo($buttonsHost)
					.append(this.renderIcon(breakpoint.icon))

			}.bind(this))
		},
		renderIcon: function(iconName) {
			return $('<span class="icon">').addClass(iconName)
		},
		result: function() {
			var selectedBreakpoints = $.grep(this.$buttons, function($button) {
				return $button.data('breakpointSelected')
			}).map(function($button) { return $button.data('breakpoint') });

			this.$element.val( selectedBreakpoints.join(',') )
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
		$('[data-om-cc-breakpoint-select]')[pluginName]()
	})

} )( jQuery, window, document );