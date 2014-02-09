<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @subpackage Reverie
 * @since Reverie 4.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		 <h2><a href="<?php the_permalink(); ?>"></a></h2>
		
	</header>
	<div class="entry-content2">
		<?php the_content(__('Continue reading...', 'reverie')); ?>
	</div>
	<hr />
</article>