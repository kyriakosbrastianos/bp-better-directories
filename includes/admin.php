<?php

class BPBD_Admin {
	/**
	 * PHP 4 constructor
	 *
	 * @package BP Better Directories
	 * @since 1.0
	 */
	function bpbd_admin() {
		$this->__construct();
	}

	/**
	 * PHP 5 constructor
	 *
	 * @package BP Better Directories
	 * @since 1.0
	 */	
	function __construct() {
		// Add the admin menu
		// todo: change this when BP 1.3 is out
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'add_admin_page' ) );
	}
	
	function add_admin_page() {
		$plugin_page = add_submenu_page( 'bp-general-settings', __( 'BP Better Directories', 'bpbd' ), __( 'BP Better Directories', 'bpbd' ), 'manage_options', 'bpbd', array( $this, 'admin_page_markup' ) );
	
		add_action( "admin_print_scripts-$plugin_page", array( $this, 'admin_scripts' ) );
		add_action( "admin_print_styles-$plugin_page", array( $this, 'admin_styles' ) );
	}
	
	function admin_page_markup() {
		$this->catch_form_save();
		
		$saved_fields = get_blog_option( BP_ROOT_BLOG, 'bpdb_fields' );
		
		$groups = BP_XProfile_Group::get( array(
			'fetch_fields' => true
		));

		?>
		<form action="" method="post" id="bpbd-form">
		
		<ul>
		<?php foreach ( $groups as $group ) : ?>
			<li>
				<h4><?php echo esc_html( $group->name ) ?></h4>
				
				<?php if ( !empty( $group->fields ) ) : ?>
					<ul>
					<?php foreach ( $group->fields as $field ) : ?>
						<?php $checked = array_search( $field->id, $saved_fields ) !== false ? 'checked="checked" ' : ''; ?>
						
						<li>
							<input type="checkbox" name="fields[<?php echo $field->id ?>]" id="field-<?php echo $field->id ?>" class="field field-group-<?php $group->id ?>" <?php echo $checked ?>/> <?php echo esc_html( $field->name ) ?>
						</li>
					<?php endforeach ?>
					</ul>
				<?php endif ?>
			</li>
		<?php endforeach ?>
		</ul>
		
		<input type="submit" name="bpbd_submit" class="button-primary" value="<?php _e( 'Save', 'bpbd' ) ?>" />
		
		</form>
		<?php

	}
	
	function catch_form_save() {
		if ( isset( $_POST['bpbd_submit'] ) ) {
			$this->save();
		}
	}
	
	function save() {
		$fields = array();
		
		if ( !empty( $_POST['fields'] ) ) {
			foreach( $_POST['fields'] as $field_id => $on ) {
				$fields[] = $field_id;	
			}
		}
		
		update_blog_option( BP_ROOT_BLOG, 'bpdb_fields', $fields );
	}
	
	function admin_scripts() {
	
	}
	
	function admin_styles() {
	
	}
}

?>