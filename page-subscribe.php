<?php
/**
 * 
 * Template Name: Tips Page
 *
 * @package garbage-geek
 */

get_header();

$query_obj = array( 
	'post_type' => 'geek_tip',
    'posts_per_page' => 1
);

query_posts( $query_obj );
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<!-- Subscriber Signup -->
        <div class='subscriber-signup'>

        </div>
		<form action='.' id='subscriber-signup-form'>
            <div class='subscriber-email-container'>
                <input type="text" id="subscriber-email" />
            </div>
            <div class='subscribe-button-container'>
                <button id='subscribe-button'>Subscribe</button>
            </div>
		</form>

		<?php
		if( have_posts() ):
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				the_post_navigation();

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
		else:
		?>
			<p>No tips today...</p>
		<?
		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<script>
	document
		.querySelector('#subscriber-signup-form')
		.addEventListener(
			'submit',
			function(e) {
                e.preventDefault();
                var email = document.querySelector('#subscriber-email').value;

                var post_vars = {
                    action: 'add_email',
                    email: email
                };
                jQuery.post(
                    'wp-admin/admin-post.php',
                    post_vars,
                    function(response) {
                        console.log( response );
                    }
                );
			}
			)
</script>
<?php
get_sidebar();
get_footer();
