<?php
/**
 * توابع کمکی مشترک.
 *
 * @package elmira
 */

defined( 'ABSPATH' ) || exit;

/**
 * تنظیمات قالب (پیشخوان ← نمایش ← المیرا آزمون).
 *
 * @param string $key کلید.
 * @return string
 */
function elmira_option( $key ) {
	$opts = get_option( 'elmira_options', array() );
	return isset( $opts[ $key ] ) ? (string) $opts[ $key ] : '';
}

/**
 * آدرس سامانهٔ نوبت‌دهی سالن.
 */
function elmira_booking_url() {
	return elmira_option( 'booking_url' );
}

/**
 * عدد لاتین → رقم فارسی (با جداکنندهٔ هزارگان برای اعداد صحیح).
 *
 * @param int|string $n عدد یا متن.
 */
function elmira_fa( $n ) {
	$s = ( is_int( $n ) || ctype_digit( (string) $n ) ) ? number_format( (int) $n, 0, '', '٬' ) : (string) $n;
	return strtr( $s, array_combine( range( 0, 9 ), array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ) ) );
}

/**
 * پیوندهای نسبی به ریشه (مثل /shop/) را به نشانی کامل تبدیل می‌کند.
 * اگر برگه‌ای با همین مسیر وجود داشته باشد، پیوند یکتای آن استفاده می‌شود تا با هر
 * ساختار پیوند یکتا و هر زیرپوشه‌ای کار کند.
 *
 * @param string $href پیوند.
 */
function elmira_url( $href ) {
	static $cache = array();
	$href = (string) $href;
	if ( '' === $href || '/' !== $href[0] || 0 === strpos( $href, '//' ) ) {
		return $href;
	}
	if ( isset( $cache[ $href ] ) ) {
		return $cache[ $href ];
	}
	$hash = '';
	$path = $href;
	$pos  = strpos( $href, '#' );
	if ( false !== $pos ) {
		$hash = substr( $href, $pos );
		$path = substr( $href, 0, $pos );
	}
	$slug = trim( $path, '/' );
	$url  = '';
	if ( '' === $slug ) {
		$url = home_url( '/' );
	} elseif ( 'blog' === $slug && get_option( 'page_for_posts' ) ) {
		$url = get_permalink( (int) get_option( 'page_for_posts' ) );
	} else {
		$page = get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			$url = get_permalink( $page );
		}
	}
	if ( ! $url ) {
		$url = home_url( $path );
	}
	$cache[ $href ] = $url . $hash;
	return $cache[ $href ];
}

/**
 * پیوندهای درون یک تکه HTML را اصلاح می‌کند (مسیرهای نسبی و دکمه‌های نوبت‌دهی).
 *
 * @param string $html HTML.
 */
function elmira_links( $html ) {
	if ( false === strpos( $html, 'href="/' ) && false === strpos( $html, 'data-booking' ) ) {
		return $html;
	}
	$html    = preg_replace_callback(
		'/href="(\/(?!\/)[^"]*)"/',
		function ( $m ) {
			return 'href="' . esc_url( elmira_url( html_entity_decode( $m[1] ) ) ) . '"';
		},
		$html
	);
	$booking = elmira_booking_url();
	if ( $booking && class_exists( 'WP_HTML_Tag_Processor' ) ) {
		$p = new WP_HTML_Tag_Processor( $html );
		while ( $p->next_tag( 'a' ) ) {
			if ( null !== $p->get_attribute( 'data-booking' ) ) {
				$p->set_attribute( 'href', $booking );
				$p->set_attribute( 'target', '_blank' );
				$p->set_attribute( 'rel', 'noopener' );
			}
		}
		$html = $p->get_updated_html();
	}
	return $html;
}

/**
 * آرایهٔ ویژگی‌ها → رشتهٔ ویژگی‌های HTML.
 *
 * @param array $attrs ویژگی‌ها.
 */
