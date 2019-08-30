<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    require_once __DIR__ . "/../config.inc";
    require_once "$basedir/commons/common.inc";
    require_once "$basedir/ezmanager/lib_ezmam.php";

    if(file_exists("$basedir/commons/api.credentials.inc")) {
        require_once "$basedir/commons/api.credentials.inc";
        if ($enable_api_access == 1) {
            $input = array_merge($_POST, $_GET);

            $ApiAction = isset($input['ApiAction']) ? $input['ApiAction'] : NULL;
            $ApiClient = isset($input['ApiClient']) ? $input['ApiClient'] : NULL;
            $ApiUserid = isset($input['ApiUserid']) ? $input['ApiUserid'] : NULL;
            $ApiSecret = isset($input['ApiSecret']) ? $input['ApiSecret'] : NULL;
            $ApiNbrElm = isset($input['ApiNbrElm']) ? (int)$input['ApiNbrElm'] : 1;

            $ApiLogin = 0;

            if (isset($api[$ApiClient])) {
                $apiInfo = $api[$ApiClient];
                if ($apiInfo["ApiUser"] == $ApiUserid && $apiInfo["ApiSecret"] == $ApiSecret && $apiInfo["ApiStatus"] == 1) {
                    $ApiLogin = 1;
                } else {
                    $ApiLogin = 0;
                    $ApiMsg = "credentials_error";
                }
            } else {
                $ApiLogin = 0;
                $ApiMsg = "client_not_found";
            }

            if ($ApiLogin == 0)
                echo json_encode($ApiMsg);

            else {
                switch ($ApiAction) {
                    case 'courses_list':
                        require_once('api/courses_list.php');
                        break;
                    default:
                        echo json_encode('action_not_found');
                        break;
                }
            }
        } else echo json_encode('api_access_is_not_enabled');
    }
    else echo json_encode('api.credentials.inc_doesnt_exists');
?>