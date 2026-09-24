<?php
/**
 * محتوای نمونهٔ مجله برای راه‌اندازی یک‌کلیکی.
 * ساخته‌شده با wordpress/build/demo_posts.py؛ مستقیم ویرایش نکنید.
 *
 * @package elmira
 */

defined( 'ABSPATH' ) || exit;

return array(
	'categories' => array(
		'skin' => 'مراقبت پوست',
		'hair' => 'مو و رنگ',
		'makeup' => 'آرایش',
		'nail' => 'ناخن',
		'career' => 'آموزش و شغل',
	),
	'posts' => array( array(
			'slug' => 'autumn-skincare-routine',
			'title' => 'روتین مراقبت پوست در پاییز: از شست‌وشو تا ضدآفتاب',
			'cat' => 'skin',
			'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1400&h=900&q=70',
			'sticky' => true,
			'excerpt' => '[خلاصهٔ نمونه] با سرد شدن هوا، پوست زودتر آب از دست می‌دهد. در این مقاله قدم‌به‌قدم روتین صبح و شب را مرور می‌کنیم.',
			'content' => '<!-- wp:paragraph {"className":"is-style-lead"} -->
<p class="is-style-lead">[متن نمونه] این مقاله نمونه است؛ متن واقعی «روتین مراقبت پوست در پاییز» را این‌جا بنویسید.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۱. نکتهٔ اول</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ اول و این‌که چرا مهم است.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۲. نکتهٔ دوم</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ دوم.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-check"} -->
<ul class="wp-block-list is-style-check"><!-- wp:list-item -->
<li>[مورد نمونه ۱]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۲]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۳]</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۳. جمع‌بندی</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] جمع‌بندی و پیشنهاد کارشناس سالن.</p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"elmira/booking-cta"} /-->',
			'tags' => array( 'مراقبت پوست', 'پاییز' ),
		), array(
			'slug' => 'after-facial-routine',
			'title' => 'روتین پوست بعد از فیشیال: ۴۸ ساعت اول',
			'cat' => 'skin',
			'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1400&h=900&q=70',
			'excerpt' => 'چه کارهایی را انجام بدهید و از چه چیزهایی دوری کنید تا نتیجهٔ پاکسازی بماند.',
			'content' => '<!-- wp:paragraph {"className":"is-style-lead"} -->
<p class="is-style-lead">[متن نمونه] پاکسازی پوست منافذ را باز و لایهٔ بیرونی را حساس‌تر می‌کند. کاری که در دو روز بعد انجام می‌دهید تعیین می‌کند نتیجه چقدر بماند.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۱. چرا ۴۸ ساعت اول مهم است</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>بعد از فیشیال، پوست در حال ترمیم است و نسبت به نور، گرما و مواد فعال واکنش بیشتری نشان می‌دهد. اگر در این مدت فشار زیادی به آن بیاید، قرمزی و جوش‌های ریز ظاهر می‌شوند.</p>
<!-- /wp:paragraph -->

<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://images.unsplash.com/photo-1585945037805-5fd82c2e60b1?auto=format&fit=crop&w=900&h=500&q=70" alt="رد کرم مراقبت پوست روی سطح روشن"/><figcaption class="wp-element-caption">یک کرم آبرسان ساده، بهترین دوست پوست بعد از پاکسازی است.</figcaption></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۲. چه کارهایی انجام بدهید</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>صورت را فقط با آب ولرم و شویندهٔ ملایم بشویید، آبرسان بزنید و اگر بیرون می‌روید حتماً ضدآفتاب استفاده کنید.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>«بعد از پاکسازی، کمتر بیشتر است: شست‌وشوی ملایم، آبرسانی و ضدآفتاب.»</p>
<!-- /wp:paragraph --><cite>— المیرا آزمون</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۳. از چه چیزهایی دوری کنید</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-avoid"} -->
<ul class="wp-block-list is-style-avoid"><!-- wp:list-item -->
<li>لایه‌بردارها و اسکراب‌ها تا دست‌کم ۴۸ ساعت</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>سونا، استخر و ورزش سنگین در روز اول</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>آرایش سنگین و کرم‌پودرهای غلیظ</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۴. جمع‌بندی</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>دو روز مراقبت ساده، نتیجهٔ یک جلسهٔ پاکسازی را هفته‌ها نگه می‌دارد. اگر سؤال خاصی دربارهٔ پوستتان دارید، در نوبت بعد از کارشناس بپرسید.</p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"elmira/booking-cta"} /-->',
			'tags' => array( 'مراقبت پوست', 'پاکسازی', 'پوست چرب', 'سرم ویتامین C' ),
			'comments' => array( array( 'کاربر نمونه', 'ممنون، دقیقاً همین سؤال را داشتم. ضدآفتاب معدنی بهتر است یا شیمیایی؟', 'بعد از پاکسازی، ضدآفتاب معدنی معمولاً ملایم‌تر است.' ) ),
		), array(
			'slug' => 'make-hair-color-last',
			'title' => 'چطور رنگ مو دیرتر بشوید؟',
			'cat' => 'hair',
			'image' => 'https://images.unsplash.com/photo-1634449571010-02389ed0f9b0?auto=format&fit=crop&w=1400&h=900&q=70',
			'excerpt' => 'دمای آب، نوع شامپو و فاصلهٔ شست‌وشو؛ سه چیزی که عمر رنگ را تعیین می‌کند.',
			'content' => '<!-- wp:paragraph {"className":"is-style-lead"} -->
<p class="is-style-lead">[متن نمونه] این مقاله نمونه است؛ متن واقعی «چطور رنگ مو دیرتر بشوید؟» را این‌جا بنویسید.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۱. نکتهٔ اول</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ اول و این‌که چرا مهم است.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۲. نکتهٔ دوم</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ دوم.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-check"} -->
<ul class="wp-block-list is-style-check"><!-- wp:list-item -->
<li>[مورد نمونه ۱]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۲]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۳]</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۳. جمع‌بندی</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] جمع‌بندی و پیشنهاد کارشناس سالن.</p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"elmira/booking-cta"} /-->',
		), array(
			'slug' => 'clean-makeup-brushes',
			'title' => 'برس‌های آرایشی را چطور بشوییم',
			'cat' => 'makeup',
			'image' => 'https://images.unsplash.com/photo-1596704017254-9b121068fb31?auto=format&fit=crop&w=1400&h=900&q=70',
			'excerpt' => 'روش ساده و هفتگی برای تمیز نگه داشتن برس‌ها و اسفنج‌ها.',
			'content' => '<!-- wp:paragraph {"className":"is-style-lead"} -->
<p class="is-style-lead">[متن نمونه] این مقاله نمونه است؛ متن واقعی «برس‌های آرایشی را چطور بشوییم» را این‌جا بنویسید.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۱. نکتهٔ اول</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ اول و این‌که چرا مهم است.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۲. نکتهٔ دوم</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ دوم.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-check"} -->
<ul class="wp-block-list is-style-check"><!-- wp:list-item -->
<li>[مورد نمونه ۱]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۲]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۳]</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۳. جمع‌بندی</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] جمع‌بندی و پیشنهاد کارشناس سالن.</p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"elmira/booking-cta"} /-->',
		), array(
			'slug' => 'student-to-pro-artist',
			'title' => 'از هنرجو تا آرایشگر حرفه‌ای: ۵ قدم اول',
			'cat' => 'career',
			'image' => 'https://images.unsplash.com/photo-1595475884562-073c30d45670?auto=format&fit=crop&w=1400&h=900&q=70',
			'excerpt' => 'تجربهٔ هنرجوهای آموزشگاه از ماه‌های اول کار.',
			'content' => '<!-- wp:paragraph {"className":"is-style-lead"} -->
<p class="is-style-lead">[متن نمونه] این مقاله نمونه است؛ متن واقعی «از هنرجو تا آرایشگر حرفه‌ای» را این‌جا بنویسید.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۱. نکتهٔ اول</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ اول و این‌که چرا مهم است.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۲. نکتهٔ دوم</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ دوم.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-check"} -->
<ul class="wp-block-list is-style-check"><!-- wp:list-item -->
<li>[مورد نمونه ۱]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۲]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۳]</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۳. جمع‌بندی</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] جمع‌بندی و پیشنهاد کارشناس سالن.</p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"elmira/booking-cta"} /-->',
		), array(
			'slug' => 'brittle-nails',
			'title' => 'ناخن‌های شکننده: علت‌ها و راه‌حل‌ها',
			'cat' => 'nail',
			'image' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=1400&h=900&q=70',
			'excerpt' => 'چرا ناخن می‌شکند و چه عادت‌هایی کمک می‌کند.',
			'content' => '<!-- wp:paragraph {"className":"is-style-lead"} -->
<p class="is-style-lead">[متن نمونه] این مقاله نمونه است؛ متن واقعی «ناخن‌های شکننده» را این‌جا بنویسید.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۱. نکتهٔ اول</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ اول و این‌که چرا مهم است.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۲. نکتهٔ دوم</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ دوم.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-check"} -->
<ul class="wp-block-list is-style-check"><!-- wp:list-item -->
<li>[مورد نمونه ۱]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۲]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۳]</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۳. جمع‌بندی</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] جمع‌بندی و پیشنهاد کارشناس سالن.</p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"elmira/booking-cta"} /-->',
		), array(
			'slug' => 'vitamin-c-serum-guide',
			'title' => 'سرم ویتامین C را کِی و چطور بزنیم؟',
			'cat' => 'skin',
			'image' => 'https://images.unsplash.com/photo-1620916297397-a4a5402a3c6c?auto=format&fit=crop&w=1400&h=900&q=70',
			'excerpt' => 'ترتیب درست لایه‌ها و ترکیب‌هایی که بهتر است کنار هم نباشند.',
			'content' => '<!-- wp:paragraph {"className":"is-style-lead"} -->
<p class="is-style-lead">[متن نمونه] این مقاله نمونه است؛ متن واقعی «سرم ویتامین C» را این‌جا بنویسید.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۱. نکتهٔ اول</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ اول و این‌که چرا مهم است.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۲. نکتهٔ دوم</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] توضیح کوتاه دربارهٔ نکتهٔ دوم.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-check"} -->
<ul class="wp-block-list is-style-check"><!-- wp:list-item -->
<li>[مورد نمونه ۱]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۲]</li>
<!-- /wp:list-item --><!-- wp:list-item -->
<li>[مورد نمونه ۳]</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2 class="wp-block-heading">۳. جمع‌بندی</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[متن نمونه] جمع‌بندی و پیشنهاد کارشناس سالن.</p>
<!-- /wp:paragraph -->

<!-- wp:pattern {"slug":"elmira/booking-cta"} /-->',
		) ),
);
