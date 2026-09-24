<?php
/**
 * کارت جستجوی مجله.
 *
 * @package elmira
 * @var array $attributes
 */

defined( 'ABSPATH' ) || exit;

$elmira_id = wp_unique_id( 'q-' );
?>
<form class="card p-5" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-reveal="left">
  <label for="<?php echo esc_attr( $elmira_id ); ?>" class="label"><?php echo esc_html( $attributes['label'] ); ?></label>
  <div class="relative"><input id="<?php echo esc_attr( $elmira_id ); ?>" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" class="input !pe-12" placeholder="<?php echo esc_attr( $attributes['placeholder'] ); ?>"><input type="hidden" name="post_type" value="post"><button class="absolute left-1.5 top-1/2 -translate-y-1/2 grid place-items-center size-10 rounded-lg bg-ink text-bg" aria-label="جستجو"><i class="ph-light ph-magnifying-glass" aria-hidden="true"></i></button></div>
</form>
