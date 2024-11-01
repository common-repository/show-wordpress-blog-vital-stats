<?php
/*
Plugin Name: WP Blog Vitals
Description: Simple WordPress Stats Plugin to show Google Page Rank, Alexa rank, Twitter follower count, Technorati rank, Number of Posts, Comments etc anywhere on your blog to show off to your visitors.
Version: 1.8
Plugin URI: http://www.tipsandtricks-hq.com/wordpress-blog-vitals-plugin-show-your-wordpress-blogs-vital-statistics-to-your-visitors-1322
Author: Tips and Tricks HQ
Author URI: http://www.tipsandtricks-hq.com/
License: GPL2
*/

define('WP_VITALS_VERSION', "1.8");
define('WP_VITALS_FOLDER', dirname(plugin_basename(__FILE__)));
define('WP_VITALS_URL', plugins_url('',__FILE__));

define('WP_VITALS_PR_IMG_DIR', WP_VITALS_URL.'/images/pr-images/');
define('WP_VITALS_ALEXA_IMG_DIR', WP_VITALS_URL.'/images/alexa-images/');
define('WP_VITALS_TECHNORATI_IMG_DIR', WP_VITALS_URL.'/images/technorati-images/');
define('WP_VITALS_BLOG_DATA_IMG_DIR', WP_VITALS_URL.'/images/blog-data-images/');
//$siteurl = get_bloginfo('wpurl') ;
define('WP_VITALS_SITE_URL', site_url());

// Includes
include_once('vitals-settings-page.php');
include_once('vitals-pr-functions.php');
include_once('vitals-alexa-functions.php');
include_once('vitals-misc-functions.php');

function wp_blog_vitals_handler($atts)
{
	return print_wp_blog_vitals();
}

function filter_wp_blog_vitals_tag($content)
{
    if (strpos($content, "<!--wp_blog_vitals-->") !== FALSE)
    {
        $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        $content = str_replace('<!--wp_blog_vitals-->', print_wp_blog_vitals(), $content);
    }
    return $content;
}

