<?php

namespace OM\CC\Shared\Lib\Utils;

use OM\CC\Shared\Lib\Html\Images;
use OM\CC\Shared\Lib\Html\HtmlWriter;

class Grid {
	
	/**
	 * @var array
	 */
	private static $_sizes = array('-xs-', '-sm-', '-md-', '-lg-');
	
	/**
	 * @var array
	 */
	private static $_aspectDefaults = array(
		'low'        => array(
			'1'  => 1,
			'2'  => 1,
			'3'  => 1,
			'4'  => 2,
			'6'  => 3,
			'12' => 6,
		),
		'horizontal' => array(
			'1'  => 1,
			'2'  => 1,
			'3'  => 2,
			'4'  => 3,
			'6'  => 4,
			'12' => 9,
		),
		'square'     => array(
			'1'  => 1,
			'2'  => 2,
			'3'  => 3,
			'4'  => 4,
			'6'  => 6,
			'12' => 12,
		),
		'vertical'   => array(
			'1'  => 1,
			'2'  => 3,
			'3'  => 4,
			'4'  => 5,
			'6'  => 8,
			'12' => 15,
		),
		'high'       => array(
			'1'  => 2,
			'2'  => 4,
			'3'  => 5,
			'4'  => 7,
			'6'  => 10,
			'12' => 18,
		),
	);
	
	/**
	 * @var string
	 */
	private static $_emptyBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==';
	
	/**
	 * @return array
	 */
	public static function getBpMax() {
		$bpMax = array(
			'xs' => '767',
			'sm' => '991',
			'md' => '1199',
		);
		
		return apply_filters('om_cp_breakpoints_max', $bpMax);
	}
	
	/**
	 * @return array
	 */
	public static function getBpMin() {
		$bpMin = array(
			'xs' => '320',
			'sm' => '768',
			'md' => '992',
			'lg' => '1200',
		);
		
		return apply_filters('om_cp_breakpoints_min', $bpMin);
	}
	
	/**
	 * @param string $device
	 *
	 * @return array
	 */
	public static function getPositionsListOptions($device = null) {
		if ($device) {
			$result = self::_getDeviceInheritOption($device);
		} else {
			$result = array();
		}
		
		$result = array_merge($result, array(
			esc_html__('Top Left', OM_CP_SHARED_TEXTDOMAIN)      => 'top-left',
			esc_html__('Top Center', OM_CP_SHARED_TEXTDOMAIN)    => 'top',
			esc_html__('Top Right', OM_CP_SHARED_TEXTDOMAIN)     => 'top-right',
			esc_html__('Center Left', OM_CP_SHARED_TEXTDOMAIN)   => 'center-left',
			esc_html__('Center', OM_CP_SHARED_TEXTDOMAIN)        => 'center',
			esc_html__('Center Right', OM_CP_SHARED_TEXTDOMAIN)  => 'center-right',
			esc_html__('Bottom Left', OM_CP_SHARED_TEXTDOMAIN)   => 'bottom-left',
			esc_html__('Bottom Center', OM_CP_SHARED_TEXTDOMAIN) => 'bottom',
			esc_html__('Bottom Right', OM_CP_SHARED_TEXTDOMAIN)  => 'bottom-right',
		));
		
		return $result;
	}
	
	/**
	 * @return array
	 */
	public static function getSlidesOptions() {
		return array(
			esc_html__('None', OM_CP_SHARED_TEXTDOMAIN)       => 'none',
			esc_html__('Up Left', OM_CP_SHARED_TEXTDOMAIN)    => 'up-left',
			esc_html__('Up', OM_CP_SHARED_TEXTDOMAIN)         => 'up',
			esc_html__('Up Right', OM_CP_SHARED_TEXTDOMAIN)   => 'up-right',
			esc_html__('Right', OM_CP_SHARED_TEXTDOMAIN)      => 'right',
			esc_html__('Left', OM_CP_SHARED_TEXTDOMAIN)       => 'left',
			esc_html__('Down Left', OM_CP_SHARED_TEXTDOMAIN)  => 'down-left',
			esc_html__('Down', OM_CP_SHARED_TEXTDOMAIN)       => 'down',
			esc_html__('Down Right', OM_CP_SHARED_TEXTDOMAIN) => 'down-right',
		);
	}
	
