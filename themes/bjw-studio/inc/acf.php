<?php
/**
 * Advanced Custom Fields integration.
 *
 * Every editable string on the front end goes through bjw_field() / bjw_rows(),
 * which read ACF when the plugin is active and fall back to the values baked
 * into the templates otherwise. Field groups are registered in code so the site
 * ships ready to edit — nothing has to be rebuilt in the ACF UI.
 *
 * @package bjw-studio
 */

if ( ! function_exists( 'bjw_acf_active' ) ) {
	/**
	 * Is ACF (free or Pro) available?
	 *
	 * @return bool
	 */
	function bjw_acf_active() {
		return function_exists( 'get_field' ) && function_exists( 'acf_add_local_field_group' );
	}
}

if ( ! function_exists( 'bjw_acf_pro' ) ) {
	/**
	 * Is ACF Pro available (options pages and repeaters)?
	 *
	 * @return bool
	 */
	function bjw_acf_pro() {
		return function_exists( 'acf_add_options_page' );
	}
}

if ( ! function_exists( 'bjw_field' ) ) {
	/**
	 * A single site-content value.
	 *
	 * @param string $key     Field name.
	 * @param mixed  $default Value used when ACF is unavailable or empty.
	 * @return mixed
	 */
	function bjw_field( $key, $default = '' ) {
		if ( ! bjw_acf_active() || ! bjw_acf_pro() ) {
			return $default;
		}

		$value = get_field( $key, 'option' );

		if ( null === $value || '' === $value || array() === $value ) {
			return $default;
		}

		return $value;
	}
}

if ( ! function_exists( 'bjw_rows' ) ) {
	/**
	 * A repeatable set of site-content values (ACF Pro repeater).
	 *
	 * @param string $key      Repeater name, e.g. "services".
	 * @param array  $defaults Default rows; also defines the sub-field keys.
	 * @return array
	 */
	function bjw_rows( $key, $defaults ) {
		if ( ! bjw_acf_active() || ! bjw_acf_pro() ) {
			return $defaults;
		}

		$rows = get_field( $key, 'option' );

		return ( is_array( $rows ) && $rows ) ? $rows : $defaults;
	}
}

if ( ! function_exists( 'add_action' ) ) {
	// Rendered outside WordPress (design preview harness): the getters above are
	// enough, there is nothing to register.
	return;
}

/**
 * Text/textarea/image field definition helper.
 *
 * @param string $name  Field name.
 * @param string $label Field label.
 * @param string $type  ACF field type.
 * @return array
 */
function bjw_acf_field( $name, $label, $type = 'text' ) {
	return array(
		'key'   => 'field_' . $name,
		'name'  => $name,
		'label' => $label,
		'type'  => $type,
	);
}

/**
 * Register the theme's field groups.
 */
function bjw_register_acf_fields() {
	if ( ! bjw_acf_active() ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_bjw_track',
			'title'    => __( 'Tandem Audio', 'bjw-studio' ),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'bjw_track',
					),
				),
			),
			'fields'   => array(
				bjw_acf_field( 'bjw_artist', __( 'Artist', 'bjw-studio' ) ),
				bjw_acf_field( 'bjw_a_label', __( 'Version A label', 'bjw-studio' ) ),
				array(
					'key'           => 'field_bjw_a_src',
					'name'          => 'bjw_a_src',
					'label'         => __( 'Version A audio', 'bjw-studio' ),
					'type'          => 'file',
					'return_format' => 'url',
					'mime_types'    => 'mp3,wav,m4a,ogg',
					'instructions'  => __( 'Both versions must be the same length so they stay in sync.', 'bjw-studio' ),
				),
				bjw_acf_field( 'bjw_b_label', __( 'Version B label', 'bjw-studio' ) ),
				array(
					'key'           => 'field_bjw_b_src',
					'name'          => 'bjw_b_src',
					'label'         => __( 'Version B audio', 'bjw-studio' ),
					'type'          => 'file',
					'return_format' => 'url',
					'mime_types'    => 'mp3,wav,m4a,ogg',
				),
			),
		)
	);

	if ( ! bjw_acf_pro() ) {
		// Site copy lives on an options page, an ACF Pro feature; without it the
		// templates keep rendering their built-in defaults.
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Site Content', 'bjw-studio' ),
			'menu_title' => __( 'Site Content', 'bjw-studio' ),
			'menu_slug'  => 'bjw-site-content',
			'icon_url'   => 'dashicons-edit-page',
			'position'   => 3,
			'capability' => 'edit_theme_options',
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_bjw_content',
			'title'    => __( 'Site Content', 'bjw-studio' ),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'bjw-site-content',
					),
				),
			),
			'fields'   => bjw_acf_content_fields(),
		)
	);
}
add_action( 'acf/init', 'bjw_register_acf_fields' );

