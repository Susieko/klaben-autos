<?php
/** Single editorial article — regular WordPress Posts. */
get_header();
if (have_posts()): while (have_posts()): the_post();
    $terms = get_the_category();
    $category = $terms ? $terms[0]->name : 'Journal';
    $news_url = home_url('/nieuws/');
?>
<article <?php post_class('news-article'); ?>>
    <header class="news-article__header">
        <div class="container news-article__header-inner">
            <nav class="news-crumbs" aria-label="Broodkruimelnavigatie"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span aria-hidden="true">/</span><a href="<?php echo esc_url($news_url); ?>">Nieuws</a><span aria-hidden="true">/</span><span aria-current="page">Artikel</span></nav>
            <p class="news-kicker"><?php echo esc_html($category); ?> <span aria-hidden="true">/</span> KLABEN JOURNAL</p>
            <h1><?php the_title(); ?></h1>
            <div class="news-article__meta"><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time><span aria-hidden="true">—</span><span>KLABEN Autos</span></div>
        </div>
    </header>
    <?php if (has_post_thumbnail()): ?>
        <div class="news-article__cover container"><?php the_post_thumbnail('full', ['loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async']); ?></div>
    <?php endif; ?>
    <div class="news-article__layout container">
        <div class="news-article__content">
            <?php the_content(); ?>
            <?php wp_link_pages(['before' => '<nav class="news-article__pages" aria-label="Artikelpagina’s">', 'after' => '</nav>']); ?>
        </div>
        <aside class="news-article__aside" aria-label="Meer van Klaben">
            <div class="news-article__aside-box"><span class="news-kicker">THE KLABEN JOURNAL</span><h2>Auto’s met een verhaal.</h2><p>Meer ontdekken? Bekijk de rest van onze artikelen en zorgvuldig geselecteerde auto’s.</p><a href="<?php echo esc_url($news_url); ?>">Alle verhalen ↗</a><a href="<?php echo esc_url(home_url('/autos/')); ?>">Bekijk de collectie ↗</a></div>
        </aside>
    </div>
    <div class="news-article__footer container"><a href="<?php echo esc_url($news_url); ?>">← Terug naar alle verhalen</a></div>
</article>
<?php endwhile; endif; get_footer(); ?>
