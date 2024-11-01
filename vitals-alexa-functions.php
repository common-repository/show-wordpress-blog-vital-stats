<?php
include_once('class_vitals_http.php');

function vitals_get_alexa_method_2($domain){
    $remote_url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url='.trim($domain);
    $search_for = '<POPULARITY URL';
    if ($handle = @fopen($remote_url, "r")) {
        while (!feof($handle)) {
            $part .= fread($handle, 100);
            $pos = strpos($part, $search_for);
            if ($pos === false)
            continue;
            else
            break;
        }
        $part .= fread($handle, 100);
        fclose($handle);
    }
    $str = explode($search_for, $part);
    $str = array_shift(explode('"/>', $str[1]));
    $str = explode('TEXT="', $str);
 
    return number_format($str[1]);
}

function vitals_get_alexa_rank($url)
{
    $h1 = new vitals_http();

    if ($url == '')
    {
        $url = get_bloginfo('home');
    }
    $blog_url = urlencode($url);

    $url1 = "http://xml.alexa.com/data?cli=10&dat=nsa&url=".$blog_url;
    $rankurl = "http://www.alexa.com/data/details/main?q=&amp;url=".$blog_url;
    if (!$h1->fetch($url1, "daily", "alexa"))
    {
        echo "Error with the request!";
        echo $h1->log;
        return;
    }
    $data=$h1->body;
	preg_match ('/<POPULARITY URL=([^`]*?)\/>/', $data, $matches);
	preg_match ('/" TEXT="([^`]*?)"/', $matches[1], $rank);
	$rank = number_format($rank[1]);

	return $rank;
}

function vitals_get_alexa_rank_image($rank, $img_dir)
{
    $alexa_rank = $rank;
    $alexa_image_dir = $img_dir;
    if ($alexa_rank < 1000)
    {
        return $alexa_image_dir.'alexa-cat8.gif';
    }
    if ($alexa_rank < 20000)
    {
        return $alexa_image_dir.'alexa-cat7.gif';
    }
    if ($alexa_rank < 50000)
    {
        return $alexa_image_dir.'alexa-cat6.gif';
    }
    if ($alexa_rank < 100000)
    {
        return $alexa_image_dir.'alexa-cat5.gif';
    }
    if ($alexa_rank < 200000)
    {
        return $alexa_image_dir.'alexa-cat4.gif';
    }
    if ($alexa_rank < 500000)
    {
        return $alexa_image_dir.'alexa-cat3.gif';
    }
    if ($alexa_rank < 1000000)
    {
        return $alexa_image_dir.'alexa-cat2.gif';
    }
    if ($alexa_rank < 2000000)
    {
        return $alexa_image_dir.'alexa-cat1.gif';
    }

    return $alexa_image_dir.'alexa-cat0.gif';
}

// Testing


?>