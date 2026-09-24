<?php
/**
 * فهرست مطالب خودکار از تیترهای h2 نوشته (theme.js بخش فعال را هنگام اسکرول مشخص می‌کند).
 *
 * @package elmira
 * @var array    $attributes
 * @var WP_Block $block
 */

defined( 'ABSPATH' ) || exit;

$elmira_post = get_post( elmira_context_post_id( $block ) );
$elmira_h    = array();

/**
 * تیترهای سطح ۲ در بلاک‌های نوشته.
 *
 * @param array $blocks بلاک‌ها.
 * @param array $out    خروجی.
 */
$elmira_walk = function ( $blocks, &$out ) use ( &$elmira_walk ) {
	foreach ( $blocks as $b ) {
		if ( 'core/heading' === $b['blockName'] && 2 === ( isset( $b['attrs']['level'] ) ? (int) $b['attrs']['level'] : 2 ) ) {
			$text = trim( wp_strip_all_tags( $b['innerHTML'] ) );
			$id   = ! empty( $b['attrs']['anchor'] ) ? $b['attrs']['anchor'] : ( preg_match( '/\sid="([^"]+)"/', $b['innerHTML'], $m ) ? $m[1] : elmira_heading_id( $text ) );
			if ( $text ) {
				$out[] = array( $id, $text );
			}
		}
		if ( ! empty( $b['innerBlocks'] ) ) {
			$elmira_walk( $b['innerBlocks'], $out );
		}
	}
};
if ( $elmira_post ) {
	$elmira_walk( parse_blocks( $elmira_post->post_content ), $elmira_h );
}

if ( count( $elmira_h ) < 2 ) {
	// ستون خالی تا چیدمان سه‌ستونه به هم نریزد.
	echo '<div class="hidden lg:block" aria-hidden="true"></div>';
	return;
}
?>
<nav class="lg:sticky lg:top-24 card p-5 text-sm [&_a.is-active]:text-copper-ink [&_a.is-active]:border-copper" data-spy aria-label="<?php echo esc_attr( $attributes['title'] ); ?>">
  <p class="font-semibold mb-3"><?php echo esc_html( $attributes['title'] ); ?></p>
  <ol class="flex flex-col gap-1">
    <?php foreach ( $elmira_h as $elmira_x ) : ?>
    <li><a href="#<?php echo esc_attr( $elmira_x[0] ); ?>" class="block py-1.5 ps-3 border-s-2 border-line transition hover:text-copper-ink"><?php echo esc_html( $elmira_x[1] ); ?></a></li>
    <?php endforeach; ?>
  </ol>
</nav>
