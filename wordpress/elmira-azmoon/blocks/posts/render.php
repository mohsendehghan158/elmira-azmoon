<?php
/**
 * کارت‌های مقاله (کامپوننت post قالب HTML) از نوشته‌های واقعی وردپرس.
 *
 * layout=featured: نوشتهٔ ویژه (آخرین نوشتهٔ سنجاق‌شده) در قاب بزرگ.
 * inherit=true:    فهرست کوئری اصلی صفحه (مجله، دسته، جستجو) با صفحه‌بندی.
 *
 * @package elmira
 * @var array $attributes
 */

defined( 'ABSPATH' ) || exit;

$elmira_preview = elmira_is_editor_preview();

/* ---------- نوشتهٔ ویژه ---------- */
if ( 'featured' === $attributes['layout'] ) {
	if ( is_paged() ) {
		return;
	}
	$elmira_id = elmira_featured_post_id();
	if ( ! $elmira_id && $elmira_preview ) {
		$elmira_latest = get_posts( array( 'posts_per_page' => 1, 'fields' => 'ids' ) );
		$elmira_id     = $elmira_latest ? $elmira_latest[0] : 0;
	}
	if ( ! $elmira_id ) {
		if ( $elmira_preview ) {
			echo '<p class="wrap py-10 text-ink-2">برای نمایش مقالهٔ ویژه، یک نوشته را «سنجاق» کنید.</p>';
		}
		return;
	}
	$elmira_img    = elmira_post_image( $elmira_id );
	$elmira_author = (int) get_post_field( 'post_author', $elmira_id );
	?>
<section class="pt-14 md:pt-20">
  <div class="wrap">
    <a href="<?php echo esc_url( get_permalink( $elmira_id ) ); ?>" class="group grid lg:grid-cols-[1.3fr_1fr] gap-8 items-center" data-cursor="بخوانید">
      <div class="zoom rounded-2xl aspect-[16/10] img-ph" data-reveal="clip"><?php if ( $elmira_img ) : ?><img src="<?php echo esc_url( elmira_sized( $elmira_img, 1100, 690 ) ); ?>" alt="" class="size-full object-cover" width="1100" height="690"><?php endif; ?></div>
      <div class="flex flex-col gap-4" data-reveal="up">
        <span class="tag tag-salmon w-fit">مقالهٔ ویژه</span>
        <h2 class="font-display text-4xl md:text-5xl leading-tight transition group-hover:text-copper-ink"><?php echo esc_html( get_the_title( $elmira_id ) ); ?></h2>
        <p class="text-ink-2"><?php echo esc_html( get_the_excerpt( $elmira_id ) ); ?></p>
        <p class="flex items-center gap-3 text-xs text-ink-2"><img src="<?php echo esc_url( get_avatar_url( $elmira_author, array( 'size' => 80 ) ) ); ?>" alt="" class="size-9 rounded-full object-cover" width="80" height="80"><span><strong class="text-ink"><?php echo esc_html( get_the_author_meta( 'display_name', $elmira_author ) ); ?></strong> · <?php echo esc_html( elmira_fa( get_the_date( '', $elmira_id ) ) ); ?> · <?php echo esc_html( elmira_reading_label( $elmira_id ) ); ?> مطالعه</span></p>
        <span class="link-arrow">خواندن مقاله <i class="ph-light ph-arrow-left" aria-hidden="true"></i></span>
      </div>
    </a>
  </div>
</section>
	<?php
	return;
}

/* ---------- شبکهٔ کارت‌ها ---------- */
$elmira_inherit = ! empty( $attributes['inherit'] ) && ! $elmira_preview;
if ( $elmira_inherit ) {
	global $wp_query;
	$elmira_q = $wp_query;
} else {
	$elmira_args = array(
		'posts_per_page'      => max( 1, (int) $attributes['count'] ),
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => true,
	);
	if ( $attributes['category'] ) {
		$elmira_args['category_name'] = $attributes['category'];
	}
	$elmira_q = new WP_Query( $elmira_args );
}

$elmira_cols = preg_match( '/grid-cols-3/', $attributes['gridClass'] ) ? 3 : 2;

