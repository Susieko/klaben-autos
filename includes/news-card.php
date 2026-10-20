<?php
/**
 * KLABEN Journal — reusable editorial card.
 * Renders content from the current WordPress post in the archive loop.
 */
$variant = isset($args['variant']) && in_array($args['variant'], ['featured', 'compact', 'regular'], true)
    ? $args['variant'] : 'regular';
$terms = get_the_category();
$category = $terms ? $terms[0]->name : 'Journal';
$has_image = has_post_thumbnail();
// Estimate reading time from the post body, excluding HTML/block markup.
$post_body = trim(wp_strip_all_tags(strip_shortcodes((string) get_post_field('post_content', get_the_ID()))));
$words = preg_split('/\s+/u', $post_body, -1, PREG_SPLIT_NO_EMPTY);
$minutes = max(1, (int) ceil(count($words ?: []) / 200));
?>
<article <?php post_class('news-card news-card--' . $variant); ?>>
    <a class="news-card__media <?php echo $has_image ? '' : 'news-card__media--placeholder'; ?>" href="<?php the_permalink(); ?>" aria-label="Lees: <?php echo esc_attr(get_the_title()); ?>">
        <?php if ($has_image): ?>
            <?php the_post_thumbnail($variant === 'featured' ? 'large' : 'medium_large', ['loading' => 'lazy', 'decoding' => 'async']); ?>
        <?php else: ?>
            <span class="news-card__placeholder-word" aria-hidden="true">K.</span>
            <span class="news-card__placeholder-tag" aria-hidden="true">KLABEN JOURNAL</span>
        <?php endif; ?>
        <span class="news-card__media-arrow" aria-hidden="true">↗</span>
    </a>
    <div class="news-card__body">
        <div class="news-card__meta">
            <span><?php echo esc_html($category); ?></span>
            <span class="news-card__reading-time"><?php echo esc_html((string) $minutes); ?> min leestijd</span>
        </div>
        <h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p class="news-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), $variant === 'featured' ? 24 : ($variant === 'compact' ? 14 : 21), '…')); ?></p>
        <a class="news-card__read" href="<?php the_permalink(); ?>">Lees het verhaal <span aria-hidden="true">↗</span></a>
    </div>
</article>
