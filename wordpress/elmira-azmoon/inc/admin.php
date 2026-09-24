<?php
/**
 * پیشخوان: نمایش ← المیرا آزمون
 * - تنظیمات (آدرس سامانهٔ نوبت‌دهی)
 * - راه‌اندازی یک‌کلیکی: برگه‌های طراحی‌شده، صفحهٔ اصلی و مجله، منو و مقاله‌های نمونه
 *
 * @package elmira
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_init',
	function () {
		register_setting(
			'elmira',
			'elmira_options',
			array(
				'type'              => 'array',
				'sanitize_callback' => function ( $v ) {
					return array( 'booking_url' => esc_url_raw( isset( $v['booking_url'] ) ? $v['booking_url'] : '' ) );
				},
				'default'           => array(),
			)
		);
	}
);

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'المیرا آزمون', 'المیرا آزمون', 'edit_theme_options', 'elmira', 'elmira_admin_page' );
	}
);

// پس از فعال‌سازی قالب، راهنمای راه‌اندازی نشان داده شود.
add_action(
	'admin_notices',
	function () {
		if ( get_option( 'elmira_demo_done' ) || ! current_user_can( 'edit_theme_options' ) || ( isset( $_GET['page'] ) && 'elmira' === $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		printf(
			'<div class="notice notice-info"><p><strong>قالب المیرا آزمون فعال شد.</strong> برای ساخت خودکار برگه‌ها، منو و مقاله‌های نمونه به <a href="%s">نمایش ← المیرا آزمون</a> بروید.</p></div>',
			esc_url( admin_url( 'themes.php?page=elmira' ) )
		);
	}
);

add_action(
	'admin_post_elmira_demo',
	function () {
		if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'publish_pages' ) ) {
			wp_die( 'دسترسی ندارید.' );
		}
		check_admin_referer( 'elmira_demo' );
		$log = elmira_import_demo( ! empty( $_POST['with_posts'] ), ! empty( $_POST['permalinks'] ) );
		set_transient( 'elmira_demo_log', $log, 300 );
		wp_safe_redirect( admin_url( 'themes.php?page=elmira&done=1' ) );
		exit;
	}
);

/**
 * صفحهٔ تنظیمات.
 */
function elmira_admin_page() {
	$log = get_transient( 'elmira_demo_log' );
	delete_transient( 'elmira_demo_log' );
	?>
	<div class="wrap">
		<h1>المیرا آزمون</h1>
		<?php if ( $log ) : ?>
			<div class="notice notice-success"><p><strong>راه‌اندازی انجام شد.</strong></p><ul style="list-style:disc;padding-inline-start:20px">
			<?php foreach ( $log as $line ) : ?>
				<li><?php echo wp_kses_post( $line ); ?></li>
			<?php endforeach; ?>
			</ul><p><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">مشاهدهٔ سایت</a></p></div>
		<?php endif; ?>

		<h2>تنظیمات</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'elmira' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="elmira-booking">آدرس سامانهٔ نوبت‌دهی</label></th>
					<td>
						<input id="elmira-booking" class="regular-text" dir="ltr" type="url" name="elmira_options[booking_url]" value="<?php echo esc_attr( elmira_booking_url() ); ?>" placeholder="https://">
						<p class="description">رزرو نوبت در سایت انجام نمی‌شود؛ همهٔ دکمه‌های «رزرو نوبت» در تب جدید به این آدرس می‌روند.</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'ذخیرهٔ تنظیمات' ); ?>
		</form>

		<hr>
		<h2>راه‌اندازی سایت نمونه</h2>
		<p>برگه‌های طراحی‌شدهٔ قالب (خانه، خدمات، فروشگاه، آموزشگاه، گالری، درباره، تماس و ...) ساخته می‌شوند، «خانه» صفحهٔ اصلی و «مجلهٔ زیبایی» صفحهٔ نوشته‌ها می‌شود و منوی اصلی تنظیم می‌شود. برگه‌هایی که از قبل با همان نامک وجود دارند دست نمی‌خورند.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="elmira_demo">
			<?php wp_nonce_field( 'elmira_demo' ); ?>
			<p><label><input type="checkbox" name="with_posts" value="1" checked> مقاله‌ها، دسته‌ها و دیدگاه‌های نمونهٔ مجله هم ساخته شوند</label></p>
			<?php if ( ! get_option( 'permalink_structure' ) ) : ?>
			<p><label><input type="checkbox" name="permalinks" value="1" checked> پیوندهای یکتا روی «نام نوشته» تنظیم شود (الان ساده است)</label></p>
			<?php endif; ?>
			<?php submit_button( get_option( 'elmira_demo_done' ) ? 'راه‌اندازی دوباره (فقط موارد ناموجود)' : 'راه‌اندازی سایت نمونه', 'primary', 'submit', false ); ?>
		</form>

		<hr>
		<h2>راهنما</h2>
		<ul style="list-style:disc;padding-inline-start:20px">
			<li>هر برگه در ویرایشگر بلاک (گوتنبرگ) باز می‌شود؛ روی هر متن کلیک کنید و بنویسید. برای عوض کردن تصویر، تصویر را انتخاب کنید و از نوار ابزار «جایگزینی» را بزنید. پیوند هر دکمه یا کارت در ستون تنظیمات بلاک است.</li>
			<li>الگوهای آماده (بخش‌های هر صفحه و برگه‌های کامل) در افزودنگر بلاک ← «الگوها» با پیشوند «المیرا» هستند.</li>
			<li>برگه‌های طراحی‌شده از قالب «بوم کامل» استفاده می‌کنند؛ برگه‌های متنی ساده (مثل قوانین) با قالب پیش‌فرض، هیروی تیره و عنوان خودکار می‌گیرند.</li>
			<li>منوی سربرگ: <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">نمایش ← فهرست‌ها</a>، جایگاه «منوی اصلی». متن «توضیح عنوان» هر آیتم، برچسب همان آیتم در منوی موبایل است.</li>
			<li>پانویس در <a href="<?php echo esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . get_stylesheet() . '//footer&canvas=edit' ) ); ?>">ویرایشگر سایت ← قطعه‌ها ← پانویس</a> ویرایش می‌شود.</li>
		</ul>
	</div>
	<?php
}

