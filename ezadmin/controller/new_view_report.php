<?php
require_once 'config.inc';
require_once('/usr/loca/ezcast/commons/lib_statistics.php');

function index($param = array()){
    global $input;
    if (!session_key_check($input['sesskey'])) {
        echo "Usage: Session key is not valid";
        die;
    }
    if((isset($input["start_date"]) && !empty($input["start_date"])) || (isset($input["end_date"]) && !empty($input["end_date"]))){
        $startdate = $input["start_date"];
        $end_date = $input["end_date"];
        $Stat = new GenerateStatistics($startdate,$end_date);
    }

    include template_getpath('div_main_header.php');
    $resultat = $Stat->startdate;
    $resultat .= var_dump($Stat->getRecords("total"));

    include template_getpath('div_new_view_report.php');
    include template_getpath('div_main_footer.php');
}

?>