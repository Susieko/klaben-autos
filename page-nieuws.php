<?php
/**
 * KLABEN Nieuws — uses the standard WordPress post type.
 * Create a regular Page with slug "nieuws". Do NOT assign it as the Settings > Reading "Posts page".
 */
$topic = isset($_GET['topic']) && is_string($_GET['topic']) ? sanitize_title(wp_unslash($_GET['topic'])) : '';
$category = $topic ? get_category_by_slug($topic) : null;
$invalid_topic = $topic !== '' && !$category;
$page_number = isset($_GET['nieuws_p']) && is_string($_GET['nieuws_p']) ? max(1, min(9999, absint($_GET['nieuws_p']))) : 1;
$per_page = 7;
$args = ['post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $per_page, 'paged' => $page_number, 'ignore_sticky_posts' => true];
if ($category) $args['cat'] = (int) $category->term_id;
if ($invalid_topic) $args['post__in'] = [0];
$news = new WP_Query($args);
$categories = get_categories(['hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC']);
$news_url = get_permalink();
get_header();
?>
<section class="news-intro news-intro--editorial" aria-labelledby="news-title">
    <div class="container news-editorial">
        <nav class="news-crumbs" aria-label="Broodkruimelnavigatie">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Nieuws</span>
        </nav>
        <div class="news-editorial__topline">
            <p class="news-kicker">KLABEN JOURNAL <span aria-hidden="true">/</span> VERHALEN & INZICHTEN</p>
            <span class="news-editorial__index" aria-hidden="true">VERHALEN MET KARAKTER</span>
        </div>
        <div class="news-editorial__layout">
            <h1 id="news-title">Meer dan alleen <em>de rit.</em></h1>
            <div class="news-editorial__description">
                <p>Verhalen over bijzondere auto's, slimme keuzes en alles wat rijden mooi maakt. Met aandacht samengesteld.</p>
                <a href="#verhalen" class="news-editorial__cta">Ontdek de verhalen <span aria-hidden="true">↓</span></a>
            </div>
        </div>
        <div class="news-editorial__footer" aria-hidden="true">
            <span>AUTONIEUWS <b>·</b> KOOPADVIES <b>·</b> ACHTER DE SCHERMEN</span>
            <span class="news-editorial__mark">K / JOURNAL</span>
        </div>
    </div>
</section>

<section class="news-feed news-feed--journal" id="verhalen" aria-labelledby="news-feed-title">
    <div class="container">
        <div class="news-section-heading">
            <div><p class="news-kicker">ONZE REDACTIE</p><h2 id="news-feed-title">Laatste <span>verhalen.</span></h2></div>
            <p>Van rijplezier tot praktische tips. Ideeën, inspiratie en verhalen uit de autowereld.</p>
        </div>
        <nav class="news-topics" aria-label="Filter verhalen op onderwerp">
            <a class="news-topics__link <?php echo $topic === '' ? 'is-current' : ''; ?>" <?php echo $topic === '' ? 'aria-current="page"' : ''; ?> href="<?php echo esc_url($news_url); ?>">Alles</a>
            <?php foreach ($categories as $cat): ?>
                <a class="news-topics__link <?php echo $topic === $cat->slug ? 'is-current' : ''; ?>" <?php echo $topic === $cat->slug ? 'aria-current="page"' : ''; ?> href="<?php echo esc_url(add_query_arg('topic', $cat->slug, $news_url)); ?>"><?php echo esc_html($cat->name); ?></a>
            <?php endforeach; ?>
        </nav>
        <?php if ($news->have_posts()): ?>
            <div class="news-list news-list--journal" aria-label="Artikelen">
                <?php $index = 0; while ($news->have_posts()): $news->the_post();
                    get_template_part('includes/news-card', null, ['variant' => $page_number === 1 && $index === 0 ? 'featured' : ($page_number === 1 && $index <= 2 ? 'compact' : 'regular')]);
                    $index++;
                endwhile; wp_reset_postdata(); ?>
            </div>
            <?php if ((int) $news->max_num_pages > 1): ?>
                <nav class="news-pagination" aria-label="Paginering van nieuws">
                    <?php if ($page_number > 1): ?>
                        <a href="<?php echo esc_url(add_query_arg(array_filter(['topic' => $topic, 'nieuws_p' => $page_number - 1]), $news_url)); ?>" rel="prev">← Vorige</a>
                    <?php else: ?><span aria-hidden="true"></span><?php endif; ?>
                    <span>Pagina <?php echo esc_html((string) $page_number); ?> van <?php echo esc_html((string) $news->max_num_pages); ?></span>
                    <?php if ($page_number < (int) $news->max_num_pages): ?>
                        <a href="<?php echo esc_url(add_query_arg(array_filter(['topic' => $topic, 'nieuws_p' => $page_number + 1]), $news_url)); ?>" rel="next">Volgende →</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <div class="news-empty" role="status">
                <span class="news-empty__icon" aria-hidden="true">✳</span>
                <p class="news-kicker">BINNENKORT MEER</p>
                <h3><?php echo $topic ? 'Nog geen artikelen in deze categorie.' : 'Het eerste verhaal wordt nog geschreven.'; ?></h3>
                <p><?php echo $topic ? 'Ontdek ondertussen onze andere verhalen.' : 'Binnenkort vind je hier het Klaben Journal. Tot die tijd: ontdek de collectie.'; ?></p>
                <a class="button button--primary" href="<?php echo esc_url($topic ? $news_url : home_url('/autos/')); ?>"><?php echo $topic ? 'Bekijk alle verhalen' : 'Bekijk onze auto’s'; ?> ↗</a>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</section>

<section class="news-bottom" aria-label="Bekijk het aanbod"><div class="container news-bottom__inner">
    <div><p class="news-kicker">KLABEN AUTOS</p><h2>Genoeg gelezen.<br><span>Tijd om te rijden.</span></h2><p>Van de eerste kennismaking tot de perfecte match.</p></div>
    <a class="button button--primary" href="<?php echo esc_url(home_url('/autos/')); ?>">Ontdek de collectie ↗</a>
</div></section>
<?php get_footer(); ?>
