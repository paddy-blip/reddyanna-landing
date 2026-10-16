<?php
/**
 * Theme Settings (ACF options page): header, footer, contact, colours, Open Graph, scripts.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		acf_add_options_page(
			array(
				'page_title' => __( 'Theme Settings', 'reddyanna' ),
				'menu_title' => __( 'Theme Settings', 'reddyanna' ),
				'menu_slug'  => 'ra-theme-settings',
				'capability' => 'edit_others_pages', // Editors can update contact details, footer etc.
				'icon_url'   => 'dashicons-admin-customizer',
				'position'   => 59,
				'redirect'   => false,
				'autoload'   => true,
			)
		);

		// Fields are editable in Custom ACF → Field Groups; PHP copy is a fallback only.
		if ( ra_acf_group_exists( 'group_ra_theme_settings' ) ) {
			return;
		}

		$d   = ra_defaults();
		$tab = function ( $key, $label ) {
			return array(
				'key'       => 'field_ra_opt_tab_' . $key,
				'label'     => $label,
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'left',
			);
		};
		$o   = function ( $name, $type, $label, $args = array() ) {
			return ra_f( 'opt', $name, $type, $label, $args );
		};

		$fields = array(
			// ---------------- HEADER ----------------.
			$tab( 'header', 'Header' ),
			$o(
				'ra_header_logo',
				'image',
				'Header logo',
				array(
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => 'Transparent PNG/WebP recommended. Leave empty to use the bundled Reddy Anna logo.',
					'wrapper'       => array( 'width' => 50 ),
				)
			),
			$o(
				'ra_logo_width',
				'number',
				'Logo display width (px)',
				array(
					'default_value' => 170,
					'min'           => 80,
					'max'           => 400,
					'wrapper'       => array( 'width' => 25 ),
				)
			),
			$o(
				'ra_sticky_header',
				'true_false',
				'Sticky header',
				array(
					'default_value' => 1,
					'ui'            => 1,
					'wrapper'       => array( 'width' => 25 ),
				)
			),
			$o(
				'ra_header_search',
				'true_false',
				'Show search button',
				array(
					'default_value' => 1,
					'ui'            => 1,
					'wrapper'       => array( 'width' => 25 ),
				)
			),
			$o(
				'ra_header_button',
				'link',
				'Header button',
				array(
					'instructions' => 'Use URL "#whatsapp" to open WhatsApp chat. Leave empty to hide.',
					'wrapper'      => array( 'width' => 75 ),
				)
			),
			$o(
				'ra_topbar_enable',
				'true_false',
				'Show announcement bar',
				array(
					'default_value' => 0,
					'ui'            => 1,
					'wrapper'       => array( 'width' => 25 ),
				)
			),
			$o(
				'ra_topbar_text',
				'text',
				'Announcement text',
				array(
					'wrapper'           => array( 'width' => 75 ),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_ra_opt_ra_topbar_enable',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				)
			),

			// ---------------- FOOTER ----------------.
			$tab( 'footer', 'Footer' ),
			$o(
				'ra_footer_logo',
				'image',
				'Footer logo',
				array(
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => 'Leave empty to reuse the header logo.',
					'wrapper'       => array( 'width' => 50 ),
				)
			),
			$o(
				'ra_footer_logo_width',
				'number',
				'Footer logo width (px)',
				array(
					'default_value' => 200,
					'min'           => 80,
					'max'           => 400,
					'wrapper'       => array( 'width' => 50 ),
				)
			),
			$o( 'ra_footer_about', 'textarea', 'About text (under logo)', array( 'rows' => 3, 'new_lines' => '' ) ),
			$o( 'ra_footer_contact_title', 'text', 'Column 2 title (contact)', array( 'default_value' => 'Get in Touch', 'wrapper' => array( 'width' => 33 ) ) ),
			$o( 'ra_footer_pages_title', 'text', 'Column 3 title (menu: Footer – Important Pages)', array( 'default_value' => 'Important Pages', 'wrapper' => array( 'width' => 33 ) ) ),
			$o( 'ra_footer_legal_title', 'text', 'Column 4 title (menu: Footer – Legal Pages)', array( 'default_value' => 'Legal Pages', 'wrapper' => array( 'width' => 34 ) ) ),
			$o( 'ra_footer_disclaimer', 'textarea', 'Disclaimer', array( 'rows' => 3, 'new_lines' => '' ) ),
			$o(
				'ra_copyright',
				'text',
				'Copyright text',
				array(
					'default_value' => 'Copyright © {year} {site}. All Rights Reserved.',
					'instructions'  => 'Tokens: {year}, {site}',
				)
			),

			// ---------------- CONTACT & SOCIAL ----------------.
			$tab( 'contact', 'Contact & Social' ),
			$o( 'ra_phone', 'text', 'Phone (display)', array( 'placeholder' => $d['phone'], 'wrapper' => array( 'width' => 50 ) ) ),
			$o( 'ra_email', 'email', 'Email', array( 'placeholder' => $d['email'], 'wrapper' => array( 'width' => 50 ) ) ),
			$o( 'ra_address', 'textarea', 'Address / service area', array( 'rows' => 2, 'new_lines' => 'br' ) ),
			$o(
				'ra_whatsapp_number',
				'text',
				'WhatsApp number',
				array(
					'instructions' => 'Country code + number, digits only (e.g. 919000000000).',
					'placeholder'  => $d['whatsapp'],
					'wrapper'      => array( 'width' => 50 ),
				)
			),
			$o( 'ra_whatsapp_message', 'text', 'WhatsApp pre-filled message', array( 'placeholder' => $d['wa_message'], 'wrapper' => array( 'width' => 50 ) ) ),
			$o(
				'ra_whatsapp_float',
				'true_false',
				'Floating WhatsApp button',
				array(
					'default_value' => 1,
					'ui'            => 1,
				)
			),
			$o(
				'ra_social',
				'repeater',
				'Social links',
				array(
					'layout'       => 'table',
					'button_label' => 'Add social link',
					'sub_fields'   => array(
						ra_f(
							'opt_social',
							'network',
							'select',
							'Network',
							array(
								'choices' => array(
									'facebook'  => 'Facebook',
									'instagram' => 'Instagram',
									'x'         => 'X (Twitter)',
									'telegram'  => 'Telegram',
									'youtube'   => 'YouTube',
									'whatsapp'  => 'WhatsApp',
								),
							)
						),
						ra_f( 'opt_social', 'url', 'url', 'URL' ),
					),
				)
			),

			// ---------------- COLOURS ----------------.
			$tab( 'colors', 'Brand Colours' ),
			$o( 'ra_color_primary', 'color_picker', 'Gold (primary)', array( 'default_value' => '#d4a537', 'wrapper' => array( 'width' => 33 ) ) ),
			$o( 'ra_color_primary_light', 'color_picker', 'Gold light (highlight)', array( 'default_value' => '#f3d77a', 'wrapper' => array( 'width' => 33 ) ) ),
			$o( 'ra_color_text', 'color_picker', 'Body text', array( 'default_value' => '#f2efe6', 'wrapper' => array( 'width' => 34 ) ) ),
			$o( 'ra_color_bg', 'color_picker', 'Page background', array( 'default_value' => '#0b0b0b', 'wrapper' => array( 'width' => 50 ) ) ),
			$o( 'ra_color_surface', 'color_picker', 'Card / panel background', array( 'default_value' => '#141414', 'wrapper' => array( 'width' => 50 ) ) ),

			// ---------------- OPEN GRAPH / SEO ----------------.
			$tab( 'og', 'Open Graph / SEO' ),
			array(
				'key'     => 'field_ra_opt_og_msg',
				'label'   => '',
				'name'    => '',
				'type'    => 'message',
				'message' => 'Default social-share image used when a page or post has no image of its own. Synced automatically to Yoast SEO → Social → Default image. Per-page titles, descriptions and images are edited in the Yoast box on each page.',
			),
			$o(
				'ra_og_image',
				'image',
				'Default share image / site logo (1200 × 630)',
				array(
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'min_width'     => 1200,
					'min_height'    => 630,
					'instructions'  => 'Exactly 1200 × 630 px (JPG/PNG, under 300 KB).',
				)
			),
			$o( 'ra_og_title', 'text', 'Default share title', array( 'instructions' => 'Fallback og:title when Yoast is inactive. Defaults to the site name.' ) ),
			$o( 'ra_og_description', 'textarea', 'Default share description', array( 'rows' => 2, 'new_lines' => '' ) ),
			$o( 'ra_twitter_site', 'text', 'X / Twitter username', array( 'placeholder' => '@reddyanna', 'wrapper' => array( 'width' => 50 ) ) ),
			$o(
				'ra_theme_color',
				'color_picker',
				'Mobile browser theme colour',
				array(
					'default_value' => '#0b0b0b',
					'wrapper'       => array( 'width' => 50 ),
				)
			),

			// ---------------- SCRIPTS ----------------.
			$tab( 'scripts', 'Scripts' ),
			$o(
				'ra_head_scripts',
				'textarea',
				'Head scripts',
				array(
					'rows'         => 5,
					'new_lines'    => '',
					'instructions' => 'e.g. Google Analytics / Search Console verification. Output before </head>.',
				)
			),
			$o( 'ra_body_scripts', 'textarea', 'Body open scripts', array( 'rows' => 4, 'new_lines' => '', 'instructions' => 'Output right after <body> (e.g. GTM noscript).' ) ),
			$o( 'ra_footer_scripts', 'textarea', 'Footer scripts', array( 'rows' => 4, 'new_lines' => '', 'instructions' => 'Output before </body>.' ) ),
		);

		acf_add_local_field_group(
			array(
				'key'      => 'group_ra_theme_settings',
				'title'    => 'Theme Settings',
				'fields'   => $fields,
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'ra-theme-settings',
						),
					),
				),
				'style'    => 'seamless',
			)
		);
	}
);

/** Print custom scripts (admins only can save these — capability manage_options). */
add_action(
	'wp_head',
	function () {
		echo ra_opt( 'ra_head_scripts', '' ); // phpcs:ignore WordPress.Security.EscapeOutput -- admin-provided code.
	},
	99
);
add_action(
	'wp_body_open',
	function () {
		echo ra_opt( 'ra_body_scripts', '' ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
);
add_action(
	'wp_footer',
	function () {
		echo ra_opt( 'ra_footer_scripts', '' ); // phpcs:ignore WordPress.Security.EscapeOutput
	},
	99
);
