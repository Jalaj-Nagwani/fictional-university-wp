<div class="post-item">
    <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div class="metabox">
        Posted By <?php the_author_posts_link(); ?> on <?php the_time('j-M-Y'); ?> in <?php echo get_the_category_list(', '); ?>
    </div>

    <div class="generic-conttent">
        <?php the_excerpt(); ?>
        <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue Reading >></a></p>
    </div>

</div>