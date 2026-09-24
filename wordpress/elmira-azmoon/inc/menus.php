<?php
/**
 * منوی اصلی: از «نمایش ← فهرست‌ها» خوانده می‌شود؛ اگر منویی تعیین نشده باشد،
 * همان منوی طراحی قالب نمایش داده می‌شود.
 *
 * @package elmira
 */

defined( 'ABSPATH' ) || exit;

/**
 * منوی پیش‌فرض طراحی: [عنوان سربرگ، عنوان منوی موبایل، مسیر].
 */
function elmira_default_menu() {
	return array(
		array( 'خانه', 'خانه', '/' ),
		array( 'خدمات', 'خدمات سالن', '/services/' ),
		array( 'فروشگاه', 'فروشگاه', '/shop/' ),
		array( 'آموزشگاه', 'آموزشگاه', '/academy/' ),
		array( 'گالری', 'گالری نمونه‌کار', '/gallery/' ),
		array( 'مجله', 'مجله زیبایی', '/blog/' ),
		array( 'درباره ما', 'درباره ما', '/about/' ),
		array( 'تماس', 'تماس', '/contact/' ),
	);
}

/**
 * آیتم‌های سطح اول منو: [title, mobile, url, current].
 */
function elmira_menu_items() {
	static $items = null;
	if ( null !== $items ) {
		return $items;
	}
	$items     = array();
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations['primary'] ) ) {
		$menu_items = wp_get_nav_menu_items( $locations['primary'], array( 'update_post_term_cache' => false ) );
		if ( $menu_items ) {
			_wp_menu_item_classes_by_context( $menu_items );
			foreach ( $menu_items as $it ) {
				if ( (int) $it->menu_item_parent ) {
					continue;
				}
				$classes = (array) $it->classes;
				$items[] = array(
					'title'   => $it->title,
					'mobile'  => $it->attr_title ? $it->attr_title : $it->title,
					'url'     => $it->url,
					'current' => (bool) array_intersect( array( 'current-menu-item', 'current-menu-ancestor', 'current-page-ancestor', 'current_page_parent' ), $classes ),
				);
			}
		}
	}
	if ( ! $items ) {
		$here = untrailingslashit( strtok( home_url( add_query_arg( array() ) ), '?' ) );
		foreach ( elmira_default_menu() as $m ) {
			$url     = elmira_url( $m[2] );
			$items[] = array(
				'title'   => $m[0],
				'mobile'  => $m[1],
				'url'     => $url,
				'current' => untrailingslashit( $url ) === $here || ( '/blog/' === $m[2] && ( is_home() || is_singular( 'post' ) || is_category() || is_tag() ) ),
			);
		}
	}
	return $items;
}

/**
 * پیوندهای منوی دسکتاپ.
 */
function elmira_desktop_menu_html() {
	$out = '';
	foreach ( elmira_menu_items() as $it ) {
		$out .= sprintf(
			'<a class="nav-link" href="%s"%s>%s</a>' . "\n",
			esc_url( $it['url'] ),
			$it['current'] ? ' aria-current="page"' : '',
			esc_html( $it['title'] )
		);
	}
	return $out;
}

/**
 * پیوندهای کشوی منوی موبایل.
 */
function elmira_mobile_menu_html() {
	$out   = '';
	$items = elmira_menu_items();
	$items[] = array(
		'mobile'  => 'حساب کاربری',
		'url'     => elmira_url( '/account/' ),
		'current' => false,
	);
	foreach ( $items as $it ) {
		$out .= sprintf(
			'    <a class="dn-link" href="%s"%s>%s <i class="ph-light ph-arrow-left text-base" aria-hidden="true"></i></a>' . "\n",
			esc_url( $it['url'] ),
			$it['current'] ? ' aria-current="page"' : '',
			esc_html( $it['mobile'] )
		);
	}
	return $out;
}