if ( ! $elmira_q->have_posts() ) {
	echo '<div class="card p-8 text-center text-ink-2"><i class="ph-light ph-article text-4xl text-copper-ink" aria-hidden="true"></i><p class="mt-3">مطلبی پیدا نشد.</p></div>';
	return;
}
?>
<div class="<?php echo esc_attr( $attributes['gridClass'] ); ?>"<?php echo $elmira_inherit ? ' id="posts"' : ''; ?>>
<?php
$elmira_i = 0;
while ( $elmira_q->have_posts() ) :
	$elmira_q->the_post();
	$elmira_cat = elmira_primary_category( get_the_ID() );
	$elmira_img = elmira_post_image( get_the_ID(), 'medium_large' );
	?>
  <a href="<?php the_permalink(); ?>" class="group flex flex-col gap-4 fx-item" data-cat="<?php echo esc_attr( $elmira_cat ? $elmira_cat->slug : 'all' ); ?>" data-reveal="up" style="--d:<?php echo (int) ( $elmira_i % $elmira_cols ); ?>" data-cursor="بخوانید">
    <div class="zoom rounded-xl aspect-[3/2] img-ph"><?php if ( $elmira_img ) : ?><img src="<?php echo esc_url( elmira_sized( $elmira_img, 700, 470 ) ); ?>" alt="" class="size-full object-cover" loading="lazy" width="700" height="470"><?php endif; ?></div>
    <div class="flex flex-col gap-2">
      <p class="flex items-center gap-3 text-xs"><?php if ( $elmira_cat ) : ?><span class="tag tag-soft"><?php echo esc_html( $elmira_cat->name ); ?></span><?php endif; ?><span class="inline-flex items-center gap-1 text-ink-2"><i class="ph-light ph-clock" aria-hidden="true"></i><span><?php echo esc_html( elmira_reading_label() ); ?> مطالعه</span></span></p>
      <h3 class="font-display text-2xl leading-snug transition group-hover:text-copper-ink"><?php the_title(); ?></h3>
      <?php if ( ! empty( $attributes['showExcerpt'] ) ) : ?>
      <p class="text-sm text-ink-2 line-clamp-2"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
      <?php endif; ?>
    </div>
  </a>
	<?php
	++$elmira_i;
endwhile;
?>
</div>
<?php
if ( $elmira_inherit ) {
	$elmira_total = (int) $elmira_q->max_num_pages;
	$elmira_cur   = max( 1, (int) get_query_var( 'paged' ) );
	if ( $elmira_total > 1 ) {
		echo '<nav class="flex justify-center gap-2 mt-14" aria-label="صفحه‌بندی">';
		if ( $elmira_cur > 1 ) {
			printf( '<a href="%s" class="round-btn !size-11" aria-label="صفحهٔ قبل"><i class="ph-light ph-arrow-right" aria-hidden="true"></i></a>', esc_url( get_pagenum_link( $elmira_cur - 1 ) ) );
		}
		$elmira_prev = 0;
		for ( $elmira_n = 1; $elmira_n <= $elmira_total; $elmira_n++ ) {
			if ( 1 !== $elmira_n && $elmira_total !== $elmira_n && abs( $elmira_n - $elmira_cur ) > 1 ) {
				continue;
			}
			if ( $elmira_prev && $elmira_n - $elmira_prev > 1 ) {
				echo '<span class="grid place-items-center size-11 text-ink-2">…</span>';
			}
			if ( $elmira_n === $elmira_cur ) {
				printf( '<a href="%s" class="round-btn !size-11 !bg-ink !text-bg !border-ink" aria-current="page">%s</a>', esc_url( get_pagenum_link( $elmira_n ) ), esc_html( elmira_fa( $elmira_n ) ) );
			} else {
				printf( '<a href="%s" class="round-btn !size-11">%s</a>', esc_url( get_pagenum_link( $elmira_n ) ), esc_html( elmira_fa( $elmira_n ) ) );
			}
			$elmira_prev = $elmira_n;
		}
		if ( $elmira_cur < $elmira_total ) {
			printf( '<a href="%s" class="round-btn !size-11" aria-label="صفحهٔ بعد"><i class="ph-light ph-arrow-left" aria-hidden="true"></i></a>', esc_url( get_pagenum_link( $elmira_cur + 1 ) ) );
		}
		echo '</nav>';
	}
}
wp_reset_postdata();
