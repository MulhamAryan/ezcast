<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    require_once __DIR__."/../config.inc";
    require_once __DIR__."/../lib_ezmam.php";
    //require_once __DIR__."/../../commons/lib_sql_management.php";


    $input = array_merge($_POST, $_GET);
    $ApiAction = isset($input['ApiAction']) ? $input['ApiAction'] : NULL;
    $ApiClient = isset($input['ApiClient']) ? $input['ApiClient'] : NULL;
    $ApiUserid = isset($input['ApiUserid']) ? $input['ApiUserid'] : NULL;
    $ApiSecret = isset($input['ApiSecret']) ? $input['ApiSecret'] : NULL;
    $ApiNbrElm = isset($input['ApiNbrElm']) ? (int)$input['ApiNbrElm'] : 1;

    $ApiJsonFile = file_get_contents("$basedir/commons/api.credentials.json");
    $ApiJson = json_decode($ApiJsonFile, true);
    $ApiLogin = 0;

    foreach ($ApiJson as $ApiItem) {
        if($ApiItem["client"] == $ApiClient){
            if($ApiItem["credentials"]["username"] == $ApiUserid && $ApiItem["credentials"]["secretkey"] == $ApiSecret){
                $ApiLogin = 1;
            }
            else{
                $ApiLogin = 0;
                $ApiMsg = "credentials_error";
            }
            break;
        }
        else{
            $ApiLogin = 0;
            $ApiMsg = "client_not_found";
        }
    }

    if($ApiLogin == 0)
        echo json_encode($ApiMsg);

    else{
        switch ($ApiAction) {
            case 'courses_list':
                require_once('api/courses_list.php');
                break;
            default:
                echo json_encode('action_not_found');
                break;
        }
    }

?>