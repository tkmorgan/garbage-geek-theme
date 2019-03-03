<?php
/**
 * 
 * Template Name: Tips Page
 *
 * @package garbage-geek
 */

get_header();

$cat = isset( $_GET['tip-category'] ) ? $_GET['tip-category'] : false;
if( $cat ) {
	$query_obj = array( 
		'post_type' => 'geek_tip',
		'meta_key' => 'tip_category',
		'meta_value' => $cat
	);
} else {
	$query_obj = array( 
		'post_type' => 'geek_tip'
	);
}

query_posts( $query_obj );
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<!-- Categories dropdown -->
		<form action='.' id='tip-category-form'>
			<select id='tip_category_dropdown' name='tip-category'>
				<option value=''>Categories</option>
				<?php foreach( tipCategory::$categories as $machine_name => $human_name ):?>
					<option 
						value='<?=$machine_name?>' 
						<?=( $cat == $machine_name ) ? 'SELECTED' : ''?>
					>
						<?=$human_name?>
					</option>
				<?php endforeach;?>
			</select>
		</form>
		<style>
			#main{
				margin-top: 12vw;
			}
			#main form Select{
				width: 80vw;
				height: 6vw;
				margin: auto;
				margin-top: 17vw;
			}
		</style>
		<?php
		if( have_posts() ):
			while ( have_posts() ) :
				the_post();
				get_template_part('template-parts/content', get_post_type());
			endwhile; // End of the loop.
		else:
		?>
			<p>No tips today...</p>
		<?php
		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<script>
	document
		.querySelector('#tip_category_dropdown')
		.addEventListener(
			'change',
			function() {
				document.querySelector('#tip-category-form').submit();
			}
			)
</script>
<?php
get_footer();