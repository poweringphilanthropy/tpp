<?php

namespace OM\CC\Shared\Lib\Data;

class PostType extends BaseEntity {
	
	/**
	 * @var array Default post type register settings
	 * menu_position below: 5 - Posts,
	 *                      10 - Media,
	 *                      15 - Links,
	 *                      20 - Pages,
	 *                      25 - comments,
	 *                      60 - separator,
	 *                      65 - Plugins,
	 *                      70 - Users,
	 *                      75 - Tools,
	 *                      80 - Settings,
	 *                      100 - second separator
	 */
	static private $defaults = array(
		'public'        => true,
		'menu_position' => 20,
		'supports'      => array('title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'page-attributes'),
		'has_archive'   => true,
		'menu_icon'     => false,
		// 'taxonomies' => array(),
		// 'exclude_from_search' => opposite of 'public'
		// 'publicly_queryable'  => {value of public},
		// 'show_ui'             => {value of public},
		// 'show_in_nav_menus'   => {value of public},
		// 'show_in_menu'        => {value of show_ui},
		// 'show_in_admin_bar'   => {value of show_in_menu}
		// 'capability_type'     => 'post',
		// 'hierarchical'        => false,
		// 'rewrite'             => true,
		// 'query_var'           => true,
		// 'can_export'          => true,
	);
	
	/**
	 * @var string[] The following post types are reserved and used by WordPress already
	 */
	static private $reservedPostTypes = array('post', 'page', 'attachment', 'revision', 'nav_menu_item');
	
	/**
	 * @var string[] The following post types should not be used as they interfere with other WordPress functions
	 */
	static private $systemPostTypes = array('action', 'order', 'theme');
	
	/**
	 * @var string DateTime format
	 */
	static private $scheduleDateTimeFormat;
	
	/**
	 * @var array Custom update messages
	 */
	private $messages;
	
	/**
	 * @var string Text fo view link
	 */
	private $viewLinkText;
	
	/**
	 * @var string Text fo preview link
	 */
	private $previewLinkText;
	
	/**
	 * Post type constructor
	 *
	 * @param array $options Custom post type options
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
	 *       Icons:
	 * @link https://developer.wordpress.org/resource/dashicons/
	 */
	public function __construct(array $options) {
		parent::__construct($options);
		
		if (in_array($options['name'], self::$systemPostTypes, true)) {
			throw new \InvalidArgumentException("Post type name invalid ({$options['name']})");
		}
		
		$this->settings = $this->_parsePostTypeSettings($options);
		$this->messages = $this->_getMessages($options);
		
		$this->viewLinkText    = sprintf(__('View %s', OM_CP_SHARED_TEXTDOMAIN), $this->singular);
		$this->previewLinkText = sprintf(__('Preview %s', OM_CP_SHARED_TEXTDOMAIN), $this->singular);
		
		if (!self::$scheduleDateTimeFormat) {
			self::$scheduleDateTimeFormat = __('M j, Y @ G:i', OM_CP_SHARED_TEXTDOMAIN);
		}
		
		add_action('init', array($this, '__register'));
		add_filter('post_updated_messages', array($this, '__updateMessages'));
	}
	
	/**
	 * Register post type hook
	 */
	public function __register() {
		register_post_type($this->name, $this->settings);
	}
	
	/**
	 * Post type update messages filter
	 *
	 * @param array $messages Existing post update messages.
	 *
	 * @return array Amended post update messages with new CPT update messages.
	 */
	public function __updateMessages(array $messages) {
		if ($this->messages) {
			
			$post = get_post();
			
			$postType     = get_post_type_object($this->name);
			$postMessages = $this->messages;
			
			$postMessages[5] = array_key_exists('revision', $_GET) ? $postMessages[5] . ' ' . wp_post_revision_title((int)$_GET['revision'], false) : '';
			$postMessages[9] .= sprintf(' <strong>$s</strong>.', date_i18n(self::$scheduleDateTimeFormat, strtotime($post->post_date)));
			
			if ($postType->publicly_queryable) {
				$viewUrl    = get_permalink($post->ID);
				$previewUrl = esc_url(add_query_arg('preview', 'true', $viewUrl));
				
				$viewLink    = sprintf(' <a href="%s">%s</a>', esc_url($viewUrl), $this->viewLinkText);
				$previewLink = sprintf(' <a target="_blank" href="%s">%s</a>', esc_url($previewUrl), $this->previewLinkText);
				
				$postMessages[1] .= $viewLink;
				$postMessages[6] .= $viewLink;
				$postMessages[8] .= $previewLink;
				$postMessages[9] .= $viewLink;
				$postMessages[10] .= $previewLink;
			}
			
			$messages[$this->name] = $postMessages;
		}
		
		return $messages;
	}
	
	/**
	 * Compose post type settings for register
	 *
	 * @param array $options Custom entity options
	 *
	 * @return array
	 */
	private function _parsePostTypeSettings($options) {
		static $labels;
		
		if(!$labels) {
			$labels = array(
				'name'               => '%2$s',
				'singular_name'      => '%1$s',
				'add_new'            => esc_attr__('Add New', OM_CP_SHARED_TEXTDOMAIN),
				'add_new_item'       => esc_attr__('Add New %1$s', OM_CP_SHARED_TEXTDOMAIN),
				'edit_item'          => esc_attr__('Edit %1$s', OM_CP_SHARED_TEXTDOMAIN),
				'new_item'           => esc_attr__('New %1$s', OM_CP_SHARED_TEXTDOMAIN),
				'view_item'          => esc_attr__('View %1$s', OM_CP_SHARED_TEXTDOMAIN),
				'search_items'       => esc_attr__('Search %2$s', OM_CP_SHARED_TEXTDOMAIN),
				'not_found'          => esc_attr__('No %2$s found', OM_CP_SHARED_TEXTDOMAIN),
				'not_found_in_trash' => esc_attr__('No %2$s found in Trash', OM_CP_SHARED_TEXTDOMAIN),
				'parent_item_colon'  => esc_attr__('Parent %1$s:', OM_CP_SHARED_TEXTDOMAIN),
				'menu_name'          => '%2$s',
				'all_items'          => esc_attr__('All %2$s', OM_CP_SHARED_TEXTDOMAIN),
			);
		}
		
		return parent::_parseSettings(self::$defaults, $options, $labels);
	}
	
	/**
	 * Generate post type update messages
	 *
	 * @param array $options Custom post type options
	 *
	 * @return array Array of messages, used by WP to show update notifications
	 */
	private function _getMessages(array $options) {
		if (!array_key_exists('messages', $options) && in_array($this->name, self::$reservedPostTypes, true)) {
			return null;
		}
		
		$messages = array(
			0  => '', // Unused. Messages start at index 1.
			1  => '%1$s updated.',
			2  => 'Custom field updated.',
			3  => 'Custom field deleted.',
			4  => '%1$s updated.',
			5  => '%1$s restored to revision from',
			6  => '%1$s published.',
			7  => '%1$s saved.',
			8  => '%1$s submitted.',
			9  => '%1$s scheduled for:',
			10 => '%1$s draft updated.'
		);
		
		if (array_key_exists('messages', $options)) {
			$messages = array_merge($messages, $options['messages']);
		}
		
		foreach ($messages as &$message) {
			$message = sprintf($message, $this->singular, $this->plural);
		}
		
		return $messages;
	}
}