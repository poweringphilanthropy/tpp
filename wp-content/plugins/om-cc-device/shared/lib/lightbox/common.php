<?php

namespace OM\CC\Shared\Lib\Lightbox;

class Common {
	
	/**
	 * @return  array[]
	 */
	public static function getConfig() {
		return array(
			'lightbox' => array(
				'name'     => esc_html__('Lightbox', OM_CP_SHARED_TEXTDOMAIN),
				'position' => 140,
				'sections' => array(
					'lightbox_colors'  => array(
						'name'     => esc_html__('Colors', OM_CP_SHARED_TEXTDOMAIN),
						'position' => 10,
						'options'  => array(
							array(
								'name'  => esc_html__('Background', OM_CP_SHARED_TEXTDOMAIN),
								'id'    => 'background',
								'type'  => 'color-advanced',
								'alpha' => true
							),
							array(
								'name'  => esc_html__('Image background', OM_CP_SHARED_TEXTDOMAIN),
								'id'    => 'image_background_color',
								'type'  => 'color-advanced',
								'alpha' => true
							),
							array(
								'name' => esc_html__('Buttons', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'button_color',
								'type' => 'color'
							),
							array(
								'name' => esc_html__('Arrows', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'arrow_color',
								'type' => 'color'
							),
							array(
								'name'  => esc_html__('Arrow background', OM_CP_SHARED_TEXTDOMAIN),
								'id'    => 'arrow_background',
								'type'  => 'color-advanced',
								'alpha' => true
							),
							array(
								'name'  => esc_html__('Arrow background hover', OM_CP_SHARED_TEXTDOMAIN),
								'id'    => 'arrow_background_hover',
								'type'  => 'color-advanced',
								'alpha' => true
							),
							array(
								'name' => esc_html__('Counter', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'counter_color',
								'type' => 'color'
							),
							array(
								'name' => esc_html__('Caption', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'caption_color',
								'type' => 'color'
							),
							array(
								'name' => esc_html__('Share link', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'share_color',
								'type' => 'color'
							),
							array(
								'name'  => esc_html__('Share link background', OM_CP_SHARED_TEXTDOMAIN),
								'id'    => 'share_background',
								'type'  => 'color-advanced',
								'alpha' => true
							),
							array(
								'name'  => esc_html__('Share overlay', OM_CP_SHARED_TEXTDOMAIN),
								'id'    => 'overlay_color',
								'type'  => 'color-advanced',
								'alpha' => true
							)
						)
					),
					'lightbox_general' => array(
						'name'     => esc_html__('General settings', OM_CP_SHARED_TEXTDOMAIN),
						'position' => 20,
						'options'  => array(
							array(
								'name' => esc_html__('Fullscreen button', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'fullscreen',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('Zoom button', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'zoom',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('Download button', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'share_download',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('Facebook share', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'share_facebook',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('Twitter share', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'share_twitter',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('Pinterest share', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'share_pinterest',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('Tumblr share', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'share_tumblr',
								'type' => 'checkbox'
							),
							array(
								'name' => 'Google+ share',
								'id'   => 'share_google_plus',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('VK share', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'share_vk',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('Reddit share', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'share_reddit',
								'type' => 'checkbox'
							),
							array(
								'name' => esc_html__('Disable titles', OM_CP_SHARED_TEXTDOMAIN),
								'id'   => 'title_disable',
								'type' => 'checkbox'
							)
						)
					)
				)
			)
		);
	}
	
	/**
	 * @param integer $imageId
	 *
	 * @return string
	 */
	public static function getLightboxImageCaption($imageId) {
		$caption = '';
		
		$attachment = get_post($imageId);
		
		if ($attachment) {
			$caption = $attachment->post_title;
			$excerpt = $attachment->post_excerpt;
			
			if (is_string($excerpt) && count($excerpt)) {
				$caption = count($caption) ? $excerpt : "{$caption}<br/><small>{$excerpt}</small>";
			}
		}
		
		return $caption;
	}
}