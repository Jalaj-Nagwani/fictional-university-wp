<?php

function universitySearchResults($data)
{
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'general_info' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() == "post" or get_post_type() == 'page') {

            array_push($results['general_info'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'link' => get_the_permalink(),
                'type' => get_post_type(),
                'author_name' => get_the_author(),
            ));
        }

        if (get_post_type() == "professor") {

            array_push($results['professors'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'link' => get_the_permalink(),
                'image_link' => get_the_post_thumbnail_url(0, 'professorLandscape'),
            ));
        }

        if (get_post_type() == "program") {

            array_push($results['programs'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'link' => get_the_permalink(),
            ));
        }

        if (get_post_type() == "event") {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if (has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 15);
            }

            array_push($results['events'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'link' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'date' => $eventDate->format('d'),
                'description' => $description
            ));
        }

        if (get_post_type() == "campus") {

            array_push($results['campuses'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'link' => get_the_permalink(),
            ));
        }
    }

    if ($results['programs']) {

        $progrmasMetaQuery = array('relation' => 'OR');

        foreach ($results['programs'] as $item) {
            array_push($progrmasMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"',
            ));
        }

        $programRelQuery = new WP_Query(array(
            'post_type' => 'professor',
            'meta_query' => $progrmasMetaQuery,
        ));

        while ($programRelQuery->have_posts()) {
            $programRelQuery->the_post();

            if (get_post_type() == "professor") {

                array_push($results['professors'], array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'link' => get_the_permalink(),
                    'image_link' => get_the_post_thumbnail_url(0, 'professorLandscape'),
                ));
            }
        }

        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR)); // To Remove the duplicate values from Professors Array
    }

    return $results;
}

function universityRegisterSearch()
{
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_Server::READABLE,  // GET Method
        'callback' => 'universitySearchResults'
    ));
}

add_action('rest_api_init', 'universityRegisterSearch');
