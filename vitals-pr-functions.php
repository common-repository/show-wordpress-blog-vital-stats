<?php

function vitals_getpagerank($url) 
{
    $fp = fsockopen("toolbarqueries.google.com", 80, $errno, $errstr, 30);
    if (!$fp) 
    {
        return '';
    } 
    else 
    {
        $out = "GET /tbr?client=navclient-auto&ch=".vitals_CheckHash(vitals_HashURL($url))."&features=Rank&q=info:".$url."&num=100&filter=0 HTTP/1.1\r\n";
        $out .= "Host: toolbarqueries.google.com\r\n";
        $out .= "User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)\r\n";
        $out .= "Connection: Close\r\n\r\n";
       
        fwrite($fp, $out);
   
        while (!feof($fp)) 
        {
	        $data = fgets($fp, 128);
	        $pos = strpos($data, "Rank_");
	        if($pos === false){} 
            else
            {
		        $pagerank = substr($data, $pos + 9);

            }
        }
        fclose($fp);
        return $pagerank;
    }
}

/*
 * convert a string to a 32-bit integer
 */
function vitals_StrToNum($Str, $Check, $Magic)
{
    $Int32Unit = 4294967296;  // 2^32

    $length = strlen($Str);
    for ($i = 0; $i < $length; $i++) 
    {
        $Check *= $Magic; 	
        //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
        //  the result of converting to integer is undefined
        //  refer to http://www.php.net/manual/en/language.types.integer.php
        if ($Check >= $Int32Unit) 
        {
            $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
            //if the check less than -2^31
            $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i}); 
    }
    return $Check;
}

/* 
 * Genearate a hash for a url
 */
function vitals_HashURL($String)
{
    $Check1 = vitals_StrToNum($String, 0x1505, 0x21);
    $Check2 = vitals_StrToNum($String, 0, 0x1003F);

    $Check1 >>= 2; 	
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);	
	
    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
	
    return ($T1 | $T2);
}

/* 
 * genearate a checksum for the hash string
 */
function vitals_CheckHash($Hashnum)
{
    $CheckByte = 0;
    $Flag = 0;

    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);
	
    for ($i = $length - 1;  $i >= 0;  $i --) {
        $Re = $HashStr{$i};
        if (1 === ($Flag % 2)) {              
            $Re += $Re;     
            $Re = (int)($Re / 10) + ($Re % 10);
        }
        $CheckByte += $Re;
        $Flag ++;	
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte) {
        $CheckByte = 10 - $CheckByte;
        if (1 === ($Flag % 2) ) {
            if (1 === ($CheckByte % 2)) {
                $CheckByte += 9;
            }
            $CheckByte >>= 1;
        }
    }

    return '7'.$CheckByte.$HashStr;
}

function vitals_check_pagerank($url, $img_dir)
{
    $pr_image_dir = $img_dir;
	$pr = vitals_getpagerank($url);
	switch(trim($pr))
	{
		case ''     :	return $pr_image_dir.'pr0.gif';
						break;
		case '0'	:   return $pr_image_dir.'pr0.gif';
						break;
		case '1'	: 	return $pr_image_dir.'pr1.gif';
						break;
		case '2'	: 	return $pr_image_dir.'pr2.gif';
						break;
		case '3'	: 	return $pr_image_dir.'pr3.gif';
						break;
		case '4'	: 	return $pr_image_dir.'pr4.gif';
						break;
		case '5'	: 	return $pr_image_dir.'pr5.gif';
						break;
		case '6'	: 	return $pr_image_dir.'pr6.gif';
						break;
		case '7'	: 	return $pr_image_dir.'pr7.gif';
						break;
		case '8'	: 	return $pr_image_dir.'pr8.gif';
						break;
		case '9'	: 	return $pr_image_dir.'pr9.gif';
						break;
		case '10'	: 	return $pr_image_dir.'pr10.gif';
						break;
						
		default: 
					return 'null';
					break;				
		
	}
}
?>