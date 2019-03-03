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
    'posts_per_page' => 10
);

query_posts( $query_obj );
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<!-- Subscriber Signup -->
        <div class='subscriber-signup-form'>
            Email
            <form action='.' id='subscriber-signup-form'>
                <div class='subscriber-email-container'>
                    <input type="text" id="subscriber-email" />
                </div>
                <div class='subscribe-button-container'>
                    <button id='subscribe-button'>Subscribe</button>
                </div>
            </form>
        </div>

        <div class='subscriber-signup-result' id='subscriber-signup-loading'>
            <image src="<?=get_template_directory_uri()?>/images/Loading_2.gif">
        </div>

        <div class='subscriber-signup-result' id='subscriber-signup-success'>

        </div>
        <div class='subscriber-signup-result' id='subscriber-signup-error'>
error
        </div>


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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
                $('#subscriber-signup-form').hide();
                $('#subscriber-signup-loading').show();
                
                $.post(
                    '/wp-admin/admin-post.php',
                    post_vars,
                    function(response) {
                        $('#subscriber-signup-loading').hide();
                        if( !isNaN(response) ) {
                            var msg = $('#subscriber-signup-success');
                            document.querySelector('#subscriber-signup-success').innerHTML = `
                            <p>Thank you. You have been signed up!</p>
                            `;
                            msg.show();
                        } else {
                            $('#subscriber-signup-error').show();
                            document.querySelector('#subscriber-signup-error').innerHTML = `
                            <p>${response[0].msg[0]}</p>
                            `;
                            
                        }
                        console.log( response );
                    },
                    'json'
                );
			}
			)
</script>
<?php
get_sidebar();
get_footer();
