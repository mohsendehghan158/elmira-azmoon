<?php
/**
 * سربرگ سایت (همان _src/partials/header.html قالب HTML).
 *
 * @package elmira
 * @var array $attributes
 */

defined( 'ABSPATH' ) || exit;

$elmira_name    = get_bloginfo( 'name' );
$elmira_booking = elmira_booking_url();
?>
<header class="site-header">
  <div class="bar">
    <div class="wrap h-[76px] flex items-center justify-between gap-4">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand shrink-0" aria-label="<?php echo esc_attr( $elmira_name . '، صفحه اصلی' ); ?>">
        <i class="ph-light ph-flower-lotus" aria-hidden="true"></i><span><?php echo esc_html( $elmira_name ); ?></span>
      </a>

      <nav class="hidden lg:flex items-center gap-1" aria-label="منوی اصلی">
        <?php echo elmira_desktop_menu_html(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
      </nav>

      <div class="flex items-center gap-0.5">
        <button type="button" class="hdr-icon theme-toggle inline-grid" data-theme-toggle aria-label="تغییر حالت روشن و تیره">
          <i class="ph-light ph-sun sun" aria-hidden="true"></i><i class="ph-light ph-moon-stars moon" aria-hidden="true"></i>
        </button>
        <?php if ( ! empty( $attributes['showAccount'] ) ) : ?>
        <a href="<?php echo esc_url( elmira_url( '/account/' ) ); ?>" class="hdr-icon hidden sm:inline-grid" aria-label="حساب کاربری"><i class="ph-light ph-user" aria-hidden="true"></i></a>
        <?php endif; ?>
        <?php if ( ! empty( $attributes['showCart'] ) ) : ?>
        <button type="button" class="hdr-icon cart-target inline-grid" data-open="cart-drawer" aria-controls="cart-drawer" aria-expanded="false" aria-label="سبد خرید">
          <i class="ph-light ph-handbag" aria-hidden="true"></i><span class="cart-count" data-n="3">۳</span>
        </button>
        <?php endif; ?>
        <?php if ( ! empty( $attributes['bookingLabel'] ) ) : ?>
        <a href="<?php echo esc_url( $elmira_booking ? $elmira_booking : '#' ); ?>" data-booking<?php echo $elmira_booking ? ' target="_blank" rel="noopener"' : ''; ?> class="btn btn-salmon btn-sm hidden md:inline-flex ms-2" data-magnetic=".25"><?php echo esc_html( $attributes['bookingLabel'] ); ?> <i class="ph-light ph-arrow-up-left" aria-hidden="true"></i></a>
        <?php endif; ?>
        <button type="button" class="hdr-icon inline-grid lg:hidden" data-open="nav-drawer" aria-controls="nav-drawer" aria-expanded="false" aria-label="باز کردن منو"><i class="ph-light ph-list" aria-hidden="true"></i></button>
      </div>
    </div>
  </div>
</header>
