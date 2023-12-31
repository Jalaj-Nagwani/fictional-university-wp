<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

function universityCustomRest()
{
    register_rest_field('post', 'author_name', array(
        'get_callback' => function () {
            return get_the_author();
        }
    ));

    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function () {
            return count_user_posts(get_current_user_id(), 'note');
        }
    ));
}

add_action('rest_api_init', 'universityCustomRest');


function pageBanner($args = NULL)
{

    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if (!isset($args['photo'])) {
        if (get_field('page_banner_background_image') and !is_archive() and !is_home()) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['professorLandscape'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>

<?php }


function slideshow()
{
?>

    <div class="hero-slider__slide" style="background-image: url(<?php echo get_field('slide_image'); ?>)">
        <div class="hero-slider__interior container">
            <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center"><?php echo get_field('slide_heading'); ?></h2>
                <p class="t-center"><?php echo get_field('slide_subheading'); ?></p>
                <p class="t-center no-margin"><a href="<?php if (get_field('button_link') != null) {echo get_field('button_link');} else{ echo "#"; }?>" class="btn btn--blue"><?php echo get_field('button_text'); ?></a></p>
            </div>
        </div>
    </div>

<?php }


function university_files()
{
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key="<GOOGLE-API-KEY>"', array('jquery'), '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

    wp_localize_script('main-university-js', 'universityData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}




add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
    register_nav_menu('headerMenuLocation', 'Header Menu');
    register_nav_menu('exploreMenuLocation', 'Explore Menu');
    register_nav_menu('learnMenuLocation', 'Learn Menu');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');


function university_adjust_queries($query)
{


    if (!is_admin() and is_post_type_archive('campus') and is_main_query()) {
        $query->set('posts_per_page', '-1');
    }

    if (!is_admin() and is_post_type_archive('program') and is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', '-1');
    }


    if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => date('Ymd'),
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api)
{
    $api['KEY'] = '<GOOGLE-MAPS-API-KEY>';
    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');


//  Redirect Subscriber account out of admin and onto homepage

function redirect_subs_to_frontend()
{

    $current_user = wp_get_current_user();

    if (count($current_user->roles) == 1 and $current_user->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('admin_init', 'redirect_subs_to_frontend');

//  Hide admin bar for subscribers

function no_admin_bar_for_subs()
{

    $current_user = wp_get_current_user();

    if (count($current_user->roles) == 1 and $current_user->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

add_action('wp_loaded', 'no_admin_bar_for_subs');

// Customize Login Screen

function ourHeaderUrl()
{
    return esc_url(site_url('/'));
}

add_filter('login_headerurl', 'ourHeaderUrl');

function our_login_css()
{
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

add_action('login_enqueue_scripts', 'our_login_css');

function ourLoginTitle()
{
    return get_bloginfo('name');
}

add_filter('login_headertitle', 'ourLoginTitle');


// Force Note Posts to be private


function makeNotePrivate($data, $postarr)
{

    // Prevent user from creating a note after a specific limit has reached

    if ($data['post_type'] == 'note') {

        if (count_user_posts(get_current_user_id(), 'note') >= 5 and !$postarr['ID']) {
            die('You have reached the limit');  // It prevent anything even the below data to be executed
        }

        //  Escape/Strip the html before saving data into the database

        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }

    // Make the Note post_type private instead of publish before saving it in the database

    if ($data['post_type'] == 'note' and $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }

    return $data;
}

add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);  // 10 for the priority which is by default and 2 for working with 2 arguments


// Exclude the node_modules folder from All in One WP Migrations

add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');

function ignoreCertainFiles()
{
    $exclude_filters[] = "themes/fictional-university-theme/node_modules";
    return $exclude_filters;
}
