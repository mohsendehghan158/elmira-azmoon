<?php
/**
 * فهرست دسته‌بندی‌ها با تعداد مقاله.
 *
 * @package elmira
 * @var array $attributes
 */

defined( 'ABSPATH' ) || exit;

$elmira_cats = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
if ( ! $elmira_cats ) {
	return;
}
?>
<div class="card p-5" data-reveal="left" style="--d:2">
  <h2 class="font-semibold mb-3"><?php echo esc_html( $attributes['title'] ); ?></h2>
  <ul class="flex flex-col text-sm">
    <?php foreach ( $elmira_cats as $elmira_c ) : ?>
    <li><a href="<?php echo esc_url( get_category_link( $elmira_c ) ); ?>" class="flex justify-between py-2 group"<?php echo is_category( $elmira_c->term_id ) ? ' aria-current="page"' : ''; ?>><span class="transition group-hover:text-copper-ink group-hover:-translate-x-1"><?php echo esc_html( $elmira_c->name ); ?></span><span class="tag tag-mute"><?php echo esc_html( elmira_fa( $elmira_c->count ) ); ?></span></a></li>
    <?php endforeach; ?>
  </ul>
</div>
