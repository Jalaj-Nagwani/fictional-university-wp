<?php

/*

Plugin Name: Are You Paying Attention Quiz
Description: Give your readers a multiple choice question.
Version: 1.0
Author: Jalaj
Author URL: https://google.com/

*/

if (!defined('ABSPATH')) exit;  // Exit if accessed directly

class AreYouPayingAttention
{

    function __construct()
    {
        add_action('init', array($this, 'adminAssets'));
    }

    function adminAssets()
    {
        wp_register_script('ournewblocktype', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-element'));
        register_block_type('ourplugin/are-you-paying-attention', array(
            'editor_script' => 'ournewblocktype',
            'render_callback' => array($this, 'theHtml')
        ));
    }

    function theHtml($attr)
    {
        ob_start();
        $query = new WP_Query(array(
            'post_type' => 'post',
            'post_per_page' => 1
        ));

        while ($query->have_posts()) {
            $query->the_post(); ?>

            <h3><?php the_title(); ?></h3>

        <?php }
        
        return ob_get_clean();
    }
}

$areYouPayingAttention = new AreYouPayingAttention();
