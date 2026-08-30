<?php
/**
 * Fallback template.
 *
 * @package bjw-studio
 */

get_header();
?>
<section class="section surface-ink">
	<div class="shell shell--narrow">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article class="reveal" style="margin-bottom:64px">
					<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
					<h2 style="font-size:2.4rem;margin-bottom:18px"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="about-copy"><?php the_content(); ?></div>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<h2><?php esc_html_e( 'Nothing here yet.', 'bjw-studio' ); ?></h2>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
