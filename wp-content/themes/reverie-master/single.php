<?php get_header(); ?>

<!-- Row for main content area -->
	<div class="small-12 large-8 columns" role="main">
	
	<?php /* Start loop */ ?>
	<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<div class="entry-content2">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; // End the loop ?>
   
   
<?php get_footer(); ?>

	</div>
	