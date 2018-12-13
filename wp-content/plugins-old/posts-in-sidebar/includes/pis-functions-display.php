<?php
/**
 * This file contains the display functions of the plugin.
 *
 * @package PostsInSidebar
 * @since 1.23
 */

/**
 * Prevent direct access to this file.
 *
 * @since 2.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'No script kiddies please!' );
}

/*
 * Display functions.
 ******************************************************************************
 */

/**
 * Returns the title of the post.
 *
 * @param array $args {
 *     The array containing the custom parameters.
 *     @type string  $title_margin      The margin for the title.
 *     @type string  $margin_unit       The measure unit for the margin.
 *                                      Accepted values:
 *                                      px (default), %, em, rem
 *     @type boolean $gravatar_display  If the Gravatar should be displayed.
 *     @type string  $gravatar_position The position of the Gravatar.
 *                                      Accepted values:
 *                                      next_author (default), next_title, next_post
 *     @type string  $gravatar_author   The ID of the post author.
 *     @type integer $gravatar_size     The size of the displayed Gravatar.
 *     @type string  $gravatar_default  The URL of the default Gravatar image.
 *     @type boolean $link_on_title     If the title should be linked to the post.
 *     @type boolean $arrow             If an HTML arrow should be added to the title.
 *     @type integer $title_length      The length of the post title.
 *                                      Accepted values:
 *                                      0 (default, meaning no shortening), any positive integer.
 *     @type boolean $title_hellipsis   If an horizontal ellipsis should be added after the shortened title.
 * }
 *
 * @return string The HTML paragraph with the title.
 * @since 3.8.4
 * @since 4.4.0 Added `$title_length` option.
 * @since 4.4.0 Added `$title_hellipsis` option.
 */
