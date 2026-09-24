<?php
/**
 * راه‌اندازی قالب: پشتیبانی‌ها، دسته‌های الگو، اجزای مشترک صفحه و فیلترها.
 *
 * @package elmira
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'elmira', ELMIRA_DIR . '/languages' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		register_nav_menus(
			array(
				'primary' => 'منوی اصلی (سربرگ و منوی موبایل)',
			)
		);
	}
);

/* ---------- دسته‌های الگو ---------- */
add_action(
	'init',
	function () {
		$cats = array(
			'elmira-pages'   => 'المیرا: برگه‌های کامل',
			'elmira-general' => 'المیرا: عمومی',
			'elmira-salon'   => 'المیرا: سالن',
			'elmira-shop'    => 'المیرا: فروشگاه',
			'elmira-academy' => 'المیرا: آموزشگاه',
		);
		foreach ( $cats as $slug => $label ) {
			register_block_pattern_category( $slug, array( 'label' => $label ) );
		}
	}
);

/* ---------- قالب همیشه فارسی و راست‌به‌چپ ---------- */
add_filter(
	'language_attributes',
	function ( $output, $doctype ) {
		if ( is_admin() || 'html' !== $doctype ) {
			return $output;
		}
		return 'lang="fa-IR" dir="rtl"';
	},
	10,
	2
);

/* ---------- <head> ---------- */
add_action(
	'wp_head',
	function () {
		// پیش از هر استایلی اجرا شود تا حالت تیره چشمک نزند.
		echo "<script>(function(){var t;try{t=localStorage.getItem('ea-theme');}catch(e){}var d=t?t==='dark':matchMedia('(prefers-color-scheme: dark)').matches;document.documentElement.classList.add(d?'dark':'light');})();</script>\n";
		echo '<meta name="theme-color" content="#2a3040">' . "\n";
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( ELMIRA_URI . '/assets/fonts/vazirmatn-arabic-wght-normal.woff2' ) );
		// توضیح متا از چکیدهٔ برگه/نوشته، اگر افزونهٔ سئو فعال نباشد.
		if ( is_singular() && ! defined( 'WPSEO_VERSION' ) && ! defined( 'RANK_MATH_VERSION' ) && has_excerpt() ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_strip_all_tags( get_the_excerpt() ) ) );
		}
	},
	1
);

/* ---------- اجزای ثابت ابتدای <body>: پرده، نوار پیشرفت، پرش به محتوا ---------- */
add_action(
	'wp_body_open',
	function () {
		$name = esc_html( get_bloginfo( 'name' ) );
		echo '<div class="curtain" aria-hidden="true"><div class="curtain-mark"><i class="ph-light ph-flower-lotus"></i><span>' . $name . '</span><div class="curtain-bar"></div></div></div>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
		echo '<noscript><style>.curtain{display:none}</style></noscript>' . "\n";
		echo '<div class="scroll-progress" aria-hidden="true"><span></span></div>' . "\n";
		echo '<a href="#main" class="skip">پرش به محتوای اصلی</a>' . "\n";
	}
);

// پیوند «پرش به محتوا»ی خود وردپرس لازم نیست؛ قالب نسخهٔ خودش را دارد.
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_block_template_skip_link' );
remove_action( 'wp_footer', 'the_block_template_skip_link' );

/* ---------- اجزای شناور مشترک انتهای صفحه: کشوها، لایت‌باکس، اعلان‌ها ---------- */
add_action(
	'wp_footer',
	function () {
		$tail = (string) file_get_contents( ELMIRA_DIR . '/inc/chrome/tail.html' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		// منوی موبایل از منوی اصلی وردپرس ساخته می‌شود.
		$tail = preg_replace_callback(
			'#(<nav class="flex-1 overflow-y-auto px-5 py-4" aria-label="منوی موبایل">).*?(</nav>)#s',
			function ( $m ) {
				return $m[1] . "\n" . elmira_mobile_menu_html() . '  ' . $m[2];
			},
			$tail
		);
		$tail = str_replace( '<span>المیرا آزمون</span>', '<span>' . esc_html( get_bloginfo( 'name' ) ) . '</span>', $tail );
		echo elmira_links( $tail ); // phpcs:ignore WordPress.Security.EscapeOutput
	},
	5
);

/* ---------- تیترهای h2 شناسه می‌گیرند تا فهرست مطالب کار کند ---------- */
add_filter(
	'render_block_core/heading',
	function ( $html, $block ) {
		$level = isset( $block['attrs']['level'] ) ? (int) $block['attrs']['level'] : 2;
		if ( 2 !== $level || ! is_singular( 'post' ) || preg_match( '/<h2[^>]*\sid=/', $html ) ) {
			return $html;
		}
		$id = elmira_heading_id( $html );
		return $id ? preg_replace( '/<h2/', '<h2 id="' . esc_attr( $id ) . '"', $html, 1 ) : $html;
	},
	10,
	2
);

/* ---------- مجله: نوشتهٔ ویژه (سنجاق‌شده) جدا نمایش داده می‌شود ---------- */
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}
		if ( $q->is_home() ) {
			$q->set( 'ignore_sticky_posts', 1 );
			$featured = elmira_featured_post_id();
			if ( $featured ) {
				$q->set( 'post__not_in', array( $featured ) );
			}
		}
		if ( $q->is_home() || $q->is_archive() || $q->is_search() ) {
			$q->set( 'posts_per_page', max( 6, (int) get_option( 'posts_per_page' ) ) );
		}
	}
);

/**
 * جدیدترین نوشتهٔ سنجاق‌شده.
 */
function elmira_featured_post_id() {
	$sticky = array_filter( array_map( 'intval', (array) get_option( 'sticky_posts', array() ) ) );
	if ( ! $sticky ) {
		return 0;
	}
	$q = get_posts(
		array(
			'post__in'            => $sticky,
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => 1,
			'fields'              => 'ids',
		)
	);
	return $q ? (int) $q[0] : 0;
}

add_filter(
	'excerpt_length',
	function () {
		return 22;
	}
);
add_filter(
	'excerpt_more',
	function () {
		return '…';
	}
);

/* ---------- پاسخ دیدگاه‌ها ---------- */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);

/* ---------- سبک‌های بلاک برای نوشته‌ها ---------- */
add_action(
	'init',
	function () {
		register_block_style( 'core/paragraph', array( 'name' => 'lead', 'label' => 'پاراگراف آغازین' ) );
		register_block_style( 'core/list', array( 'name' => 'avoid', 'label' => 'فهرست پرهیز (ضربدر)' ) );
		register_block_style( 'core/list', array( 'name' => 'check', 'label' => 'فهرست تیک‌دار' ) );
	}
);

/*
 * استایل پیش‌فرض وردپرس برای پیوندها (زیرخط) و تیترها بدون لایه چاپ می‌شود و در ویرایشگر
 * بر کلاس‌های طراحی (مثل رنگ دکمه‌ها) غلبه می‌کند؛ قالب این‌ها را خودش در theme.css دارد.
 */
add_filter(
	'wp_theme_json_data_default',
	function ( $theme_json ) {
		$data = $theme_json->get_data();
		foreach ( array( 'link', 'heading', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'caption', 'cite' ) as $el ) {
			unset( $data['styles']['elements'][ $el ] );
		}
		return new WP_Theme_JSON_Data( $data, 'default' );
	}
);
