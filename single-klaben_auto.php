<?php
/** Dedicated single vehicle template. Falls back gracefully if ACF is inactive. */
$car = null;
foreach (klaben_get_vehicles() as $entry) {
    if (!empty($entry['post_id']) && (int) $entry['post_id'] === get_queried_object_id()) {
        $car = $entry;
        break;
    }
}
if ($car === null) {
    status_header(404);
    nocache_headers();
}
get_header();
if ($car !== null) {
    require get_theme_file_path('/includes/car-detail.php');
} else { ?>
    <section class="inventory-empty container" style="padding:100px 0 130px">
        <p class="inventory-eyebrow">Niet gevonden</p>
        <h1>Dit voertuig is momenteel niet beschikbaar.</h1>
        <a class="button button--primary" href="<?php echo esc_url(home_url('/autos/')); ?>">Bekijk alle auto’s</a>
    </section>
<?php }
get_footer();