function pis_the_title( $args ) {
	$defaults = array(
		'title_margin'      => '',
		'margin_unit'       => '',
		'gravatar_display'  => false,
		'gravatar_position' => '',
		'gravatar_author'   => '',
		'gravatar_size'     => '32',
		'gravatar_default'  => '',
		'link_on_title'     => true,
		'arrow'             => false,
		'title_length'      => 0,
		'title_length_unit' => 'words',
		'title_hellipsis'   => true,
	);

	$args = wp_parse_args( $args, $defaults );

	$output = '<p ' . pis_paragraph( $args['title_margin'], $args['margin_unit'], 'pis-title', 'pis_title_class' ) . '>';

	// The Gravatar.
	if ( $args['gravatar_display'] && 'next_title' === $args['gravatar_position'] ) {
		$output .= pis_get_gravatar(
			array(
				'author'  => $args['gravatar_author'],
				'size'    => $args['gravatar_size'],
				'default' => $args['gravatar_default'],
			)
		);
	}

	if ( $args['link_on_title'] ) {
		$output .= '<a ' . pis_class( 'pis-title-link', apply_filters( 'pis_title_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
	}

	if ( 0 === $args['title_length'] ) {
		$output .= get_the_title();
	} else {
		$args['title_hellipsis'] ? $title_hellip = '&hellip;' : $title_hellip = '';
		if ( 'words' === $args['title_length_unit'] ) {
			$output .= wp_trim_words( get_the_title(), $args['title_length'], $title_hellip );
		} else {
			if ( strlen( get_the_title() ) <= $args['title_length'] ) {
				$title_hellip = '';
			}
			$output .= rtrim( mb_substr( get_the_title(), 0, $args['title_length'], get_option( 'blog_charset' ) ) ) . $title_hellip;
		}
	}

	if ( $args['arrow'] ) {
		$output .= pis_arrow();
	}

	if ( $args['link_on_title'] ) {
		$output .= '</a>';
	}

	$output .= '</p>';

	return $output;
}

/**
 * Returns the categories of the post.
 *
 * @param array $args {
 *     The array containing the custom parameters.
 *     @type string $post_id           The ID of the post.
 *     @type string $categ_sep         The separator for the categories.
 *     @type string $categories_margin The margin for the categories.
 *     @type string $margin_unit       The measure unit for the margin.
 *                                     Accepted values:
 *                                     px (default), %, em, rem
 *     @type string $categ_text        The leading text for the categories.
 * }
 *
 * @return string The HTML paragraph with the categories.
 * @since 3.8.4
 */
function pis_the_categories( $args ) {
	$defaults = array(
		'post_id'           => '',
		'categ_sep'         => ',',
		'categories_margin' => '',
		'margin_unit'       => 'px',
		'categ_text'        => esc_html__( 'Category:', 'posts-in-sidebar' ),
	);

	$args = wp_parse_args( $args, $defaults );

	$output = '';

	$list_of_categories = get_the_term_list( $args['post_id'], 'category', '', $args['categ_sep'] . ' ', '' );

	if ( $list_of_categories ) {
		$output = '<p ' . pis_paragraph( $args['categories_margin'], $args['margin_unit'], 'pis-categories-links', 'pis_categories_class' ) . '>';
		if ( $args['categ_text'] ) {
			$output .= $args['categ_text'] . '&nbsp;';
		}
		$output .= apply_filters( 'pis_categories_list', $list_of_categories );
		$output .= '</p>';
	}

	return $output;
}

/**
 * Return the tags of the post.
 *
 * @param array $args {
 *     The array containing the custom parameters.
 *     @type string $post_id      The ID of the post.
 *     @type string $hashtag      The symbol to be used as hashtag.
 *     @type string $tag_sep      The separator for the tags.
 *     @type string $tags_margin  The margin for the tags.
 *     @type string $margin_unit  The measure unit for the margin.
 *                                Accepted values:
 *                                px (default), %, em, rem
 *     @type string $tags_text    The leading text for the tags.
 * }
 * @return string The HTML paragraph with the tags.
 * @uses pis_paragraph()
 * @since 3.8.4
 */
function pis_the_tags( $args ) {
	$defaults = array(
		'post_id'     => '',
		'hashtag'     => '#',
		'tag_sep'     => '',
		'tags_margin' => '',
		'margin_unit' => 'px',
		'tags_text'   => esc_html__( 'Tags:', 'posts-in-sidebar' ),
	);

	$args = wp_parse_args( $args, $defaults );

	$output = '';

	$list_of_tags = get_the_term_list( $args['post_id'], 'post_tag', $args['hashtag'], $args['tag_sep'] . ' ' . $args['hashtag'], '' );

	if ( $list_of_tags ) {
		$output .= '<p ' . pis_paragraph( $args['tags_margin'], $args['margin_unit'], 'pis-tags-links', 'pis_tags_class' ) . '>';
		if ( $args['tags_text'] ) {
			$output .= $args['tags_text'] . '&nbsp;';
		}
		$output .= apply_filters( 'pis_tags_list', $list_of_tags );
		$output .= '</p>';
	}

	return $output;
}

/**
 * Return the custom fields of the post.
 *
 * @param array $args {
 *     The array containing the custom parameters.
 *     @type string  $post_id             The ID of the post.
 *     @type boolean $custom_field_all    If the user want to display all the custom fields of the post.
 *     @type string  $meta                The post meta.
 *     @type string  $custom_field_txt    The leading text for custom fields.
 *     @type boolean $custom_field_key    If the user want to display the custom field key.
 *     @type string  $custom_field_sep    The separator between meta key and value.
 *     @type string  $custom_field_count  The custom field content length (in characters).
 *     @type string  $custom_field_hellip The separator between meta key and value.
 *     @type string  $custom_field_margin The custom field bottom margin.
 *     @type string  $margin_unit         The unit for margin.
 * }
 * @since 3.8.4
 */
function pis_custom_field( $args ) {
	$defaults = array(
		'post_id'             => '',
		'custom_field_all'    => false,
		'meta'                => '',
		'custom_field_txt'    => '',
		'custom_field_key'    => false,
		'custom_field_sep'    => ':',
		'custom_field_count'  => '',
		'custom_field_hellip' => '&hellip;',
		'custom_field_margin' => '',
		'margin_unit'         => 'px',
	);

	$args = wp_parse_args( $args, $defaults );

	$post_id             = $args['post_id'];
	$custom_field_all    = $args['custom_field_all'];
	$meta                = $args['meta'];
	$custom_field_txt    = $args['custom_field_txt'];
	$custom_field_key    = $args['custom_field_key'];
	$custom_field_sep    = $args['custom_field_sep'];
	$custom_field_count  = $args['custom_field_count'];
	$custom_field_hellip = $args['custom_field_hellip'];
	$custom_field_margin = $args['custom_field_margin'];
	$margin_unit         = $args['margin_unit'];

	$output = '';

	// The leading text for the custom fields.
	if ( $custom_field_txt ) {
		$cf_text = '<span class="pis-custom-field-text-before">' . rtrim( $custom_field_txt ) . '</span> ';
	} else {
		$cf_text = '';
	}

	// If the user want to display all the custom fields of the post.
	if ( $custom_field_all ) {
		$the_custom_fields = get_post_custom( $post_id );
		if ( $the_custom_fields ) {
			foreach ( $the_custom_fields as $cf_key => $cf_value ) {
				// Make sure to avoid custom fields starting with _ (an underscore).
				if ( '_' !== substr( $cf_key, 0, 1 ) ) {
					foreach ( $cf_value as $k => $cf_v ) {

						// If we have to display a text before the custom field.
						if ( $custom_field_key ) {
							$key = '<span class="pis-custom-field-key">' . $cf_key . '</span><span class="pis-custom-field-divider">' . $custom_field_sep . '</span>';
						} else {
							$key = '';
						}

						// If we have to reduce the length of the custom field value.
						if ( ! empty( $custom_field_count ) ) {
							if ( $custom_field_count > strlen( $cf_v ) ) {
								$cf_h = '';
							} else {
								$cf_h = $custom_field_hellip;
							}
							$cf_text_value = rtrim( mb_substr( $cf_v, 0, $custom_field_count, get_option( 'blog_charset' ) ) ) . $cf_h;
						} else {
							$cf_text_value = $cf_v;
						}

						// Build the custom field value line.
						$cf_value = '<span class="pis-custom-field-value">' . $cf_text_value . '</span>';

						// Create the class from the key of the custom field key.
						$pis_cf_key_class = ' pis-' . preg_replace( '/[\s]+/', '-', trim( $cf_key, ' -' ) );

						// Build the final output.
						$output .= '<p ' . pis_paragraph( $custom_field_margin, $margin_unit, 'pis-custom-field' . $pis_cf_key_class, 'pis_custom_fields_class' ) . '>';
						$output .= $cf_text . $key . $cf_value;
						$output .= '</p>';
					}
				}
			}
		}
	} else {
		$the_custom_field = get_post_meta( $post_id, $meta, false );
		if ( $the_custom_field ) {
			if ( $custom_field_key ) {
				$key = '<span class="pis-custom-field-key">' . $meta . '</span><span class="pis-custom-field-divider">' . $custom_field_sep . '</span>';
			} else {
				$key = '';
			}
			if ( ! empty( $custom_field_count ) ) {
				if ( $custom_field_count > strlen( $the_custom_field[0] ) ) {
					$custom_field_hellip = '';
				}
				/* It was originally: `$cf_text_value = wp_trim_words( $the_custom_field[0], $custom_field_count, $custom_field_hellip );` */
				$cf_text_value = rtrim( mb_substr( $the_custom_field[0], 0, $custom_field_count, get_option( 'blog_charset' ) ) ) . $custom_field_hellip;
			} else {
				if ( isset( $the_custom_field[0] ) ) {
					$cf_text_value = $the_custom_field[0];
				} else {
					$cf_text_value = '';
				}
			}
			$cf_value = '<span class="pis-custom-field-value">' . $cf_text_value . '</span>';

			$output .= '<p ' . pis_paragraph( $custom_field_margin, $margin_unit, 'pis-custom-field ' . preg_replace( '/[\s]+/', '-', trim( $custom_field_key, ' -' ) ), 'pis_custom_fields_class' ) . '>';
			$output .= $cf_text . $key . $cf_value;
			$output .= '</p>';
		}
	}

	return $output;
}

/**
 * Add the thumbnail of the post.
 *
 * @param array $args {
 *    The array of parameters.
 *
 *    @type string  image_align         Alignment of the image. Accepts 'no_change', 'left', 'right', 'center'. Default 'no_change'.
 *    @type string  side_image_margin   The left/right margin for the image. Default null.
 *    @type string  bottom_image_margin The left/right margin for the image. Default null.
 *    @type string  margin_unit         The margin unit. Accepts 'px', '%', 'em', 'rem'. Default 'px'.
 *    @type string  pis_query           The query containing the post. Default empty.
 *    @type string  image_size          The size of the image. Default 'thumbnail'.
 *    @type boolean thumb_wrap          If the image should be wrapped in a HTML p element. Default false.
 *    @type string  custom_image_url    The URL of the custom thumbnail. Default empty.
 *    @type boolean custom_img_no_thumb If the custom image should be used only if the post has not a featured image. Default true.
 *    @type string  post_type           The post type. Default 'post'.
 *    @type string  image_link          The URL to a custom address. Default empty.
 *    @type boolean image_link_to_post  If the thumbnail should be linked to the post. Default true.
 * }
 * @since 1.18
 * @return string The HTML for the thumbnail.
 */
function pis_the_thumbnail( $args ) {
	$defaults = array(
		'image_align'         => 'no_change',
		'side_image_margin'   => null,
		'bottom_image_margin' => null,
		'margin_unit'         => 'px',
		'pis_query'           => '',
		'image_size'          => 'thumbnail',
		'thumb_wrap'          => false,
		'custom_image_url'    => '',
		'custom_img_no_thumb' => true,
		'post_type'           => 'post',
		'image_link'          => '',
		'image_link_to_post'  => true,
	);

	$args = wp_parse_args( $args, $defaults );

	$image_align         = $args['image_align'];
	$side_image_margin   = $args['side_image_margin'];
	$bottom_image_margin = $args['bottom_image_margin'];
	$margin_unit         = $args['margin_unit'];
	$pis_query           = $args['pis_query'];
	$image_size          = $args['image_size'];
	$thumb_wrap          = $args['thumb_wrap'];
	$custom_image_url    = $args['custom_image_url'];
	$custom_img_no_thumb = $args['custom_img_no_thumb'];
	$post_type           = $args['post_type'];
	$image_link          = $args['image_link'];
	$image_link_to_post  = $args['image_link_to_post'];

	if ( $thumb_wrap ) {
		$open_wrap  = '<p class="pis-thumbnail">';
		$close_wrap = '</p>';
	} else {
		$open_wrap  = '';
		$close_wrap = '';
	}

	switch ( $image_align ) {
		case 'left':
			$image_class = 'alignleft ';
			$image_style = '';
			if ( ! is_null( $side_image_margin ) || ! is_null( $bottom_image_margin ) ) {
				$image_style = ' style="display: inline; float: left; margin-right: ' . $side_image_margin . $margin_unit . '; margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
				$image_style = str_replace( ' margin-right: px;', '', $image_style );
				$image_style = str_replace( ' margin-bottom: px;', '', $image_style );
			}
			break;
		case 'right':
			$image_class = 'alignright ';
			$image_style = '';
			if ( ! is_null( $side_image_margin ) || ! is_null( $bottom_image_margin ) ) {
				$image_style = ' style="display: inline; float: right; margin-left: ' . $side_image_margin . $margin_unit . '; margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
				$image_style = str_replace( ' margin-left: px;', '', $image_style );
				$image_style = str_replace( ' margin-bottom: px;', '', $image_style );
			}
			break;
		case 'center':
			$image_class = 'aligncenter ';
			$image_style = '';
			if ( ! is_null( $bottom_image_margin ) ) {
				$image_style = ' style="margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
			}
			break;
		default:
			$image_class = '';
			$image_style = '';
	}

	$output = $open_wrap;

	if ( $image_link_to_post ) {
		// Figure out if a custom link for the featured image has been set.
		if ( $image_link ) {
			$the_image_link = $image_link;
		} else {
			$the_image_link = get_permalink();
		}
		$output .= '<a ' . pis_class( 'pis-thumbnail-link', apply_filters( 'pis_thumbnail_link_class', '' ), false ) . ' href="' . esc_url( wp_strip_all_tags( $the_image_link ) ) . '" rel="bookmark">';
	}

	/**
	 * If the post type is an attachment (an image, or any other attachment),
	 * the construct is different.
	 *
	 * @since 1.28
	 */
	if ( 'attachment' === $post_type ) {
		$final_image_class = rtrim( "attachment-$image_size pis-thumbnail-img " . $image_class . apply_filters( 'pis_thumbnail_class', '' ) );
		$image_html        = wp_get_attachment_image(
			$pis_query->post->ID,
			$image_size,
			false,
			array( 'class' => $final_image_class )
		);
	} else {
		$final_image_class = rtrim( 'pis-thumbnail-img ' . $image_class . apply_filters( 'pis_thumbnail_class', '' ) );
		/**
		 * If the post has not a post-thumbnail AND a custom image URL is defined (in this case the custom image will be used only if the post has not a featured image)
		 * OR
		 * if custom image URL is defined AND the custom image should be used in every case (in this case the custom image will be used for all posts, even those who already have a featured image).
		 */
		if ( ( ! has_post_thumbnail() && $custom_image_url ) || ( $custom_image_url && ! $custom_img_no_thumb ) ) {
			$image_html = '<img src="' . esc_url( $custom_image_url ) . '" alt="" class="' . $final_image_class . '">';
		} else {
			$image_html = get_the_post_thumbnail(
				$pis_query->post->ID,
				$image_size,
				array( 'class' => $final_image_class )
			);
		}
	}

	$output .= str_replace( '<img', '<img' . $image_style, $image_html );

	if ( $image_link_to_post ) {
		$output .= '</a>';
	}

	$output .= $close_wrap;

	return $output;
}

/**
 * Add the text of the post in form of excerpt, full post, and so on.
 *
 * @since 1.18
 * @param array $args The array containing the custom parameters.
 * @return string The HTML for the text of the post.
 * @uses pis_break_text()
 * @uses pis_more_arrow()
 */
function pis_the_text( $args ) {
	$defaults = array(
		'excerpt'         => 'excerpt',
		'pis_query'       => '',
		'exc_length'      => 20,
		'exc_length_unit' => 'words',
		'the_more'        => esc_html__( 'Read more&hellip;', 'posts-in-sidebar' ),
		'exc_arrow'       => false,
	);

	$args = wp_parse_args( $args, $defaults );

	$excerpt         = $args['excerpt'];
	$pis_query       = $args['pis_query'];
	$exc_length      = $args['exc_length'];
	$exc_length_unit = $args['exc_length_unit'];
	$the_more        = $args['the_more'];
	$exc_arrow       = $args['exc_arrow'];

	$output = '';

	/*
		"Full content"   = the content of the post as displayed in the page.
		"Rich content"   = the content with inline images, titles and more (shortcodes will be executed).
		"Content"        = the full text of the content, whitout any ornament (shortcodes will be stripped).
		"More excerpt"   = the excerpt up to the point of the "more" tag (inserted by the user, shortcodes will be stripped).
		"Excerpt"        = the excerpt as defined by the user or generated by WordPress (shortcodes will be stripped).
		"Only Read more" = no excerpt, only the Read more link
	*/
	switch ( $excerpt ) :

		case 'full_content':
			/**
			 * Filter the post content. If not filtered, shortcodes (and other things) will not be executed.
			 * See https://codex.wordpress.org/Function_Reference/get_the_content
			 */
			$output = apply_filters( 'the_content', get_the_content() );
			break;

		case 'rich_content':
			$content = $pis_query->post->post_content;
			// Honor any paragraph break.
			$content = pis_break_text( $content );
			$content = do_shortcode( $content );
			$output  = apply_filters( 'pis_rich_content', $content );
			break;

		case 'content':
			// Remove shortcodes.
			$content = strip_shortcodes( $pis_query->post->post_content );
			// remove any HTML tag.
			$content = wp_kses( $content, array() );
			// Honor any paragraph break.
			$content = pis_break_text( $content );
			$output  = apply_filters( 'pis_content', $content );
			break;

		case 'more_excerpt':
			$excerpt_text = strip_shortcodes( $pis_query->post->post_content );
			$testformore  = strpos( $excerpt_text, '<!--more-->' );
			if ( $testformore ) {
				$excerpt_text = substr( $excerpt_text, 0, $testformore );
			} else {
				if ( 'words' === $exc_length_unit ) {
					$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
				} else {
					$excerpt_text = substr( $excerpt_text, 0, $exc_length ) . '&hellip;';
				}
			}
			$output = apply_filters( 'pis_more_excerpt_text', $excerpt_text ) . pis_more_arrow( $the_more, false, $exc_arrow, false, true );
			break;

		case 'excerpt':
			/**
			 * Check if the Relevanssi plugin is active and restore the user-defined excerpt in place of the Relevanssi-generated excerpt.
			 *
			 * @see https://wordpress.org/support/topic/issue-with-excerpts-when-using-relevanssi-search
			 * @since 1.26
			 */
			if ( function_exists( 'relevanssi_do_excerpt' ) && isset( $pis_query->post->original_excerpt ) ) {
				$pis_query->post->post_excerpt = $pis_query->post->original_excerpt;
			}

			// If we have a user-defined excerpt...
			if ( $pis_query->post->post_excerpt ) {
				// Honor any paragraph break.
				$user_excerpt = pis_break_text( $pis_query->post->post_excerpt );
				$output       = apply_filters( 'pis_user_excerpt', $user_excerpt ) . pis_more_arrow( $the_more, false, $exc_arrow, false, true );
				$output       = trim( $output );
			} else { // ... else generate an excerpt.
				$excerpt_text = wp_strip_all_tags( strip_shortcodes( $pis_query->post->post_content ) );
				$no_the_more  = false;
				$hellip       = '&hellip;';
				if ( 'words' === $exc_length_unit ) {
					if ( count( explode( ' ', $excerpt_text ) ) <= $exc_length ) {
						$no_the_more = true;
					}
					$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, $hellip );
				} else {
					if ( strlen( $excerpt_text ) <= $exc_length ) {
						$no_the_more = true;
						$hellip      = '';
					}
					$excerpt_text = rtrim( mb_substr( $excerpt_text, 0, $exc_length, get_option( 'blog_charset' ) ) ) . $hellip;
				}
				$output = apply_filters( 'pis_excerpt_text', $excerpt_text );
				$output = trim( $output );
				if ( $output ) {
					$output .= pis_more_arrow( $the_more, $no_the_more, $exc_arrow, false, true );
				}
			}
			break;

		case 'only_read_more':
			$excerpt_text = '';
			$output       = apply_filters( 'pis_only_read_more', $excerpt_text ) . pis_more_arrow( $the_more, false, $exc_arrow, false, true );
			$output       = trim( $output );

	endswitch; // Close The text.

	return $output;
}

/**
 * Add the utilities section: author, date of the post and comments.
 *
 * @param array $args {
 *    The array of parameters.
 *
 *    @type boolean display_author    If display the post's outhor. Default false.
 *    @type boolean display_date      If display the post's date. Default false.
 *    @type boolean display_mod_date  If display the modification date of the post. Default false.
 *    @type boolean comments          If display comments number. Default false.
 *    @type integer utility_margin    The CSS margin value for the section. Default null value.
 *    @type string  margin_unit       The margin unit for $utility_margin. Accepts 'px', '%', 'em', 'rem'. Default 'px'.
 *    @type string  author_text       The text to be prepended before the author's name. Default 'By'.
 *    @type boolean linkify_author    If link the author name to the posts' archive of the author. Default false.
 *    @type string  utility_sep       The separator between the elements of the section. Default '|'.
 *    @type string  date_text         The text to be prepended before the date. Default 'Published on'.
 *    @type boolean linkify_date      If link the date name to the posts. Default false.
 *    @type string  mod_date_text     The text to be prepended before the modification date. Default 'Modified on'.
 *    @type boolean linkify_mod_date  If link the modification date to the post. Default false.
 *    @type string  comments_text     The text to be prepended before the comments number. Default 'Comments:'.
 *    @type string  pis_post_id       The ID of the post. Default empy.
 *    @type boolean link_to_comments  If link the comments text to the comments form. Default true.
 *    @type boolean gravatar_display  If display the Gravatar. Default false.
 *    @type string  gravatar_position The position for the Gravatar. Accepts 'next_title', 'next_post', 'next_author'. Default empty.
 *    @type string  gravatar_author   The ID of the post's author. Default empty value.
 *    @type integer gravatar_size     The size of the Gravatar. Default 32.
 *    @type string  gravatar_default  The default image for Gravatar when unavailable. Default empty string.
 * }
 * @since 1.18
 * @return string The HTML for the section.
 * @uses pis_paragraph()
 * @uses pis_class()
 * @uses pis_get_comments_number()
 */
function pis_utility_section( $args ) {
	$defaults = array(
		'display_author'        => false,
		'display_date'          => false,
		'display_time'          => false,
		'display_mod_date'      => false,
		'display_mod_time'      => false,
		'comments'              => false,
		'utility_margin'        => null,
		'margin_unit'           => 'px',
		'author_text'           => esc_html__( 'By', 'posts-in-sidebar' ),
		'linkify_author'        => false,
		'utility_sep'           => '|',
		'date_text'             => esc_html__( 'Published on', 'posts-in-sidebar' ),
		'linkify_date'          => false,
		'mod_date_text'         => esc_html__( 'Modified on', 'posts-in-sidebar' ),
		'linkify_mod_date'      => false,
		'comments_text'         => esc_html__( 'Comments:', 'posts-in-sidebar' ),
		'pis_post_id'           => '',
		'link_to_comments'      => true,
		'display_comm_num_only' => false,
		'hide_zero_comments'    => false,
		'gravatar_display'      => false,
		'gravatar_position'     => '',
		'gravatar_author'       => '',
		'gravatar_size'         => 32,
		'gravatar_default'      => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$display_author        = $args['display_author'];
	$display_date          = $args['display_date'];
	$display_time          = $args['display_time'];
	$display_mod_date      = $args['display_mod_date'];
	$display_mod_time      = $args['display_mod_time'];
	$comments              = $args['comments'];
	$utility_margin        = $args['utility_margin'];
	$margin_unit           = $args['margin_unit'];
	$author_text           = $args['author_text'];
	$linkify_author        = $args['linkify_author'];
	$utility_sep           = $args['utility_sep'];
	$date_text             = $args['date_text'];
	$linkify_date          = $args['linkify_date'];
	$mod_date_text         = $args['mod_date_text'];
	$linkify_mod_date      = $args['linkify_mod_date'];
	$comments_text         = $args['comments_text'];
	$pis_post_id           = $args['pis_post_id'];
	$link_to_comments      = $args['link_to_comments'];
	$display_comm_num_only = $args['display_comm_num_only'];
	$hide_zero_comments    = $args['hide_zero_comments'];
	$gravatar_display      = $args['gravatar_display'];
	$gravatar_position     = $args['gravatar_position'];
	$gravatar_author       = $args['gravatar_author'];
	$gravatar_size         = $args['gravatar_size'];
	$gravatar_default      = $args['gravatar_default'];

	$output = '';

	if ( $display_author || $display_date || $display_mod_date || $comments ) {
		$output .= '<p ' . pis_paragraph( $utility_margin, $margin_unit, 'pis-utility', 'pis_utility_class' ) . '>';
	}

	/* The Gravatar */
	if ( $gravatar_display && 'next_author' === $gravatar_position ) {
		$output .= pis_get_gravatar(
			array(
				'author'  => $gravatar_author,
				'size'    => $gravatar_size,
				'default' => $gravatar_default,
			)
		);
	}

	/* The author */
	if ( $display_author ) {
		$output .= '<span ' . pis_class( 'pis-author', apply_filters( 'pis_author_class', '' ), false ) . '>';
		if ( $author_text ) {
			$output .= $author_text . ' ';
		}
		if ( $linkify_author ) {
			$author_link = get_author_posts_url( get_the_author_meta( 'ID' ) );
			$output     .= '<a ' . pis_class( 'pis-author-link', apply_filters( 'pis_author_link_class', '' ), false ) . ' href="' . $author_link . '" rel="author">';
			$output     .= get_the_author();
			$output     .= '</a>';
		} else {
			$output .= get_the_author();
		}
		$output .= '</span>';
	}

	/* The date */
	if ( $display_date ) {
		if ( $display_author ) {
			$output .= '<span ' . pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ), false ) . '> ' . $utility_sep . ' </span>';
		}
		$output .= '<span ' . pis_class( 'pis-date', apply_filters( 'pis_date_class', '' ), false ) . '>';
		if ( $date_text ) {
			$output .= $date_text . ' ';
		}
		if ( $display_time ) {
			// translators: %s is the time of the post.
			$post_time = ' <span class="' . pis_class( 'pis-time', apply_filters( 'pis_time_class', '' ), false ) . '">' . sprintf( esc_html_x( 'at %s', '%s is the time of the post.', 'posts-in-sidebar' ), get_the_time() ) . '</span>';
		} else {
			$post_time = '';
		}
		if ( $linkify_date ) {
			$output .= '<a ' . pis_class( 'pis-date-link', apply_filters( 'pis_date_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
			$output .= get_the_date() . $post_time;
			$output .= '</a>';
		} else {
			$output .= get_the_date() . $post_time;
		}
		$output .= '</span>';
	}

	/**
	 * The modification date.
	 * When publishing a new post, WordPress stores two dates:
	 * - the creation date into `post_date` database column;
	 * - the modification date into `post_modified` database column.
	 * and the two dates and times are the same.
	 * In this situation, in order to figure out if a post has been modified
	 * after its publication, we have to compare the times (not simply the dates).
	 */
	if ( $display_mod_date && get_the_modified_time() !== get_the_time() ) {
		if ( $display_author || $display_date ) {
			$output .= '<span ' . pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ), false ) . '> ' . $utility_sep . ' </span>';
		}
		$output .= '<span ' . pis_class( 'pis-mod-date', apply_filters( 'pis_mod_date_class', '' ), false ) . '>';
		if ( $mod_date_text ) {
			$output .= $mod_date_text . ' ';
		}
		if ( $display_mod_time ) {
			// translators: %s is the time of the post modified.
			$post_mod_time = ' <span class="' . pis_class( 'pis-mod-time', apply_filters( 'pis_mod_time_class', '' ), false ) . '">' . sprintf( esc_html_x( 'at %s', '%s is the time of the post modified.', 'posts-in-sidebar' ), get_the_modified_time() ) . '</span>';
		} else {
			$post_mod_time = '';
		}
		if ( $linkify_mod_date ) {
			$output .= '<a ' . pis_class( 'pis-mod-date-link', apply_filters( 'pis_mod_date_link_class', '' ), false ) . ' href="' . get_permalink() . '" rel="bookmark">';
			$output .= get_the_modified_date() . $post_mod_time;
			$output .= '</a>';
		} else {
			$output .= get_the_modified_date() . $post_mod_time;
		}
		$output .= '</span>';
	}

	/* The comments */
	if ( ! post_password_required() ) {
		if ( $comments ) {
			$num_comments = get_comments_number( $pis_post_id );
			if ( '0' === $num_comments && $hide_zero_comments ) {
				$output .= '';
			} else {
				if ( $display_author || $display_date || $display_mod_date ) {
					$output .= '<span ' . pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ), false ) . '> ' . $utility_sep . ' </span>';
				}
				$output .= '<span ' . pis_class( 'pis-comments', apply_filters( 'pis_comments_class', '' ), false ) . '>';
				if ( $comments_text ) {
					$output .= $comments_text . ' ';
				}
				$output .= pis_get_comments_number( $pis_post_id, $link_to_comments, $display_comm_num_only );
				$output .= '</span>';
			}
		}
	}

	if ( $display_author || $display_date || $display_mod_date || $comments ) {
		$output .= '</p>';
	}

	return $output;
}

