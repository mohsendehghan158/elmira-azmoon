<?php
/**
 * تصویر شاخص عریض نوشته.
 *
 * @package elmira
 * @var WP_Block $block
 */

defined( 'ABSPATH' ) || exit;

$elmira_id  = elmira_context_post_id( $block );
$elmira_img = elmira_post_image( $elmira_id, 'full' );
if ( ! $elmira_img ) {
	return;
}
$elmira_alt = has_post_thumbnail( $elmira_id ) ? get_post_meta( get_post_thumbnail_id( $elmira_id ), '_wp_attachment_image_alt', true ) : '';
?>
<div class="rounded-2xl overflow-hidden aspect-[21/9] mb-12" data-reveal="clip"><img src="<?php echo esc_url( elmira_sized( $elmira_img, 1400, 600 ) ); ?>" alt="<?php echo esc_attr( $elmira_alt ); ?>" class="size-full object-cover scale-110" width="1400" height="600" data-parallax=".05"></div>
