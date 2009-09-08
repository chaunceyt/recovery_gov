#!/usr/bin/php -q
<?php
require_once 'Excel/reader.php'; 
error_reporting(E_ALL ^ E_NOTICE); 
mysql_connect('localhost','root','vibespin');
mysql_select_db('test_db');


function parseWeeklyReport($agency)
{
    $data = new Spreadsheet_Excel_Reader(); 
    $data->setOutputEncoding('ASCII'); 
    $files = glob($agency.'/weekly*.xls');
    $f=0;
    foreach($files as $file) {
        $_filename = str_replace($agency.'/','',$file);
        $_str_replace = array('weeklyreport_WR', '.xls', strtoupper($agency));
        $datetime = str_replace($_str_replace,'',$_filename);
    

        $data->read("/home/cthorn/src/recovery_gov/".$file); 
        $sheet1 = $data->sheets[0]['cells'];
        $sheet2 = $data->sheets[1]['cells'];

        //get field names
        //print_r($sheet1[6]);
        //print_r($keys);
   
        $keys = array_keys($sheet1[6]);
        $agency_name = strtolower($sheet1[2][3]);

        $week_end_date = $sheet1[3][3];
        $submitter_name = $sheet1[4][3];
        $submitter_contact_info = trim($sheet1[5][3]);
        $text_fields = array(2,3,4,5,6,7);
        $table_rows = $sheet1[6];
        unset($sheet1[1]);
        unset($sheet1[2]);
        unset($sheet1[3]);
        unset($sheet1[4]);
        unset($sheet1[6]);

        $sql = "INSERT INTO reportfiles (id, agency, file_date, file_name, submitter_name, submitter_contact_info)
                VALUES ('',
                        '".$agency."',
                        '".$datetime."',
                        '".$file."',
                        '".$submitter_name."',
                        '".$submitter_contact_info."')";

        if(!mysql_query($sql)) {
            if(preg_match('/Duplicate entry/is',mysql_error())) {
                    continue;
            }
            else {
                die('ERROR: '. mysql_error());
            }
        }
        else {
            $last_insert_id = mysql_insert_id();
        }

        //print_r($table_rows);
        //unset the headings
        unset($sheet1[6]);
        $str_replace_arr = array('Program Source/ Treasury Account Symbol:',
                                 'Program Source/Treasury Account Symbol:',
                                 'Program Source/Treasury Account Symbol;',
                                 '-','(',')');
        //print_r($item);

        foreach($sheet2 as $action) {
            if(sizeof($action) >= 2) {
                $sql = "INSERT INTO majoractivities (id, reportfile_id, major_actions, planned_actions)
                        VALUES ('',
                                '".$last_insert_id."',
                                '".$action[2]."',
                                '".$action[3]."')";
                //echo $sql ."\n";
                mysql_query($sql);
                //print_r($action);
            
            }
        }

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
                    $xlsdata[$f][$i]['reportfile_id'] = $last_insert_id;
                    //$xlsdata[$f][$i]['week_end_date'] = $week_end_date;
                    //$xlsdata[$f][$i]['submitter_name'] = trim($submitter_name);
                    //$xlsdata[$f][$i]['submitter_contact_info'] = trim($submitter_contact_info);
                    $xlsdata[$f][$i][$tbl_header] = $item[$key];
                }
                //print_r($item);
            }
            $i++;
        }
        $f++;
    }
    return $xlsdata;
}


//
function saveWeekReport($xlsdata) 
{

    foreach($xlsdata as $_entry) {
        //print_r($_entry);

        $i=0;
        foreach($_entry as $entry) {
            //print_r($entry);
            $sql = "INSERT INTO weeklyreports (id,
                                               reportfile_id,
                                               week_end_date, 
                                               agency_code,
                                               account_code,
                                               subaccount_code_optional,
                                               total_appropriation,
                                               total_obligations,
                                               total_disbursements)
                    VALUES ('',
                            '".$entry['reportfile_id']."',
                            '".$entry['week_end_date']."',
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
}
$params = $_SERVER["argv"];
$agency = $params[1];
//$agency = 'dhs';
$xlsdata = parseWeeklyReport($agency);
saveWeekReport($xlsdata);
//print_r($xlsdata);