/**
 * Return the custom taxonomies of the current post.
 *
 * @since 1.29
 * @param array $args The array containing the custom parameters.
 * @see https://codex.wordpress.org/Function_Reference/get_the_terms#Get_terms_for_all_custom_taxonomies
 */
function pis_custom_taxonomies_terms_links( $args ) {
	$defaults = array(
		'post_id'      => '',
		'term_hashtag' => '',
		'term_sep'     => ',',
		'terms_margin' => null,
		'margin_unit'  => 'px',
	);

	$args = wp_parse_args( $args, $defaults );

	$post_id      = $args['post_id'];
	$term_hashtag = $args['term_hashtag'];
	$term_sep     = $args['term_sep'];
	$terms_margin = $args['terms_margin'];
	$margin_unit  = $args['margin_unit'];

	// Get post by post id.
	$post = get_post( $post_id );

	// Get post type by post.
	$post_type = $post->post_type;

	// Get post type taxonomies.
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );

	$output = '';

	foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {
		// Exclude the standard WordPress 'category' and 'post_tag' taxonomies otherwise we'll have a duplicate in the front-end.
		if ( 'category' !== $taxonomy_slug && 'post_tag' !== $taxonomy_slug ) {
			// Get the terms related to post.
			$list_of_terms = get_the_term_list( $post_id, $taxonomy_slug, $term_hashtag, $term_sep . ' ' . $term_hashtag, '' );
			if ( ! ( is_wp_error( $list_of_terms ) ) && ( $list_of_terms ) ) {
				$output .= '<p ' . pis_paragraph( $terms_margin, $margin_unit, 'pis-terms-links pis-' . $taxonomy_slug, 'pis_terms_class' ) . '>';
				$output .= '<span class="pis-tax-name">' . $taxonomy->label . '</span>: ' . apply_filters( 'pis_terms_list', $list_of_terms );
				$output .= '</p>';
			}
		}
	}

	return $output;
}

