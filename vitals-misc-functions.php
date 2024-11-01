<?php
//include_once('../../../wp-load.php');

function load_snoopy_for_vitals() 
{
    if (!class_exists('Snoopy')) 
    {
        require_once ABSPATH . WPINC . '/class-snoopy.php';
    }
}

function vitals_get_post_count() 
{
	global $wpdb;		
	return (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->posts . ' WHERE post_status = "publish" AND post_type = "post"');
}

function vitals_get_comment_count() 
{
	global $wpdb;
	return (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->comments . ' WHERE comment_approved = "1"');
}

function vitals_get_trackback_count() 
{
	global $wpdb;
	return (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->comments . ' WHERE comment_type = "pingback"');
}

function vitals_get_technorati_rank($url)
{
    load_snoopy_for_vitals();
    //$this->snoopy = new Snoopy();
    $snoopy = new Snoopy();
    if ($snoopy->fetch('http://www.technorati.com/blogs/' . urlencode(str_replace(array('http://', 'www.'), '', $url)) . '?reactions'))
    {
        if (preg_match('/<div class="rank">Rank: ([0-9\,]+)/si', $snoopy->results, $match))
        {
            $rank = (int) str_replace(',', '', $match[1]);
        }
        return $rank;
    }
}

function vitals_get_twitter_count($account_name)
{
	$url = "http://twitter.com/users/show/".$account_name;
	$response = file_get_contents ( $url );
	$t_profile = new SimpleXMLElement ( $response );
	$count = $t_profile->followers_count;
  	return $count;
  	
  	//Old method
	//$xml=file_get_contents('http://api.twitter.com/users/'.$account_name);
	//if (preg_match('/followers_count>(.*)</',$xml,$match)!=0) {
	//$tw['count'] = $match[1];
	//}
	//return $tw['count'];	
}

function vitals_get_google_plus_data($Google_API_key, $Google_Page_Id, $use_curl=false)
{
	$json_url = 'https://www.googleapis.com/plus/v1/people/'.$Google_Page_Id.'?key='.$Google_API_key.'';
	
    if($use_curl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $json_url);
        $json_data = curl_exec($ch);
        curl_close($ch);
        return json_decode($json_data);
    }
    else
    {
        $json_data = file_get_contents($json_url);
        return json_decode($json_data);
    }
}

function vitals_get_gplus_one_count($gplus_api_key, $gplus_page_id)
{
	$count = 0;
	$google_data = vitals_get_google_plus_data($gplus_api_key, $gplus_page_id);
	$count = $google_data->plusOneCount;//circledByCount;
	return $count;
}
