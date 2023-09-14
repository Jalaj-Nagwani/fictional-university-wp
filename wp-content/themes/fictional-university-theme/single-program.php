<?php

get_header();


while (have_posts()) {
    the_post();
    pageBanner(array(
        'title' => get_the_title(),
        'photo' => get_theme_file_uri('/images/library-hero.jpg')
    ));
?>


    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs </a> <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>


        <?php
        $relatedProfessor = new WP_Query(array(
            'post_type' => 'professor',
            'posts_per_page' => -1, // -1 for all posts
            'orderby' => 'title',  // rand/title/event_date etc.
            'order' => 'ASC',  // ASC/DESC
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            ),
        ));

        if ($relatedProfessor->have_posts()) {

            echo "<hr class='section-break'>";
            echo "<h2 class='headline headline--medium'>" . get_the_title() . " Professors</h2>";

            echo "<ul class='professor-cards'>";

            while ($relatedProfessor->have_posts()) {
                $relatedProfessor->the_post(); ?>

                <li class="professor-card__list-item">
                    <a href="<?php the_permalink(); ?>" class="professor-card">

                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
                        <span class="professor-card__name"><?php the_title(); ?></span>

                    </a>
                </li>

        <?php }
            echo "</ul>";
        }
        wp_reset_postdata();
        ?>


        <?php }

    $homepageEvents = new WP_Query(array(
        'post_type' => 'event',
        'posts_per_page' => -1, // -1 for all posts
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',  // rand/title/event_date etc.
        'order' => 'ASC',  // ASC/DESC
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => date('Ymd'),
                'type' => 'numeric'
            ),
            array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
            )
        ),
    ));


    if ($homepageEvents->have_posts()) {

        echo "<hr class='section-break'>";
        echo "<h2 class='headline headline--medium'>Upcoming " . get_the_title() . " Events</h2>";

        while ($homepageEvents->have_posts()) {
            $homepageEvents->the_post();
            get_template_part('template-parts/content-event');
        }
    }

    wp_reset_postdata();

    $relatedCampuses = get_field('related_campuses');

    if ($relatedCampuses) {

        echo "<hr class='section-break'>";
        echo '<h2 class="headline headline--medium">' . get_the_title() . ' is available at these campuses </h2>';

        echo '<ul class="min-list link-list">';

        foreach ($relatedCampuses as $relatedCampus) {
        ?>

            <li><a href="<?php echo get_the_permalink($relatedCampus); ?>"> <?php echo get_the_title($relatedCampus); ?></a></li>
    <?php }

        echo '</ul>';
    }
    ?>




    </div>


    <?php

    get_footer();

    ?>