/**
 * Returns the HTML for the comments link.
 *
 * @param integer $pis_post_id The ID of the post.
 * @param boolean $link If the output is to be wrapped into a link to comments.
 * @param boolean $display_comm_num_only If displaying the number of comments only.
 * @since 3.0
 */
function pis_get_comments_number( $pis_post_id, $link, $display_comm_num_only ) {
	// get_comments_number() returns only a numeric value in form of a string.
	$num_comments = get_comments_number( $pis_post_id );

	/**
	 * Build the comments number string.
	 *
	 * The value returned by get_comments_number() is
	 * "a numeric string representing the number of comments the post has".
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_comments_number/
	 */
	if ( '0' === $num_comments ) {
		// Zero comments.
		if ( comments_open( $pis_post_id ) ) {
			$comments_text = esc_html__( 'Leave a comment', 'posts-in-sidebar' );
			if ( $display_comm_num_only ) {
				$comments_text = $num_comments;
			}
			$comments_text = apply_filters( 'pis_zero_comments', $comments_text );
		} else {
			$comments_text = esc_html__( 'Comments are closed', 'posts-in-sidebar' );
			$comments_text = apply_filters( 'pis_zero_comments_closed', $comments_text );
		}
	} elseif ( '1' === $num_comments ) {
		// 1 comment.
		$comments_text = esc_html__( '1 Comment', 'posts-in-sidebar' );
		if ( $display_comm_num_only ) {
			$comments_text = $num_comments;
		}
		$comments_text = apply_filters( 'pis_one_comment', $comments_text );
	} else {
		// More than 1 comments.
		// translators: %d is the number of comments.
		$comments_text = sprintf( esc_html__( '%s Comments', 'posts-in-sidebar' ), $num_comments );
		if ( $display_comm_num_only ) {
			$comments_text = $num_comments;
		}
		$comments_text = apply_filters( 'pis_more_comments', $comments_text );
	}

	// Build the HTML string for the comments.
	if ( $link ) {
		// If there is no comment and comments are closed, do not create a link to comment form, since there is no #respond CSS id.
		if ( '0' === $num_comments && ! comments_open( $pis_post_id ) ) {
			$output = $comments_text;
		} else {
			$output = '<a ' . pis_class( 'pis-comments-link', apply_filters( 'pis_comments_link_class', '' ), false ) . ' href="' . get_comments_link( $pis_post_id ) . '">' . $comments_text . '</a>';
		}
	} else {
		$output = $comments_text;
	}

	return $output;
}

