<?php

get_header(); 
pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events',
    'photo' => get_theme_file_uri('/images/library-hero.jpg')
));
?>

<div class="container container--narrow page-section">
    <?php
    $pastpageEvents = new WP_Query(array(
        'paged' => get_query_var('paged', 1),
        'post_type' => 'event',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',  // rand/title/event_date etc.
        'order' => 'ASC',  // ASC/DESC
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'compare' => '<',
                'value' => date('Ymd'),
                'type' => 'numeric'
            )
        ),
    ));

    while ($pastpageEvents->have_posts()) {
        $pastpageEvents->the_post();
        get_template_part('template-parts/content-event');
    }

    echo paginate_links(array(
        'total' => $pastpageEvents->max_num_pages,
    ));
    ?>
</div>

<?php

get_footer();

?>