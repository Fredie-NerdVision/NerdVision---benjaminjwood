<?php
/**
 * "Tandem track" content type: each track holds two renders of the same piece
 * (for example an original mix and a re-worked one) that the front-end player
 * runs in lockstep.
 *
 * @package bjw-studio
 */

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
					'src'   => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
				),
				'b'      => array(
					'label' => 'Version B',
					'src'   => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
				),
			),
			array(
				'title'  => 'Eudaimonia (Orchestral Version)',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Version A',
					'src'   => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3',
				),
				'b'      => array(
					'label' => 'Version B',
					'src'   => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3',
				),
			),
			array(
				'title'  => 'Circumbinary',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Version A',
					'src'   => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-5.mp3',
				),
				'b'      => array(
					'label' => 'Version B',
					'src'   => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-6.mp3',
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
				'name'          => __( 'Tandem Tracks', 'bjw-studio' ),
				'singular_name' => __( 'Tandem Track', 'bjw-studio' ),
				'add_new_item'  => __( 'Add Tandem Track', 'bjw-studio' ),
				'edit_item'     => __( 'Edit Tandem Track', 'bjw-studio' ),
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
		'bjw_a_label' => __( 'Version A label', 'bjw-studio' ),
		'bjw_a_src'   => __( 'Version A audio URL', 'bjw-studio' ),
		'bjw_b_label' => __( 'Version B label', 'bjw-studio' ),
		'bjw_b_src'   => __( 'Version B audio URL', 'bjw-studio' ),
		'bjw_artist'  => __( 'Artist', 'bjw-studio' ),
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
		__( 'Tandem Audio', 'bjw-studio' ),
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

	echo '<p class="description">' . esc_html__( 'Both files should be the same length so the two versions stay in sync.', 'bjw-studio' ) . '</p>';
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
				'label' => bjw_track_value( $post->ID, 'bjw_a_label' ) ?: __( 'Version A', 'bjw-studio' ),
				'src'   => $a_src,
			),
			'b'      => array(
				'label' => bjw_track_value( $post->ID, 'bjw_b_label' ) ?: __( 'Version B', 'bjw-studio' ),
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