	/**
	 * @param array  $lines
	 * @param string $device
	 *
	 * @return array
	 */
	public static function getVisibilityOptions(array $lines, $device = null) {
		$result = array();
		
		foreach ($lines as $line) {
			switch ($line) {
				case 'inherit':
					$result = array_merge($result, self::_getDeviceInheritOption($device));
					break;
				case 'visible':
					$result = array_merge($result, array(
						esc_html__('Visible', OM_CP_SHARED_TEXTDOMAIN) => 'yes',
					));
					break;
				case 'hidden':
					$result = array_merge($result, array(
						esc_html__('Hidden', OM_CP_SHARED_TEXTDOMAIN) => 'no',
					));
					break;
				case 'static':
					$result = array_merge($result, array(
						esc_html__('Static', OM_CP_SHARED_TEXTDOMAIN) => 'static',
					));
					break;
				case 'show':
					$result = array_merge($result, array(
						esc_html__('Show right away and hide on mouseover', OM_CP_SHARED_TEXTDOMAIN) => 'disapp',
					));
					break;
				case 'hide':
					$result = array_merge($result, array(
						esc_html__('Show on mouseover', OM_CP_SHARED_TEXTDOMAIN) => 'app',
					));
					break;
			}
		}
		
		return $result;
	}
	
	/**
	 * @return array
	 */
	public static function getAnimationOptions() {
		return array(
			esc_html__('Move from the initial position', OM_CP_SHARED_TEXTDOMAIN) => 'hover',
			esc_html__('Move to the initial position', OM_CP_SHARED_TEXTDOMAIN)   => 'rest',
		);
	}
	
	/**
	 * @return array
	 */
	public static function getScalingOptions() {
		return array(
			esc_html__('None', OM_CP_SHARED_TEXTDOMAIN) => 'none',
			esc_html__('In', OM_CP_SHARED_TEXTDOMAIN)   => 'down',
			esc_html__('Out', OM_CP_SHARED_TEXTDOMAIN)  => 'up',
		);
	}
	
	/**
	 * @return array
	 */
	public static function getRotationOptions() {
		return array(
			esc_html__('None', OM_CP_SHARED_TEXTDOMAIN)             => 'none',
			esc_html__('Clockwise', OM_CP_SHARED_TEXTDOMAIN)        => 'clockwise',
			esc_html__('Counterclockwise', OM_CP_SHARED_TEXTDOMAIN) => 'counterclockwise',
		);
	}
	
	/**
	 * @param string $element
	 * @param array  $value
	 *
	 * @return array
	 */
	public static function getDependency($element, array $value) {
		return array(
			'element' => $element,
			'value'   => $value,
		);
	}
	
	/**
	 * @return array
	 */
	public static function getSizeUnitsOptions() {
		$result = array(
			'px'  => 'px',
			'em'  => 'em',
			'rem' => 'rem',
			'%'   => '%',
		);
		
		return $result;
	}
	
	/**
	 * @param array  $cols
	 * @param string $key
	 *
	 * @return array
	 */
	public static function getAspectRows(array $cols, $key) {
		$aspect = self::$_aspectDefaults[$key];
		$rows   = array();
		
		foreach ($cols as $col) {
			$rows[] = $aspect[$col];
		}
		
		return $rows;
	}
	
	/**
	 * @param string $type
	 * @param array  $rates
	 *
	 * @return string
	 */
	public static function getRatesClasses($type, $rates) {
		$classes = array();
		
		foreach ($rates as $index => $rate) {
			$classes[] = sanitize_html_class($type . self::$_sizes[$index] . $rate);
		}
		
		return $classes;
	}
	
