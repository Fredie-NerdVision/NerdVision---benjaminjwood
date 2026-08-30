<?php
/**
 * Plugin Name: BJW Staging Theme Switch
 * Description: Lets the client compare the two Benjamin J. Wood designs on one staging URL. Visit ?bjw=obsidian or ?bjw=studio to switch, ?bjw=off to restore the default theme.
 * Version: 1.0.0
 *
 * @package bjw-staging
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BJW_STAGING_COOKIE', 'bjw_design' );
define( 'BJW_STAGING_DEFAULT', 'obsidian' );

/**
 * Map of design slug => theme directory.
 *
 * @return array
 */
function bjw_staging_themes() {
	return array(
		'obsidian' => 'bjw-obsidian',
		'studio'   => 'bjw-studio',
	);
}

/**
 * Resolve the design requested for this page view.
 *
 * @return string Design slug, or empty string for the site default theme.
 */
function bjw_staging_choice() {
	static $choice = null;

	if ( null !== $choice ) {
		return $choice;
	}

	$themes = bjw_staging_themes();

	if ( isset( $_GET['bjw'] ) ) {
		$requested = sanitize_key( wp_unslash( $_GET['bjw'] ) );

		if ( 'off' === $requested ) {
			$choice = '';
		} elseif ( isset( $themes[ $requested ] ) ) {
			$choice = $requested;
		}

		if ( null !== $choice && ! headers_sent() ) {
			setcookie( BJW_STAGING_COOKIE, $choice, time() + WEEK_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/' );
		}
	}

	if ( null === $choice && isset( $_COOKIE[ BJW_STAGING_COOKIE ] ) ) {
		$cookie = sanitize_key( wp_unslash( $_COOKIE[ BJW_STAGING_COOKIE ] ) );
		$choice = isset( $themes[ $cookie ] ) ? $cookie : '';
	}

	if ( null === $choice ) {
		$choice = BJW_STAGING_DEFAULT;
	}

	return $choice;
}

/**
 * Whether this admin-context request handles a front-end submission, such as
 * the contact form posting to admin-post.php. Those handlers live in the
 * previewed theme, so the swap has to apply there too.
 *
 * @return bool
 */
function bjw_staging_is_front_end_endpoint() {
	$script = isset( $_SERVER['SCRIPT_NAME'] ) ? basename( sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) ) : '';

	return in_array( $script, array( 'admin-post.php', 'admin-ajax.php' ), true );
}

/**
 * Swap the active theme for this request only.
 *
 * @param string $theme Current theme directory.
 * @return string
 */
function bjw_staging_theme( $theme ) {
	if ( is_admin() && ! bjw_staging_is_front_end_endpoint() ) {
		return $theme;
	}

	$choice = bjw_staging_choice();
	$themes = bjw_staging_themes();

	if ( ! $choice || ! isset( $themes[ $choice ] ) ) {
		return $theme;
	}

	$dir = $themes[ $choice ];

	return is_dir( WP_CONTENT_DIR . '/themes/' . $dir ) ? $dir : $theme;
}
add_filter( 'template', 'bjw_staging_theme' );
add_filter( 'stylesheet', 'bjw_staging_theme' );

/**
 * Render the floating comparison switcher.
 */
function bjw_staging_bar() {
	$choice = bjw_staging_choice();

	if ( ! $choice ) {
		return;
	}

	$links = array(
		'obsidian' => __( 'Version 1 — Obsidian', 'bjw-staging' ),
		'studio'   => __( 'Version 2 — Studio', 'bjw-staging' ),
	);
	?>
	<style>
		@media (max-width: 900px) {
			#bjw-staging-bar {
				top: auto !important;
				right: auto !important;
				left: 12px;
				bottom: 132px;
				transform: scale(.85);
				transform-origin: left bottom;
			}
		}
	</style>
	<div id="bjw-staging-bar" style="position:fixed;top:14px;right:14px;z-index:99999;display:flex;gap:6px;padding:6px;border-radius:999px;background:rgba(12,12,14,.82);backdrop-filter:blur(10px);box-shadow:0 8px 30px rgba(0,0,0,.35);font:600 12px/1 system-ui,-apple-system,'Segoe UI',sans-serif;">
		<?php foreach ( $links as $slug => $label ) : ?>
			<a href="<?php echo esc_url( add_query_arg( 'bjw', $slug, home_url( '/' ) ) ); ?>"
				style="padding:9px 14px;border-radius:999px;text-decoration:none;color:<?php echo $slug === $choice ? '#11110f' : '#e8e4dc'; ?>;background:<?php echo $slug === $choice ? 'linear-gradient(180deg,#e7c69a,#c08d4f)' : 'transparent'; ?>;">
				<?php echo esc_html( $label ); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
}
add_action( 'wp_footer', 'bjw_staging_bar', 99 );

/**
 * Keep the staging site out of search results.
 */
add_filter( 'pre_option_blog_public', '__return_zero' );

/**
 * Present the staging site under the client's name while a design is previewed.
 *
 * @param mixed $value Stored option value.
 * @return mixed
 */
function bjw_staging_blogname( $value ) {
	return bjw_staging_choice() ? 'Benjamin J. Wood' : $value;
}
add_filter( 'pre_option_blogname', 'bjw_staging_blogname' );

/**
 * Matching tagline for the previewed design.
 *
 * @param mixed $value Stored option value.
 * @return mixed
 */
function bjw_staging_blogdescription( $value ) {
	return bjw_staging_choice() ? 'Composer, Producer & Audio Engineer' : $value;
}
add_filter( 'pre_option_blogdescription', 'bjw_staging_blogdescription' );
