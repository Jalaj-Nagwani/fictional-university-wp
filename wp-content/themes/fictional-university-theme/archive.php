<!--            
                // Code to conditionally display different categories
                
                <?php if (is_category()) {
                    echo single_cat_title();
                }

                if (is_author()) {
                    echo 'All posts by ';
                    the_author();
                } ?> 
                    
-->

<?php

get_header(); 

pageBanner(array(
    'title' => get_the_archive_title(),
    'subtitle' => get_the_archive_description(),
    'photo' => get_theme_file_uri('/images/library-hero.jpg')
));
?>

<div class="container container--narrow page-section">
    <?php
    while (have_posts()) {
        the_post(); ?>

        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </div>
        <div class="metabox">
            Posted By <?php the_author_posts_link(); ?> on <?php the_time('j-M-Y'); ?> in <?php echo get_the_category_list(', '); ?>
        </div>

        <div class="generic-conttent">
            <?php the_excerpt(); ?>
            <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue Reading >></a></p>
        </div>

    <?php }

    echo paginate_links();
    ?>
</div>

<?php

get_footer();

?>