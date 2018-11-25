<?php

namespace OM\CC\Shared\Lib\Utils;

class Devices {
	
	private static $codes = array(
		//flat
		'flat-browser-dark'             => array(
			'device_type'               => 'om-cc-flat',
			'device_flat_mockup'        => 'om-cc-browser',
			'device_flat_browser_color' => 'om-cc-dark'
		),
		'flat-browser-light'            => array(
			'device_type'               => 'om-cc-flat',
			'device_flat_mockup'        => 'om-cc-browser',
			'device_flat_browser_color' => 'om-cc-light'
		),
		'flat-desktop-dark'             => array(
			'device_type'               => 'om-cc-flat',
			'device_flat_mockup'        => 'om-cc-desktop',
			'device_flat_desktop_color' => 'om-cc-dark'
		),
		'flat-desktop-light'            => array(
			'device_type'               => 'om-cc-flat',
			'device_flat_mockup'        => 'om-cc-desktop',
			'device_flat_desktop_color' => 'om-cc-light'
		),
		'flat-laptop-dark'              => array(
			'device_type'              => 'om-cc-flat',
			'device_flat_mockup'       => 'om-cc-laptop',
			'device_flat_laptop_color' => 'om-cc-dark'
		),
		'flat-laptop-light'             => array(
			'device_type'              => 'om-cc-flat',
			'device_flat_mockup'       => 'om-cc-laptop',
			'device_flat_laptop_color' => 'om-cc-light'
		),
		'flat-laptop-alternative-dark'  => array(
			'device_type'              => 'om-cc-flat',
			'device_flat_mockup'       => 'om-cc-laptop',
			'device_flat_laptop_color' => 'om-cc-alternative-dark'
		),
		'flat-laptop-alternative-light' => array(
			'device_type'              => 'om-cc-flat',
			'device_flat_mockup'       => 'om-cc-laptop',
			'device_flat_laptop_color' => 'om-cc-alternative-light'
		),
		'flat-phone-landscape-dark'     => array(
			'device_type'                       => 'om-cc-flat',
			'device_flat_mockup'                => 'om-cc-phone-landscape',
			'device_flat_phone_landscape_color' => 'om-cc-dark'
		),
		'flat-phone-landscape-light'    => array(
			'device_type'                       => 'om-cc-flat',
			'device_flat_mockup'                => 'om-cc-phone-landscape',
			'device_flat_phone_landscape_color' => 'om-cc-light'
		),
		'flat-phone-landscape-gold'     => array(
			'device_type'                       => 'om-cc-flat',
			'device_flat_mockup'                => 'om-cc-phone-landscape',
			'device_flat_phone_landscape_color' => 'om-cc-gold'
		),
		'flat-phone-landscape-rose'     => array(
			'device_type'                       => 'om-cc-flat',
			'device_flat_mockup'                => 'om-cc-phone-landscape',
			'device_flat_phone_landscape_color' => 'om-cc-rose'
		),
		'flat-phone-landscape-silver'   => array(
			'device_type'                       => 'om-cc-flat',
			'device_flat_mockup'                => 'om-cc-phone-landscape',
			'device_flat_phone_landscape_color' => 'om-cc-silver'
		),
		'flat-phone-landscape-grey'     => array(
			'device_type'                       => 'om-cc-flat',
			'device_flat_mockup'                => 'om-cc-phone-landscape',
			'device_flat_phone_landscape_color' => 'om-cc-grey'
		),
		'flat-phone-portrait-dark'      => array(
			'device_type'                      => 'om-cc-flat',
			'device_flat_mockup'               => 'om-cc-phone-portrait',
			'device_flat_phone_portrait_color' => 'om-cc-dark'
		),
		'flat-phone-portrait-light'     => array(
			'device_type'                      => 'om-cc-flat',
			'device_flat_mockup'               => 'om-cc-phone-portrait',
			'device_flat_phone_portrait_color' => 'om-cc-light'
		),
		'flat-phone-portrait-gold'      => array(
			'device_type'                      => 'om-cc-flat',
			'device_flat_mockup'               => 'om-cc-phone-portrait',
			'device_flat_phone_portrait_color' => 'om-cc-gold'
		),
		'flat-phone-portrait-rose'      => array(
			'device_type'                      => 'om-cc-flat',
			'device_flat_mockup'               => 'om-cc-phone-portrait',
			'device_flat_phone_portrait_color' => 'om-cc-rose'
		),
		'flat-phone-portrait-silver'    => array(
			'device_type'                      => 'om-cc-flat',
			'device_flat_mockup'               => 'om-cc-phone-portrait',
			'device_flat_phone_portrait_color' => 'om-cc-silver'
		),
		'flat-phone-portrait-grey'      => array(
			'device_type'                      => 'om-cc-flat',
			'device_flat_mockup'               => 'om-cc-phone-portrait',
			'device_flat_phone_portrait_color' => 'om-cc-grey'
		),
		'flat-tablet-landscape-dark'    => array(
			'device_type'                        => 'om-cc-flat',
			'device_flat_mockup'                 => 'om-cc-tablet-landscape',
			'device_flat_tablet_landscape_color' => 'om-cc-dark'
		),
		'flat-tablet-landscape-light'   => array(
			'device_type'                        => 'om-cc-flat',
			'device_flat_mockup'                 => 'om-cc-tablet-landscape',
			'device_flat_tablet_landscape_color' => 'om-cc-light'
		),
		'flat-tablet-portrait-dark'     => array(
			'device_type'                        => 'om-cc-flat',
			'device_flat_mockup'                 => 'om-cc-tablet-portrait',
			'device_flat_tablet_landscape_color' => 'om-cc-dark'
		),
		'flat-tablet-portrait-light'    => array(
			'device_type'                        => 'om-cc-flat',
			'device_flat_mockup'                 => 'om-cc-tablet-portrait',
			'device_flat_tablet_landscape_color' => 'om-cc-light'
		),
		'flat-watch-dark'               => array(
			'device_type'             => 'om-cc-flat',
			'device_flat_mockup'      => 'om-cc-watch',
			'device_flat_watch_color' => 'om-cc-dark'
		),
		'flat-watch-light'              => array(
			'device_type'             => 'om-cc-flat',
			'device_flat_mockup'      => 'om-cc-watch',
			'device_flat_watch_color' => 'om-cc-light'
		),
		'flat-watch-blue'               => array(
			'device_type'             => 'om-cc-flat',
			'device_flat_mockup'      => 'om-cc-watch',
			'device_flat_watch_color' => 'om-cc-blue'
		),
		'flat-watch-red'                => array(
			'device_type'             => 'om-cc-flat',
			'device_flat_mockup'      => 'om-cc-watch',
			'device_flat_watch_color' => 'om-cc-red'
		),
		'flat-watch-yellow'             => array(
			'device_type'             => 'om-cc-flat',
			'device_flat_mockup'      => 'om-cc-watch',
			'device_flat_watch_color' => 'om-cc-yellow'
		),
		//real
		'real-laptop-gold'              => array(
			'device_type'              => 'om-cc-real',
			'device_real_mockup'       => 'om-cc-laptop',
			'device_real_laptop_color' => 'om-cc-gold'
		),
		'real-laptop-silver'            => array(
			'device_type'              => 'om-cc-real',
			'device_real_mockup'       => 'om-cc-laptop',
			'device_real_laptop_color' => 'om-cc-silver'
		),
		'real-laptop-grey'              => array(
			'device_type'              => 'om-cc-real',
			'device_real_mockup'       => 'om-cc-laptop',
			'device_real_laptop_color' => 'om-cc-grey'
		),
		'real-laptop-alternative'       => array(
			'device_type'              => 'om-cc-real',
			'device_real_mockup'       => 'om-cc-laptop',
			'device_real_laptop_color' => 'om-cc-alternative'
		),
		'real-phone-gold'               => array(
			'device_type'             => 'om-cc-real',
			'device_real_mockup'      => 'om-cc-phone',
			'device_real_phone_color' => 'om-cc-gold'
		),
		'real-phone-rose'               => array(
			'device_type'             => 'om-cc-real',
			'device_real_mockup'      => 'om-cc-phone',
			'device_real_phone_color' => 'om-cc-rose'
		),
		'real-phone-silver'             => array(
			'device_type'             => 'om-cc-real',
			'device_real_mockup'      => 'om-cc-phone',
			'device_real_phone_color' => 'om-cc-silver'
		),
		'real-phone-grey'               => array(
			'device_type'             => 'om-cc-real',
			'device_real_mockup'      => 'om-cc-phone',
			'device_real_phone_color' => 'om-cc-grey'
		),
		'real-phone-landscape-gold'     => array(
			'device_type'                       => 'om-cc-real',
			'device_real_mockup'                => 'om-cc-phone-landscape',
			'device_real_phone_landscape_color' => 'om-cc-gold'
		),
		'real-phone-landscape-rose'     => array(
			'device_type'                       => 'om-cc-real',
			'device_real_mockup'                => 'om-cc-phone-landscape',
			'device_real_phone_landscape_color' => 'om-cc-rose'
		),
		'real-phone-landscape-silver'   => array(
			'device_type'                       => 'om-cc-real',
			'device_real_mockup'                => 'om-cc-phone-landscape',
			'device_real_phone_landscape_color' => 'om-cc-silver'
		),
		'real-phone-landscape-grey'     => array(
			'device_type'                       => 'om-cc-real',
			'device_real_mockup'                => 'om-cc-phone-landscape',
			'device_real_phone_landscape_color' => 'om-cc-grey'
		),
		'real-tablet-black'             => array(
			'device_type'              => 'om-cc-real',
			'device_real_mockup'       => 'om-cc-tablet',
			'device_real_tablet_color' => 'om-cc-black'
		),
		'real-tablet-gold'              => array(
			'device_type'              => 'om-cc-real',
			'device_real_mockup'       => 'om-cc-tablet',
			'device_real_tablet_color' => 'om-cc-gold'
		),
		'real-tablet-silver'            => array(
			'device_type'              => 'om-cc-real',
			'device_real_mockup'       => 'om-cc-tablet',
			'device_real_tablet_color' => 'om-cc-silver'
		),
		'real-tablet-landscape-black'   => array(
			'device_type'                        => 'om-cc-real',
			'device_real_mockup'                 => 'om-cc-tablet-landscape',
			'device_real_tablet_landscape_color' => 'om-cc-black'
		),
		'real-tablet-landscape-gold'    => array(
			'device_type'                        => 'om-cc-real',
			'device_real_mockup'                 => 'om-cc-tablet-landscape',
			'device_real_tablet_landscape_color' => 'om-cc-gold'
		),
		'real-tablet-landscape-silver'  => array(
			'device_type'                        => 'om-cc-real',
			'device_real_mockup'                 => 'om-cc-tablet-landscape',
			'device_real_tablet_landscape_color' => 'om-cc-silver'
		),
		'real-watch-blue'               => array(
			'device_type'             => 'om-cc-real',
			'device_real_mockup'      => 'om-cc-watch',
			'device_real_watch_color' => 'om-cc-blue'
		),
		'real-watch-red'                => array(
			'device_type'             => 'om-cc-real',
			'device_real_mockup'      => 'om-cc-watch',
			'device_real_watch_color' => 'om-cc-red'
		),
		'real-watch-steel'              => array(
			'device_type'             => 'om-cc-real',
			'device_real_mockup'      => 'om-cc-watch',
			'device_real_watch_color' => 'om-cc-steel'
		),
		'real-watch-green'              => array(
			'device_type'             => 'om-cc-real',
			'device_real_mockup'      => 'om-cc-watch',
			'device_real_watch_color' => 'om-cc-green'
		),
		'real-browser'                  => array(
			'device_type'        => 'om-cc-real',
			'device_real_mockup' => 'om-cc-browser'
		),
		'real-desktop'                  => array(
			'device_type'        => 'om-cc-real',
			'device_real_mockup' => 'om-cc-desktop'
		),
		'real-display'                  => array(
			'device_type'        => 'om-cc-real',
			'device_real_mockup' => 'om-cc-display'
		),
	);
	
	/**
	 * @param string $code
	 *
	 * @return array|null
	 */
	public static function getByCode($code) {
		if (!preg_match('/^flat|^real/', $code)) {
			$code = 'flat-' . $code;
		}
		
		if (array_key_exists($code, self::$codes)) {
			return self::$codes[$code];
		}
		
		return null;
	}
	
	/**
	 * @return mixed
	 */
	public static function supportGrid() {
		return defined('OM_CC_VC_DEVICE_VERSION') && version_compare(OM_CC_VC_DEVICE_VERSION, '1.3.0', '>=');
	}
}






