<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package garbage-geek
 */

?>
		</div><!-- #content -->
		<footer id="colophon" class="site-footer">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-2',
					'menu_id'        => 'Footer_Menu',
				) );
			?>
		</footer><!-- #colophon -->
	</div><!-- #page -->
	<nav id="site-navigation" class="main-navigation">
		<div class="close"></div>
		<?php
		wp_nav_menu( array(
			'theme_location' => 'menu-1',
			'menu_id'        => 'primary-menu',
		) );
		?>
		<style>
			body #site-navigation .close{
				background:url(<?php echo get_template_directory_uri().'/img/x-circle_63x63.png'; ?>);
				background-size: contain;
				background-repeat: no-repeat;
				width: 8vw;
				height: 8vw;
				margin-left: 66vw;
				margin-right: 2vw;
				margin-top: 2.5vw;
			}
			body #site-navigation .close:hover{
				cursor: pointer;
			}
		</style>
	</nav><!-- #site-navigation -->
<?php wp_footer(); ?>
</body>
</html>
