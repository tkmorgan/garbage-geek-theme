<?php
/**
 * Front Page Template
 *
 *
 * @package garbage-geek
 */

get_header();
query_posts( array( 'post_type' => 'geek_tip' ) );
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<?php
		// while ( have_posts() ) :
			// the_post();
			// the_post_navigation();
			// If comments are open or we have at least one comment, load up the comment template.
			// if ( comments_open() || get_comments_number() ) :
				// comments_template();
			// endif;
		// endwhile; // End of the loop.
		?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
// get_sidebar();
// get_footer();
