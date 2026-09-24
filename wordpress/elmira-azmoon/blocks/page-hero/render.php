<?php
/**
 * هیروی صفحه: همان page_hero قالب HTML، با عنوان و مسیر از صفحهٔ جاری وردپرس.
 *
 * @package elmira
 * @var array    $attributes
 * @var WP_Block $block
 */

defined( 'ABSPATH' ) || exit;

$elmira_post_id = elmira_context_post_id( $block );
$elmira_preview = elmira_is_editor_preview();
$elmira_title   = '';
$elmira_sub     = '';
$elmira_outline = '';
$elmira_crumbs  = array( array( 'خانه', home_url( '/' ) ) );
$elmira_blog    = get_option( 'page_for_posts' ) ? array( get_the_title( (int) get_option( 'page_for_posts' ) ), get_permalink( (int) get_option( 'page_for_posts' ) ) ) : array( 'مجله', home_url( '/' ) );
$elmira_is_post = false;

if ( is_home() || ( $elmira_preview && ! $elmira_post_id ) ) {
	$elmira_title = $elmira_blog[0];
} elseif ( is_search() ) {
	$elmira_title    = 'جستجو: «' . get_search_query() . '»';
	$elmira_sub      = sprintf( '%s نتیجه پیدا شد.', elmira_fa( (int) $GLOBALS['wp_query']->found_posts ) );
	$elmira_crumbs[] = $elmira_blog;
	$elmira_outline  = 'جستجو';
} elseif ( is_404() ) {
	$elmira_title = 'این صفحه پیدا نشد';
} elseif ( is_category() || is_tag() || is_tax() ) {
	$elmira_title    = single_term_title( '', false );
	$elmira_sub      = wp_strip_all_tags( term_description() );
	$elmira_crumbs[] = $elmira_blog;
} elseif ( is_author() ) {
	$elmira_title    = get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) );
	$elmira_sub      = get_the_author_meta( 'description', (int) get_query_var( 'author' ) );
	$elmira_crumbs[] = $elmira_blog;
} elseif ( is_archive() ) {
	$elmira_title    = wp_strip_all_tags( get_the_archive_title() );
	$elmira_crumbs[] = $elmira_blog;
} elseif ( $elmira_post_id ) {
	$elmira_p     = get_post( $elmira_post_id );
	$elmira_title = get_the_title( $elmira_p );
	if ( $elmira_p && 'post' === $elmira_p->post_type ) {
		$elmira_is_post  = true;
		$elmira_crumbs[] = $elmira_blog;
		$elmira_cat      = elmira_primary_category( $elmira_p );
		if ( $elmira_cat ) {
			$elmira_crumbs[] = array( $elmira_cat->name, get_category_link( $elmira_cat ) );
			$elmira_outline  = $elmira_cat->name;
		}
	} elseif ( $elmira_p ) {
		foreach ( array_reverse( get_post_ancestors( $elmira_p ) ) as $elmira_anc ) {
			$elmira_crumbs[] = array( get_the_title( $elmira_anc ), get_permalink( $elmira_anc ) );
		}
		if ( has_excerpt( $elmira_p ) ) {
			$elmira_sub = get_the_excerpt( $elmira_p );
		}
	}
}

if ( '' !== $attributes['title'] ) {
	$elmira_title = $attributes['title'];
}
if ( '' !== $attributes['sub'] ) {
	$elmira_sub = $attributes['sub'];
}
if ( '' !== $attributes['outline'] ) {
	$elmira_outline = $attributes['outline'];
}
if ( '' === $elmira_outline ) {
	$elmira_words   = preg_split( '/\s+/u', wp_strip_all_tags( $elmira_title ) );
	$elmira_outline = $elmira_words ? $elmira_words[0] : '';
}
if ( '' === $elmira_title ) {
	$elmira_title = 'عنوان صفحه';
}
$elmira_crumbs[] = array( $elmira_title, '' );
?>
<section class="grad spotlight page-hero" data-hero>
  <div class="aurora" aria-hidden="true"><span></span><span></span><span></span></div>
  <span class="outline-text font-display absolute -bottom-6 md:-bottom-12 left-2 md:left-8 text-[26vw] md:text-[15rem] leading-none pointer-events-none select-none" aria-hidden="true" data-parallax="-.12"><?php echo esc_html( $elmira_outline ); ?></span>
  <div class="wrap relative">
    <nav class="crumbs" aria-label="مسیر صفحه" data-reveal="up">
      <?php
      foreach ( $elmira_crumbs as $elmira_i => $elmira_c ) {
	      if ( $elmira_i ) {
		      echo '<span aria-hidden="true">/</span>';
	      }
	      if ( $elmira_c[1] ) {
		      printf( '<a href="%s" class="link-u">%s</a>', esc_url( $elmira_c[1] ), esc_html( $elmira_c[0] ) );
	      } else {
		      printf( '<span aria-current="page" class="text-white">%s</span>', esc_html( $elmira_c[0] ) );
	      }
      }
      ?>
    </nav>
    <h1 class="font-display text-5xl md:text-7xl leading-[1.05] mt-4" data-split><?php echo esc_html( $elmira_title ); ?></h1>
    <?php if ( $elmira_sub ) : ?>
    <p class="mt-5 max-w-xl text-white/75" data-reveal="up" style="--d:3"><?php echo esc_html( $elmira_sub ); ?></p>
    <?php endif; ?>
    <?php
    if ( $elmira_is_post || 'post' === $attributes['variant'] && $elmira_post_id ) :
	    $elmira_author = (int) get_post_field( 'post_author', $elmira_post_id );
	    $elmira_avatar = get_avatar_url( $elmira_author, array( 'size' => 96 ) );
	    ?>
    <div class="flex flex-wrap items-center justify-between gap-4 mt-8" data-reveal="up" style="--d:4">
      <p class="flex items-center gap-3 text-sm text-white/80">
        <img src="<?php echo esc_url( $elmira_avatar ); ?>" alt="" class="size-11 rounded-full object-cover" width="96" height="96">
        <span><strong class="text-white"><?php echo esc_html( get_the_author_meta( 'display_name', $elmira_author ) ); ?></strong><br><span class="text-xs"><?php echo esc_html( elmira_fa( get_the_date( '', $elmira_post_id ) ) . ' · ' . elmira_reading_label( $elmira_post_id ) . ' مطالعه' ); ?></span></span>
      </p>
      <div class="flex gap-2">
        <button type="button" class="btn btn-ghost btn-sm" data-share><i class="ph-light ph-share-network"></i>اشتراک‌گذاری</button>
        <button type="button" class="btn btn-ghost btn-sm" onclick="eaToast('مقاله ذخیره شد','ph-bookmark-simple')"><i class="ph-light ph-bookmark-simple"></i>ذخیره</button>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
