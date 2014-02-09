<?php 
// Prevents accidental unistall
if( !defined( 'WP_UNINSTALL_PLUGIN' ) || !WP_UNINSTALL_PLUGIN ) exit;

delete_option( 'post_sorter_enabled' );
delete_option( 'post_sorter_direction' );
delete_option( 'post_sorter_enabled_roles' );
delete_option( 'post_sorter_metakey' );

delete_option( 'post_sorter_arrows_enabled' );
delete_option( 'post_sorter_custom_types' );

delete_option( 'post_sorter_custom_enabled' );
delete_option( 'post_sorter_join_clause' );
delete_option( 'post_sorter_order_by_clause' );

delete_post_meta_by_key( 'post_sorter_order' );
?>