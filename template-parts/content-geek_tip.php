<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package garbage-geek
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h2 class="entry-title">
		    <?php the_title( '<h2 class="entry-title">', '</h2>' );?>
        </h2>
	</header><!-- .entry-header -->

    <div class='geek-tip-category'>
        <?=tipCategory::get_human_readable_by_post_id( $post->ID );?>
    </div>

</article><!-- #post-<?php the_ID(); ?> -->