<?php
/**
 * ACF page builder (flexible content) — registered in PHP.
 *
 * Every page gets a "Page Sections" field. When sections exist the page renders
 * them; otherwise it falls back to the normal editor content (used for legal pages).
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field factory.
 *
 * @param string $p     Key prefix.
 * @param string $name  Field name.
 * @param string $type  Field type.
 * @param string $label Label.
 * @param array  $args  Extra args.
 * @return array
 */
function ra_f( $p, $name, $type, $label, $args = array() ) {
	return array_merge(
		array(
			'key'   => 'field_ra_' . $p . '_' . $name,
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		),
		$args
	);
}

/**
 * Whether a field group is already stored in the DB or in acf-json (i.e. editable in the ACF UI).
 *
 * @param string $key Field group key.
 * @return bool
 */
function ra_acf_group_exists( $key ) {
	if ( function_exists( 'acf_get_field_group_post' ) && acf_get_field_group_post( $key ) ) {
		return true;
	}
	return file_exists( get_stylesheet_directory() . '/acf-json/' . $key . '.json' );
}

/** Common fields appended to every layout. */
function ra_common_fields( $p ) {
	return array(
		ra_f(
			$p,
			'background',
			'select',
			'Background',
			array(
				'choices'       => array(
					''      => 'Default (black)',
					'alt'   => 'Alternate (dark grey)',
					'glow'  => 'Gold glow',
				),
				'default_value' => '',
				'wrapper'       => array( 'width' => 50 ),
			)
		),
		ra_f(
			$p,
			'section_id',
			'text',
			'Section anchor ID',
			array(
				'instructions' => 'Optional. Lets you link to this section, e.g. #faq',
				'wrapper'      => array( 'width' => 50 ),
			)
		),
	);
}

/** Eyebrow + title + intro fields. */
function ra_head_fields( $p, $intro = true ) {
	$f = array(
		ra_f( $p, 'eyebrow', 'text', 'Small label (eyebrow)', array( 'wrapper' => array( 'width' => 35 ) ) ),
		ra_f(
			$p,
			'title',
			'text',
			'Heading',
			array(
				'instructions' => 'Wrap words in <span> to colour them gold.',
				'wrapper'      => array( 'width' => 65 ),
			)
		),
	);
	if ( $intro ) {
		$f[] = ra_f( $p, 'intro', 'textarea', 'Intro text', array( 'rows' => 2, 'new_lines' => '' ) );
	}
	return $f;
}

/** Icon select. */
function ra_icon_field( $p, $default = 'check-circle' ) {
	return ra_f(
		$p,
		'icon',
		'select',
		'Icon',
		array(
			'choices'       => ra_icon_choices(),
			'default_value' => $default,
			'ui'            => 1,
			'wrapper'       => array( 'width' => 25 ),
		)
	);
}

/**
 * Build a layout.
 */
