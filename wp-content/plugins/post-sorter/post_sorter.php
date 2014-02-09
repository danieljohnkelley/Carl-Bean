<?php
/*
  Plugin Name: Post Sorter
  Plugin URI: http://intellisys.org/
  Description: Plugin for easy sorting of posts and pages by numeric value, both ascending and descending.
  Version: 1.4
  Author: Lyubomir Gardev
  Author URI: http://rolice.intellisys.info/
  Text Domain: post_sorter
  License: GPLv2 or later
 */

define( 'POST_SORTER_META_KEY', 'post_sorter_order' );

class PostSorter {

	/**
	 * Whether custom sorting is enabled
	 * @var bool
	 */
	private $custom = FALSE;

	private $plugin_data = NULL;

	public function __construct() {
		$this->init();
	}

	public function init() {
		load_plugin_textdomain( 'post_sorter', FALSE, plugin_dir_path(__FILE__) . 'lang/' );

		add_filter( 'manage_posts_columns', array( $this, 'add_sorter_column' ) );
		add_filter( 'manage_pages_columns', array( $this, 'add_sorter_column' ) );

		add_action( 'manage_posts_custom_column', array( $this, 'show_sorter_column' ) );
		add_action( 'manage_pages_custom_column', array( $this, 'show_sorter_column' ) );

		add_filter( 'manage_edit-post_sortable_columns', array( $this, 'add_sorter_sort' ) );
		add_filter( 'manage_edit-page_sortable_columns', array( $this, 'add_sorter_sort' ) );

		$custom_types = get_option( 'post_sorter_custom_types' );
		$custom_types = explode( ',', sanitize_text_field( trim( str_replace( ' ', '', $custom_types ) ) ) );

		if( $custom_types && is_array( $custom_types ) )
			foreach( $custom_types as $t )
				add_filter( 'manage_edit-' . $t . '_sortable_columns', array( $this, 'add_sorter_sort' ) );

		//add_filter( 'request', array( $this, 'order_by_at_request' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'attach_on_save' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_ajax_save_sort_position', array( $this, 'save_sort_position' ) );
		add_action( 'wp_ajax_move_sort_post', array( $this, 'move_post' ) );

		add_filter( 'posts_join', array( $this, 'join' ) );
		add_filter( 'posts_orderby', array( $this, 'order_by' ) );
		
		// Internal filters
		add_filter( 'post_sorter_join', array( $this, 'internal_join' ) );
		add_filter( 'post_sorter_order', array( $this, 'internal_order' ) );

		/* == = = = = = = = = = = ADMIN STUFF = = = = = = = = = = == */

		if ( !is_admin() )
			return;

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'set_up' ) );
	}

	/**
	 * Activation hook of the plugin
	 */
	public function activate() {
		update_option( 'post_sorter_enabled', TRUE );
		update_option( 'post_sorter_direction', 'ASC' );
		update_option( 'post_sorter_enabled_roles', array( 'administrator' ) );

		$args = array(
			'numberposts' => -1,
			'post_type' => array( 'post', 'page' ),
			'post_status' => array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash' )
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			$current = get_post_meta( $post->ID, POST_SORTER_META_KEY, TRUE );

			if ($current || 0 === $current)
				continue;

			update_post_meta( $post->ID, POST_SORTER_META_KEY, 0 );
		}
	}

	/**
	 * Deactivation hook of the plugin
	 */
	public function deactivate() {
		
	}

	public function meta( $key ) {
		return isset( $this->plugin_data[ $key ] ) ? $this->plugin_data[ $key ] : NULL;
	}

	/**
	 * Enqueues scripts (JavaScripts) and CSS styles
	 * @return [type] [description]
	 */
	public function enqueue_scripts() {
		wp_register_style( 'post_sorter', plugin_dir_url(__FILE__) . 'css/style.css' );
		wp_enqueue_style( 'post_sorter' );

		wp_enqueue_script( 'post_sorter', plugin_dir_url(__FILE__) . 'js/common.js' );
	}

	/**
	 * Adds (registers) new column in the admin posts lists
	 * @param  array $columns The columns to be rendered in the admin post lists
	 * @return array          The columns to be rendered with added new column inside
	 */
	public function add_sorter_column( $columns ) {
		if( $this->can_use() )
			$columns['sort'] = __( 'Sorting', 'post_sorter' );

		return $columns;
	}

	/**
	 * Handles the call for rendering a column and calls corresponding render method 
	 * @param  string $name A column name
	 */
	public function show_sorter_column( $name ) {
		switch ( $name ) {
			case 'sort':
				$this->render_sort_cell();
				break;
		}
	}

	/**
	 * Renders (outputs) the column html
	 */
	public function render_sort_cell() {
		global $post;

		$val = (int) get_post_meta( $post->ID, POST_SORTER_META_KEY, TRUE );
		
		if( $this->custom || !$this->can_use() ) {
			echo '<div class="post_sorter">—</div>';
			return;
		}
		
		$html = '<div class="post_sorter">';
		
		if ( get_option( 'post_sorter_arrows_enabled' ) ) {
			$html .= '<a href="#" title="' . __( 'Move this element up', 'post_sorter' ) . '" class="up icon_button"
			onclick="post_sorter_moveUp(' . $post->ID . '); return false;"></a>';

			$html .= '<a href="#" title="' . __( 'Move this element down', 'post_sorter' ) . '" class="down icon_button"
			onclick="post_sorter_moveDown(' . $post->ID . '); return false;"></a>';
		}

		$html .= '<input name="post_sorter_inline[]" id="post_sorter_inline_' . $post->ID . '" type="text" value="' . $val . '" class="inline_field"
		onkeyup="return post_sorter_saveOnKeyUp(event, this, ' . $post->ID . ')" onblur="post_sorter_save(this, ' . $post->ID . ')" />';

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Adds (registers) new column in the admin posts lists as sortable one
	 * @param  array $columns The sortable columns to be rendered in the admin post lists
	 * @return array          The sortable columns to be rendered with added new column inside
	 */
	public function add_sorter_sort( $columns ) {
		if( $this->can_use() )
			$columns['sort'] = 'sort';

		return $columns;
	}

	/**
	 * Handles ordering at request filter
	 * @deprecated This function is deprecated, use order_by (previously order_by_front) now, it handles actually both admin and front ordering
	 * @param  array $vars Initial request variables
	 * @return array       Filtered (altered) request variables
	 */
	public function order_by_at_request( $vars ) {
		if ( !isset( $vars['orderby'] ) || 'sort' != $vars['orderby'] )
			return $vars;

		$direction = mb_strtoupper( get_option( 'post_sorter_direction' ) ) == 'DESC' ? ' DESC' : '';

		$vars = array_merge( $vars, array(
			'meta_key' => POST_SORTER_META_KEY,
			'order_by' => 'CAST(meta_value_num AS INT)' . $direction
		));

		return $vars;
	}

	/**
	 * Handles join part of the query built for retrieving posts
	 * @param  string $sql Original join SQL
	 * @return strung      Modified join SQL
	 */
	public function join( $sql ) {
		global $wpdb;

		// Plugin disabled - nothing to do
		if ( !get_option( 'post_sorter_enabled' ) )
			return $sql;
		
		// If we have some hooks apply them - other plugins, etc.
		if( 2 <= $this->_count_filter_hooks( 'post_sorter_join' ) ) {
			$this->custom = TRUE;
			$sql = apply_filters( 'post_sorter_join', $sql );
			return $sql;
		}
			
		$sql .= "LEFT JOIN {$wpdb->postmeta} AS post_sorter ON ({$wpdb->posts}.ID = post_sorter.post_id AND post_sorter.meta_key = '" . POST_SORTER_META_KEY . "')";

		return $sql;
	}

	/**
	 * Handles ordering part of the query built for retrieving posts
	 * @param  string $sql Original order by SQL
	 * @return strung      Modified order by SQL
	 */
	public function order_by( $sql ) {
		//global $wpdb;

		// Plugin disabled - nothing to do
		if ( !get_option('post_sorter_enabled') )
			return $sql;
		
		// If we have some hooks apply them - other plugins, etc.
		if( 2 <= $this->_count_filter_hooks( 'post_sorter_order' ) ) {
			$this->custom = TRUE;
			$sql = apply_filters( 'post_sorter_order', $sql );
			return $sql;
		}

		$direction = mb_strtoupper( get_option( 'post_sorter_direction' ) ) == 'DESC' ? ' DESC' : '';

		return 'CAST(post_sorter.meta_value AS UNSIGNED)' . $direction . ( $sql ? ', ' . $sql : '' );
	}

	
	/**
	 * Saves new position along with post save
	 */
	public function attach_on_save( $post_id ) {
		$post_id = (int) $post_id;

		// No actual post to manage
		if( 0 >= $post_id )
			return;

		// Do nothing on autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		// Verify nonce for expected request
		if( !wp_verify_nonce( isset( $_POST['post_sorter'] ) ? $_POST['post_sorter'] : '', plugin_basename(__FILE__) ) )
			return;

		// Check permissions for modifing posts to do that (post edit screen)
		if( !current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if our plugin allows this user to save
		if( !$this->can_use() )
			return;

		$position = $_POST['post_sorter_inline'];

		if( is_array( $position ) && !empty( $position ) )
			$position = $position[0];

		$position = (int) $position;

		update_post_meta( $post_id, POST_SORTER_META_KEY, $position );
	}

	/**
	 * Adds new menu in WordPress administration
	 */
	public function add_menu() {
		add_menu_page(
			__( 'Post Sorter :: General', 'post_sorter' ), 
			__( 'Post Sorter', 'post_sorter' ),
			'administrator',
			'post-sorter',
			array( $this, 'render_main_menu' )
		);

		add_submenu_page(
			'post-sorter', 
			__( 'Post Sorter :: Permissions', 'post_sorter' ),
			__( 'Permissions', 'post_sorter' ),
			'administrator',
			'post-sorter-permissions',
			array( $this, 'render_permissions_menu' )
		);

		add_submenu_page(
			'post-sorter', 
			__( 'Post Sorter :: About', 'post_sorter' ),
			__( 'About', 'post_sorter' ),
			'read',
			'post-sorter-about',
			array( $this, 'render_about_menu' )
		);
	}

	/**
	 * Renders (outputs) main admin menu - display the page behind the main menu
	 */
	public function render_main_menu() {
		include( plugin_dir_path(__FILE__) . 'page/general.php' );
	}

	/**
	 * Renders (outputs) admin menu for permissions - display the page behind the permissions menu
	 */
	public function render_permissions_menu() {
		include( plugin_dir_path(__FILE__) . 'page/permissions.php' );
	}

	/**
	 * Renders (outputs) about page of the plugin
	 */
	public function render_about_menu() {
		include( plugin_dir_path(__FILE__) . 'page/about.php' );
	}

	// Initial plugin safe-initialization (admin initialized), check for correct state (plugin update), etc.
	public function set_up() {
		// Keep the proper condition on enabled roles (when wrong or plugin update)
		$roles = get_option( 'post_sorter_enabled_roles' );
		if(!$roles || !is_array( $roles ) || empty( $roles ) )
			update_option( 'post_sorter_enabled_roles', array( 'administrator' ) );

		// Load plugin meta-data for runtime routines
		$this->plugin_data = get_plugin_data( __FILE__ );
	}


	/**
	 * Saves settings for the plugin
	 */
	public function save_settings() {
		if ( !current_user_can( 'manage_options' ) )
			return;

		if ( empty( $_POST ) )
			return;

		update_option( 'post_sorter_enabled', isset( $_POST['post_sorter_enabled'] ) );
		update_option( 'post_sorter_direction', isset( $_POST['post_sorter_direction'] ) && mb_strtoupper( $_POST['post_sorter_direction'] ) == 'DESC' ? 'DESC' : ''	);

		$custom_types = isset($_POST['post_sorter_custom_types']) ? $_POST['post_sorter_custom_types'] : ''; // Get custom post types from $_POST or make it empty if no data is received
		$custom_types = sanitize_text_field( trim( str_replace( ' ', '', $custom_types ) ) ); // cleanup, trimming and space removal

		$custom_types = explode( ',', $custom_types ); // To be sure it explodable

		update_option( 'post_sorter_arrows_enabled', isset( $_POST['post_sorter_arrows_enabled'] ) );
		update_option( 'post_sorter_custom_types', implode( ', ', $custom_types ) );
		
		update_option( 'post_sorter_custom_enabled', isset( $_POST['post_sorter_custom_enabled'] ) );
		$own_risk = get_option( 'post_sorter_custom_enabled' );
		
		update_option( 'post_sorter_join_clause', $own_risk ? $this->_sanitize_sql( $_POST['post_sorter_join_clause'] ) : '' );
		update_option( 'post_sorter_order_by_clause', $own_risk ? $this->_sanitize_sql( $_POST['post_sorter_order_by_clause'] ) : '' );
	}

	/**
	 * Saves settings for the plugin
	 */
	public function save_permissions() {
		$roles = ( isset( $_POST['enabled_roles'] ) && is_array( $_POST['enabled_roles'] ) )  ? $_POST['enabled_roles'] : array();

		// Administrator should by put inside always, no matter of selection
		if(!in_array('administrator', $roles))
			$roles[] = 'administrator';

		// To be sure we remove any possible duplicates
		$roles = array_unique($roles);

		// Save the role selection
		update_option( 'post_sorter_enabled_roles', $roles );
	}

	/**
	 * Save a position for a given post. Data for post ID and position is retrieved from $_POST
	 */
	public function save_sort_position() {
		$post_id = (int) $_POST['post_id'];
		$position = (int) $_POST['position'];

		$this->save( $post_id, $position );
	}

	/**
	 * Attaches the new metabox to WordPress (logically)
	 */
	public function add_meta_box() {
		if( $this->can_use() )
			add_meta_box( 'post_sorter', __( 'Post Sorter', 'post_sorter' ), array( $this, 'render_meta_box' ), NULL, 'side', 'core' );
	}

	/**
	 * Renders (outputs) metabox in WordPress edit screen
	 * @param  WP_Post $post the post which is being edited
	 */
	public function render_meta_box($post) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'post_sorter' );

		$val = (int) get_post_meta( $post->ID, POST_SORTER_META_KEY, TRUE );
		
		if( $this->custom ) {
			echo '<div class="post_sorter">' . __( 'Custom sorting is enabled. Factor is ignored.', 'post_sorter' ) . '</div>';
			return;
		}

		$html = '<div class="post_sorter">';

		$html .= __('Position Factor', 'post_sorter') . ': ';

		$html .= '<input name="post_sorter_inline[]" id="post_sorter_inline_' . $post->ID . '" type="text" value="' . $val . '" class="inline_field"
		onkeyup="return post_sorter_saveOnKeyUp(event, this, ' . $post->ID . ');" onblur="post_sorter_save(this, ' . $post->ID . ')" />';

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Performs checkup and cleanup of SQL commands for not proper commands (injections)
	 * @param  string $sql The SQL which is built
	 * @return string      The filtered (cleared) SQL
	 */
	private function _sanitize_sql($sql) {
		$sql = str_ireplace( array( 'UNION', 'SELECT', 'FROM', 'DELETE FROM', 'LOCK ', 'GRANT ', 'DROP ', 'TRUNCATE TABLE', 'USE ', 'SHOW ' ), '', $sql ); // Dangerous statements
		
		return $sql;
	}

	/**
	 * Performs a discrete, single-step movement of the post in the given direction
	 * @param  int $post_id      The ID of the post we are going to move
	 * @param  string $direction The direction we are going to move to [up, down]
	 * @return mixed             FALSE on failure, array with details on success
	 */
	private function _move( $post_id, $direction = 'down' ) {
		global $wpdb;

		$post_id = (int) $post_id;

		if ( 0 >= $post_id || !$this->can_use() )
			return FALSE;

		$sort_direction = mb_strtoupper( get_option( 'post_sorter_direction' ) ) == 'DESC' ? ' DESC' : '';
		$current = (int) get_post_meta( $post_id, POST_SORTER_META_KEY, TRUE );

		$factor = (bool) $sort_direction ^ $direction == 'down';

		$sign_compare = $factor ? '>' : '<';
		$sign_modify = $factor ? '+' : '-';

		$post = get_post( $post_id );

		$sql = "
			SELECT
				pm.meta_value {$sign_modify} 1
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON (p.ID = pm.post_id AND pm.meta_key = '" . POST_SORTER_META_KEY . "')
			WHERE
				p.post_type = '{$post->post_type}'
			AND
				p.post_status = '{$post->post_status}'
			AND
				pm.meta_value {$sign_compare} {$current}
			GROUP BY
				pm.meta_value
			ORDER BY
				(pm.meta_value {$sign_modify} 1) {$sort_direction},
				pm.meta_value{$sort_direction}
			LIMIT 1
		";

		//echo $sql;
				
		$target = (int) $wpdb->get_var( $sql );
		
		//print_r($target);
		//exit;

		return array(
			//'sql' => $sql,
			'target' => $target,
			'factor' => $factor,
			'result' => 0 != $target ? (bool) update_post_meta( $post_id, POST_SORTER_META_KEY, $target ) : FALSE
		);
	}

	/**
	 * Moves a post up with one step
	 * @param  int $post_id The ID of the post to be moved
	 * @return mixed        The result of _move with direction up
	 */
	public function move_up( $post_id ) {
		return $this->_move( $post_id, 'up' );
	}

	/**
	 * Moves a post down with one step
	 * @param  int $post_id The ID of the post to be moved
	 * @return mixed        The result of _move with direction down
	 */
	public function move_down( $post_id ) {
		return $this->_move( $post_id );
	}
	
	/**
	 * Counts  attached hooks to a filter tag
	 * @param  string $tag The filter tag to be count for hooks
	 * @return int         The number of hooks attached to the fitlter
	 */
	private function _count_filter_hooks( $tag ){
		global $wp_filter;
		
		if( !$tag || !isset( $wp_filter[$tag] ) )
			return 0;

		return count( $wp_filter[$tag] );
	}
	
	/**
	 * Get roles available in the system (WordPress installation)
	 * @param  bool  $all Whether to return all roles or only those which are not enabled
	 * @return array      Array with available roles
	 */
	public function get_available_roles( $all = FALSE ) {
		global $wp_roles;

		$enabled_keys = get_option( 'post_sorter_enabled_roles' );
		$all_roles = $wp_roles->roles;
		$editable_roles = apply_filters( 'editable_roles', $all_roles );

		if($all)
			return $editable_roles;

		return array_diff_key( $editable_roles, array_flip( $enabled_keys ) );
	}

	/**
	 * Returns the roles that are enabled to access plugin functionality
	 * @return array The roles which are capable of using the plugin
	 */
	public function get_enabled_roles() {
		$enabled_keys = get_option( 'post_sorter_enabled_roles' );
		$roles = $this->get_available_roles( TRUE );

		return array_intersect_key( $roles, array_flip( $enabled_keys ) );
	}

	private function can_use() {
		$user = wp_get_current_user();
		$enabled = get_option( 'post_sorter_enabled_roles' );
		$enabled = is_array( $enabled ) ? $enabled : array();

		error_reporting(E_ALL);
		ini_set('display_errors', 'yes');

		return 0 < count( array_intersect( $user->roles, $enabled ) );
	}
	
	/* == = = = = = = = = = INERNAL FILTERS = = = = = = = = = = == */
	
	/**
	 * Internal hook for Post Sorter`s join filter tag
	 * @param  string $sql Original join SQL
	 * @return string      Modified join SQL
	 */
	public function internal_join( $sql ) {
		if( !( $join = str_replace( '\\', '', get_option( 'post_sorter_join_clause' ) ) ) )
			return $sql;
		
		return $join . $sql;
	}
	
	/**
	 * Internal hook for Post Sorter`s order filter tag
	 * @param  string $sql Original order by SQL
	 * @return string      Modified order by SQL
	 */
	public function internal_order( $sql ) {
		if( !( $orderby = str_replace( '\\', '', get_option( 'post_sorter_order_by_clause' ) ) ) )
			return $sql;
		
		return $orderby . ( $sql ? ', ' . $sql : '' );
	}

	
	
	/* == = = = = = = = = = = AJAX HANDLERS = = = = = = = = = = == */

	/**
	 * AJAX save handler on post modification
	 * @param  int $post_id  The ID of the post which is modified
	 * @param  int $position The new position to be assigned
	 */
	public function save( $post_id, $position ) {
		$post_id = (int) $post_id;
		$position = (int) $position;

		if ( 0 >= $post_id || 0 == $position )
			return;

		$result = new stdClass();

		$result->result = update_post_meta( $post_id, POST_SORTER_META_KEY, $position );

		if ( $result->result ) {
			$result->post_id = $post_id;
			$result->position = $position;
		}

		die( json_encode( $result ) );
	}

	/**
	 * AJAX handler for post movment (arrows or other)
	 */
	public function move_post() {
		$post_id = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		$direction = isset( $_POST['direction'] ) && mb_strtolower( $_POST['direction'] ) == 'up' ? 'up' : 'down';
		
		if ( 0 >= $post_id )
			die( json_encode( array( 'result' => '0', 'message' => __( 'Invalid post selected.', 'post_sorter' ) ) ) );

		$data = $this->_move( $post_id, $direction );
		$result = new stdClass();

		if ( is_array( $data ) )
			foreach ( $data as $key => $value )
				$result->$key = $value;
		

		die( json_encode( $result ) );
	}

}

// Assigning new object to variable in order to be easy usable from other parts of the system
$post_sorter = new PostSorter();

if ( !isset( $post_sorter ) || !is_object( $post_sorter ) || 'PostSorter' != get_class( $post_sorter ) )
	return;

register_activation_hook( __FILE__ , array( $post_sorter, 'activate' ) );
register_deactivation_hook( __FILE__ , array( $post_sorter, 'deactivate' ) );
?>
