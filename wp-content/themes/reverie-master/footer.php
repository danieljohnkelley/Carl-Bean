	</div><!-- Row End -->
</section><!-- Container End -->

<div class="row full-width">
	<?php dynamic_sidebar("Footer"); ?>
</div>

<footer class="row full-width" role="contentinfo">
  <!--<div class="small-12 large-4 columns">
		<p>&copy; <?php echo date('Y'); ?>. <?php _e('Crafted on','reverie'); ?> <a href="http://themefortress.com/reverie/" rel="nofollow" title="Reverie Framework">Reverie</a>.</p>
	</div> -->
	
<!-- 
		<?php wp_nav_menu(array('theme_location' => 'utility', 'container' => false, 'menu_class' => 'inline-list right')); ?>
	</div>-->
   
   <div class="small-12 large-8 columns">
   <?php
$postlist = get_posts('sort_column=menu_order&sort_order=asc');
$posts = array();
foreach ($postlist as $post) {
   $posts[] += $post->ID;
}

$current = array_search(get_the_ID(), $posts);
$prevID = $posts[$current-1];
$nextID = $posts[$current+1];
?>

<div class="footertime">
<a href="<?php echo get_permalink($prevID); ?>"
  title="<?php echo get_the_title($prevID); ?>">Previous Project</a>
<a href="<?php echo get_permalink($nextID); ?>" 
 title="<?php echo get_the_title($nextID); ?>">Next Project</a>
</div>
   </div>
   


   
</footer>

<?php wp_footer(); ?>

<script>
	(function($) {
		$(document).foundation();
	})(jQuery);
</script>
	
</body>
</html>