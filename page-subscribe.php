<?php
/**
 * 
 * Template Name: Subscribe Page
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
                <form action='.' id='subscriber-signup-form'>
                    <div class='subscriber-email-container'>
                        <label style="display:block;">Email</label>
                        <input type="text" id="subscriber-email"/>
                    </div>
                    <div class='subscribe-button-container'>
                        <button id='subscribe-button'>Subscribe</button>
                    </div>
                </form>
            </div>
            <div class='subscriber-signup-result' id='subscriber-signup-loading' style="display:none;">
                <img style="margin:auto;display:block;"src="<?= get_template_directory_uri() ?>/images/Loading_2.gif" >
            </div>
            <div class='subscriber-signup-result' id='subscriber-signup-success' style="display:none;"></div>
            <div class='subscriber-signup-result' id='subscriber-signup-error' style="display:none;">error</div>
            <div class='slider'>
                <?php
                    if( have_posts() ):
                        while ( have_posts() ) :
                                the_post();                
                                get_template_part( 'template-parts/content', get_post_type() );?>
                            <?php
                        endwhile; // End of the loop.
                    else:?>
                        <p>No tips today...</p><?php
                    endif;
                ?>
            </div>
		</main><!-- #main -->
    </div><!-- #primary -->
<style>
    .bx-wrapper{
        -moz-box-shadow: unset !important;
        -webkit-box-shadow: unset !important;
        box-shadow: unset !important;
        border: unset !important;
        background: #2D303E !important;
        margin-top: 10vw;
    }
    #main .slider .slide{
        text-align: center;
        line-height: 8vw;
        color: #FFF;
        padding-right: 1em;
    }
    #main .slider .slide h2{
        display: block;
        height: 24vw;
    }
    #main .slider .slide span{
        display: block;
        font-size: 5vw;
        line-height: 10vw;
        font-weight:500;
        text-align: center;
        color: #93C840;
        margin-bottom: 3vw;
    }
</style>
<script>
    jQuery(document).ready(function($){
        $('#main .slider').bxSlider({
            pager : false,
            controls: false,
            auto: true,
            pause: 4000
        });
        document.querySelector('#subscriber-signup-form').addEventListener(
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
                    },
                    'json'
                );
			}
        );
    });
</script>
<?php
get_footer();
