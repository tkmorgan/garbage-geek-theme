<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package garbage-geek
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<header id="masthead" class="site-header">
		<?php the_custom_logo(); ?>
		<div class='menu'></div>
		<style>
			html {
				margin-top:unset !important;
			}
			body #page #masthead .menu{
				background:url(<?php echo get_template_directory_uri().'/img/Hamburger_icon_x53x53.png'; ?>);
				background-size: contain;
				background-repeat: no-repeat;
				justify-self: flex-end;
				height: 100%;
				width: 8vw;
				height: 8vw;
				margin-left: 33vw;
				margin-right: 2vw;
			}
			body #page #masthead .menu:hover{
				cursor: pointer;
			}
		</style>
		<script>
			jQuery(document).ready(function($){
				$('body #page #masthead .menu').click(function(){
					$('body #page').css('transform', 'translateX(-80vw)');
					$('body #site-navigation').css('transform', 'translateX(-100vw)');
				});
				$('body #site-navigation .close').click(function(){
					$('body #page').css('transform', 'translateX(0vw)');
					$('body #site-navigation').css('transform', 'translateX(0vw)');
				});
			});
		</script>
	</header><!-- #masthead -->
	<?php
		if ( is_front_page() ) : ?>
			<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
	 	<?php
		endif;
	?>
	<div id="content" class="site-content">
