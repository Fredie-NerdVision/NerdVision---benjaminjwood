<?php
/**
 * Single page template.
 *
 * @package bjw-studio
 */

get_header();
?>
<section class="section surface-ink">
	<div class="shell shell--narrow">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<div class="section-head reveal">
				<h2><?php the_title(); ?></h2>
				<div class="rule"></div>
			</div>
			<div class="about-copy reveal"><?php the_content(); ?></div>
			<?php
		endwhile;
		?>
	</div>
</section>
<?php
get_footer();
