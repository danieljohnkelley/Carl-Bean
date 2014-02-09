<?php
global $post_sorter;

if( !empty( $_POST ) )
	$post_sorter->save_permissions();

$available = $post_sorter->get_available_roles();
$enabled = $post_sorter->get_enabled_roles();
?>
<div class="wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php _e( 'Permissions :: Post Sorter', 'post_sorter' ) ?></h2>

	<p><?php _e( 'Set access to plugin functionality for users by their role.', 'post_sorter' ) ?></p>
	<p><?php _e( '<b>Note:</b> Administrator will be always enabled.', 'post_sorter' ) ?></p>

	<form id="post_sorter_settings" action="" method="post" class="post_sorter_form">
		<div class="left multi-select">
			<label for="available_roles"><?php _e( 'Available Roles', 'post_sorter' ) ?>:</label>
			<select name="available_roles[]" id="available_roles" size="10" multiple>
			<?php foreach($available as $key => $role): ?>
				<option value="<?php echo $key ?>"><?php echo $role['name'] ?></option>
			<?php endforeach ?>
			</select>
		</div>

		<div class="toggle multi-select">
			<input type="button" class="button-primary" value="&raquo;" onclick="enable_roles()" />
			<input type="button" class="button" value="&laquo;" onclick="disable_roles()" />
		</div>

		<div class="right multi-select">
			<label for="enabled_roles"><?php _e( 'Enabled Roles', 'post_sorter' ) ?>:</label>
			<select name="enabled_roles[]" id="enabled_roles" size="10" multiple>
			<?php foreach($enabled as $key => $role): ?>
				<option value="<?php echo $key ?>"><?php echo $role['name'] ?></option>
			<?php endforeach ?>
			</select>
		</div>

		<div class="controller">
			<a href="#" class="button-primary" onclick="jQuery('#enabled_roles option').attr('selected', 'selected'); jQuery('#post_sorter_settings').trigger('submit'); return false;"><?php _e( 'Save', 'post_sorter' ) ?></a>
			<a href="#" class="button" onclick="jQuery('#post_sorter_settings').trigger('reset'); return false;"><?php _e( 'Cancel', 'post_sorter' ) ?></a>
		</div>
	</form>
</div>