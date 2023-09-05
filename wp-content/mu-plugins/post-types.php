<?php


function university_post_types(){


    // Event Post Type

    register_post_type('event', array(
        'supports' => array('title', 'editor', 'excerpt', 'revisions', 'thumbnail'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'events'),
        'public' => true,  // Makes it visible to Viewers and Editors of the website 
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt',
    ));

    // Program Post Type

    register_post_type('program', array(
        'supports' => array('title', 'editor', 'excerpt', 'revisions', 'thumbnail'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'programs'),
        'public' => true,  // Makes it visible to Viewers and Editors of the website 
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-book-alt',
    ));


    // Professor Post Type

    register_post_type('professor', array(
        'supports' => array('title', 'editor', 'excerpt', 'revisions', 'thumbnail'),
        'public' => true,  // Makes it visible to Viewers and Editors of the website 
        'labels' => array(
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-businessperson',
    ));


    // Campus Post Type

    register_post_type('campus', array(
        'supports' => array('title', 'editor', 'excerpt', 'revisions', 'thumbnail'),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'campuses'),
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-building',
    ));
}

add_action('init', 'university_post_types');

?>