/**
 * Returns the HTML string for the author's Gravatar image.
 *
 * @param array $args The array containing the custom args.
 * @since 3.0
 */
function pis_get_gravatar( $args ) {
	$defaults = array(
		'author'  => '',
		'size'    => 32,
		'default' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$output = '<span ' . pis_class( 'pis-gravatar', apply_filters( 'pis_gravatar_class', '' ), false ) . '>' . get_avatar( $args['author'], $args['size'], $args['default'] ) . '</span>';

	return $output;
}

/**
 * Returns the HTML string for the archive link.
 *
 * @param array $args The array containing the custom args.
 * @since 3.0
 */
function pis_archive_link( $args ) {
	$defaults = array(
		'link_to'        => 'category',
		'tax_name'       => '',
		'tax_term_name'  => '',
		'archive_text'   => esc_html__( 'Display all posts', 'posts-in-sidebar' ),
		'archive_margin' => null,
		'margin_unit'    => 'px',
	);

	$args = wp_parse_args( $args, $defaults );

	$link_to        = $args['link_to'];
	$tax_name       = $args['tax_name'];
	$tax_term_name  = $args['tax_term_name'];
	$archive_text   = $args['archive_text'];
	$archive_margin = $args['archive_margin'];
	$margin_unit    = $args['margin_unit'];

	switch ( $link_to ) {
		case 'author':
			$term_identity = get_user_by( 'slug', $tax_term_name );
			if ( $term_identity ) {
				$term_link = get_author_posts_url( $term_identity->ID, $tax_term_name );
				$term_name = $term_identity->display_name;
			}
			break;

		case 'category':
			$term_identity = get_term_by( 'slug', $tax_term_name, 'category' );
			if ( $term_identity ) {
				$term_link = get_term_link( $term_identity->term_id, 'category' );
				$term_name = $term_identity->name;
			}
			break;

		case 'tag':
			$term_identity = get_term_by( 'slug', $tax_term_name, 'post_tag' );
			if ( $term_identity ) {
				$term_link = get_term_link( $term_identity->term_id, 'post_tag' );
				$term_name = $term_identity->name;
			}
			break;

		case 'custom_post_type':
			if ( post_type_exists( $tax_term_name ) ) {
				$term_link        = get_post_type_archive_link( $tax_term_name );
				$post_type_object = get_post_type_object( $tax_term_name );
				$term_name        = $post_type_object->labels->name;
			}
			break;

		case 'custom_taxonomy':
			$term_identity = get_term_by( 'slug', $tax_term_name, $tax_name );
			if ( $term_identity ) {
				$term_link = get_term_link( $term_identity->term_id, $tax_name );
				$term_name = $term_identity->name;
			}
			break;

		default: // This is the case of post formats.
			$term_identity = get_term_by( 'slug', $link_to, 'post_format' );
			if ( $term_identity ) {
				$term_link = get_post_format_link( substr( $link_to, 12 ) );
				$term_name = $term_identity->name;
			}
	}

	if ( isset( $term_link ) ) {
		if ( strpos( $archive_text, '%s' ) ) {
			$archive_text = str_replace( '%s', $term_name, $archive_text );
		}
		$output  = '<p ' . pis_paragraph( $archive_margin, $margin_unit, 'pis-archive-link', 'pis_archive_class' ) . '>';
		$output .= '<a ' . pis_class( 'pis-archive-link-class', apply_filters( 'pis_archive_link_class', '' ), false ) . ' href="' . esc_url( $term_link ) . '" rel="bookmark">';
		$output .= esc_html( $archive_text );
		$output .= '</a>';
		$output .= '</p>';
	}

	if ( isset( $output ) ) {
		return $output;
	} else {
		return '';
	}
}

/**
 * Return the "Generated by..." HTML comment.
 * Includes version of Posts in Sidebar and the status of the cache.
 *
 * @param boolean $cached If the cache is active or not.
 * @since 2.0.3
 */
function pis_generated( $cached ) {
	/* Whether the cache is active */
	if ( $cached ) {
		$pis_cache_active = ' - Cache is active';
	} else {
		$pis_cache_active = '';
	}
	/* Output the HTML comment */
	return '<!-- Generated by Posts in Sidebar v' . PIS_VERSION . $pis_cache_active . ' -->' . "\n";
}

/**
 * Return the debugging informations.
 *
 * @param array $parameters {
 *     The array containing the custom parameters.
 *
 *     @type boolean $admin_only   If the administrators only can view the debugging informations.
 *     @type boolean $debug_query  If display the query used for retrieving posts.
 *     @type boolean $debug_params If display the set of options of the widget.
 *     @type string  $params       The parameters for the query.
 *     @type string  $args         The set of options of the widget.
 *     @type boolean $cached       If the output of the widget has been cached.
 * }
 * @since 2.0.3
 */
function pis_debug( $parameters ) {
	$defaults = array(
		'admin_only'   => true,
		'debug_query'  => false,
		'debug_params' => false,
		'params'       => '',
		'args'         => '',
		'cached'       => false,
		'widget_id'    => '',
	);

	$parameters = wp_parse_args( $parameters, $defaults );

	$admin_only   = $parameters['admin_only'];
	$debug_query  = $parameters['debug_query'];
	$debug_params = $parameters['debug_params'];
	$params       = $parameters['params'];
	$args         = $parameters['args'];
	$cached       = $parameters['cached'];
	$widget_id    = $parameters['widget_id'];

	$output = '';

	if ( $debug_query || $debug_params ) {
		global $wp_version;
		$output .= '<!-- Start PiS Debug -->';
		// translators: %s is the name of the plugin.
		$output .= '<h3 class="pis-debug-title-main">' . sprintf( esc_html__( '%s Debug', 'posts-in-sidebar' ), 'Posts in Sidebar' ) . '</h3>' . "\n";
		$output .= '<p class="pis-debug-title"><strong>' . esc_html__( 'Environment information:', 'posts-in-sidebar' ) . '</strong></p>' . "\n";
		$output .= '<ul class="pis-debug-ul">' . "\n";
		// translators: %s is the site URL.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'Site URL: %s', 'posts-in-sidebar' ), site_url() ) . '</li>' . "\n";
		// translators: %s is the WordPress version.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'WP version: %s', 'posts-in-sidebar' ), $wp_version ) . '</li>' . "\n";
		// translators: %s is the plugin version.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'PiS version: %s', 'posts-in-sidebar' ), PIS_VERSION ) . '</li>' . "\n";
		// translators: %s is the ID of the widget.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'Widget ID: %s', 'posts-in-sidebar' ), $widget_id ) . '</li>' . "\n";

		if ( $cached ) {
			$output .= '<li class="pis-debug-li">' . esc_html__( 'Cache: active', 'posts-in-sidebar' ) . '</li>' . "\n";
		} else {
			$output .= '<li class="pis-debug-li">' . esc_html__( 'Cache: not active', 'posts-in-sidebar' ) . '</li>' . "\n";
		}
		// translators: %1$s is the number of queries, %2$s is the number of seconds.
		$output .= '<li class="pis-debug-li">' . sprintf( esc_html__( 'Queries: %1$s queries in %2$s seconds', 'posts-in-sidebar' ), get_num_queries(), timer_stop() ) . '</li>' . "\n";
		$output .= '</ul>';
	}

	if ( $debug_query ) {
		$output .= '<p class="pis-debug-title"><strong>' . esc_html__( 'The parameters for the query:', 'posts-in-sidebar' ) . '</strong></p>' . "\n";
		$output .= '<ul class="pis-debug-ul">' . "\n";
		foreach ( $params as $key => $value ) {
			if ( is_array( $value ) ) {
				$output .= '<li class="pis-debug-li">' . $key . ':</li>' . "\n";
				$output .= '<ul class="pis-debug-ul" style="margin-bottom: 0;">' . pis_array2string( $value ) . '</ul>' . "\n";
			} else {
				$output .= '<li class="pis-debug-li">' . $key . ': <code>' . esc_html( $value ) . '</code></li>' . "\n";
			}
		}
		$output .= '</ul>';
	}

	if ( $debug_params ) {
		$output .= '<p class="pis-debug-title"><strong>' . esc_html__( 'The options of the widget:', 'posts-in-sidebar' ) . '</strong></p>' . "\n";
		$output .= '<ul class="pis-debug-ul">' . "\n";
		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) ) {
				$output .= '<li class="pis-debug-li">' . $key . ': <code>' . implode( ', ', $value ) . '</code></li>' . "\n";
			} else {
				$output .= '<li class="pis-debug-li">' . $key . ': <code>' . esc_html( $value ) . '</code></li>' . "\n";
			}
		}
		$output .= '</ul>' . "\n";
	}

	if ( $debug_query || $debug_params ) {
		$output .= '<!-- End PiS Debug -->' . "\n";
	}

	/**
	 * Display debugging informations to admins only.
	 *
	 * @since 3.8.3
	 */
	if ( $admin_only ) {
		if ( current_user_can( 'create_users' ) ) {
			return $output;
		} else {
			return '';
		}
	} else {
		return $output;
	}
}
