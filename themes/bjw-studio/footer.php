</main>

<footer class="site-footer">
	<div class="shell site-footer__inner">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'Benjamin J. Wood. All Rights Reserved.', 'bjw-studio' ); ?></p>
		<div class="site-footer__links">
			<a href="#"><?php esc_html_e( 'Privacy Policy', 'bjw-studio' ); ?></a>
			<a href="#"><?php esc_html_e( 'Terms of Service', 'bjw-studio' ); ?></a>
		</div>
	</div>
</footer>

<?php bjw_section( 'player-bar' ); ?>

<?php wp_footer(); ?>
</body>
</html>
