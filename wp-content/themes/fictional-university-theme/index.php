<!-- <?php
        function custom($name, $color)
        {
            echo "Hello my name is $name and my favorite color is $color";
        }
        custom('John', 'Red');
        ?>

<h1><?php bloginfo('name'); ?></h1>
<h2><?php bloginfo('description'); ?></h1>


<?php
$names = array('Jalaj', 'John', 'Jane');

$count = 0;

while ($count < count($names)) {
    echo "<h1> Hi I'm $names[$count] <h1>";
    $count++;
}
?> -->


<?php

get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg') ?>)"></div>
    <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome to our blog !</h1>
        <h2 class="headline headline--medium">Keep checking for latest update</h2>
        <!-- <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
        <a href="#" class="btn btn--large btn--blue">Find Your Major</a> -->
    </div>
</div>

<div class="container container--narrow page-section">
    <?php 
        while (have_posts()){ 
            the_post();?>

            <div class="post-item">
                <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </div>
            <div class="metabox">
                Posted By <?php the_author_posts_link(); ?> on <?php the_time('j-M-Y'); ?> in <?php echo get_the_category_list(', '); ?>
            </div>

            <div class="generic-conttent">
            <?php the_excerpt();?>
            <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue Reading >></a></p>
            </div>

        <?php }

        echo paginate_links();
    ?>
</div>

<?php

get_footer();

?>











