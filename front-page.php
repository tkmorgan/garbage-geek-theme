<?php
/**
 * Front Page Template
 *
 *
 * @package garbage-geek
 */

get_header();

function total_weight_iterator($carry, $item) {
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

function get_total_landfill_recycling( $commodity_recycling_centers, $recyclable_type ) {
	$rv = 0;
	foreach( $commodity_recycling_centers['centers'] as $center ) {
		$idx = find_element_idx_by_slug($center['fields'], $recyclable_type);
		
		$rv+= (int)$center['fields'][$idx]['value'];
	}

	return (float)$rv;
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


	$tmpCenter['total-pounds'] = array_reduce( $tmpCenter['fields'], 'total_weight_iterator' );
	$tmpCenter['total-tons'] = (float)$tmpCenter['total-pounds'] / 2000;
	$commodity_recycling_centers['centers'][] = $tmpCenter;
	// the_post_navigation();
	// If comments are open or we have at least one comment, load up the comment template.
	// if ( comments_open() || get_comments_number() ) :
		// comments_template();
	// endif;
endwhile; // End of the loop.

$commodity_recycling_centers['grand-total-in-pounds'] = 0;
$commodity_recycling_centers['grand-total-in-tons'] = 0;
foreach( recyclingCenterTotals::$recyclable_types as $slug => $name ) {
	$commodity_recycling_centers["drop-off-total-${slug}"] = get_drop_off_center_total($commodity_recycling_centers, $slug);
	$commodity_recycling_centers["total-commodity-recycling-${slug}"] = get_total_commodity_recycling($commodity_recycling_centers, $slug);
	$commodity_recycling_centers['grand-total-in-pounds'] += $commodity_recycling_centers["total-commodity-recycling-${slug}"] * 2000;
	$commodity_recycling_centers['grand-total-in-tons'] += $commodity_recycling_centers["total-commodity-recycling-${slug}"];
}





query_posts( array( 'post_type' => 'landfill_classes', 'posts_per_page' => -1 ) ); 
$landfills = [];
$landfills['centers'] = [];
while ( have_posts() ) :
	the_post();
	global $post;
	
	$tmpCenter = [];
	$tmpCenter['fields'] = [];
	$tmpCenter['id'] = $post->ID;
	$tmpCenter['title'] = $post->post_title;
	$landfillType = get_post_meta( $post->ID, 'landfill-type', true );
	$tmpCenter['landfill-type'] = $landfillType;
	// pretend you didn't see this
	foreach( landfillClasses::$waste_types as $slug => $name ) {
		// print_r( get_post_meta( $post->id, '', true ) );

		// print_r( $post );

		$tmpFields = [];
		$tmpFields['slug'] = $slug;
		$tmpFields['value'] = get_post_meta( $post->ID, $slug, true );
		$tmpFields['name'] = $name;
		$tmpCenter['fields'][] = $tmpFields;
	}


	$tmpCenter['total-tons'] = array_reduce( $tmpCenter['fields'], 'total_weight_iterator' );
	$landfills['centers'][$landfillType] = $tmpCenter;
endwhile; // End of the loop.

$landfills['grand-total'] = 0;
foreach( landfillClasses::$waste_types as $slug => $name ) {
	$landfills["total-landfill-${slug}"] += get_total_landfill_recycling($landfills, $slug);
	$landfills['grand-total'] += $landfills["total-landfill-${slug}"];
}









query_posts( array( 'post_type' => 'swmf', 'posts_per_page' => -1 ) ); 
$swmfs = [];
$swmfs['centers'] = [];
while ( have_posts() ) :
	the_post();
	global $post;
	
	$tmpCenter = [];
	$tmpCenter['fields'] = [];
	$tmpCenter['id'] = $post->ID;
	$tmpCenter['title'] = $post->post_title;
	$smwfType = get_post_meta( $post->ID, 'landfill-type', true );
	$tmpCenter['smwf-type'] = $smwfType;

	// pretend you didn't see this
	foreach( swmfs::$waste_types as $slug => $name ) {
		// print_r( get_post_meta( $post->id, '', true ) );

		// print_r( $post );

		$tmpFields = [];
		$tmpFields['slug'] = $slug;
		$tmpFields['value'] = get_post_meta( $post->ID, $slug, true );
		$tmpFields['name'] = $name;
		$tmpCenter['fields'][] = $tmpFields;
	}


	$tmpCenter['total-tons'] = array_reduce( $tmpCenter['fields'], 'total_weight_iterator' );
	$swmfs['centers'][$smwfType] = $tmpCenter;
endwhile; // End of the loop.

$swmfs['grand-total'] = 0;
foreach( swmfs::$waste_types as $slug => $name ) {
	$swmfs["total-smwf-${slug}"] += get_total_landfill_recycling($swmfs, $slug);
	$swmfs['grand-total'] += $swmfs["total-smwf-${slug}"];
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

$total_wastestream = [];

$cr = $commodity_recycling_centers['grand-total-in-tons'];
$swmfr = $swmfs['centers']['recycled']['total-tons'];
$mr = $mulch['total'];
$diverted = $landfills['centers']['diverted']['total-tons'] + $swmfs['centers']['diverted']['total-tons'];
$garbage = $landfills['centers']['garbage']['total-tons'];
$total_waste = $cr + $swmfr + $mr + $diverted + $garbage;

function asPercent( $numerator, $denominator ) {
	$fraction = (float)$numerator/$denominator;
	return round((float)$fraction * 100 ) . '%';
}
$total_wastestream['breakdown']['commodity-recycled'] = [
	'total' => $cr,
	'percent' => asPercent( $cr, $total_waste ) 
];

$total_wastestream['breakdown']['swmf-recycled'] = [
	'total' => $swmfr,
	'percent' => asPercent( $swmfr, $total_waste )
];

$total_wastestream['breakdown']['mulch-recycled'] = [
	'total' => $mr,
	'percent' => asPercent( $mr, $total_waste )
];

$total_wastestream['breakdown']['diverted'] = [
	'total' => $diverted,
	'percent' => asPercent( $diverted, $total_waste )
];

$total_wastestream['breakdown']['garbage'] = [
	'total' => $garbage,
	'percent' => asPercent( $garbage, $total_waste )
];

$total_wastestream['total'] = [
	'total' => $total_waste,
	'percent' => asPercent( $total_waste, $total_waste )
];
/*
echo "<pre>Commodity Recycline Centers
========================================";
print_r( $commodity_recycling_centers );
echo "</pre>";


echo "<pre>Landfills
========================================";
print_r( $landfills );
echo "</pre>";

echo "<pre>swmfs
========================================";
print_r( $swmfs );
echo "</pre>";

echo "<pre>Mulch
========================================";
print_r( $mulch );
echo "</pre>";


echo "<pre>Total Wastestream
========================================";
print_r( $total_wastestream );
echo "</pre>";
*/
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
		<?php
			$landfillTot = $landfills['grand-total'];
			$recycleTot = (int)($total_wastestream['breakdown']['commodity-recycled']['total'] + $total_wastestream['breakdown']['swmf-recycled']['total'] + $total_wastestream['breakdown']['mulch-recycled']['total']);
			$superTot = $landfillTot + $recycleTot;//Total of Land Fill + Recycle
			$perCntLandFill = (string)(($landfillTot / $superTot) * 100);
			$perCntRecycle = (string)(($recycleTot / $superTot) * 100);
		?>
		<div class="progress-bar">
			<div class="red"></div><div class="green"></div>
		</div>
		<div class="progress-titles">
			<div style="float: left;">
				<h3>Land Fill Tons</h3><br/>
				<span style="color: #800020;"><?php echo $landfillTot; ?></span><!-- PHP code here -->
			</div>
			<div style="float: right;">
				<h3>Recycled Tons</h3><br/>
				<span style="color: #93C840;"><?php echo $recycleTot; ?></span><!-- PHP code here -->
			</div>
		</div>
		<canvas id="pi-chart"></canvas>
		<script>
			var ctx = document.getElementById('pi-chart').getContext('2d');
			var chart = new Chart(ctx, {
				// The type of chart we want to create
				type: 'pie',
				// The data for our dataset
				data: {
					labels: ["Comodity Recycled", "SWMF Recycled", "Mulch Recycled"/*green*/, "C&D (class3) Diverted"/*yelllow*/, "Garbage (Class 1)"/*red*/],
					datasets: [{
						label: "Where your Garbage is going",
						backgroundColor: ['green', 'green', 'green', 'yellow', 'red'],
						data: [3, 10, 5, 6, 7]
					}]
				},

				// Configuration options go here
				options: {}
			});
		</script>
		<style>
			#main .progress-bar .red {
				display: inline-block;
				width: <?php echo $perCntLandFill; ?>%;
				height: 6vw;
				background: #800020;
			}
			#main .progress-bar .green {
				display: inline-block;
				width: <?php echo $perCntRecycle; ?>%;
				height: 6vw;
				background: #93C840;
			}
		</style>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
// get_sidebar();
get_footer();
