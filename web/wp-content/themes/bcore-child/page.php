<?php 
/**
 * Default Page Template
 */
get_header(); ?>
<main id="site-main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php new FullWidthContent(); ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>