/**
 * Every editable field on the one-page layout.
 *
 * @return array
 */
function bjw_acf_content_fields() {
	$fields = array(
		array(
			'key'   => 'field_bjw_tab_hero',
			'label' => __( 'Hero', 'bjw-studio' ),
			'type'  => 'tab',
		),
		bjw_acf_field( 'hero_kicker', __( 'Kicker', 'bjw-studio' ) ),
		bjw_acf_field( 'hero_title', __( 'Name (first line)', 'bjw-studio' ) ),
		bjw_acf_field( 'hero_title_accent', __( 'Name (accent)', 'bjw-studio' ) ),
		bjw_acf_field( 'hero_lede', __( 'Tagline', 'bjw-studio' ), 'textarea' ),
		bjw_acf_field( 'hero_cta', __( 'Button label', 'bjw-studio' ) ),

		array(
			'key'   => 'field_bjw_tab_about',
			'label' => __( 'About', 'bjw-studio' ),
			'type'  => 'tab',
		),
		bjw_acf_field( 'about_eyebrow', __( 'Eyebrow', 'bjw-studio' ) ),
		bjw_acf_field( 'about_heading', __( 'Heading', 'bjw-studio' ) ),
		array(
			'key'           => 'field_bjw_about_portrait',
			'name'          => 'about_portrait',
			'label'         => __( 'Portrait', 'bjw-studio' ),
			'type'          => 'image',
			'return_format' => 'url',
		),
		bjw_acf_field( 'about_paragraph_1', __( 'Paragraph 1', 'bjw-studio' ), 'textarea' ),
		bjw_acf_field( 'about_paragraph_2', __( 'Paragraph 2', 'bjw-studio' ), 'textarea' ),
		bjw_acf_field( 'about_quote', __( 'Pull quote', 'bjw-studio' ), 'textarea' ),

		array(
			'key'   => 'field_bjw_tab_services',
			'label' => __( 'Services', 'bjw-studio' ),
			'type'  => 'tab',
		),
		bjw_acf_field( 'services_eyebrow', __( 'Eyebrow', 'bjw-studio' ) ),
		bjw_acf_field( 'services_heading', __( 'Heading', 'bjw-studio' ) ),
	);

	$fields = array_merge(
		$fields,
		bjw_acf_repeatable(
			'services',
			__( 'Service', 'bjw-studio' ),
			array(
				'title' => array( __( 'Title', 'bjw-studio' ), 'text' ),
				'copy'  => array( __( 'Description', 'bjw-studio' ), 'textarea' ),
			)
		)
	);

	$fields[] = array(
		'key'   => 'field_bjw_tab_listen',
		'label' => __( 'A/B Room', 'bjw-studio' ),
		'type'  => 'tab',
	);
	$fields[] = bjw_acf_field( 'listen_eyebrow', __( 'Eyebrow', 'bjw-studio' ) );
	$fields[] = bjw_acf_field( 'listen_heading', __( 'Heading', 'bjw-studio' ) );
	$fields[] = bjw_acf_field( 'listen_intro', __( 'Intro', 'bjw-studio' ), 'textarea' );
	$fields[] = bjw_acf_tandem_repeater();

	$fields[] = array(
		'key'   => 'field_bjw_tab_portfolio',
		'label' => __( 'Portfolio', 'bjw-studio' ),
		'type'  => 'tab',
	);
	$fields[] = bjw_acf_field( 'portfolio_eyebrow', __( 'Eyebrow', 'bjw-studio' ) );
	$fields[] = bjw_acf_field( 'portfolio_heading', __( 'Heading', 'bjw-studio' ) );

	$fields = array_merge(
		$fields,
		bjw_acf_repeatable(
			'works',
			__( 'Project', 'bjw-studio' ),
			array(
				'title' => array( __( 'Title', 'bjw-studio' ), 'text' ),
				'meta'  => array( __( 'Subtitle', 'bjw-studio' ), 'text' ),
				'link'  => array( __( 'Link', 'bjw-studio' ), 'url' ),
			)
		)
	);

	$fields[] = array(
		'key'   => 'field_bjw_tab_contact',
		'label' => __( 'Contact', 'bjw-studio' ),
		'type'  => 'tab',
	);
	$fields[] = bjw_acf_field( 'contact_eyebrow', __( 'Eyebrow', 'bjw-studio' ) );
	$fields[] = bjw_acf_field( 'contact_heading', __( 'Heading', 'bjw-studio' ) );
	$fields[] = bjw_acf_field( 'contact_intro', __( 'Intro', 'bjw-studio' ), 'textarea' );
	$fields[] = array(
		'key'          => 'field_bjw_contact_email',
		'name'         => 'contact_email',
		'label'        => __( 'Inquiry recipient', 'bjw-studio' ),
		'type'         => 'email',
		'instructions' => __( 'Leave empty to use the WordPress admin email.', 'bjw-studio' ),
	);

	return $fields;
}

