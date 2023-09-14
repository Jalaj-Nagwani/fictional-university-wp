<?php

get_header();
pageBanner(array(
    'title' => 'All Campuses',
    'subtitle' => 'See what is going on in our world',
    'photo' => get_theme_file_uri('/images/ocean.jpg')
));
?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php
        while (have_posts()) {
            the_post();
            $mapLocation = get_field('map_location');
        ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

            <!-- Google Map Code -->

            <!-- <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php echo $mapLocation['address'] ?>
            </div> -->

        <?php } ?>

    </ul>

    <hr class="section-break">

</div>

<?php

get_footer();

?>