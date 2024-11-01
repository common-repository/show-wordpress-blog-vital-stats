<?php

function wp_blog_vitals_options_page() 
{    
	// Some default options
	add_option('wp_vitals_widget_title', 'Blog Vitals');
	add_option('wp_vitals_show_doctor_image', '1');  
	add_option('wp_vitals_show_pagerank', '1');
	add_option('wp_vitals_show_alexa_rank', '1');
	add_option('wp_vitals_cache_time', '86400');
    
    if (isset($_POST['info_update']))
    {
        echo '<div id="message" class="updated fade"><p><strong>';

        update_option('wp_vitals_widget_title', (string)$_POST["wp_vitals_widget_title"]);
        update_option('wp_vitals_cache_time', (string)$_POST["wp_vitals_cache_time"]);
        update_option('wp_vitals_show_doctor_image', ($_POST['wp_vitals_show_doctor_image']=='1') ? '1':'-1' );
		update_option('wp_vitals_show_pagerank', ($_POST['wp_vitals_show_pagerank']=='1') ? '1':'-1' );
		update_option('wp_vitals_show_alexa_rank', ($_POST['wp_vitals_show_alexa_rank']=='1') ? '1':'-1' );
		update_option('wp_vitals_show_technorati_rank', ($_POST['wp_vitals_show_technorati_rank']=='1') ? '1':'-1' );
		update_option('wp_vitals_show_posts_count', ($_POST['wp_vitals_show_posts_count']=='1') ? '1':'-1' );
		update_option('wp_vitals_show_comments_count', ($_POST['wp_vitals_show_comments_count']=='1') ? '1':'-1' );
		update_option('wp_vitals_show_pingbacks_count', ($_POST['wp_vitals_show_pingbacks_count']=='1') ? '1':'-1' );

		update_option('wp_vitals_show_twitter_count', ($_POST['wp_vitals_show_twitter_count']=='1') ? '1':'-1' );
		update_option('wp_vitals_twitter_username', trim($_POST["wp_vitals_twitter_username"]));

		update_option('wp_vitals_show_google_plus_one_count', ($_POST['wp_vitals_show_google_plus_one_count']=='1') ? '1':'-1' );
		update_option('wp_vitals_google_plus_api_key', trim($_POST["wp_vitals_google_plus_api_key"]));
		update_option('wp_vitals_google_plus_page_id', trim($_POST["wp_vitals_google_plus_page_id"]));

		update_option('wp_vitals_show_feedburner_count', ($_POST['wp_vitals_show_feedburner_count']=='1') ? '1':'-1' );
        $fb_html_code = htmlentities(stripslashes($_POST['wp_vitals_fb_fc_html']) , ENT_COMPAT);
        update_option('wp_vitals_fb_fc_html', $fb_html_code);
		
        echo 'Options Updated!';
        echo '</strong></p></div>';
    }

    ?>

    <div class=wrap>
    <div id="poststuff"><div id="post-body">

    <h2>WordPress Blog Vitals Settings v <?php echo WP_VITALS_VERSION; ?></h2>
    
	<div style="background: none repeat scroll 0 0 #FFF6D5;border: 1px solid #D1B655;color: #3F2502;margin: 10px 0;padding: 5px 5px 5px 10px;text-shadow: 1px 1px #FFFFFF;">
    <p>For information and updates, please visit the <a href="http://www.tipsandtricks-hq.com/?p=1322" target="_blank">blog vitals plugin page</a>
    </p>
    </div>

	<div class="postbox">
	<h3><label for="title">Usage</label></h3>
	<div class="inside">

    <p>There are three ways you can use this plugin on your site:</p>
    <ol>
    <li>Add the shortcode <strong>[wp_blog_vitals]</strong> to a post or page</li>
    <li>Call the function from a template file: <strong>&lt;?php echo print_wp_blog_vitals(); ?&gt;</strong></li>
    <li>Use the <strong>WP Blog Vitals</strong> Widget from the Widgets menu</li>
    </ol>

    </div></div>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <input type="hidden" name="info_update" id="info_update" value="true" />
    
	<div class="postbox">
	<h3><label for="title">WP Blog Vitals Plugin Options</label></h3>
	<div class="inside">
    <table class="form-table">

    <tr valign="top"><td width="25%" align="left">
    <strong>Blog Vitals Widget Title:</strong>
    </td><td align="left">
    <input name="wp_vitals_widget_title" type="text" size="35" value="<?php echo get_option('wp_vitals_widget_title'); ?>" />
    <br /><i>This is the title of the blog vital widget</i>
    </td></tr>

    <tr valign="top"><td width="25%" align="left">
    <strong>Show Blog Vital Image : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_doctor_image" type="checkbox"<?php if(get_option('wp_vitals_show_doctor_image')!='-1') echo ' checked="checked"'; ?> value="1" />
	<i> If unchecked the blog vital image won't be shown.</i>
	</td></tr>

    <tr valign="top"><td width="25%" align="left">
    <strong>Show Google PageRank : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_pagerank" type="checkbox"<?php if(get_option('wp_vitals_show_pagerank')!='-1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the Google Page Rank will be shown to your visitors.</i>
	</td></tr>

    <tr valign="top"><td width="25%" align="left">
    <strong>Show Alexa Rank : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_alexa_rank" type="checkbox"<?php if(get_option('wp_vitals_show_alexa_rank')!='-1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the Alexa Rank will be shown to your visitors.</i>
	</td></tr>

    <tr valign="top"><td width="25%" align="left">
    <strong>Show Technorati Rank : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_technorati_rank" type="checkbox"<?php if(get_option('wp_vitals_show_technorati_rank')!='-1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the Technorait Rank will be shown to your visitors. Your blog will need to be registered with Technorati to get ranking.</i>
	</td></tr>	

    <tr valign="top"><td width="25%" align=left>
    <strong>Show Total Posts Count : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_posts_count" type="checkbox"<?php if(get_option('wp_vitals_show_posts_count')!='-1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the total posts count of your blog will be shown to your visitors.</i>
	</td></tr>	

    <tr valign="top"><td width="25%" align="left">
    <strong>Show Total Comments Count : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_comments_count" type="checkbox"<?php if(get_option('wp_vitals_show_comments_count')!='-1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the total comments count of your blog will be shown to your visitors.</i>
	</td></tr>	
	
    <tr valign="top"><td width="25%" align="left">
    <strong>Show Total Pingbacks Count : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_pingbacks_count" type="checkbox"<?php if(get_option('wp_vitals_show_pingbacks_count')!='-1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the total pingbacks count of your blog will be shown to your visitors.</i>
	</td></tr>

    <tr valign="top"><td width="25%" align="left">
    <strong>Show Twitter Follower Count : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_twitter_count" type="checkbox"<?php if(get_option('wp_vitals_show_twitter_count')=='1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the total twitter follower count of your twitter account will be shown to your visitors.</i>
	<br />
	<strong>Twitter Username: </strong><input name="wp_vitals_twitter_username" type="text" size="40" value="<?php echo get_option('wp_vitals_twitter_username'); ?>" />
	<i> Enter your twitter account username.</i>	
	</td></tr>
	
    <tr valign="top"><td width="25%" align="left">
    <strong>Google Plus : </strong>
    </td><td align="left">
    <strong>Show Google Plus One Count : </strong>
    <input name="wp_vitals_show_google_plus_one_count" type="checkbox"<?php if(get_option('wp_vitals_show_google_plus_one_count')=='1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the total Google plus one count of your Google+ page will be shown to your visitors.</i>
	<br />
	<strong>Google Plus API Key: </strong><input name="wp_vitals_google_plus_api_key" type="text" size="60" value="<?php echo get_option('wp_vitals_google_plus_api_key'); ?>" />
	<i> Enter your Google+ API Key.</i>	
	<br />
	<strong>Google Plus Page ID: </strong><input name="wp_vitals_google_plus_page_id" type="text" size="60" value="<?php echo get_option('wp_vitals_google_plus_page_id'); ?>" />
	<i> Enter your Google+ Page ID.</i>
	</td></tr>

    <tr valign="top"><td width="25%" align="left">
    <strong>Show Feedburner Feed Count : </strong>
    </td><td align="left">
    <input name="wp_vitals_show_feedburner_count" type="checkbox"<?php if(get_option('wp_vitals_show_feedburner_count')!='-1') echo ' checked="checked"'; ?> value="1" />
	<i> If checked the feed count from Feedburner will be shown to your visitors.</i>
    <br /><strong>Feed Count HTML Code from Feedburner: </strong>
    <br /><textarea name="wp_vitals_fb_fc_html" rows="5" cols="60"><?php echo get_option('wp_vitals_fb_fc_html'); ?></textarea>	
	<br /><i>Copy the Feed Count HTML Code from your Feedburner account and paste it here.</i>
	</td></tr>
	
    <tr valign="top"><td width="25%" align="left">
    <strong>Blog Vitals Cache Time: </strong>
    </td><td align="left">
    <input name="wp_vitals_cache_time" type="text" size="10" value="<?php echo get_option('wp_vitals_cache_time'); ?>" />
    <br /><i>This is the amount of time the stats are saved in cache for quicker page load. This value is in seconds. Set it to one day (86400 seconds) as these stats don't update frequently.</i>
    </td></tr>
    					
    </table>
    </div></div>

    <div class="submit">
        <input type="submit" name="info_update" value="<?php _e('Update options'); ?> &raquo;" />
    </div>

    </form>
    </div></div>
    
	<div style="background: none repeat scroll 0 0 #DFF7DD;color: #324531;border: 1px solid #F1F9F0;margin: 10px 0;padding: 15px 10px 10px 10px;">
    <p>
    Follow us on <a href="https://plus.google.com/102469783420435518783" target="_blank">Google Plus</a> to receive plugin updates and news.
    </p>  
    </div>  
    
    </div><?php

}