function elmira_attrs( $attrs ) {
	$out = '';
	foreach ( $attrs as $k => $v ) {
		if ( null === $v || false === $v || ! preg_match( '/^[a-zA-Z_:][-a-zA-Z0-9_:.]*$/', $k ) ) {
			continue;
		}
		if ( true === $v || ( '' === $v && ! in_array( $k, array( 'alt', 'value', 'content' ), true ) ) ) {
			$out .= ' ' . $k;
			continue;
		}
		$out .= sprintf( ' %s="%s"', $k, in_array( $k, array( 'href', 'src', 'action' ), true ) ? esc_url( $v ) : esc_attr( $v ) );
	}
	return $out;
}

/**
 * زمان تقریبی مطالعه (دقیقه).
 *
 * @param int|WP_Post|null $post نوشته.
 */
function elmira_reading_time( $post = null ) {
	$post  = get_post( $post );
	$words = $post ? count( preg_split( '/\s+/u', trim( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) ) ) ) : 0;
	return max( 1, (int) round( $words / 180 ) );
}

/**
 * «۵ دقیقه»
 *
 * @param int|WP_Post|null $post نوشته.
 */
function elmira_reading_label( $post = null ) {
	return elmira_fa( elmira_reading_time( $post ) ) . ' دقیقه';
}

/**
 * تصویر شاخص نوشته؛ اگر نداشت، تصویر نمونهٔ درون‌ریزی‌شده (متای _elmira_image).
 *
 * @param int|WP_Post $post نوشته.
 * @param string      $size اندازه.
 */
function elmira_post_image( $post, $size = 'large' ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	if ( has_post_thumbnail( $post ) ) {
		return (string) get_the_post_thumbnail_url( $post, $size );
	}
	return (string) get_post_meta( $post->ID, '_elmira_image', true );
}

/**
 * نسخهٔ Unsplash با اندازهٔ دلخواه؛ برای تصاویر دیگر همان نشانی.
 *
 * @param string $url نشانی.
 * @param int    $w   پهنا.
 * @param int    $h   ارتفاع.
 */
function elmira_sized( $url, $w, $h ) {
	if ( false === strpos( $url, 'images.unsplash.com' ) ) {
		return $url;
	}
	return add_query_arg( array( 'w' => $w, 'h' => $h, 'fit' => 'crop', 'auto' => 'format', 'q' => 70 ), strtok( $url, '?' ) );
}

/**
 * نخستین دستهٔ نوشته.
 *
 * @param int|WP_Post $post نوشته.
 * @return WP_Term|null
 */
function elmira_primary_category( $post ) {
	$cats = get_the_category( is_object( $post ) ? $post->ID : $post );
	return $cats ? $cats[0] : null;
}

/**
 * شناسهٔ تیتر برای فهرست مطالب؛ از خود متن ساخته می‌شود تا ثابت بماند.
 *
 * @param string $text متن تیتر.
 */
function elmira_heading_id( $text ) {
	$id = preg_replace( '/[\s\p{P}\p{S}]+/u', '-', trim( wp_strip_all_tags( html_entity_decode( $text ) ) ) );
	return trim( (string) $id, '-' );
}

/**
 * شناسهٔ نوشتهٔ جاری در بلاک‌های پویا (قالب، پیش‌نمایش ویرایشگر).
 *
 * @param WP_Block|null $block بلاک.
 */
function elmira_context_post_id( $block = null ) {
	if ( $block && ! empty( $block->context['postId'] ) ) {
		return (int) $block->context['postId'];
	}
	// پیش‌نمایش ServerSideRender در ویرایشگر.
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_GET['post_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return (int) $_GET['post_id']; // phpcs:ignore WordPress.Security.NonceVerification
	}
	if ( is_singular() ) {
		return (int) get_queried_object_id();
	}
	return (int) get_the_ID();
}

/**
 * آیا در پیش‌نمایش ویرایشگر هستیم؟
 */
function elmira_is_editor_preview() {
	return defined( 'REST_REQUEST' ) && REST_REQUEST;
}
