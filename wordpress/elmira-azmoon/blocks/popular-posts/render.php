<?php
/**
 * پرخواننده‌ترین‌ها (بیشترین دیدگاه، سپس جدیدترین).
 *
 * @package elmira
 * @var array $attributes
 */

defined( 'ABSPATH' ) || exit;

$elmira_posts = get_posts(
	array(
		'posts_per_page'      => max( 1, (int) $attributes['count'] ),
		'orderby'             => array( 'comment_count' => 'DESC', 'date' => 'DESC' ),
		'ignore_sticky_posts' => 1,
	)
);
if ( ! $elmira_posts ) {
	return;
}
?>
<div class="card p-5" data-reveal="left" style="--d:1">
  <h2 class="font-semibold mb-3"><?php echo esc_html( $attributes['title'] ); ?></h2>
  <ol class="flex flex-col divide-y divide-line">
    <?php foreach ( $elmira_posts as $elmira_i => $elmira_p ) : ?>
    <li><a href="<?php echo esc_url( get_permalink( $elmira_p ) ); ?>" class="group flex items-center gap-3 py-3"><span class="font-display text-3xl text-line w-6 transition group-hover:text-copper"><?php echo esc_html( elmira_fa( $elmira_i + 1 ) ); ?></span><span class="flex-1 text-sm group-hover:text-copper-ink transition"><?php echo esc_html( get_the_title( $elmira_p ) ); ?></span><small class="text-xs text-ink-2"><?php echo esc_html( elmira_reading_label( $elmira_p ) ); ?></small></a></li>
    <?php endforeach; ?>
  </ol>
</div>
