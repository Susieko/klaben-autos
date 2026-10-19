<?php
/**
 * Homepage process section: a three-step introduction to the Klaben journey.
 * The copy and imagery live together here for easy future editing.
 */
$klaben_process_steps = [
    [
        'number' => '01',
        'stage' => 'Ontdek',
        'title' => 'Ontdek jouw auto',
        'description' => 'Bekijk onze selectie, filter op jouw voorkeuren en vind een occasion die bij je past.',
        'image' => '01-ontdek.webp',
        'position' => '60% 50%',
    ],
    [
        'number' => '02',
        'stage' => 'Ervaar',
        'title' => 'Plan een bezichtiging',
        'description' => 'Heb je een favoriet gevonden? Neem contact op en plan een bezichtiging of proefrit.',
        'image' => '02-ervaar.webp',
        'position' => '58% 50%',
    ],
    [
        'number' => '03',
        'stage' => 'Rijd',
        'title' => 'Rijd zorgeloos weg',
        'description' => 'Kies desgewenst voor financiering en ga met vertrouwen op weg in jouw volgende auto.',
        'image' => '03-rijd.webp',
        'position' => '62% 50%',
    ],
];
?>
<section class="klaben-process" id="werkwijze" aria-labelledby="klaben-process-title">
    <div class="container">
        <header class="klaben-process__intro reveal">
            <p class="klaben-process__eyebrow">Eenvoudig en vertrouwd</p>
            <h2 class="klaben-process__heading" id="klaben-process-title">
                Zo werkt het <span>bij Klaben</span>
            </h2>
            <p class="klaben-process__lead">
                Van eerste selectie tot aflevering: in drie heldere stappen helpen we je naar jouw volgende occasion.
            </p>
        </header>

        <ol class="klaben-process__grid" aria-label="Drie stappen naar jouw volgende occasion">
            <?php foreach ($klaben_process_steps as $index => $step): ?>
                <li class="klaben-process__step reveal" style="--reveal-delay: <?php echo esc_attr((string) ($index * 110)); ?>ms;">
                    <article class="klaben-process__card">
                        <div class="klaben-process__copy">
                            <p class="klaben-process__stage">
                                <span class="klaben-process__index"><?php echo esc_html($step['number']); ?></span>
                                <span><?php echo esc_html($step['stage']); ?></span>
                            </p>
                            <h3 class="klaben-process__title"><?php echo esc_html($step['title']); ?></h3>
                            <p class="klaben-process__description"><?php echo esc_html($step['description']); ?></p>
                        </div>
                        <div class="klaben-process__media" aria-hidden="true">
                            <img
                                src="<?php echo esc_url(get_theme_file_uri('/assets/images/process/' . $step['image'])); ?>"
                                alt=""
                                width="1080"
                                height="810"
                                loading="lazy"
                                decoding="async"
                                style="object-position: <?php echo esc_attr($step['position']); ?>;"
                            >
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ol>

        <p class="klaben-process__footnote reveal">
            Eerst rustig rondkijken? <a href="<?php echo esc_url(home_url('/autos/')); ?>">Ontdek onze selectie <span aria-hidden="true">→</span></a>
        </p>
    </div>
</section>
