<?php
/**
 * KLABEN Autos — ACF Free + WordPress data adapter.
 *
 * Requires no ACF PRO fields: CPT klaben_auto, taxonomy klaben_merk,
 * ACF Free fields, featured image, and optional Post Object multi-selects.
 * If ACF is inactive or no complete published vehicle exists, the existing
 * data/cars.php dataset remains available through klaben_get_vehicles().
 *
 * Keep the ACF field names aligned with Voertuiggegevens in WordPress.
 */

/** @return int[] */
function klaben_acf_selected_ids($value): array
{
    if ($value instanceof WP_Post) {
        $value = [$value];
    } elseif (!is_array($value)) {
        $value = is_numeric($value) ? [$value] : [];
    }
    $ids = [];
    foreach ($value as $entry) {
        $id = $entry instanceof WP_Post ? $entry->ID : (is_numeric($entry) ? (int) $entry : 0);
        if ($id > 0 && !in_array($id, $ids, true)) {
            $ids[] = $id;
        }
    }
    return $ids;
}

/** Resolve an ACF image ID / WP thumbnail ID to a responsive generated image URL. */
function klaben_acf_image_url($value, string $size = 'large'): string
{
    if (is_array($value)) {
        $value = $value['ID'] ?? $value['id'] ?? $value['url'] ?? null;
    }
    if (is_numeric($value)) {
        $url = wp_get_attachment_image_url((int) $value, $size);
        return is_string($url) ? $url : '';
    }
    if (is_string($value) && preg_match('~^https?://~i', $value)) {
        return esc_url_raw($value);
    }
    return '';
}

/**
 * Normalise a vehicle image: Media Library ID/URL or legacy theme filename.
 * Images referenced in fallback data/cars.php still work unchanged.
 */
function klaben_resolve_vehicle_image($image): string
{
    $media_url = klaben_acf_image_url($image);
    if ($media_url !== '') {
        return $media_url;
    }
    if (!is_string($image) || $image === '') {
        return '';
    }
    $relative = '/assets/images/cars/' . basename($image);
    return is_file(get_theme_file_path($relative)) ? get_theme_file_uri($relative) : '';
}

/** @return array<int,array<string,mixed>> */
function klaben_acf_vehicles(): array
{
    if (!function_exists('get_field') || !post_type_exists('klaben_auto')) {
        return [];
    }

    $posts = get_posts([
        'post_type' => 'klaben_auto',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'suppress_filters' => false,
    ]);
    $vehicles = [];
    foreach ($posts as $post) {
        $id = (int) $post->ID;
        $brand_terms = get_the_terms($id, 'klaben_merk');
        $brand = !is_wp_error($brand_terms) && is_array($brand_terms) && $brand_terms
            ? (string) reset($brand_terms)->name : '';
        $model = trim((string) get_field('model', $id));
        $price = (int) get_field('price', $id);
        $year = (int) get_field('year', $id);

        // Don't accidentally publish an incomplete entry to the catalogue.
        if ($brand === '' || $model === '' || $price < 1 || $year < 1950) {
            continue;
        }

        $images = [];
        $featured_id = get_post_thumbnail_id($id);
        if ($featured_id) {
            $image_url = klaben_acf_image_url($featured_id);
            if ($image_url !== '') {
                $images[] = $image_url;
            }
        }
        for ($n = 1; $n <= 4; $n++) {
            $image_url = klaben_acf_image_url(get_field('extra_image_' . $n, $id));
            if ($image_url !== '' && !in_array($image_url, $images, true)) {
                $images[] = $image_url;
            }
        }

        $vehicles[] = [
            'id' => $id,
            'post_id' => $id,
            'slug' => (string) $post->post_name,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'price' => $price,
            'mileage' => max(0, (int) get_field('mileage', $id)),
            'fuel' => (string) get_field('fuel', $id),
            'transmission' => (string) get_field('transmission', $id),
            'body' => (string) get_field('body', $id),
            'location' => (string) get_field('location', $id),
            'image' => $images[0] ?? '',
            'images' => $images,
            'featured' => (bool) get_field('featured', $id),
            'related_vehicle_ids' => klaben_acf_selected_ids(get_field('related_vehicles', $id)),
            'related_article_ids' => klaben_acf_selected_ids(get_field('related_articles', $id)),
        ];
    }
    return $vehicles;
}

function klaben_vehicle_url(array $car): string
{
    if (!empty($car['post_id'])) {
        return get_permalink((int) $car['post_id']);
    }
    return add_query_arg('auto', (string) $car['slug'], home_url('/autos/'));
}

/** @return array<int,array<string,mixed>> */
function klaben_related_vehicle_records(array $car): array
{
    $all = klaben_get_vehicles();
    if (array_key_exists('post_id', $car)) {
        // Editor choices are authoritative: an empty selection means show none.
        $by_id = [];
        foreach ($all as $entry) {
            if (!empty($entry['post_id'])) $by_id[(int) $entry['post_id']] = $entry;
        }
        $related = [];
        foreach (($car['related_vehicle_ids'] ?? []) as $id) {
            if (isset($by_id[$id]) && $id !== (int) $car['post_id']) {
                $related[] = $by_id[$id];
            }
        }
        return array_slice($related, 0, 2);
    }
    // Legacy demo cards continue to show the other two sample vehicles.
    return array_slice(array_values(array_filter($all,
        static fn (array $entry): bool => $entry['slug'] !== $car['slug'])), 0, 2);
}

/** @return WP_Post[] */
function klaben_related_article_posts(array $car): array
{
    $ids = $car['related_article_ids'] ?? [];
    if (!$ids) return [];
    return get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'post__in' => array_slice(array_map('intval', $ids), 0, 3),
        'orderby' => 'post__in',
        'posts_per_page' => 3,
        'suppress_filters' => false,
    ]);
}
