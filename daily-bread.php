<?php
/**
 * @package Daily Bread
 * @version 0.0.1
 */
/*
Plugin Name: Daily Bread
Plugin URI: https://7pixel.cl
Description: This plugin displays a random proverb on the admin screen.
Author: Marco Alvarado
Version: 0.0.1
Author URI: https://7pixel.cl
*/

function daily_bread_get_verse() {
    // Load proverbs from a JSON file
    $proverbs_json = file_get_contents(plugin_dir_path(__FILE__) . 'proverbs.json');
    
    if ($proverbs_json === false) {
        // Handle error when file is not found or not accessible
        error_log('Failed to load proverbs.json');
        return '';
    }

    $proverbs = json_decode($proverbs_json, true);

    if ($proverbs === null) {
        // Handle error when JSON is not valid
        error_log('Failed to parse proverbs.json');
        return '';
    }

    // And then randomly choose a line
    return $proverbs[mt_rand(0, count($proverbs) - 1)];
}

function daily_bread() {
    $chosen = daily_bread_get_verse();
    $lang   = '';
    if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
        $lang = ' lang="en"';
    }

    printf(
        '<p id="daily-verse "><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
        __( 'Quote from proverbs, by Marco Alvarado:', 'daily-verse' ),
        $lang,
        $chosen
    );
}

add_action( 'admin_notices', 'daily_bread' );

function daily_verse_css() {
    // Enqueue a CSS file
    wp_enqueue_style('daily-verse', plugins_url('daily-verse.css', __FILE__));
}

add_action( 'admin_enqueue_scripts', 'daily_verse_css' );