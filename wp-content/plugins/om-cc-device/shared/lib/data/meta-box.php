<?php
namespace OM\CC\Shared\Lib\Data;

use OM\CC\Shared\Lib\Data\MetaBox\BaseControl;
use OM\CC\Shared\Lib\Html\HtmlWriter;

class MetaBox {
	
	/**
	 * @var string
	 */
	public $id;
	
	/**
	 * @var string
	 */
	public $name;
	
	/**
	 * @var array
	 */
	public $postTypes;
	
	/**
	 * @var string
	 */
	public $position;
	
	/**
	 * @var string
	 */
	public $priority;
	
	/**
	 * @var string
	 */
	public $capability;
	
	/**
	 * @var BaseControl[]
	 */
	public $controls;
	
	public function __construct($id, $name, $postTypes, $position, $priority, $capability, $controls) {
		$this->id         = $id;
		$this->name       = $name;
		$this->postTypes  = $postTypes;
		$this->position   = $position;
		$this->priority   = $priority;
		$this->capability = $capability;
		$this->controls   = $controls;
		
		add_action('add_meta_boxes', array($this, '__create'));
		add_action('save_post', array($this, '__save'), 1, 2);
	}
	
	public function __create() {
		if (current_user_can($this->capability)) {
			add_meta_box(
				$this->id,
				$this->name,
				array($this, '__render'),
				$this->postTypes,
				$this->position,
				$this->priority
			);
		}
	}
	
	public function __render() {
		$post = get_post();
		
		$html = HtmlWriter::init();
		
		$html->text(wp_nonce_field($this->id, $this->id . '_wpnonce', false, false));
		
		foreach ($this->controls as $control) {
			if (current_user_can($control->capability)) {
				if ($control->title !== '') {
					$html->p()->strong()->text($control->title)->end(2);
				}
				
				$value = MetaBox::get($post->ID, $this->id, $control->id);
				$value = $value ?: '';
				
				$html->div(null, $control->render(self::_getFullMetaKey($this->id, $control->id), $value), true);
				
				if ($control->description !== '') {
					$html->span('class="description"', $control->description, true);
				}
			}
		}
		
		echo $html;
	}
	
	/**
	 * @param int      $post_id
	 * @param \WP_Post $post
	 */
	public function __save($post_id, $post) {
		
		if (in_array($post->post_type, $this->postTypes, true)) {
			$id = $this->id;
			
			if ($id
			    && is_array($this->postTypes)
			    && !in_array($post->post_type, $this->postTypes, true)
			    && !current_user_can('edit_post', $post_id)
			    && !wp_verify_nonce($_POST[$id . '_wpnonce'], $id)
			) {
				return;
			}
			
			if (is_array($this->controls)) {
				foreach ($this->controls as $control) {
					if (current_user_can($control->capability)) {
						$controlID = self::_getFullMetaKey($this->id, $control->id);
						
						if (!empty($_POST[$controlID])) {
							update_post_meta($post_id, $controlID, trim($_POST[$controlID]));
						} else {
							delete_post_meta($post_id, $controlID);
						}
					}
				}
			}
		}
	}
	
	/**
	 * @param int    $postId
	 * @param string $metaBoxKey
	 * @param string $metaKey
	 *
	 * @return mixed
	 */
	public static function get($postId, $metaBoxKey, $metaKey) {
		return get_post_meta($postId, self::_getFullMetaKey($metaBoxKey, $metaKey), true);
	}
	
	/**
	 * @param string $metaBoxKey
	 * @param string $metaKey
	 *
	 * @return string
	 */
	private static function _getFullMetaKey($metaBoxKey, $metaKey) {
		return $metaBoxKey . '_' . $metaKey;
	}
}