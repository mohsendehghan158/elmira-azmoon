<?php
/**
 * برچسب‌های نوشته و کارت نویسنده.
 *
 * @package elmira
 * @var WP_Block $block
 */

defined( 'ABSPATH' ) || exit;

$elmira_id     = elmira_context_post_id( $block );
$elmira_tags   = get_the_tags( $elmira_id );
$elmira_author = (int) get_post_field( 'post_author', $elmira_id );
if ( ! $elmira_author ) {
	return;
}
$elmira_bio = get_the_author_meta( 'description', $elmira_author );
?>
<?php if ( $elmira_tags ) : ?>
<div class="flex flex-wrap gap-2 mb-10 mt-10">
  <?php foreach ( $elmira_tags as $elmira_t ) : ?>
  <a href="<?php echo esc_url( get_tag_link( $elmira_t ) ); ?>" class="chip">#<?php echo esc_html( str_replace( ' ', '_', $elmira_t->name ) ); ?></a>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<div class="card p-6 flex gap-5 items-center mb-14<?php echo $elmira_tags ? '' : ' mt-10'; ?>">
  <img src="<?php echo esc_url( get_avatar_url( $elmira_author, array( 'size' => 200 ) ) ); ?>" alt="" class="size-20 rounded-full object-cover shrink-0" loading="lazy" width="200" height="200">
  <div><p class="font-display text-2xl !text-ink !mb-0"><a href="<?php echo esc_url( get_author_posts_url( $elmira_author ) ); ?>"><?php echo esc_html( get_the_author_meta( 'display_name', $elmira_author ) ); ?></a></p><?php if ( $elmira_bio ) : ?><p class="text-sm !mb-0"><?php echo esc_html( $elmira_bio ); ?></p><?php endif; ?></div>
</div>
