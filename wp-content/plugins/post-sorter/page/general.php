<?php
global $post_sorter;

if( !empty( $_POST ) )
	$post_sorter->save_settings();
?>

<div id="icon-plugins" class="icon32"></div>

<div class="wrap">
	<h2><?php _e( 'Post Sorter', 'post_sorter' ) ?></h2>
	
	<form id="post_sorter_settings" action="" method="post" class="post_sorter_form">
		<h3><?php _e( 'Basic Settings', 'post_sorter' ) ?></h3>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="post_sorter_enabled"><?php _e( 'Enable Post Sorter', 'post_sorter' ) ?></label>:</th>
					<td>
						<input name="post_sorter_enabled" id="post_sorter_enabled" type="checkbox" <?php checked( 1, get_option( 'post_sorter_enabled' ) )?> />
						<span class="hint"><?php _e( 'The plugin will only work on the client side, if it is enabled', 'post_sorter' ) ?></span>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><label for="post_sorter_direction_asc"><?php _e( 'Sort Ascending', 'post_sorter' ) ?></label>:</th>
					<td>
						<input name="post_sorter_direction" id="post_sorter_direction_asc" type="radio" value="ASC" <?php checked( '', get_option( 'post_sorter_direction' ) )?> />
						<span class="hint"><?php _e( 'If selected all posts will be sorted in ascending order', 'post_sorter' ) ?></span>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><label for="post_sorter_direction_desc"><?php _e( 'Sort Descending', 'post_sorter' ) ?></label>:</th>
					<td>
						<input name="post_sorter_direction" id="post_sorter_direction_desc" type="radio" value="DESC" <?php checked( 'DESC', get_option( 'post_sorter_direction' ) )?> />
						<span class="hint"><?php _e( 'If selected all posts will be sorted in descending order', 'post_sorter' ) ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<br class="clear" />
		<br class="clear" />
		
		<h3><?php _e( 'Advanced', 'post_sorter' ) ?></h3>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="post_sorter_arrows_enabled"><?php _e( 'Enable Arrows', 'post_sorter' ) ?></label>:</th>
					<td>
						<input name="post_sorter_arrows_enabled" id="post_sorter_arrows_enabled" type="checkbox" <?php checked( 1, get_option( 'post_sorter_arrows_enabled' ) )?> />
						<span class="hint"><?php _e( 'Allows quick sorting with arrow buttons in the sorting column', 'post_sorter' ) ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="post_sorter_custom_types"><?php _e( 'Custom post types', 'post_sorter' ) ?></label>:</th>
					<td>
						<input name="post_sorter_custom_types" id="post_sorter_custom_types" type="text" value="<?php echo get_option( 'post_sorter_custom_types' ) ?>" /><br />
						<span class="hint"><?php _e( 'Enables sorting on the Sorting column for those types.<br />However, the sort column is shown on every post type. Example: <b>custom1, custom2</b>', 'post_sorter' ) ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<br class="clear" />
		<br class="clear" />
		
		<h3><?php _e( 'Expert Settings', 'post_sorter' ) ?></h3>
		
		<p><b>Note</b>: From this section you will be able to customize sorting for your needs. However, good understanding of SQL and WordPress database architecture is needed.</p>
		
		<p>
			<b>Important:</b>
			Before you do anything in this section, you have to unlock it by declaring you are doing any changes by your own risk and you understand
			that any mistake here could bring your site down.
		</p>
		
		<p>
			<b>Important:</b>
			If any error occur you should be still able to reach this page and to remote any custom query statements below. This should return you site to previous working state.
		</p>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="post_sorter_custom_enabled"><?php _e( 'I do changes at my own risk', 'post_sorter' ) ?></label>:</th>
					<td>
						<input name="post_sorter_custom_enabled" id="post_sorter_custom_enabled" type="checkbox" <?php checked( 1, get_option( 'post_sorter_custom_enabled' ) )?> />
						<span class="hint"><?php _e( 'You declare by checking this checkbox that you understand the risks and you are doing changes on your own risk.', 'post_sorter' ) ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="post_sorter_join_clause"><?php _e( 'JOIN Clause', 'post_sorter' ) ?></label>:</th>
					<td valign="top">
						<textarea name="post_sorter_join_clause" id="post_sorter_join_clause" rows="8" cols="80"><?php echo str_replace( '\\', '', get_option( 'post_sorter_join_clause' ) ) ?></textarea>
						<br />
						<span class="hint"><?php _e( 'Your custom join clause that would be applied on the post list SQL query.', 'post_sorter' ) ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="post_sorter_order_by_clause"><?php _e( 'ORDER BY Clause', 'post_sorter' ) ?></label>:</th>
					<td valign="top">
						<textarea name="post_sorter_order_by_clause" id="post_sorter_order_by_clause" rows="8" cols="80"><?php echo str_replace( '\\', '', get_option( 'post_sorter_order_by_clause' ) ) ?></textarea>
						<br />
						<span class="hint"><?php _e( 'Your custom order by clause that would be applied on the post list SQL query.', 'post_sorter' ) ?></span>
					</td>
				</tr>
				<tr valign="bottom">
					<th scope="row">&nbsp;</th>
					<td>
						<div class="controller">
							<a href="#" class="button-primary" onclick="jQuery('#post_sorter_settings').trigger('submit'); return false;"><?php _e( 'Save', 'post_sorter' ) ?></a>
							<a href="#" class="button" onclick="jQuery('#post_sorter_settings').trigger('reset'); return false;"><?php _e( 'Cancel', 'post_sorter' ) ?></a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {		
		if(!jQuery("#post_sorter_custom_enabled").is(":checked")) {
			jQuery("#post_sorter_join_clause").attr("readonly", "readonly");
			jQuery("#post_sorter_order_by_clause").attr("readonly", "readonly");
			jQuery("#post_sorter_join_clause").val();
			jQuery("#post_sorter_order_by_clause").val();
		}
		
		jQuery("#post_sorter_custom_enabled").change(function() {
			if(!jQuery("#post_sorter_custom_enabled").is(":checked")) {
				jQuery("#post_sorter_join_clause").attr("readonly", true);
				jQuery("#post_sorter_order_by_clause").attr("readonly", true);
				
				jQuery("#post_sorter_join_clause").val("");
				jQuery("#post_sorter_order_by_clause").val("");
				
				return;
			}
			
			jQuery("#post_sorter_join_clause").removeAttr("readonly");
			jQuery("#post_sorter_order_by_clause").removeAttr("readonly");
		});
	});
</script>