function print_wp_blog_vitals()
{
	$debug_marker = "<!-- WP Blog Vitals Plugin v" . WP_VITALS_VERSION . " - http://www.tipsandtricks-hq.com/?p=1322 -->";
	$output .= "\n${debug_marker}\n";
	if (get_option('wp_vitals_show_doctor_image') == 1){
		$output .= '<a href="http://www.tipsandtricks-hq.com/?p=1322"><img src="'.WP_VITALS_URL.'/images/blog_vitals_doctor_48.jpg" title="Blog Vitals Plugin" alt="Blog Stats" /></a><br />';
	}

    if (get_option('wp_vitals_show_pagerank') == 1)
    {
	    if (!($pr_value_image = get_vitals_cache('pr_value_image')))
	    {
	        $pr_value_image = update_vitals_cache('pr_value_image', vitals_check_pagerank(WP_VITALS_SITE_URL, WP_VITALS_PR_IMG_DIR));
	    }
		//$output .= '<img src="'.$pr_value_image.'" title="Google PageRank" alt="Google PageRank" />';
	    $output .= '<div style="background-image:url('.$pr_value_image.');width:90px;height:24px;" >';
	    $output .= '</div>';
    }

    if (get_option('wp_vitals_show_alexa_rank') == 1)
    {
	    $alexa_rank = vitals_get_cached_alexa_rank(WP_VITALS_SITE_URL);
	    $rank_int_value = str_replace(',', '', $alexa_rank);
	    $alexa_rank_category_image = vitals_get_alexa_rank_image($rank_int_value, WP_VITALS_ALEXA_IMG_DIR);
	
	    $output .= '<div style="background-image:url('.$alexa_rank_category_image.');width:90px;height:24px;" >';
	    $output .= '<div id="alexa_chicklet_text">'.$alexa_rank.'</div>';
	    $output .= '</div>';	   
    }

    if (get_option('wp_vitals_show_technorati_rank') == 1)
    {
	    $technorati_rank = vitals_get_cached_technorati_rank(WP_VITALS_SITE_URL);
	    $technorati_image = WP_VITALS_TECHNORATI_IMG_DIR."technorati.png";

	    $output .= '<div style="background-image:url('.$technorati_image.');width:90px;height:24px;" >';
	    $output .= '<div id="technorati_chicklet_text">'.$technorati_rank.'</div>';
	    $output .= '</div>';	  	    	    
    }
     
    if (get_option('wp_vitals_show_posts_count') == 1)
    {     
	    $posts_image = WP_VITALS_BLOG_DATA_IMG_DIR."posts.png";
	    $total_posts = vitals_get_post_count(); 
	    
	    $output .= '<div style="background-image:url('.$posts_image.');width:90px;height:24px;" >';
	    $output .= '<div id="posts_chicklet_text">'.$total_posts.'</div>';
	    $output .= '</div>';		    	    
    }

    if (get_option('wp_vitals_show_comments_count') == 1)
    {                      
	    $comments_image = WP_VITALS_BLOG_DATA_IMG_DIR."comments.png";
	    $total_comments = vitals_get_cached_comments_count();
	    
	    $output .= '<div style="background-image:url('.$comments_image.');width:90px;height:24px;" >';
	    $output .= '<div id="comments_chicklet_text">'.$total_comments.'</div>';
	    $output .= '</div>';	
    }
           
    if (get_option('wp_vitals_show_pingbacks_count') == 1)
    {                    
	    $pingbacks_image = WP_VITALS_BLOG_DATA_IMG_DIR."pingbacks.png"; 
	    $total_pingbacks = vitals_get_cached_pingbacks_count();
	    
	    $output .= '<div style="background-image:url('.$pingbacks_image.');width:90px;height:24px;" >';
	    $output .= '<div id="pingbacks_chicklet_text">'.$total_pingbacks.'</div>';
	    $output .= '</div>';	           
    }        

    if (get_option('wp_vitals_show_twitter_count') == 1)
    {
    	$twitter_username = get_option('wp_vitals_twitter_username');
    	if(!empty($twitter_username)){
	    	$twitter_count = vitals_get_cached_twitter_count($twitter_username);      
		    $twitter_image = WP_VITALS_URL."/images/twitter/twitter-chicklet-bg.png";
		    $output .= '<div style="background-image:url('.$twitter_image.');width:90px;height:24px;" >';
		    $output .= '<div id="twitter_chicklet_text">'.$twitter_count.'</div>';
		    $output .= '</div>';
    	}
    	else{
    		$output .= "<p>Twitter Username is missing in the settings menu.</p>";
    	}
    }

    if (get_option('wp_vitals_show_google_plus_one_count') == 1)
    {
    	$gplus_api_key = get_option('wp_vitals_google_plus_api_key');
    	$gplus_page_id = get_option('wp_vitals_google_plus_page_id');
    	
    	if(!empty($gplus_api_key)){
	    	$gplus_one_count = vitals_get_cached_plus_one_count($gplus_api_key, $gplus_page_id);  
		    $gplus_one_image = WP_VITALS_URL."/images/gplus/gplus-one-chicklet-bg.png";
		    $output .= '<div style="background-image:url('.$gplus_one_image.');width:90px;height:24px;" >';
		    $output .= '<div id="gplus_one_chicklet_text">'.$gplus_one_count.'</div>';
		    $output .= '</div>';
    	}
    	else{
    		$output .= "<p>Google plus API Key is missing in the settings menu.</p>";
    	}
    }
        
    if (get_option('wp_vitals_show_feedburner_count') == 1)
    {
	    $fb_fc_code = get_option('wp_vitals_fb_fc_html');
	    $fb_fc_code = html_entity_decode($fb_fc_code, ENT_COMPAT);
	    $fb_fc_code = str_replace ('<p>', '', $fb_fc_code);
	    $fb_fc_code = str_replace ('</p>', '', $fb_fc_code);
	    if(!empty($fb_fc_code))
	    {    	
		    $output .= '<div style="width:90px;height:24px;">';
		    $output .=  $fb_fc_code;
		    $output .= '</div>';
	    }
	    else
	    {
	    	$output .= "<p>Feed count HTML code missing in the settings menu.</p>";
	    }
    }

	return $output;
}

function vitals_get_cached_post_count()
{
    if (!($cached_post_count = get_vitals_cache('vitals_post_count')))
    {
        $cached_post_count = update_vitals_cache('vitals_post_count', vitals_get_post_count());
    }
    return $cached_post_count;
}

function vitals_get_cached_comments_count()
{
    if (!($cached_comments_count = get_vitals_cache('vitals_comments_count')))
    {
        $cached_comments_count = update_vitals_cache('vitals_comments_count', vitals_get_comment_count());
    }
    return $cached_comments_count;
}

function vitals_get_cached_pingbacks_count()
{
    if (!($cached_pingbacks_count = get_vitals_cache('vitals_pingbacks_count')))
    {
        $cached_pingbacks_count = update_vitals_cache('vitals_pingbacks_count', vitals_get_trackback_count());
    }
    return $cached_pingbacks_count;
}