/**
 * The paired-track repeater: one row is one song, holding both renders so a
 * single selection fills both lanes of the player.
 *
 * @return array
 */
function bjw_acf_tandem_repeater() {
	$file = function ( $name, $label, $instructions = '' ) {
		return array(
			'key'           => 'field_tandem_' . $name,
			'name'          => $name,
			'label'         => $label,
			'type'          => 'file',
			'return_format' => 'url',
			'mime_types'    => 'mp3,wav,m4a,ogg',
			'instructions'  => $instructions,
		);
	};

	return array(
		'key'          => 'field_bjw_tandem_tracks',
		'name'         => 'tandem_tracks',
		'label'        => __( 'Tracks', 'bjw-studio' ),
		'type'         => 'repeater',
		'layout'       => 'block',
		'button_label' => __( 'Add track', 'bjw-studio' ),
		'instructions' => __( 'One row per song. Pick both renders of that song — the pair fills version A and version B of the player, so they must be the same recording and the same length.', 'bjw-studio' ),
		'sub_fields'   => array(
			array(
				'key'   => 'field_tandem_title',
				'name'  => 'title',
				'label' => __( 'Track title', 'bjw-studio' ),
				'type'  => 'text',
			),
			array(
				'key'   => 'field_tandem_artist',
				'name'  => 'artist',
				'label' => __( 'Artist', 'bjw-studio' ),
				'type'  => 'text',
			),
			array(
				'key'           => 'field_tandem_a_label',
				'name'          => 'a_label',
				'label'         => __( 'Version A label', 'bjw-studio' ),
				'type'          => 'text',
				'default_value' => __( 'Original', 'bjw-studio' ),
			),
			$file( 'a_src', __( 'Version A audio', 'bjw-studio' ), __( 'The untreated render.', 'bjw-studio' ) ),
			array(
				'key'           => 'field_tandem_b_label',
				'name'          => 'b_label',
				'label'         => __( 'Version B label', 'bjw-studio' ),
				'type'          => 'text',
				'default_value' => __( 'Treated', 'bjw-studio' ),
			),
			$file( 'b_src', __( 'Version B audio', 'bjw-studio' ), __( 'The same song after treatment. Leave empty while you only have one render — the player then plays version A in both lanes.', 'bjw-studio' ) ),
		),
	);
}

/**
 * Repeater definition.
 *
 * @param string $name       Repeater name.
 * @param string $label      Human label for one row.
 * @param array  $sub_fields Map of sub-field name => array( label, type ).
 * @return array
 */
function bjw_acf_repeatable( $name, $label, $sub_fields ) {
	$subs = array();

	foreach ( $sub_fields as $sub => $spec ) {
		$subs[] = array(
			'key'   => 'field_' . $name . '_' . $sub,
			'name'  => $sub,
			'label' => $spec[0],
			'type'  => $spec[1],
		);
	}

	return array(
		array(
			'key'          => 'field_' . $name,
			'name'         => $name,
			'label'        => $label,
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => sprintf( /* translators: %s: row label. */ __( 'Add %s', 'bjw-studio' ), $label ),
			'sub_fields'   => $subs,
		),
	);
}