	/**
	 * @param int    $imageId
	 * @param string $layout
	 * @param string $zoom
	 * @param string $device
	 *
	 * @return string
	 */
	public static function getImage($imageId, $layout, $zoom, $device) {
		global $_wp_additional_image_sizes;
		
		$isResponsive = $layout === 'masonry' || $layout === 'devices_masonry' && $device !== '';
		
		$html = HtmlWriter::init();
		
		if (Images::exists($imageId)) {
			$sizes = array('full');
			if (is_array($_wp_additional_image_sizes) && $_wp_additional_image_sizes) {
				foreach ($_wp_additional_image_sizes as $name => $size) {
					if ($size['width'] > 200 && !$size['crop']) {
						$sizes[] = $name;
					}
				}
			}
			$classes = array(
				$isResponsive ? 'img-responsive' : 'img-cover'
			);
			
			if ($zoom !== 'none') {
				$classes[] = 'zoom';
			}
			
			$attributes = Images::getImageAttributes($imageId, $sizes);
			
			$classes[] = $attributes['class'];
			
			$attributes['class'] = $classes;
			
			$html->img($attributes, true);
		} else if ($isResponsive) {
			$html->img(array('class' => 'img-responsive', 'src' => self::$_emptyBase64), true);
		}
		
		return (string)$html;
	}
	
	/**
	 * @param array  $cols
	 * @param array  $rows
	 * @param number $index
	 * @param number $image_id
	 * @param string $layout
	 * @param string $device
	 *
	 * @return array
	 */
	public static function getItemRates($cols, $rows, $index, $image_id, $layout, $device) {
		if ($layout === 'large_first' && $index === 0) {
			$rates = self::_getItemDoubleRates($cols, $rows);
		} else if ($layout === 'auto') {
			$rates = self::_getItemAutoRates($cols, $rows, $image_id);
		} else if ($layout === 'devices_masonry') {
			$rates = self::_getItemDeviceMasonryRates($cols, $rows, $device);
		} else {
			$rates = array('cols' => $cols, 'rows' => $rows);
		}
		
		return $rates;
	}
	
