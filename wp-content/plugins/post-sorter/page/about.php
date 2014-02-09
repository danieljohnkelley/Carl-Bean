<?php 
global $post_sorter;
?>
<div class="wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php printf( __( 'About :: %s', 'post_sorter' ), $post_sorter->meta( 'Name' ) ) ?></h2>

	<blockquote><?php echo $post_sorter->meta( 'Description' ) ?></blockquote>

	<p>This plugin is part of my work, done in spare time, more like hobby. Sadly, this time is not enough to create new stuff every day, but it is enoght. :)</p>

	<p>
		If you like my work here or not, please visit the plugin page in <a href="" target="_blank" title="Offical WordPress Site">WordPress.org</a> and rate it:
		<a href="http://wordpress.org/plugins/post-sorter/" target="_blank" title="Post Sorter plugin in WordPress">Post Sorter in WordPress Plugins</a>.
		I know that there may have some issues and bugs, I am aware of them and I am working for resolution, sometimes slowly, sometimes fast, but remember:
		you can always leave a topic or reply in the <a href="http://wordpress.org/support/plugin/post-sorter" target="_blank" title="Official support forum in WordPress.org">plugin support forum</a>
		with suggestions or questions you have.
	</p>

	<p>
		Finally, but not at last, I would like to <b>thank you for using that plugin</b>, that is the real value for me!<br />
		<a href="http://profiles.wordpress.org/rolice" target="_blank" title="My profile in WordPress">rolice</a>
		|
		<a href="https://twitter.com/LyubomirGardev" target="_blank" title="Find me on Twitter">twitter</a>
		&amp;
		<a href="http://intellisys.org/" target="_blank" title="Intelligent Systems">my company</a>
		&amp;
		<a href="http://intellisys.org/contacts" target="_blank" title="Contact me directly">contact me</a>
	</p>

	<p><?php printf( __( 'Version: <b>%s</b>', 'post_sorter' ), $post_sorter->meta( 'Version' ) ) ?></p>
</div>