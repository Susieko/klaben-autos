<?php
/** Presentational component for a post inside the News archive WP_Query loop. */
$is_featured = !empty($args['featured']);
$terms = get_the_category();
$category = $terms ? $terms[0]->name : 'Journal';
$has_image = has_post_thumbnail();
?>
<article <?php post_class('news-card reveal' . ($is_featured ? ' news-card--featured' : '')); ?>>
    <a class="news-card__media <?php echo $has_image ? '' : 'news-card__media--placeholder'; ?>" href="<?php the_permalink(); ?>" aria-label="Lees: <?php echo esc_attr(get_the_title()); ?>">
        <?php if ($has_image): ?>
            <?php the_post_thumbnail($is_featured ? 'large' : 'medium_large', ['loading' => 'lazy', 'decoding' => 'async']); ?>
        <?php else: ?>
            <span class="news-card__placeholder-word" aria-hidden="true">K.</span>
            <span class="news-card__placeholder-tag" aria-hidden="true">KLABEN JOURNAL</span>
        <?php endif; ?>
        <span class="news-card__media-arrow" aria-hidden="true">↗</span>
    </a>
    <div class="news-card__body">
        <div class="news-card__meta"><span><?php echo esc_html($category); ?></span><span class="news-card__meta-dot" aria-hidden="true">·</span><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time></div>
        <h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p class="news-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), $is_featured ? 34 : 19, '…')); ?></p>
        <a class="news-card__read" href="<?php the_permalink(); ?>">Lees het verhaal <span aria-hidden="true">↗</span></a>
    </div>
</article>