	/**
	 * @param string $scrollEffect
	 * @param number $index
	 *
	 * @return array
	 */
	public static function getScrollEffectAttributes($scrollEffect, $index) {
		switch ($scrollEffect) {
			case 'type1':
				$shift = ($index % 2) ? 10 : 40;
				
				$attributes = array(
					'data-om-cc-scroll-animate' => 'transform:translate3d(0,$1%,0)',
					'data-0_0'                  => $shift,
					'data-100_100'              => -$shift,
				);
				break;
			
			case 'type5':
				$shift = ($index % 2) ? 5 : 20;
				
				$attributes = array(
					'data-om-cc-scroll-animate' => 'transform:translate3d(0,$1%,0)',
					'data-0_0'                  => $shift,
					'data-100_100'              => -$shift,
				);
				break;
			
			case 'type6':
				$shift = ($index % 2) ? 7 : 27;
				
				$attributes = array(
					'data-om-cc-scroll-animate' => 'transform:translate3d(0,$1%,0)',
					'data-0_0'                  => $shift,
					'data-100_100'              => -$shift,
				);
				break;
			
			case 'type2':
				$attributes = array(
					'data-om-cc-scroll-animate' => 'transform:translate3d(0,$1%,0)',
					'data-0_0'                  => 30,
					'data-25_25'                => 0,
					'data-75_75'                => 0,
					'data-100_100'              => -30,
				);
				break;
			
			case 'type3':
				$dy      = mt_rand(-10, 10);
				$shift   = mt_rand(0, 40);
				$shift_1 = $shift * 0.8;
				$shift_2 = $shift * 0.6;
				$shift_3 = $shift * 0.3;
				
				$attributes = array(
					'data-om-cc-scroll-animate' => 'transform:translate3d($2%,$1%,0)',
					'data-0_0'                  => ($shift + $dy) . ',' . mt_rand(-20, 20),
					'data-10_10'                => ($shift_1 + $dy) . ',' . mt_rand(-20, 20),
					'data-20_20'                => ($shift_2 + $dy) . ',' . mt_rand(-20, 20),
					'data-30_30'                => ($shift_3 + $dy) . ',' . mt_rand(-20, 20),
					'data-40_40'                => $dy . ',' . mt_rand(-20, 20),
					'data-50_50'                => $dy . ',' . mt_rand(-20, 20),
					'data-60_60'                => $dy . ',' . mt_rand(-20, 20),
					'data-70_70'                => (-$shift_3 + $dy) . ',' . mt_rand(-20, 20),
					'data-80_80'                => (-$shift_2 + $dy) . ',' . mt_rand(-20, 20),
					'data-90_90'                => (-$shift_1 + $dy) . ',' . mt_rand(-20, 20),
					'data-100_100'              => (-$shift + $dy) . ',' . mt_rand(-20, 20),
				);
				break;
			
			case 'type4':
				$shift   = ($index % 2) ? 40 : 0;
				$shift_2 = $shift * 0.6;
				$shift_3 = $shift * 0.3;
				
				$attributes = array(
					'data-om-cc-scroll-animate' => 'transform:translate3d($2%,$1%,0)',
					'data-0_0'                  => $shift . ',' . mt_rand(-150, 150),
					'data-20_20'                => $shift_2 . ',' . mt_rand(-75, 75),
					'data-30_30'                => $shift_3 . ',' . mt_rand(-50, 50),
					'data-40_40'                => '0,0',
					'data-60_60'                => '0,0',
					'data-70_70'                => -$shift_3 . ',' . mt_rand(-50, 50),
					'data-80_80'                => -$shift_2 . ',' . mt_rand(-75, 75),
					'data-100_100'              => -$shift . ',' . mt_rand(-150, 150),
				);
				break;
			
			default:
				$attributes = array();
		}
		
		return $attributes;
	}
	
	/**
	 * @param array  $projects
	 * @param string $taxesNames
	 *
	 * @return array
	 */
	public static function getFilterTaxes(array $projects, $taxesNames) {
		$args = array(
			'hierarchical' => false,
		);
		
		$ids = array();
		
		foreach ($projects as $project) {
			$ids = array_merge($ids, wp_get_post_terms($project->ID, $taxesNames, array('fields' => 'ids')));
		}
		
		$args['include'] = array_unique($ids);
		
		return get_terms($taxesNames, $args);
	}
	
	/**
	 * @param string $number
	 * @param string $orderBy
	 * @param string $order
	 * @param string $postType
	 * @param string $filterProjects
	 * @param string $filterType
	 * @param string $filterCategories
	 *
	 * @return array
	 */
	public static function getProjects($number, $orderBy, $order, $postType, $filterProjects, $filterType, $filterCategories) {
		return get_posts(array(
			'posts_per_page'   => ($number !== null) ? $number : 9,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => $orderBy,
			'order'            => $order,
			'post_type'        => $postType,
			'include'          => $filterProjects,
			'tax_query'        => self::_getProjectsTaxQuery($filterType, $filterCategories),
			'suppress_filters' => false
		));
	}
	
	/**
	 * @param string $filterType
	 * @param string $filterCategories
	 *
	 * @return array|string
	 */
	private static function _getProjectsTaxQuery($filterType, $filterCategories) {
		if ($filterType !== 'taxonomies') {
			return '';
		}
		
		if ($filterCategories === null) {
			return '';
		}
		
		$tax_query = array('relation' => 'AND');
		$len       = strlen($filterCategories);
		if (1 < $len) {
			$terms = array();
			
			$taxes = explode(';', substr($filterCategories, 0, $len - 1));
			foreach ($taxes as $tax) {
				$buf         = explode(':', $tax);
				$key         = $buf[0];
				$terms[$key] = explode(',', $buf[1]);
			}
			
			foreach ($terms as $key => $term) {
				if ($filterCategories !== null) {
					$tax_query[] = array(
						'taxonomy' => $key,
						'field'    => 'slug',
						'terms'    => $term,
					);
				}
			}
		}
		
		return $tax_query;
	}
	
