#!/usr/bin/php -q
<?php
$path = dirname(__FILE__);
$DS = '/';

$userAgent = 'Firefox (WindowsXP) – Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6';
$opts = array('http'=> array('method'=>"GET",'header'=>$userAgent));
$context = stream_context_create($opts);
$scraper_url = 'http://www.recovery.gov/';

$url = 'http://www.recovery.gov/?q=content/agency-weekly-reports';
$response = file_get_contents($url,false,$context);
preg_match_all('#<tr class=\'colgrey\'>(.+?)<\/tr>#is', $response, $tr1);
preg_match_all('#<tr class=\'colblue\'>(.+?)<\/tr>#is', $response, $tr2);
$tr = array_merge($tr1[1], $tr2[1]);

print_r($tr);


foreach($tr as $td_data) {
    //echo $td_data;
    preg_match_all('#<td[^>]*>(.+?)<\/td>#is', $td_data, $td);
    //print_r($td[1]);
    foreach($td[1] as $a_href) {
        preg_match('#<a href=\'(.*?)\'>(.*?)<\/a>#is', $a_href, $a);
        //print_r($a);
        if(preg_match('/summary/is',$a[1])) {
            //get directory name
            preg_match('#[^>]*\((.*?)\)#is', $a[2], $dept);
            $dirname = $path.$DS.strtolower($dept[1]);
            if(!is_dir($dirname)) {
                mkdir($dirname);
            }

            chdir($dirname);
            
            //echo strtolower($dept[1]) ."\n";
            $report_url =  $scraper_url.$a[1];
            $report_response = file_get_contents($report_url,false,$context);
            preg_match('#<table width="80%" border="0" align="center" cellpadding="0" cellspacing="2" class="datatable" title="Report History">(.+?)<\/table>#is', $report_response, $report);
            preg_match_all('#<tr[^>]*>(.+?)<\/tr>#is', $report[1], $tr);
            //print_r($report[1]);
            //print_r($tr[1]);
            foreach($tr[1] as $tr_str) { 
                preg_match_all('#<a href="(.*?)">(.*?)<\/a>#is', $tr_str, $a1);
                preg_match_all('#<a href=\'(.*?)\'>(.*?)<\/a>#is', $tr_str, $a2);
                $i=0;
                $a = array_merge($a1, $a2);

                foreach($a as $xls) {
                    //download the report xls file
                    if(preg_match('/sites\/default\/files/is', $xls[$i])) {
                        list($sites, $defaults, $files, $_filename) = explode('/',$xls[$i]);
                        //echo $_filename ."\n";
                        if(!file_exists($_filename)) {
                            $wget_cmd = 'wget http://www.recovery.gov/'.$xls[$i];
                            passthru($wget_cmd);
                        }
                    }
                    $i++;
                }
            }
            
        }
    }
}