function vitals_get_cached_technorati_rank($url)
{
    if (!($cached_technorati_rank = get_vitals_cache('vitals_technorati_rank')))
    {
        $cached_technorati_rank = update_vitals_cache('vitals_technorati_rank', vitals_get_technorati_rank($url));
    }
    return $cached_technorati_rank;
}

function vitals_get_cached_alexa_rank($url)
{
    if (!($cached_alexa_rank = get_vitals_cache('alexa_rank')))
    {
        $cached_alexa_rank = update_vitals_cache('alexa_rank', vitals_get_alexa_method_2($url));
    }
    return $cached_alexa_rank;
}

function vitals_get_cached_twitter_count($account_name)
{
    if (!($cached_twitter_count = get_vitals_cache('vitals_twitter_count')))
    {
    	$twitter_count = vitals_get_twitter_count($account_name);
        $cached_twitter_count = update_vitals_cache('vitals_twitter_count', (string)$twitter_count);
    }
    return $cached_twitter_count;
}

function vitals_get_cached_plus_one_count($gplus_api_key, $gplus_page_id)
{
    if (!($cached_gplus_one_count = get_vitals_cache('vitals_gplus_one_count')))
    {
    	$gplus_one_count = vitals_get_gplus_one_count($gplus_api_key, $gplus_page_id);
        $cached_gplus_one_count = update_vitals_cache('vitals_gplus_one_count', (string)$gplus_one_count);
    }
    return $cached_gplus_one_count;
}

/****** Caching stuff *********/
function get_vitals_cache($cacheID) 
{
    $vitals_global_cache_timeout = (int)get_option('wp_vitals_cache_time'); // This value is in seconds

    $cacheTimeout = get_option('vitals_cache_time_'.$cacheID);
    if ($cacheTimeout && (time() - $cacheTimeout < $vitals_global_cache_timeout))
    {
        return get_option('vitals_cache_data_'.$cacheID);
    }
    else 
    {
        return false;
    }    
}

function update_vitals_cache($cacheID, $value) 
{
    update_option('vitals_cache_time_'.$cacheID, time());
    update_option('vitals_cache_data_'.$cacheID, $value);
    return $value;
}
/******* End of Caching stuff ***********/

// Displays Wordpress Blog Vitals Options menu
function wp_blog_vitals_add_option_page() {
    if (function_exists('add_options_page')) {
        add_options_page('WP Blog Vitals', 'WP Blog Vitals', 8, __FILE__, 'wp_blog_vitals_options_page');
    }
}

function wp_blog_vitals_css()
{
    echo '<link type="text/css" rel="stylesheet" href="'.WP_VITALS_URL.'/wp_blog_vitals_style.css" />'."\n";
}

function show_wp_blog_vitals_widget($args)
{
	extract($args);
	
	$wp_vitals_widget_title = get_option('wp_vitals_widget_title');
	echo $before_widget;
	echo $before_title . $wp_vitals_widget_title . $after_title;
    echo print_wp_blog_vitals();
    echo $after_widget;
}

function wp_blog_vitals_widget_control()
{
    ?>
    <p>
    <? _e("Set the Plugin Settings from the Settings menu"); ?>
    </p>
    <?php

}
function widget_wp_blog_vitals_init()
{
    $widget_options = array('classname' => 'widget_wp_blog_vitals', 'description' => __( "Display WP Blog Vitals.") );
    wp_register_sidebar_widget('wp_blog_vitals_widgets', __('WP Blog Vitals'), 'show_wp_blog_vitals_widget', $widget_options);
    wp_register_widget_control('wp_blog_vitals_widgets', __('WP Blog Vitals'), 'wp_blog_vitals_widget_control' );
}

function wp_vitals_add_settings_link($links, $file) 
{
	if ($file == plugin_basename(__FILE__)){
		$settings_link = '<a href="options-general.php?page=wp-blog-vitals/wp-blog-stats.php">Settings</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'wp_vitals_add_settings_link', 10, 2 );


add_filter('the_content', 'filter_wp_blog_vitals_tag');

add_action('init', 'widget_wp_blog_vitals_init');

// Insert the wp_blog_vitals_add_option_page in the 'admin_menu'
add_action('admin_menu', 'wp_blog_vitals_add_option_page');

add_action('wp_head', 'wp_blog_vitals_css');
add_shortcode('wp_blog_vitals', 'wp_blog_vitals_handler');
?>