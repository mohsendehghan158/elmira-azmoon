<?php
/**
 * بارگذاری استایل‌ها و اسکریپت‌ها در سایت و ویرایشگر.
 *
 * @package elmira
 */

defined( 'ABSPATH' ) || exit;

/**
 * استایل‌های مشترک سایت و ویرایشگر.
 */
function elmira_register_styles() {
	$v = ELMIRA_VERSION;
	wp_register_style( 'elmira-phosphor-light', ELMIRA_URI . '/assets/css/phosphor-light.css', array(), '2.1.1' );
	wp_register_style( 'elmira-phosphor-fill', ELMIRA_URI . '/assets/css/phosphor-fill.css', array(), '2.1.1' );
	wp_register_style( 'elmira-tailwind', ELMIRA_URI . '/assets/css/tailwind.css', array(), $v );
	wp_register_style( 'elmira-theme', ELMIRA_URI . '/assets/css/theme.css', array( 'elmira-tailwind', 'elmira-phosphor-light', 'elmira-phosphor-fill' ), $v );
	wp_register_style( 'elmira-wp', ELMIRA_URI . '/assets/css/wp.css', array( 'elmira-theme' ), $v );
}

add_action(
	'wp_enqueue_scripts',
	function () {
		elmira_register_styles();
		wp_enqueue_style( 'elmira-wp' );

		wp_enqueue_script( 'elmira-theme', ELMIRA_URI . '/assets/js/theme.js', array(), ELMIRA_VERSION, array( 'in_footer' => true ) );
		wp_localize_script(
			'elmira-theme',
			'elmiraTheme',
			array(
				'bookingUrl' => elmira_booking_url(),
			)
		);
	}
);

// داخل iframe ویرایشگر بلاک (برگه‌ها، نوشته‌ها و ویرایشگر سایت).
add_action(
	'enqueue_block_assets',
	function () {
		if ( ! is_admin() ) {
			return;
		}
		elmira_register_styles();
		wp_enqueue_style( 'elmira-editor', ELMIRA_URI . '/assets/css/editor.css', array( 'elmira-wp' ), ELMIRA_VERSION );
	}
);

/*
 * استایل‌های قالب (Tailwind و theme.css) داخل cascade layer هستند و استایل بدون لایه همیشه
 * بر آن‌ها غلبه می‌کند. برای همین استایل‌های هستهٔ وردپرس (global-styles و بلاک‌ها) به
 * پایین‌ترین لایه (wp) منتقل می‌شوند تا کلاس‌های طراحی بر آن‌ها مقدم باشند.
 */
add_action(
	'wp_head',
	function () {
		echo "<style id=\"elmira-layers\">@layer wp, theme, base, components, utilities;</style>\n";
	},
	0
);

/**
 * آیا این استایل از هستهٔ وردپرس است و باید به لایهٔ wp برود؟
 *
 * @param string $handle شناسه.
 */
function elmira_is_core_style( $handle ) {
	return in_array( $handle, array( 'global-styles', 'core-block-supports', 'wp-block-library', 'classic-theme-styles', 'wp-img-auto-sizes-contain', 'block-style-variation-styles' ), true )
		|| 0 === strpos( $handle, 'wp-block-' ) || 0 === strpos( $handle, 'core-block-supports' );
}

add_action(
	'wp_print_styles',
	function () {
		if ( is_admin() ) {
			return;
		}
		$styles = wp_styles();
		foreach ( $styles->registered as $handle => $dep ) {
			if ( ! elmira_is_core_style( $handle ) || ! empty( $dep->extra['elmira_layered'] ) ) {
				continue;
			}
			$after  = isset( $dep->extra['after'] ) ? (array) $dep->extra['after'] : array();
			$import = '';
			if ( $dep->src && ! isset( $dep->extra['path'] ) ) {
				$src    = $styles->_css_href( $dep->src, $dep->ver, $handle );
				$import = '@import url("' . esc_url_raw( $src ) . '") layer(wp);';
				$dep->src = false;
			} elseif ( $dep->src && isset( $dep->extra['path'] ) && is_readable( $dep->extra['path'] ) ) {
				// فایل کوچک هسته: مستقیم درون‌خطی و لایه‌دار.
				array_unshift( $after, (string) file_get_contents( $dep->extra['path'] ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
				$dep->src = false;
			}
			$css = trim( implode( "\n", array_filter( $after ) ) );
			$dep->extra['after']          = array_filter( array( $import, '' !== $css ? '@layer wp{' . $css . '}' : '' ) );
			$dep->extra['elmira_layered'] = true;
		}
	},
	100
);
