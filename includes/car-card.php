<?php

if (!isset($car) || !is_array($car)) {
    return;
}

$image_files = $car['images'] ?? [$car['image']];
$car_images = [];

foreach ($image_files as $image_file) {
    $image_relative_path = '/assets/images/cars/' . $image_file;
    $image_absolute_path = get_theme_file_path($image_relative_path);

    if (file_exists($image_absolute_path)) {
        $car_images[] = get_theme_file_uri($image_relative_path);
    }
}

if (!empty($car_images)) {
    $car_image = $car_images[0];
} else {
    $car_image = get_theme_file_uri(
        '/assets/images/hero/klaben-hero.png'
    );

    $car_images = [$car_image];
}

$car_url = add_query_arg(
    'auto',
    $car['slug'],
    home_url('/autos/')
);

?>

<article
    class="car-card reveal reveal--card"
    data-category="<?php echo esc_attr(
        strtolower($car['body'])
    ); ?>"
    style="--reveal-delay: <?php echo esc_attr(
        $card_delay ?? 0
    ); ?>ms;"
>
    <a
        class="car-card__image-link"
        href="<?php echo esc_url($car_url); ?>"
        aria-label="<?php echo esc_attr(
            'Bekijk ' . $car['brand'] . ' ' . $car['model']
        ); ?>"
    >
<img
    class="car-card__image car-hover-image"
    src="<?php echo esc_url($car_image); ?>"
    data-car-images="<?php echo esc_attr(
        wp_json_encode($car_images)
    ); ?>"
    alt="<?php echo esc_attr(
        $car['brand'] . ' ' . $car['model']
    ); ?>"
>

<?php if ($car['brand'] === 'KLABEN'): ?>

    <span class="car-card__exclusive">
        KLABEN Original
    </span>

<?php endif; ?>
    </a>

    <button
        class="car-card__favourite"
        type="button"
        aria-label="Voeg toe aan favorieten"
        aria-pressed="false"
    >
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            aria-hidden="true"
        >
            <path
                d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5
                5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5
                5.5 0 0 0 0-7.8z"
            ></path>
        </svg>
    </button>

    <div class="car-card__content">

        <p class="car-card__brand">
            <?php echo esc_html($car['brand']); ?>
        </p>

        <h3 class="car-card__title">
            <a href="<?php echo esc_url($car_url); ?>">
                <?php echo esc_html($car['model']); ?>
            </a>
        </h3>

        <div class="car-card__specs">

            <span>
                <?php echo esc_html(
                    number_format(
                        $car['mileage'],
                        0,
                        ',',
                        '.'
                    )
                ); ?> km
            </span>

            <span>
                <?php echo esc_html($car['transmission']); ?>
            </span>

            <span>
                <?php echo esc_html($car['fuel']); ?>
            </span>

        </div>

        <div class="car-card__footer">

            <p class="car-card__price">
                € <?php echo esc_html(
                    number_format(
                        $car['price'],
                        0,
                        ',',
                        '.'
                    )
                ); ?>
            </p>

            <p class="car-card__location">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11z"></path>
                    <circle cx="12" cy="10" r="2"></circle>
                </svg>

                <?php echo esc_html($car['location']); ?>
            </p>

        </div>

    </div>
</article>