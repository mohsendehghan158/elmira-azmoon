<?php
/**
 * دیدگاه‌ها با طراحی قالب: فرم در بالا و فهرست رشته‌ای در پایین.
 *
 * @package elmira
 * @var WP_Block $block
 */

defined( 'ABSPATH' ) || exit;

$elmira_id = elmira_context_post_id( $block );
if ( ! $elmira_id || post_password_required( $elmira_id ) ) {
	return;
}
$elmira_open  = comments_open( $elmira_id );
$elmira_count = (int) get_comments_number( $elmira_id );
if ( ! $elmira_open && ! $elmira_count ) {
	return;
}

if ( ! function_exists( 'elmira_comment_cb' ) ) {
	/**
	 * یک دیدگاه.
	 *
	 * @param WP_Comment $comment دیدگاه.
	 * @param array      $args    تنظیمات.
	 * @param int        $depth   عمق.
	 */
	function elmira_comment_cb( $comment, $args, $depth ) {
		$is_author = $comment->user_id && (int) $comment->user_id === (int) get_post_field( 'post_author', $comment->comment_post_ID );
		$name      = get_comment_author( $comment );
		$avatar    = $comment->user_id ? get_avatar_url( $comment, array( 'size' => 96 ) ) : '';
		echo '<div id="comment-' . (int) $comment->comment_ID . '" class="flex flex-col gap-5">';
		echo '<div class="flex gap-4">';
		if ( $avatar ) {
			echo '<img src="' . esc_url( $avatar ) . '" alt="" class="size-11 shrink-0 rounded-full object-cover" loading="lazy" width="96" height="96">';
		} else {
			echo '<span class="grid place-items-center size-11 shrink-0 rounded-full bg-tint font-display text-xl text-copper-ink">' . esc_html( mb_substr( $name, 0, 1 ) ) . '</span>';
		}
		echo '<div class="min-w-0"><p class="text-sm !text-ink !mb-1"><strong>' . esc_html( $name ) . '</strong> ';
		if ( $is_author ) {
			echo '<span class="tag tag-soft">نویسنده</span> ';
		}
		/* translators: %s: human time diff */
		echo '<span class="text-xs text-ink-2">· ' . esc_html( elmira_fa( sprintf( '%s پیش', human_time_diff( get_comment_time( 'U', true, true, $comment ), time() ) ) ) ) . '</span></p>';
		if ( '0' === $comment->comment_approved ) {
			echo '<p class="text-xs !text-copper-ink !mb-1">دیدگاه شما در انتظار تأیید است.</p>';
		}
		echo '<div class="text-sm [&_p]:!mb-2">' . wp_kses_post( wpautop( get_comment_text( $comment ) ) ) . '</div>';
		comment_reply_link(
			array_merge(
				$args,
				array(
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'reply_text' => '<i class="ph-light ph-arrow-bend-up-left" aria-hidden="true"></i> پاسخ',
					'before'     => '<p class="text-xs !mb-0 mt-1 [&_a]:inline-flex [&_a]:items-center [&_a]:gap-1 [&_a]:text-copper-ink">',
					'after'      => '</p>',
				)
			),
			$comment
		);
		echo '</div></div>';
	}
}

$elmira_commenter = wp_get_current_commenter();
$elmira_comments  = get_comments(
	array(
		'post_id'            => $elmira_id,
		'status'             => 'approve',
		'order'              => 'ASC',
		'include_unapproved' => array_filter( array( get_current_user_id(), $elmira_commenter['comment_author_email'] ) ),
	)
);
?>
<section id="comments">
  <h2 class="!mt-0">دیدگاه‌ها (<?php echo esc_html( elmira_fa( $elmira_count ) ); ?>)</h2>
  <?php
  if ( $elmira_open ) {
	  $elmira_req = '<span class="req">*</span>';
	  comment_form(
		  array(
			  'class_container'      => 'comment-respond mb-8',
			  'class_form'           => 'card p-6 flex flex-col gap-4',
			  'title_reply'          => '',
			  'title_reply_before'   => '<p id="reply-title" class="comment-reply-title text-sm !mb-0">',
			  'title_reply_after'    => '</p>',
			  'title_reply_to'       => 'پاسخ به %s',
			  'cancel_reply_link'    => 'انصراف',
			  'comment_notes_before' => '',
			  'comment_notes_after'  => '',
			  'logged_in_as'         => '<p class="text-xs text-ink-2 !mb-0">' . sprintf( 'با نام %s وارد شده‌اید.', '<strong>' . esc_html( wp_get_current_user()->display_name ) . '</strong>' ) . '</p>',
			  'comment_field'        => '<label><span class="label">دیدگاه خود را بنویسید ' . $elmira_req . '</span><textarea id="comment" name="comment" class="textarea" required maxlength="65525"></textarea></label>',
			  'fields'               => array(
				  'author'  => '<div class="grid sm:grid-cols-2 gap-4"><label><span class="label">نام ' . $elmira_req . '</span><input id="author" name="author" class="input" required autocomplete="name" value="' . esc_attr( $elmira_commenter['comment_author'] ) . '"></label>',
				  'email'   => '<label><span class="label">ایمیل ' . ( get_option( 'require_name_email' ) ? $elmira_req : '' ) . '</span><input id="email" name="email" class="input" type="email" dir="ltr"' . ( get_option( 'require_name_email' ) ? ' required' : '' ) . ' autocomplete="email" value="' . esc_attr( $elmira_commenter['comment_author_email'] ) . '"></label></div>',
				  'cookies' => '<label class="check text-xs"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . ( empty( $elmira_commenter['comment_author_email'] ) ? '' : ' checked' ) . '>نام و ایمیلم برای دیدگاه بعدی ذخیره شود.</label>',
			  ),
			  'class_submit'         => 'btn btn-dark w-fit',
			  'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="%3$s">%4$s</button>',
			  'submit_field'         => '%1$s %2$s',
			  'label_submit'         => 'ارسال دیدگاه',
		  ),
		  $elmira_id
	  );
  }
  if ( $elmira_comments ) {
	  echo '<div class="flex flex-col gap-5 ea-comment-list">';
	  wp_list_comments(
		  array(
			  'style'        => 'div',
			  'callback'     => 'elmira_comment_cb',
			  'end-callback' => function () {
				  echo '</div>';
			  },
			  'max_depth'    => (int) get_option( 'thread_comments_depth', 5 ),
		  ),
		  $elmira_comments
	  );
	  echo '</div>';
  }
  ?>
</section>
