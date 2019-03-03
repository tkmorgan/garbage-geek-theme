<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package garbage-geek
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class('slide'); ?>>
	<?php the_content(); ?>
    <span class='geek-tip-category'>
        <?=tipCategory::get_human_readable_by_post_id( $post->ID );?>
    </span>
</div>