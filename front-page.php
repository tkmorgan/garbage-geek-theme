<?php
/**
 * Front Page Template
 *
 *
 * @package garbage-geek
 */

get_header();
query_posts( array( 'post_type' => 'geek_tip' ) ); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<div class="parallax">
			<div>Garbage Done<br/> Right</div>
		</div>
		<style>
			#main .parallax{
				background:url(<?php echo get_template_directory_uri().'/img/parallax_1921x1302.jpg'; ?>);
				background-size: 100% auto;
				background-attachment: fixed;
				background-repeat: no-repeat;
				height: 44vw;
				width: 100vw;
			}
		</style>
		<a href="/subscribe"><button>Subscribe to Garbage Tips</button></a>
		<a href="/tips"><button>See Garbage Tips Now</button></a>
		<p class='community-text'>
			See How The Garbage Geek<br>
			▼ Community is Doing ▼
		</p>
		<div class="progress-bar">
			<div class="red"></div><div class="green"></div>
		</div>
		<style>
			#main .progress-bar .red {
				display: inline-block;
				width: 80%;/*Add PHP Code*/ 
				height: 6vw;
				background: #800020;
			}
			#main .progress-bar .green {
				display: inline-block;
				width: 20%;/*Add PHP Code*/
				height: 6vw;
				background: #93C840;
			}
		</style>
		<div class="progress-titles">
			<div style="float: left;">
				<h3>Land Fill Tons</h3><br/>
				<span style="color: #800020;">88376</span><!-- PHP code here -->
			</div>
			<div style="float: right;">
				<h3>Recycled Tons</h3><br/>
				<span style="color: #93C840;">786</span><!-- PHP code here -->
			</div>
		</div>
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
get_footer();
