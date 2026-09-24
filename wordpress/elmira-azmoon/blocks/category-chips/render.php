<?php
/**
 * چیپ‌های دسته‌بندی مجله؛ هر چیپ به بایگانی همان دسته می‌رود.
 *
 * @package elmira
 * @var array $attributes
 */

defined( 'ABSPATH' ) || exit;

$elmira_cats = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 8, 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
if ( ! $elmira_cats ) {
	return;
}
$elmira_blog = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/' );
$elmira_cur  = is_category() ? get_queried_object_id() : 0;
?>
<nav class="chips mb-10" aria-label="دسته‌بندی مقالات" data-reveal="up">
  <a class="chip<?php echo $elmira_cur ? '' : ' is-active'; ?>" href="<?php echo esc_url( $elmira_blog ); ?>"<?php echo $elmira_cur ? '' : ' aria-current="page"'; ?>><?php echo esc_html( $attributes['allLabel'] ); ?></a>
  <?php foreach ( $elmira_cats as $elmira_c ) : ?>
  <a class="chip<?php echo $elmira_cur === $elmira_c->term_id ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $elmira_c ) ); ?>"<?php echo $elmira_cur === $elmira_c->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $elmira_c->name ); ?></a>
  <?php endforeach; ?>
</nav>
