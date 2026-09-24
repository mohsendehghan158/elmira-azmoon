<?php
/**
 * مقالات مرتبط (هم‌دسته با نوشتهٔ جاری).
 *
 * @package elmira
 * @var array    $attributes
 * @var WP_Block $block
 */

defined( 'ABSPATH' ) || exit;

$elmira_id   = elmira_context_post_id( $block );
$elmira_args = array(
	'posts_per_page'      => max( 1, (int) $attributes['count'] ),
	'post__not_in'        => array( $elmira_id ),
	'ignore_sticky_posts' => 1,
);
$elmira_cats = wp_get_post_categories( $elmira_id );
if ( $elmira_cats ) {
	$elmira_args['category__in'] = $elmira_cats;
}
$elmira_posts = get_posts( $elmira_args );
if ( count( $elmira_posts ) < (int) $attributes['count'] ) {
	// اگر هم‌دسته کم بود، با جدیدترین‌ها پر شود.
	$elmira_more  = get_posts(
		array(
			'posts_per_page'      => (int) $attributes['count'] - count( $elmira_posts ),
			'post__not_in'        => array_merge( array( $elmira_id ), wp_list_pluck( $elmira_posts, 'ID' ) ),
			'ignore_sticky_posts' => 1,
		)
	);
	$elmira_posts = array_merge( $elmira_posts, $elmira_more );
}
if ( ! $elmira_posts ) {
	return;
}
?>
<div class="card p-5">
  <h2 class="font-semibold mb-3"><?php echo esc_html( $attributes['title'] ); ?></h2>
  <div class="flex flex-col divide-y divide-line">
    <?php
    foreach ( $elmira_posts as $elmira_p ) :
	    $elmira_img = elmira_post_image( $elmira_p, 'thumbnail' );
	    ?>
    <a href="<?php echo esc_url( get_permalink( $elmira_p ) ); ?>" class="group flex items-center gap-3 py-3"><?php if ( $elmira_img ) : ?><img src="<?php echo esc_url( elmira_sized( $elmira_img, 120, 120 ) ); ?>" alt="" class="size-14 rounded-lg object-cover" loading="lazy" width="120" height="120"><?php else : ?><span class="size-14 rounded-lg img-ph shrink-0"></span><?php endif; ?><span class="flex-1 text-sm transition group-hover:text-copper-ink"><?php echo esc_html( get_the_title( $elmira_p ) ); ?><small class="block text-xs text-ink-2"><?php echo esc_html( elmira_reading_label( $elmira_p ) ); ?></small></span></a>
    <?php endforeach; ?>
  </div>
</div>