	/**
	 * @param array  $cols
	 * @param array  $rows
	 * @param string $device
	 *
	 * @return array
	 */
	private static function _getItemDeviceMasonryRates($cols, $rows, $device) {
		if (strpos($device, 'watch') !== false || strpos($device, 'phone') !== false) {
			$multiplier = 1;
			$max        = 4;
		} else if (strpos($device, 'tablet') !== false || strpos($device, 'laptop') !== false) {
			$multiplier = 2;
			$max        = 8;
		} else if (strpos($device, 'browser') !== false) {
			$multiplier = 3;
			$max        = 12;
		} else {
			$multiplier = 4;
			$max        = 12;
		}
		
		foreach ($cols as &$col) {
			$col = min($max, $col * $multiplier);
		}
		
		return array('cols' => $cols, 'rows' => $rows);
	}
	
	/**
	 * @param array $cols
	 * @param array $rows
	 *
	 * @return array
	 */
	private static function _getItemDoubleRates($cols, $rows) {
		$indexMax = count($cols);
		for ($index = 0; $index < $indexMax; $index++) {
			$double_cols  = min(12, $cols[$index] * 2);
			$double_rows  = min(24, round($rows[$index] * $double_cols / $cols[$index]));
			$cols[$index] = $double_cols;
			$rows[$index] = $double_rows;
		}
		
		return array('cols' => $cols, 'rows' => $rows);
	}
	
	/**
	 * @param array  $cols
	 * @param array  $rows
	 * @param number $imageId
	 *
	 * @return array
	 */
	private static function _getItemAutoRates($cols, $rows, $imageId) {
		if ($imageId) {
			$info = wp_get_attachment_image_src($imageId, 'full');
			
			if ($info) {
				$ratio = $info[1] / $info[2];
				
				foreach ($cols as $index => $col) {
					$row = $rows[$index];
					$new = array('col' => $col, 'row' => $row);
					
					$diff = abs($col / $row - $ratio);
					
					for ($current_col = $col * 2; $current_col <= 12; $current_col += $col) {
						$current = abs($current_col / $row - $ratio);
						if ($current < $diff) {
							$diff       = $current;
							$new['col'] = $current_col;
						}
					}
					
					if ($new['col'] === $col) {
						for ($current_row = $row * 2; $current_row <= 24; $current_row += $row) {
							$current = abs($col / $current_row - $ratio);
							if ($current < $diff) {
								$diff       = $current;
								$new['row'] = $current_row;
							}
						}
					}
					
					$cols[$index] = $new['col'];
					$rows[$index] = $new['row'];
				}
			}
		}
		
		return array('cols' => $cols, 'rows' => $rows);
	}
	
	/**
	 * @param string $device
	 *
	 * @return array|null
	 */
	private static function _getDeviceInheritOption($device) {
		switch ($device) {
			case 'desktop':
				return array(
					esc_html__('Inherit from desktop', OM_CP_SHARED_TEXTDOMAIN) => '',
				);
				break;
			case 'laptop':
				return array(
					esc_html__('Inherit from laptop', OM_CP_SHARED_TEXTDOMAIN) => '',
				);
				break;
			case 'tablet':
				return array(
					esc_html__('Inherit from tablet', OM_CP_SHARED_TEXTDOMAIN) => '',
				);
				break;
			case 'phone':
				return array(
					esc_html__('Inherit from phone', OM_CP_SHARED_TEXTDOMAIN) => '',
				);
				break;
		}
		
		return null;
	}
}