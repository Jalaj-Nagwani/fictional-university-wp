<?php

/*

Plugin Name: Word Filter
Description: Replaces a list of words
Version: 1.0
Author: Jalaj
Author URL: https://google.com/

*/

if (!defined('ABSPATH')) exit;  // Exit if accessed directly

class OurWordFilterPlugin
{

    function __construct()
    {
        add_action('admin_menu', array($this, 'ourMenu'));
        add_action('admin_init', array($this, 'ourSettings'));
        if (get_option('plugin_words_to_filter')) add_filter('the_content', array($this, 'filterLogic'));
    }

    function filterLogic($content){
        $badWords = explode(",", get_option('plugin_words_to_filter'));
        $trimmedBadWords = array_map('trim', $badWords);
        return str_ireplace($trimmedBadWords, esc_html(get_option('replacementText', '***')), $content);
    }

    function ourMenu()
    {
        $mainPageHook = add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), 'data:image/svg+xml;base64, PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+Cg==', 100);
        // add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), plugin_dir_url(__FILE__) . 'custom.svg', 100);  // To give custom svg icon as a path
        add_submenu_page('ourwordfilter', 'Words To Filter', 'Words List', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'));  // To change the text of main menu in submenu
        add_submenu_page('ourwordfilter', 'Word Filter Options', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionsSubPage'));
        add_action("load-{$mainPageHook}", array($this, 'mainpageassets'));
    }

    function mainpageassets()
    {
        wp_enqueue_style('filterAdminCss', plugin_dir_url(__FILE__) . 'style.css');
    }

    function handleForm()
    {

        if (wp_verify_nonce(isset($_POST['ourNonce']) ? $_POST['ourNonce'] : null, 'saveFilterWords') and current_user_can('manage_options')) {
            update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter'])); ?>

            <div class="updated">
                <p>Your filtered words were saved.</p>
            </div>
        <?php } else { ?>

            <div class="error">
                <p>Sorry, you do not have permission to perform that action</p>
            </div>

        <?php }
    }

    function ourSettings(){
        add_settings_section('replacement-text-section', null, null, 'word-filter-options');
        register_setting('replacementFields', 'replacementText');
        add_settings_field('replacement-text', 'Filtered Text', array($this, 'replacementFieldHtml'), 'word-filter-options', 'replacement-text-section');
    }


    function replacementFieldHtml(){ ?>

    <input type="text" name="replacementText" id="replacementText" value="<?php echo esc_attr(get_option('replacementText', '***')); ?>">

    <?php }


    function wordFilterPage()
    {
        ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <?php
            if (isset($_POST['justsubmitted']) == true) $this->handleForm();
            ?>
            <form method="post">
                <input type="hidden" name="justsubmitted" value="true">
                <?php wp_nonce_field('saveFilterWords', 'ourNonce') ?>
                <label for="wfp_words_to_filter">
                    <p>Enter a <strong>comma-seperated</strong> list of words to filter from your site's content.</p>
                </label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter" placeholder="bad, horrible, worst"><?php echo esc_textarea(get_option('plugin_words_to_filter')); ?></textarea>
                </div>
                <input type="submit" value="Save Changes" name="submit" id="submit" class="button button-primary">
            </form>
        </div>
    <?php }

    function optionsSubPage()
    {
    ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <form action="options.php" method="post">
                <?php 
                settings_errors();
                settings_fields('replacementFields');  // The group name i.e the first argument in the register_setting()
                do_settings_sections('word-filter-options');
                submit_button();
                ?>
            </form>
        </div>
<?php }
}

$ourWordFilterPlugin = new OurWordFilterPlugin();
