<?php
/**
 * بلاک‌های قالب.
 *
 * - elmira/el: هر عنصر طراحی (بخش، کارت، تیتر، دکمه، تصویر ...) با همهٔ کلاس‌ها و
 *   data-attributeها. متن، تصویر و پیوندش در گوتنبرگ ویرایش می‌شود.
 * - بلاک‌های پویا (سربرگ، هیرو، مقاله‌ها، فهرست مطالب، دیدگاه‌ها ...) که از داده‌های
 *   وردپرس ساخته می‌شوند.
 *
 * @package elmira
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'block_categories_all',
	function ( $cats ) {
		array_unshift(
			$cats,
			array(
				'slug'  => 'elmira',
				'title' => 'المیرا آزمون',
			)
		);
		return $cats;
	}
);

add_action(
	'init',
	function () {
		wp_register_script(
			'elmira-blocks-editor',
			ELMIRA_URI . '/assets/js/editor.js',
			array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-data', 'wp-server-side-render', 'wp-i18n' ),
			ELMIRA_VERSION,
			true
		);
		foreach ( glob( ELMIRA_DIR . '/blocks/*/block.json' ) as $file ) {
			$args = array();
			if ( 'el' === basename( dirname( $file ) ) ) {
				$args['render_callback'] = 'elmira_render_el';
			}
			register_block_type( dirname( $file ), $args );
		}
	}
);

/**
 * تگ‌های بدون بستن.
 */
const ELMIRA_VOID = array( 'img', 'input', 'br', 'hr', 'source', 'wbr', 'meta', 'link' );

/**
 * رندر elmira/el.
 *
 * @param array  $a       ویژگی‌های بلاک.
 * @param string $content بلاک‌های درونی رندرشده.
 */
function elmira_render_el( $a, $content ) {
	$tag = strtolower( isset( $a['tag'] ) ? (string) $a['tag'] : 'div' );
	if ( ! preg_match( '/^[a-z][a-z0-9-]*$/', $tag ) || in_array( $tag, array( 'script', 'style', 'iframe', 'object', 'embed' ), true ) ) {
		$tag = 'div';
	}
	$mode  = isset( $a['mode'] ) ? $a['mode'] : 'blocks';
	$attrs = array();
	if ( ! empty( $a['className'] ) ) {
		$attrs['class'] = $a['className'];
	}
	foreach ( (array) ( isset( $a['attrs'] ) ? $a['attrs'] : array() ) as $k => $v ) {
		$attrs[ $k ] = is_scalar( $v ) ? (string) $v : '';
	}
	if ( isset( $attrs['href'] ) ) {
		$attrs['href'] = elmira_url( $attrs['href'] );
	}
	if ( isset( $attrs['data-booking'] ) && elmira_booking_url() ) {
		$attrs['href']   = elmira_booking_url();
		$attrs['target'] = '_blank';
		$attrs['rel']    = 'noopener';
	}
	if ( 'img' === $tag && ! empty( $a['mediaId'] ) ) {
		$srcset = wp_get_attachment_image_srcset( (int) $a['mediaId'], 'full' );
		if ( $srcset ) {
			$attrs['srcset'] = $srcset;
			$attrs['sizes']  = isset( $attrs['width'] ) ? '(max-width: ' . (int) $attrs['width'] . 'px) 100vw, ' . (int) $attrs['width'] . 'px' : '100vw';
		}
	}
	if ( 'form' === $tag && ! isset( $attrs['action'] ) && ! isset( $attrs['onsubmit'] ) && isset( $attrs['data-validate'] ) ) {
		// فرم‌های نمایشی قالب ارسال واقعی ندارند (theme.js پیام موفقیت نشان می‌دهد).
		$attrs['action'] = '#';
	}

	$open = '<' . $tag . elmira_attrs( $attrs ) . '>';
	if ( 'void' === $mode || in_array( $tag, ELMIRA_VOID, true ) ) {
		return $open;
	}
	switch ( $mode ) {
		case 'text':
			$inner = ( isset( $a['before'] ) ? $a['before'] : '' ) . ( isset( $a['content'] ) ? $a['content'] : '' ) . ( isset( $a['after'] ) ? $a['after'] : '' );
			$inner = elmira_links( $inner );
			break;
		case 'html':
			$inner = elmira_links( isset( $a['html'] ) ? (string) $a['html'] : '' );
			break;
		default:
			$inner = $content;
	}
	return $open . $inner . '</' . $tag . '>';
}
