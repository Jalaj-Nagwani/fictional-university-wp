<?php

get_header();


while (have_posts()) {
    the_post();
    pageBanner();
?>




    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses </a> <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <!-- Google Map Code -->

        <!-- <div class="acf-map">

            <?php
            $mapLocation = get_field('map_location');
            ?>

            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

            <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
                <h3><?php the_title(); ?></h3>
                <?php echo $mapLocation['address'] ?>
            </div>

        <?php } ?>

        </div> -->

        <ul>

            <?php
            $relatedPrograms = new WP_Query(array(
                'post_type' => 'program',
                'posts_per_page' => -1, // -1 for all posts
                'orderby' => 'title',  // rand/title/event_date etc.
                'order' => 'ASC',  // ASC/DESC
                'meta_query' => array(
                    array(
                        'key' => 'related_campuses',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                ),
            ));

            if ($relatedPrograms->have_posts()) {

                echo "<hr class='section-break'>";
                echo "<h2 class='headline headline--medium'> Programs Available at " . get_the_title() . "</h2>";
    
                echo "<ul class='min-list link-list'>";
    
                while ($relatedPrograms->have_posts()) {
                    $relatedPrograms->the_post(); ?>
    
                    <li>
                        <a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a>
                    </li>
    
            <?php }
            echo "</ul>";
            }
            wp_reset_postdata();
            ?>

        </ul>

    </div>


    <?php


    get_footer();

    ?>