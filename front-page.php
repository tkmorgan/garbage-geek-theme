<?php
/**
 * Front Page Template
 *
 *
 * @package garbage-geek
 */

get_header();

function total_pound_iterator($carry, $item) {
	if( !$carry ) return (int)$item['value'];
	return $carry + (int)$item['value'];
}
function find_element_idx_by_slug($elements, $slug) {
	for( $i = 0; $i < count( $elements ); $i++ )
		if( $elements[$i]['slug'] == $slug )
			return $i;
}

function get_drop_off_center_total( $commodity_recycling_centers, $recyclable_type ) {
	$rv = 0;
	foreach( $commodity_recycling_centers['centers'] as $center ) {
		if( $center['center-type'] == 'drop-off-centers' ) {
			$idx = find_element_idx_by_slug($center['fields'], $recyclable_type);
			
			$rv+= (int)$center['fields'][$idx]['value'];
		}
	}

	return $rv;
}


function get_total_commodity_recycling( $commodity_recycling_centers, $recyclable_type ) {
	$rv = 0;
	foreach( $commodity_recycling_centers['centers'] as $center ) {
		$idx = find_element_idx_by_slug($center['fields'], $recyclable_type);
			
		$rv+= (int)$center['fields'][$idx]['value'];
	}

	return (float)$rv / 2000;
}
?>
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

		query_posts( array( 'post_type' => 'mulch', 'posts_per_page' => -1 ) );
		if( have_posts() ):
			the_post();
			global $post;

			$mulch = [];
			$mulch['fields'] = []; 
			
			$tmp = [];
			$tmp['slug'] = 'leaves';
			$tmp['value'] = get_post_meta( $post->ID, 'leaves', true );
			$tmp['name'] = 'Leaves';
			$mulch['fields'][] = $tmp;			
			
			$tmp = [];
			$tmp['slug'] = 'brush';
			$tmp['value'] = get_post_meta( $post->ID, 'brush', true );
			$tmp['name'] = 'Brush';
			$mulch['fields'][] = $tmp;

			$mulch['total'] = $mulch['fields'][0]['value'] + $mulch['fields'][1]['value'];
		endif;

		query_posts( array( 'post_type' => 'rc_totals', 'posts_per_page' => -1 ) ); 
		$commodity_recycling_centers = [];
		$commodity_recycling_centers['centers'] = [];
		while ( have_posts() ) :
			the_post();
			global $post;
			
			$tmpCenter = [];
			$tmpCenter['fields'] = [];
			$tmpCenter['id'] = $post->ID;
			$tmpCenter['title'] = $post->post_title;
			$tmpCenter['center-type'] = get_post_meta( $post->ID, 'center-type', true );
			// pretend you didn't see this
			foreach( recyclingCenterTotals::$recyclable_types as $slug => $name ) {
				// print_r( get_post_meta( $post->id, '', true ) );

				// print_r( $post );

				$tmpFields = [];
				$tmpFields['slug'] = $slug;
				$tmpFields['value'] = get_post_meta( $post->ID, $slug, true );
				$tmpFields['name'] = $name;
				$tmpCenter['fields'][] = $tmpFields;
			}


			$tmpCenter['total-pounds'] = array_reduce( $tmpCenter['fields'], 'total_pound_iterator' );
			$tmpCenter['total-tons'] = (float)$tmpCenter['total-pounds'] / 2000;
			$commodity_recycling_centers['centers'][] = $tmpCenter;
			// the_post_navigation();
			// If comments are open or we have at least one comment, load up the comment template.
			// if ( comments_open() || get_comments_number() ) :
				// comments_template();
			// endif;
		endwhile; // End of the loop.

		$commodity_recycling_centers['grand-total-in-pounds'] = 0;
		$commodity_recycling_centers['grand-total-in-pounds'] = 0;
		foreach( recyclingCenterTotals::$recyclable_types as $slug => $name ) {
			$commodity_recycling_centers["drop-off-total-${slug}"] = get_drop_off_center_total($commodity_recycling_centers, $slug);
			$commodity_recycling_centers["total-commodity-recycling-${slug}"] = get_total_commodity_recycling($commodity_recycling_centers, $slug);
			$commodity_recycling_centers['grand-total-in-pounds'] += $commodity_recycling_centers["total-commodity-recycling-${slug}"] * 2000;
			$commodity_recycling_centers['grand-total-in-tons'] += $commodity_recycling_centers["total-commodity-recycling-${slug}"] * 2000;
		}

		// group commodity recycling centers by type
		$commodity_recycling_centers['data-grouped-by-type'] = [];
		foreach( $commodity_recycling_centers['centers'] as $center ) {
			$center_type = $center['center-type'];
			if( !isset( $commodity_recycling_centers['data-grouped-by-type'][$center_type] ) ) {
				$commodity_recycling_centers['data-grouped-by-type'][$center_type] = [];
			}
			$commodity_recycling_centers['data-grouped-by-type'][$center_type][] = $center;
		}

		unset($commodity_recycling_centers['centers']);

		echo "<pre>Commodity Recycline Centers
========================================";
		print_r( $commodity_recycling_centers );
		echo "</pre>";


		
		echo "<pre>Mulch
========================================";
		print_r( $mulch );
		echo "</pre>";
?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
// get_sidebar();
get_footer();
