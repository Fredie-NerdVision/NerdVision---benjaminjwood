<?php
/**
 * "Tandem track" content type: each track holds two renders of the same piece
 * (for example an original mix and a re-worked one) that the front-end player
 * runs in lockstep.
 *
 * @package bjw-obsidian
 */

if ( ! function_exists( 'bjw_demo_audio' ) ) {
	/**
	 * Placeholder audio URL, preferring a self-hosted copy so the Web Audio
	 * graph is not blocked by a cross-origin source. The two lanes of a demo
	 * track are the same recording at the same length, one of them dulled, so
	 * the A/B comparison behaves like a real pair of renders.
	 *
	 * @param int    $track Demo track number, 1-3.
	 * @param string $lane  'a' or 'b'.
	 * @return string
	 */
	function bjw_demo_audio( $track, $lane ) {
		$file = 'bjw-demo-' . (int) $track . ( 'b' === $lane ? 'b' : 'a' ) . '.mp3';

		if ( defined( 'WP_CONTENT_DIR' ) && function_exists( 'content_url' ) && file_exists( WP_CONTENT_DIR . '/uploads/bjw-demo/' . $file ) ) {
			return content_url( '/uploads/bjw-demo/' . $file );
		}

		$remote = ( 2 * (int) $track ) - ( 'b' === $lane ? 1 : 0 );

		return 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-' . $remote . '.mp3';
	}
}

if ( ! function_exists( 'bjw_default_tracks' ) ) {
	/**
	 * Placeholder tracks used until real tandem tracks are published.
	 *
	 * @return array
	 */
	function bjw_default_tracks() {
		return array(
			array(
				'title'  => 'Novella',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Version A',
					'src'   => bjw_demo_audio( 1, 'a' ),
				),
				'b'      => array(
					'label' => 'Version B',
					'src'   => bjw_demo_audio( 1, 'b' ),
				),
			),
			array(
				'title'  => 'Eudaimonia (Orchestral Version)',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Version A',
					'src'   => bjw_demo_audio( 2, 'a' ),
				),
				'b'      => array(
					'label' => 'Version B',
					'src'   => bjw_demo_audio( 2, 'b' ),
				),
			),
			array(
				'title'  => 'Circumbinary',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Version A',
					'src'   => bjw_demo_audio( 3, 'a' ),
				),
				'b'      => array(
					'label' => 'Version B',
					'src'   => bjw_demo_audio( 3, 'b' ),
				),
			),
		);
	}
}

/**
 * Tracks for the front-end player, falling back to the placeholders.
 *
 * @return array
 */
function bjw_get_tracks() {
	if ( ! function_exists( 'get_posts' ) ) {
		// Rendered outside WordPress (design preview harness).
		return bjw_default_tracks();
	}

	return bjw_get_published_tracks();
}

if ( ! function_exists( 'add_action' ) ) {
	return;
}

/**
 * Register the tandem track post type.
 */
function bjw_register_track_cpt() {
	register_post_type(
		'bjw_track',
		array(
			'labels'       => array(
				'name'          => __( 'Tandem Tracks', 'bjw-obsidian' ),
				'singular_name' => __( 'Tandem Track', 'bjw-obsidian' ),
				'add_new_item'  => __( 'Add Tandem Track', 'bjw-obsidian' ),
				'edit_item'     => __( 'Edit Tandem Track', 'bjw-obsidian' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-controls-volumeon',
			'supports'     => array( 'title', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'bjw_register_track_cpt' );

/**
 * Meta fields describing the two renders.
 *
 * @return array
 */
function bjw_track_fields() {
	return array(
		'bjw_a_label' => __( 'Version A label', 'bjw-obsidian' ),
		'bjw_a_src'   => __( 'Version A audio URL', 'bjw-obsidian' ),
		'bjw_b_label' => __( 'Version B label', 'bjw-obsidian' ),
		'bjw_b_src'   => __( 'Version B audio URL', 'bjw-obsidian' ),
		'bjw_artist'  => __( 'Artist', 'bjw-obsidian' ),
	);
}

/**
 * Register the editor meta box.
 *
 * ACF registers the same field names, so the native box only appears when the
 * plugin is missing.
 */
function bjw_track_meta_box() {
	if ( bjw_acf_active() ) {
		return;
	}

	add_meta_box(
		'bjw_track_meta',
		__( 'Tandem Audio', 'bjw-obsidian' ),
		'bjw_render_track_meta_box',
		'bjw_track',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'bjw_track_meta_box' );

/**
 * Meta box markup.
 *
 * @param WP_Post $post Current post.
 */
function bjw_render_track_meta_box( $post ) {
	wp_nonce_field( 'bjw_save_track', 'bjw_track_nonce' );

	foreach ( bjw_track_fields() as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf(
			'<p><label for="%1$s" style="display:block;font-weight:600;margin-bottom:4px;">%2$s</label>' .
			'<input type="text" class="widefat" id="%1$s" name="%1$s" value="%3$s" /></p>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $value )
		);
	}

	echo '<p class="description">' . esc_html__( 'Both files should be the same length so the two versions stay in sync.', 'bjw-obsidian' ) . '</p>';
}

/**
 * Persist meta box values.
 *
 * @param int $post_id Post ID.
 */
function bjw_save_track_meta( $post_id ) {
	if ( bjw_acf_active() ) {
		return;
	}
	if ( ! isset( $_POST['bjw_track_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['bjw_track_nonce'] ) ), 'bjw_save_track' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array_keys( bjw_track_fields() ) as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw   = wp_unslash( $_POST[ $key ] );
		$value = ( '_src' === substr( $key, -4 ) ) ? esc_url_raw( $raw ) : sanitize_text_field( $raw );
		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post_bjw_track', 'bjw_save_track_meta' );

/**
 * Published tandem tracks, or the placeholders when none exist yet.
 *
 * @return array
 */
function bjw_get_published_tracks() {
	$posts = get_posts(
		array(
			'post_type'      => 'bjw_track',
			'posts_per_page' => 12,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		)
	);

	$tracks = array();

	foreach ( $posts as $post ) {
		$a_src = bjw_track_value( $post->ID, 'bjw_a_src' );
		$b_src = bjw_track_value( $post->ID, 'bjw_b_src' );

		if ( ! $a_src || ! $b_src ) {
			continue;
		}

		$artist = bjw_track_value( $post->ID, 'bjw_artist' );

		$tracks[] = array(
			'title'  => get_the_title( $post ),
			'artist' => $artist ? $artist : get_bloginfo( 'name' ),
			'a'      => array(
				'label' => bjw_track_value( $post->ID, 'bjw_a_label' ) ?: __( 'Version A', 'bjw-obsidian' ),
				'src'   => $a_src,
			),
			'b'      => array(
				'label' => bjw_track_value( $post->ID, 'bjw_b_label' ) ?: __( 'Version B', 'bjw-obsidian' ),
				'src'   => $b_src,
			),
		);
	}

	return $tracks ? $tracks : bjw_default_tracks();
}

/**
 * One track value, read through ACF when it is active so file fields resolve to
 * URLs, and from plain post meta otherwise.
 *
 * @param int    $post_id Track ID.
 * @param string $key     Field name.
 * @return string
 */
function bjw_track_value( $post_id, $key ) {
	$value = bjw_acf_active() ? get_field( $key, $post_id ) : get_post_meta( $post_id, $key, true );

	return is_string( $value ) ? $value : '';
}