function ra_layout( $name, $label, $fields ) {
	return array(
		'key'        => 'layout_ra_' . $name,
		'name'       => $name,
		'label'      => $label,
		'display'    => 'block',
		'sub_fields' => array_merge( $fields, ra_common_fields( $name ) ),
	);
}

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}
		// Editable copy lives in the DB (Custom ACF → Field Groups) + child theme acf-json.
		// This PHP definition is only a fallback for a fresh install without them.
		if ( ra_acf_group_exists( 'group_ra_page_sections' ) ) {
			return;
		}

		$layouts = array();

		// HERO.
		$layouts[] = ra_layout(
			'hero',
			'Hero Banner',
			array(
				ra_f( 'hero', 'eyebrow', 'text', 'Small label (eyebrow)', array( 'wrapper' => array( 'width' => 35 ) ) ),
				ra_f(
					'hero',
					'title',
					'text',
					'Heading',
					array(
						'instructions' => 'Wrap words in <span> to colour them gold.',
						'wrapper'      => array( 'width' => 65 ),
					)
				),
				ra_f( 'hero', 'text', 'textarea', 'Text', array( 'rows' => 3, 'new_lines' => '' ) ),
				ra_f( 'hero', 'button_1', 'link', 'Primary button', array( 'instructions' => 'Use URL "#whatsapp" to open WhatsApp chat.', 'wrapper' => array( 'width' => 50 ) ) ),
				ra_f( 'hero', 'button_2', 'link', 'Secondary button', array( 'wrapper' => array( 'width' => 50 ) ) ),
				ra_f(
					'hero',
					'highlights',
					'repeater',
					'Highlights (tick list)',
					array(
						'layout'       => 'table',
						'button_label' => 'Add highlight',
						'max'          => 4,
						'sub_fields'   => array( ra_f( 'hero_hl', 'text', 'text', 'Text' ) ),
					)
				),
				ra_f(
					'hero',
					'image',
					'image',
					'Image',
					array(
						'return_format' => 'id',
						'preview_size'  => 'medium',
						'instructions'  => 'Optional. Leave empty to show the built-in branded ID card visual.',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				ra_f(
					'hero',
					'heading_tag',
					'select',
					'Heading tag',
					array(
						'choices'       => array(
							'h1' => 'H1 (page title – use once per page)',
							'h2' => 'H2',
						),
						'default_value' => 'h1',
						'wrapper'       => array( 'width' => 25 ),
					)
				),
				ra_f(
					'hero',
					'size',
					'select',
					'Size',
					array(
						'choices'       => array(
							'large'   => 'Large (home)',
							'compact' => 'Compact (inner page)',
						),
						'default_value' => 'large',
						'wrapper'       => array( 'width' => 25 ),
					)
				),
			)
		);

		// FEATURES / SERVICES GRID.
		$layouts[] = ra_layout(
			'features',
			'Features / Services Grid',
			array_merge(
				ra_head_fields( 'features' ),
				array(
					ra_f(
						'features',
						'columns',
						'select',
						'Columns (desktop)',
						array(
							'choices'       => array(
								'2' => '2',
								'3' => '3',
								'4' => '4',
							),
							'default_value' => '3',
							'wrapper'       => array( 'width' => 50 ),
						)
					),
					ra_f(
						'features',
						'style',
						'select',
						'Card style',
						array(
							'choices'       => array(
								'card'    => 'Card',
								'outline' => 'Outline',
								'minimal' => 'Minimal (no box)',
							),
							'default_value' => 'card',
							'wrapper'       => array( 'width' => 50 ),
						)
					),
					ra_f(
						'features',
						'items',
						'repeater',
						'Items',
						array(
							'layout'       => 'block',
							'button_label' => 'Add item',
							'collapsed'    => 'field_ra_features_item_title',
							'sub_fields'   => array(
								ra_icon_field( 'features_item' ),
								ra_f( 'features_item', 'title', 'text', 'Title', array( 'wrapper' => array( 'width' => 75 ) ) ),
								ra_f( 'features_item', 'text', 'textarea', 'Text', array( 'rows' => 2, 'new_lines' => '' ) ),
								ra_f( 'features_item', 'link', 'link', 'Link (optional)' ),
							),
						)
					),
					ra_f( 'features', 'button', 'link', 'Button below grid (optional)' ),
				)
			)
		);

		// ABOUT (text + image + points).
		$layouts[] = ra_layout(
			'about',
			'Text + Image (About)',
			array_merge(
				ra_head_fields( 'about', false ),
				array(
					ra_f(
						'about',
						'content',
						'wysiwyg',
						'Content',
						array(
							'tabs'         => 'all',
							'toolbar'      => 'basic',
							'media_upload' => 0,
						)
					),
					ra_f(
						'about',
						'points',
						'repeater',
						'Points',
						array(
							'layout'       => 'block',
							'button_label' => 'Add point',
							'collapsed'    => 'field_ra_about_point_title',
							'sub_fields'   => array(
								ra_icon_field( 'about_point', 'check-circle' ),
								ra_f( 'about_point', 'title', 'text', 'Title', array( 'wrapper' => array( 'width' => 75 ) ) ),
								ra_f( 'about_point', 'text', 'textarea', 'Text', array( 'rows' => 2, 'new_lines' => '' ) ),
							),
						)
					),
					ra_f( 'about', 'button', 'link', 'Button', array( 'wrapper' => array( 'width' => 50 ) ) ),
					ra_f(
						'about',
						'image',
						'image',
						'Image',
						array(
							'return_format' => 'id',
							'preview_size'  => 'medium',
							'instructions'  => 'Optional. Leave empty for the built-in "years of trust" visual.',
							'wrapper'       => array( 'width' => 25 ),
						)
					),
					ra_f(
						'about',
						'image_position',
						'select',
						'Image position',
						array(
							'choices'       => array(
								'right' => 'Right',
								'left'  => 'Left',
							),
							'default_value' => 'right',
							'wrapper'       => array( 'width' => 25 ),
						)
					),
				)
			)
		);

		// STATS.
		$layouts[] = ra_layout(
			'stats',
			'Stats / Achievements',
			array_merge(
				ra_head_fields( 'stats' ),
				array(
					ra_f(
						'stats',
						'items',
						'repeater',
						'Stats',
						array(
							'layout'       => 'table',
							'button_label' => 'Add stat',
							'max'          => 6,
							'sub_fields'   => array(
								ra_f( 'stats_item', 'number', 'number', 'Number' ),
								ra_f( 'stats_item', 'suffix', 'text', 'Suffix (e.g. + or K+)' ),
								ra_f( 'stats_item', 'label', 'text', 'Label' ),
							),
						)
					),
				)
			)
		);

		// STEPS.
		$layouts[] = ra_layout(
			'steps',
			'How It Works (Steps)',
			array_merge(
				ra_head_fields( 'steps' ),
				array(
					ra_f(
						'steps',
						'items',
						'repeater',
						'Steps',
						array(
							'layout'       => 'block',
							'button_label' => 'Add step',
							'max'          => 6,
							'sub_fields'   => array(
								ra_f( 'steps_item', 'title', 'text', 'Title' ),
								ra_f( 'steps_item', 'text', 'textarea', 'Text', array( 'rows' => 2, 'new_lines' => '' ) ),
							),
						)
					),
					ra_f( 'steps', 'button', 'link', 'Button (optional)' ),
				)
			)
		);

		// SPLIT CARDS (Vision / Mission).
		$layouts[] = ra_layout(
			'split_cards',
			'Split Cards (Vision / Mission)',
			array_merge(
				ra_head_fields( 'split_cards' ),
				array(
					ra_f(
						'split_cards',
						'items',
						'repeater',
						'Cards',
						array(
							'layout'       => 'block',
							'button_label' => 'Add card',
							'max'          => 4,
							'sub_fields'   => array(
								ra_icon_field( 'split_item', 'target' ),
								ra_f( 'split_item', 'title', 'text', 'Title', array( 'wrapper' => array( 'width' => 75 ) ) ),
								ra_f( 'split_item', 'text', 'textarea', 'Text', array( 'rows' => 3, 'new_lines' => 'wpautop' ) ),
							),
						)
					),
				)
			)
		);

		// FAQ.
		$layouts[] = ra_layout(
			'faq',
			'FAQ Accordion',
			array_merge(
				ra_head_fields( 'faq' ),
				array(
					ra_f(
						'faq',
						'items',
						'repeater',
						'Questions',
						array(
							'layout'       => 'block',
							'button_label' => 'Add question',
							'collapsed'    => 'field_ra_faq_item_question',
							'sub_fields'   => array(
								ra_f( 'faq_item', 'question', 'text', 'Question' ),
								ra_f( 'faq_item', 'answer', 'textarea', 'Answer', array( 'rows' => 3, 'new_lines' => 'wpautop' ) ),
							),
						)
					),
					ra_f(
						'faq',
						'schema',
						'true_false',
						'Output FAQ schema (JSON-LD)',
						array(
							'default_value' => 1,
							'ui'            => 1,
							'instructions'  => 'Enable on one FAQ block per page only.',
						)
					),
				)
			)
		);

		// TESTIMONIALS.
		$layouts[] = ra_layout(
			'testimonials',
			'Testimonials',
			array_merge(
				ra_head_fields( 'testimonials' ),
				array(
					ra_f(
						'testimonials',
						'items',
						'repeater',
						'Testimonials',
						array(
							'layout'       => 'block',
							'button_label' => 'Add testimonial',
							'collapsed'    => 'field_ra_testi_item_name',
							'sub_fields'   => array(
								ra_f( 'testi_item', 'name', 'text', 'Name', array( 'wrapper' => array( 'width' => 40 ) ) ),
								ra_f( 'testi_item', 'location', 'text', 'City / role', array( 'wrapper' => array( 'width' => 40 ) ) ),
								ra_f(
									'testi_item',
									'rating',
									'number',
									'Rating',
									array(
										'min'           => 1,
										'max'           => 5,
										'default_value' => 5,
										'wrapper'       => array( 'width' => 20 ),
									)
								),
								ra_f( 'testi_item', 'text', 'textarea', 'Review', array( 'rows' => 3, 'new_lines' => '' ) ),
							),
						)
					),
				)
			)
		);

		// CTA.
		$layouts[] = ra_layout(
			'cta',
			'Call to Action Banner',
			array(
				ra_f( 'cta', 'title', 'text', 'Heading', array( 'instructions' => 'Wrap words in <span> to colour them gold.' ) ),
				ra_f( 'cta', 'text', 'textarea', 'Text', array( 'rows' => 2, 'new_lines' => '' ) ),
				ra_f( 'cta', 'button_1', 'link', 'Primary button', array( 'instructions' => 'Use URL "#whatsapp" to open WhatsApp chat.', 'wrapper' => array( 'width' => 50 ) ) ),
				ra_f( 'cta', 'button_2', 'link', 'Secondary button', array( 'wrapper' => array( 'width' => 50 ) ) ),
				ra_f(
					'cta',
					'style',
					'select',
					'Style',
					array(
						'choices'       => array(
							'gold' => 'Gold panel',
							'dark' => 'Dark panel with gold border',
						),
						'default_value' => 'gold',
					)
				),
			)
		);

		// CONTACT.
		$layouts[] = ra_layout(
			'contact',
			'Contact (details + form)',
			array_merge(
				ra_head_fields( 'contact' ),
				array(
					ra_f(
						'contact',
						'show_details',
						'true_false',
						'Show contact details (from Theme Settings)',
						array(
							'default_value' => 1,
							'ui'            => 1,
							'wrapper'       => array( 'width' => 50 ),
						)
					),
					ra_f(
						'contact',
						'form',
						'post_object',
						'Contact Form 7 form',
						array(
							'post_type'     => array( 'wpcf7_contact_form' ),
							'return_format' => 'id',
							'allow_null'    => 1,
							'ui'            => 1,
							'wrapper'       => array( 'width' => 50 ),
						)
					),
					ra_f( 'contact', 'form_title', 'text', 'Form heading', array( 'default_value' => 'Send Us a Message' ) ),
				)
			)
		);

		// LATEST POSTS.
		$layouts[] = ra_layout(
			'posts',
			'Latest Blog Posts',
			array_merge(
				ra_head_fields( 'posts' ),
				array(
					ra_f(
						'posts',
						'count',
						'number',
						'Number of posts',
						array(
							'default_value' => 3,
							'min'           => 1,
							'max'           => 9,
							'wrapper'       => array( 'width' => 30 ),
						)
					),
					ra_f(
						'posts',
						'category',
						'taxonomy',
						'Category (optional)',
						array(
							'taxonomy'      => 'category',
							'field_type'    => 'select',
							'allow_null'    => 1,
							'return_format' => 'id',
							'add_term'      => 0,
							'wrapper'       => array( 'width' => 30 ),
						)
					),
					ra_f( 'posts', 'button', 'link', 'Button', array( 'wrapper' => array( 'width' => 40 ) ) ),
				)
			)
		);

		// FREE CONTENT.
		$layouts[] = ra_layout(
			'content',
			'Free Text Content',
			array(
				ra_f( 'content', 'content', 'wysiwyg', 'Content', array( 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 1 ) ),
				ra_f(
					'content',
					'narrow',
					'true_false',
					'Narrow reading width',
					array(
						'default_value' => 1,
						'ui'            => 1,
					)
				),
			)
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_ra_page_sections',
				'title'                 => 'Page Sections',
				'fields'                => array(
					array(
						'key'          => 'field_ra_page_sections',
						'name'         => 'ra_sections',
						'label'        => 'Sections',
						'type'         => 'flexible_content',
						'instructions' => 'Build the page from sections. Leave empty to use the normal editor content (e.g. legal pages).',
						'button_label' => 'Add Section',
						'layouts'      => $layouts,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
					),
				),
				'position'              => 'acf_after_title',
				'style'                 => 'seamless',
				'menu_order'            => 0,
				'hide_on_screen'        => array(),
				'active'                => true,
			)
		);
	}
);