/**
 * درون‌ریزی سایت نمونه.
 *
 * @param bool $with_posts مقاله‌های نمونه.
 * @param bool $permalinks تنظیم پیوند یکتا.
 * @return string[] گزارش.
 */
function elmira_import_demo( $with_posts, $permalinks ) {
	$log      = array();
	$pages    = json_decode( (string) file_get_contents( ELMIRA_DIR . '/inc/demo-pages.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	$registry = WP_Block_Patterns_Registry::get_instance();
	$ids      = array();

	if ( $permalinks && ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		$log[] = 'پیوندهای یکتا روی «نام نوشته» تنظیم شد.';
	}

	// ترتیب: والدها پیش از فرزندها.
	usort(
		$pages,
		function ( $a, $b ) {
			return substr_count( $a['path'], '/' ) - substr_count( $b['path'], '/' );
		}
	);
	foreach ( $pages as $p ) {
		$path = '' === $p['path'] ? 'home' : $p['path'];
		$old  = get_page_by_path( $path );
		if ( $old ) {
			$ids[ $p['name'] ] = $old->ID;
			continue;
		}
		$pattern = $registry->get_registered( $p['pattern'] );
		if ( ! $pattern ) {
			continue;
		}
		$parts  = explode( '/', $path );
		$slug   = array_pop( $parts );
		$parent = $parts ? get_page_by_path( implode( '/', $parts ) ) : null;
		// wp_insert_post داده را unslash می‌کند؛ بدون wp_slash نویسه‌های \u0022 در JSON بلاک‌ها خراب می‌شوند.
		$id     = wp_insert_post(
			wp_slash(
			array(
				'post_type'     => 'page',
				'post_status'   => 'publish',
				'post_title'    => $p['title'],
				'post_name'     => $slug,
				'post_parent'   => $parent ? $parent->ID : 0,
				'post_excerpt'  => $p['excerpt'],
				'post_content'  => $pattern['content'],
				'page_template' => $p['template'],
				'menu_order'    => count( $ids ),
			)
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			$ids[ $p['name'] ] = $id;
			$log[]             = sprintf( 'برگهٔ «%s» ساخته شد.', esc_html( $p['title'] ) );
		}
	}

	// صفحهٔ مجله (فهرست نوشته‌ها).
	$blog = get_page_by_path( 'blog' );
	if ( ! $blog ) {
		$blog_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => 'مجلهٔ زیبایی',
				'post_name'    => 'blog',
				'post_excerpt' => 'نکته‌های کاربردی از کارشناس‌های سالن؛ برای مراقبت در خانه و انتخاب آگاهانه.',
			)
		);
		$log[]   = 'برگهٔ «مجلهٔ زیبایی» ساخته شد.';
	} else {
		$blog_id = $blog->ID;
	}

	if ( ! empty( $ids['index'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['index'] );
		update_option( 'page_for_posts', $blog_id );
		$log[] = '«خانه» صفحهٔ اصلی و «مجلهٔ زیبایی» صفحهٔ نوشته‌ها شد.';
	}

	// منوی اصلی.
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		$menu_id = wp_create_nav_menu( 'منوی اصلی' );
		if ( ! is_wp_error( $menu_id ) ) {
			foreach ( elmira_default_menu() as $i => $m ) {
				$target = '/blog/' === $m[2] ? $blog_id : ( '/' === $m[2] ? ( isset( $ids['index'] ) ? $ids['index'] : 0 ) : ( get_page_by_path( trim( $m[2], '/' ) ) ? get_page_by_path( trim( $m[2], '/' ) )->ID : 0 ) );
				$item   = array(
					'menu-item-title'     => $m[0],
					'menu-item-attr-title' => $m[1] !== $m[0] ? $m[1] : '',
					'menu-item-status'    => 'publish',
					'menu-item-position'  => $i + 1,
				);
				if ( $target ) {
					$item += array(
						'menu-item-object-id' => $target,
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
					);
				} else {
					$item += array(
						'menu-item-url'  => elmira_url( $m[2] ),
						'menu-item-type' => 'custom',
					);
				}
				wp_update_nav_menu_item( $menu_id, 0, $item );
			}
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
			$log[] = 'منوی اصلی ساخته و در جایگاه سربرگ قرار گرفت.';
		}
	}

	if ( $with_posts ) {
		$log = array_merge( $log, elmira_import_posts() );
	}

	flush_rewrite_rules();
	update_option( 'elmira_demo_done', 1 );
	return $log;
}

/**
 * مقاله‌ها، دسته‌ها و دیدگاه‌های نمونه.
 *
 * @return string[] گزارش.
 */
function elmira_import_posts() {
	$log  = array();
	$data = require ELMIRA_DIR . '/inc/demo-posts.php';
	$cats = array();
	foreach ( $data['categories'] as $slug => $name ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( ! $term ) {
			$t    = wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
			$term = is_wp_error( $t ) ? null : get_term( $t['term_id'] );
		}
		if ( $term ) {
			$cats[ $slug ] = (int) $term->term_id;
		}
	}
	$made = 0;
	foreach ( $data['posts'] as $i => $p ) {
		if ( get_page_by_path( $p['slug'], OBJECT, 'post' ) ) {
			continue;
		}
		$id = wp_insert_post(
			wp_slash(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $p['title'],
				'post_name'     => $p['slug'],
				'post_excerpt'  => $p['excerpt'],
				'post_content'  => $p['content'],
				'post_category' => isset( $cats[ $p['cat'] ] ) ? array( $cats[ $p['cat'] ] ) : array(),
				'tags_input'    => isset( $p['tags'] ) ? $p['tags'] : array(),
				'post_date'     => gmdate( 'Y-m-d H:i:s', time() - ( $i + 1 ) * DAY_IN_SECONDS * 3 ),
			)
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			continue;
		}
		++$made;
		update_post_meta( $id, '_elmira_image', $p['image'] );
		if ( ! empty( $p['sticky'] ) ) {
			stick_post( $id );
		}
		foreach ( isset( $p['comments'] ) ? $p['comments'] : array() as $c ) {
			$parent = wp_insert_comment(
				array(
					'comment_post_ID'  => $id,
					'comment_author'   => $c[0],
					'comment_content'  => $c[1],
					'comment_approved' => 1,
				)
			);
			if ( isset( $c[2] ) ) {
				$user = wp_get_current_user();
				wp_insert_comment(
					array(
						'comment_post_ID'      => $id,
						'comment_parent'       => $parent,
						'comment_author'       => $user->display_name,
						'comment_author_email' => $user->user_email,
						'user_id'              => $user->ID,
						'comment_content'      => $c[2],
						'comment_approved'     => 1,
					)
				);
			}
		}
	}
	if ( $made ) {
		$log[] = sprintf( '%s مقالهٔ نمونه با دسته‌ها و دیدگاه ساخته شد.', elmira_fa( $made ) );
	}
	return $log;
}
