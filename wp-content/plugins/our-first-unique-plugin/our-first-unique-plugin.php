<?php

/*

Plugin Name: Our Test Plugin
Description: Testing.....
Version: 1.0
Author: Jalaj
Author URL: https://google.com/
Text Domain: wcpdomain
Domain Path: /languages

*/

class WordCountAndTimePlugin
{

    function __construct()
    {
        add_action('admin_init', array($this, 'settings'));
        add_action('admin_menu', array($this, 'adminPage'));
        add_filter('the_content', array($this, 'ifWrap'));
        add_action('init', array($this, 'languages'));
    }

    function languages(){
        load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)). '/languages');
    }

    function ifWrap($content){
        if ((is_main_query() AND is_single() AND get_post_type() == 'post') AND (get_option('wcp_word_count', '1') OR get_option('wcp_char_count', '1') OR get_option('wcp_read_time', '1'))){
            return $this->createHtml($content);
        }
        return $content;
    }

    function createHtml($content){
        $html = '<h3>'.esc_html(get_option('wcp_headline_text')).'</h3>';

        // Get word count once because both wordcount and read time will need it.

        if (get_option('wcp_word_count', "1") OR get_option('wcp_read_time', "1")){

            $wordCount = str_word_count(strip_tags($content));

        }

        if (get_option('wcp_word_count', "1")){
            $html .= esc_html__('This post has', 'wcpdomain'). ' ' . $wordCount . ' ' .esc_html__('words', 'wcpdomain'). '.<br>';
        }
        
        if (get_option('wcp_char_count', "1")){
            $html .=  esc_html__('This post has', 'wcpdomain'). ' ' . strlen(strip_tags($content)). ' ' .esc_html__('characters', 'wcpdomain'). '.<br>';
        }

        if (get_option('wcp_read_time', "1")){
            $timetoRead = round($wordCount/225, 0);
            $html .= 'This post will take '  . $timetoRead . ($timetoRead > 1 ? ' minutes' : ' minute') .' to read. <br><br>';
        }

        if (get_option('wcp_location', '0') == "0"){
            return $html.$content;
        }
        else{
            return $content.$html;
        }
    }


    function settings()
    {

        add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

        // For Location Field
        add_settings_field('wcp_location', 'Display Location', array($this, 'locationHtml'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_location', array(
            'sanitize_callback' => array($this, 'sanitizeLocation'),
            'default' => "1"
        ));

        // For Headline Field
        add_settings_field('wcp_headline_text', 'Headline Text', array($this, 'headlineHtml'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_headline_text', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => "Post Statistics"
        ));

        // For Word Count Checkbox
        add_settings_field('wcp_word_count', 'Word Count', array($this, 'wordCountHtml'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_word_count', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => "1"
        ));

        // For Character Count Checkbox
        add_settings_field('wcp_char_count', 'Character Count', array($this, 'charCountHtml'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_char_count', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => "1"
        ));

        // For Read Time Checkbox
        add_settings_field('wcp_read_time', 'Read Time', array($this, 'readTime'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_read_time', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => "1"
        ));
    }

    
    function sanitizeLocation($input){
        if ($input != '0' AND $input != "1"){
            add_settings_error('wcp_location', 'wcp_location_error', "Display Location must be either beginnng or end.");
            return get_option('wcp_location');
        }

        return $input;
    }
    
    
    function wordCountHtml()
    {
    ?>
        <input name="wcp_word_count" value="1" type="checkbox" <?php checked(get_option('wcp_word_count'), '1'); ?>>
    <?php }

    function charCountHtml()
    {
    ?>
        <input name="wcp_char_count" value="1" type="checkbox" <?php checked(get_option('wcp_char_count'), '1'); ?>>
    <?php }

    function readTime()
    {
    ?>
        <input name="wcp_read_time" value="1" type="checkbox" <?php checked(get_option('wcp_read_time'), '1'); ?>>
    <?php }


    function headlineHtml()
    {
    ?>
        <input name="wcp_headline_text" type="text" value="<?php echo esc_attr(get_option('wcp_headline_text')); ?>">
    <?php }


    function locationHtml()
    {
    ?>

        <select name="wcp_location">
            <option value="0" <?php selected(get_option('wcp_location'), "0"); ?>>Beginning of Post</option>
            <option value="1" <?php selected(get_option('wcp_location'), "1"); ?>>End of Post</option>
        </select>

    <?php }

    function adminPage()
    {
        add_options_page('Word Count Settings', __('Word Count', 'wcpdomain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHtml'));   /* $this refers to the current object */
    }

    function ourHtml()
    {
    ?>

        <div class="wrap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('wordcountplugin');
                do_settings_sections('word-count-settings-page');
                submit_button();
                ?>
            </form>
        </div>

<?php }
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();
