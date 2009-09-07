#!/usr/bin/php -q
<?php
require_once 'Excel/reader.php'; 
error_reporting(E_ALL ^ E_NOTICE); 
mysql_connect('localhost','root','vibespin');
mysql_select_db('test_db');
$data = new Spreadsheet_Excel_Reader(); 
$data->setOutputEncoding('ASCII'); 
//$agencies = array('dhs', 'dod', 'doe', 'doj', 'dos', 'dot', 'fcc', 'nasa', 'nsf', 'sba', 'ssa', 'usaid', 'usda', 'va');

$params = $_SERVER["argv"];
$agency = $params[1];
$files = glob($agency.'/weekly*.xls');
$f=0;
foreach($files as $file) {
    $_filename = str_replace($agency.'/','',$file);
    $_str_replace = array('weeklyreport_WR', '.xls', strtoupper($agency));
    $datetime = str_replace($_str_replace,'',$_filename);


    $data->read("/home/cthorn/src/recovery_gov/".$file); 
    $sheet1 = $data->sheets[0]['cells'];
    //print_r($sheet1);

    //get field names
    //print_r($sheet1[6]);
    //print_r($keys);
   
    if($agency == 'dod') {

        $keys = array_keys($sheet1[12]);
        $agency_name = strtolower($sheet1[8][3]);
        $week_end_date = $sheet1[9][3];
        $submitter_name = $sheet1[10][3];
        //lame way to clean up whitespace
        list($person, $phone) = explode('              ', trim($sheet1[11][3]));
        $submitter_contact_info = $person . ' ' .$phone;
        $text_fields = array(7,8,9,10,11,12);
        $table_rows = $sheet1[12];
        unset($sheet1[1]);
        unset($sheet1[7]);
        unset($sheet1[8]);
        unset($sheet1[9]);
        unset($sheet1[10]);
        unset($sheet1[11]);
        unset($sheet1[12]);

    }
    else {
        $keys = array_keys($sheet1[6]);
        $agency_name = strtolower($sheet1[2][3]);
        $week_end_date = $sheet1[3][3];
        $submitter_name = $sheet1[4][3];
        //lame way to clean up whitespace
        $submitter_contact_info = trim($sheet1[5][3]);
        $text_fields = array(2,3,4,5,6,7);
        $table_rows = $sheet1[6];
        unset($sheet1[1]);
        unset($sheet1[2]);
        unset($sheet1[3]);
        unset($sheet1[4]);
        unset($sheet1[6]);
    }
    
    //print_r($table_rows);
    //unset the headings
    unset($sheet1[6]);
    $str_replace_arr = array('Program Source/ Treasury Account Symbol:',
                             'Program Source/Treasury Account Symbol:',
                             'Program Source/Treasury Account Symbol;',
                             '-','(',')');
        //print_r($item);

    $i=0;
    foreach($sheet1 as $item) {

        if(sizeof($item) >= 6) {
        //print_r($item);
            foreach($keys as $key) {
                if(!isset($item[$key])) {
                    $item[$key] = 0;
                }
                $tbl_header = trim(str_replace($str_replace_arr, '', $table_rows[$key]));
                $tbl_header = strtolower($tbl_header);
                $tbl_header = str_replace(' ','_',$tbl_header);
                //echo $tbl_header . ' -- ' .$item[$key] ."\n";
                $xlsdata[$f][$i]['week_end_date'] = $datetime;
                $xlsdata[$f][$i]['submitter_name'] = trim($submitter_name);
                $xlsdata[$f][$i]['submitter_contact_info'] = trim($submitter_contact_info);
                $xlsdata[$f][$i][$tbl_header] = $item[$key];
            }
            //print_r($item);
        }
        $i++;
    }
    $f++;
}

//print_r($xlsdata);

//
foreach($xlsdata as $_entry) {
    //print_r($_entry);

    $i=0;
    foreach($_entry as $entry) {
        //print_r($entry);
        $sql = "INSERT INTO recovery_weekly_reports (id, 
                                                     week_end_date, 
                                                     submitter_name,
                                                     submitter_contact_info,
                                                     agency_code,
                                                     account_code,
                                                     subaccount_code_optional,
                                                     total_appropriation,
                                                     total_obligations,
                                                     total_disbursements)
                VALUES ('',
                        '".$entry['week_end_date']."',
                        '".$entry['submitter_name']."',
                        '".$entry['submitter_contact_info']."',
                        '".$entry['agency_code']."',
                        '".$entry['account_code']."',
                        '".$entry['subaccount_code_optional']."',
                        '".$entry['total_appropriation']."',
                        '".$entry['total_obligations']."',
                        '".$entry['total_disbursements']."')";

            //echo str_replace("\n", ' ', $sql) ."\n";
            mysql_query($sql);
        $i++;
    }
}

