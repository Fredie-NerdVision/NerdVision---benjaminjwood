<?php
/**
 * "Tandem track" content type: each track holds two renders of the same piece
 * (for example an original mix and a re-worked one) that the front-end player
 * runs in lockstep.
 *
 * @package bjw-studio
 */

if ( ! function_exists( 'bjw_track_audio' ) ) {
	/**
	 * URL of a track shipped with the site, self-hosted so the Web Audio graph
	 * is never blocked by a cross-origin source.
	 *
	 * @param string $slug File slug under uploads/bjw-audio.
	 * @return string
	 */
	function bjw_track_audio( $slug ) {
		$path = '/uploads/bjw-audio/' . $slug . '.mp3';

		if ( function_exists( 'content_url' ) ) {
			return content_url( $path );
		}

		return '/wp-content' . $path;
	}
}

if ( ! function_exists( 'bjw_default_tracks' ) ) {
	/**
	 * Tracks shipped with the theme, used until tandem tracks are published or
	 * entered on the Site Content screen.
	 *
	 * Both lanes point at the same recording for now: only one render of each
	 * piece exists, so version B is a stand-in until the treated render is
	 * dropped in.
	 *
	 * @return array
	 */
	function bjw_default_tracks() {
		return array(
			array(
				'title'  => 'Novella',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Original',
					'src'   => bjw_track_audio( 'novella' ),
				),
				'b'      => array(
					'label' => 'Treated (placeholder)',
					'src'   => bjw_track_audio( 'novella' ),
				),
			),
			array(
				'title'  => 'Eudaimonia (Orchestral Version)',
				'artist' => 'Benjamin J. Wood, Shubh Saran',
				'a'      => array(
					'label' => 'Original',
					'src'   => bjw_track_audio( 'eudaimonia' ),
				),
				'b'      => array(
					'label' => 'Treated (placeholder)',
					'src'   => bjw_track_audio( 'eudaimonia' ),
				),
			),
			array(
				'title'  => 'I Bow Deeply',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Original',
					'src'   => bjw_track_audio( 'i-bow-deeply' ),
				),
				'b'      => array(
					'label' => 'Treated (placeholder)',
					'src'   => bjw_track_audio( 'i-bow-deeply' ),
				),
			),
			array(
				'title'  => 'Circumbinary',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Original',
					'src'   => bjw_track_audio( 'circumbinary' ),
				),
				'b'      => array(
					'label' => 'Treated (placeholder)',
					'src'   => bjw_track_audio( 'circumbinary' ),
				),
			),
			array(
				'title'  => 'Bird',
				'artist' => 'Benjamin J. Wood, Rebecca Hayes',
				'a'      => array(
					'label' => 'Original',
					'src'   => bjw_track_audio( 'bird' ),
				),
				'b'      => array(
					'label' => 'Treated (placeholder)',
					'src'   => bjw_track_audio( 'bird' ),
				),
			),
			array(
				'title'  => 'Immigrant Song',
				'artist' => 'Benjamin J. Wood',
				'a'      => array(
					'label' => 'Original',
					'src'   => bjw_track_audio( 'immigrant-song' ),
				),
				'b'      => array(
					'label' => 'Treated (placeholder)',
					'src'   => bjw_track_audio( 'immigrant-song' ),
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

	$rows = bjw_acf_paired_tracks();

	return $rows ? $rows : bjw_get_published_tracks();
}

/**
 * Tracks entered as pairs on the Site Content screen (ACF Pro repeater).
 *
 * A row with only the first render still works: both lanes play it, so the
 * treated version can be dropped in later without touching anything else.
 *
 * @return array
 */
function bjw_acf_paired_tracks() {
	if ( ! function_exists( 'bjw_rows' ) || ! bjw_acf_active() || ! bjw_acf_pro() ) {
		return array();
	}

	$tracks = array();

	foreach ( bjw_rows( 'tandem_tracks', array() ) as $row ) {
		$a_src = isset( $row['a_src'] ) ? $row['a_src'] : '';
		$b_src = isset( $row['b_src'] ) && $row['b_src'] ? $row['b_src'] : $a_src;

		if ( ! $a_src ) {
			continue;
		}

		$tracks[] = array(
			'title'  => isset( $row['title'] ) && $row['title'] ? $row['title'] : __( 'Untitled', 'bjw-studio' ),
			'artist' => isset( $row['artist'] ) && $row['artist'] ? $row['artist'] : get_bloginfo( 'name' ),
			'a'      => array(
				'label' => isset( $row['a_label'] ) && $row['a_label'] ? $row['a_label'] : __( 'Original', 'bjw-studio' ),
				'src'   => $a_src,
			),
			'b'      => array(
				'label' => isset( $row['b_label'] ) && $row['b_label'] ? $row['b_label'] : __( 'Treated', 'bjw-studio' ),
				'src'   => $b_src,
			),
		);
	}

	return $tracks;
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

		if ( ! $a_src ) {
			continue;
		}

		if ( ! $b_src ) {
			$b_src = $a_src;
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
