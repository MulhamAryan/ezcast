<?php
function index($param = array())
{
    global $input;
    global $recorder_user;
    global $ezadmin_basedir;

    if (!session_key_check($input['sesskey'])) {
        echo "Usage: Session key is not valid";
        die;
    }

    //$room_ip = $input["ip"];
    $room_name = $input["room_name"];
    $result = db_classroom_get_info($room_name);
    if (!empty($result)) {
        $room_ID = $result['room_ID'];
        $masterIP = $result['IP'];
        $slaveIP = $result["IP_remote"];
        $baseDir = $result["base_dir"];
        $slave_exist = false;

        if (!empty($slaveIP)) {
            $slave_exist = true;
        }


        if (!isset($input["confirm_modification"])) {
            $_SESSION["new_config"] = '';
        }

        $urlMaster = 'http://' . $masterIP . '/ezrecorder/services/state.php';
        $chMaster = curl_init();
        curl_setopt($chMaster, CURLOPT_URL, $urlMaster);
        curl_setopt($chMaster, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chMaster, CURLOPT_TIMEOUT, 10); //timeout in seconds
        $outputMaster = curl_exec($chMaster);
        curl_close($chMaster);
        $outputMaster = json_decode($outputMaster, true);
        $statusMaster = $outputMaster["status_general"];

        if($slave_exist) {
            $urlSlave = 'http://' . $slaveIP . '/ezrecorder/services/state.php';
            $chSlave = curl_init();
            curl_setopt($chSlave, CURLOPT_URL, $urlSlave);
            curl_setopt($chSlave, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chSlave, CURLOPT_TIMEOUT, 10); //timeout in seconds
            $outputSlave = curl_exec($chSlave);
            curl_close($chSlave);
            $outputSlave = json_decode($outputSlave, true);
            $statusSlave = $outputSlave["status_general"];
        }

        if (
            $statusMaster == 'recording' || $statusMaster == 'paused' || $statusMaster == 'stopped' || $statusMaster == 'open' ||
            isset($statusSlave) == 'recording' || isset($statusSlave) == 'paused' || isset($statusSlave) == 'stopped' || isset($statusSlave) == 'open'
        ) {
            $status = false;
        } else {
            $status = true;

            function get_string_between($string, $start, $end)
            {
                $string = ' ' . $string;
                $string = str_replace('"', '', $string);
                $string = str_replace("'", '', $string);
                $ini = strpos($string, $start);
                if ($ini == 0)
                    return '';

                $ini += strlen($start);
                $len = strpos($string, $end, $ini) - $ini;
                return substr($string, $ini, $len);
            }


            $ssh_connect = new ssh_connect($masterIP, $recorder_user);
            $config = $ssh_connect->command("cat $baseDir/global_config.inc", 2);

            $classroom                   = get_string_between($config, '$classroom = ', ';');
            $ezrecorder_ip               = get_string_between($config, '$ezrecorder_ip = ', ';');
            $ezrecorder_username         = get_string_between($config, '$ezrecorder_username = ', ';');
            $ezrecorder_recorddir        = get_string_between($config, '$ezrecorder_recorddir = ', ';');
            $remote_recorder_ip          = get_string_between($config, '$remote_recorder_ip = ', ';');
            $external_remote_recorder_ip = get_string_between($config, '$external_remote_recorder_ip = ', ';');
            $remote_recorder_username    = get_string_between($config, '$remote_recorder_username = ', ';');
            $web_basedir                 = get_string_between($config, '$web_basedir = ', ';');
            $ezrecorder_web_user         = get_string_between($config, '$ezrecorder_web_user = ', ';');
            $ezcast_manager_url          = get_string_between($config, '$ezcast_manager_url = ', ';');
            $mailto_admins               = get_string_between($config, '$mailto_admins = ', ';');
            $php_cli_cmd                 = get_string_between($config, '$php_cli_cmd = ', ';');
            $ffmpeg_cli_cmd              = get_string_between($config, '$ffmpeg_cli_cmd = ', ';');
            $title_max_length            = get_string_between($config, '$title_max_length = ', ';');
            $cam_management_enabled      = get_string_between($config, '$cam_management_enabled = ', ';');
            $cam_management_module       = get_string_between($config, '$cam_management_module = ', ';');
            $cam_management_lib          = get_string_between($config, '$cam_management_lib = ', ';');
            $cam_management_views_dir    = get_string_between($config, '$cam_management_views_dir = ', ';');
            $cam_enabled                 = get_string_between($config, '$cam_enabled = ', ';');
            $cam_module                  = get_string_between($config, '$cam_module = ', ';');
            $cam_lib                     = get_string_between($config, '$cam_lib = ', ';');
            $slide_enabled               = get_string_between($config, '$slide_enabled = ', ';');
            $slide_module                = get_string_between($config, '$slide_module = ', ';');
            $slide_lib                   = get_string_between($config, '$slide_lib = ', ';');
            $sound_backup_enabled        = get_string_between($config, '$sound_backup_enabled = ', ';');
            $sound_backup_module         = get_string_between($config, '$sound_backup_module = ', ';');
            $sound_backup_lib            = get_string_between($config, '$sound_backup_lib = ', ';');
            $enable_vu_meter             = get_string_between($config, '$enable_vu_meter = ', ';');

            if(!empty($slaveIP)){
                /*$ssh_connectSlave = new ssh_connect($slaveIP, $recorder_user);
                $configSlave = $ssh_connectSlave->command("cat $baseDir/global_config.inc", 2);
                $classroomSlave = get_string_between($configSlave, '$classroom = ', ';');
                */
            }
            if (isset($input["save_new"])) {

                $config_variable = array(
                    array('$classroom', $classroom, $input["classroom"]),
                    array('$ezrecorder_ip', $ezrecorder_ip, $input["ezrecorder_ip"]),
                    array('$ezrecorder_username', $ezrecorder_username, $input["ezrecorder_username"]),
                    array('$ezrecorder_recorddir', $ezrecorder_recorddir, $input["ezrecorder_recorddir"]),
                    array('$remote_recorder_ip', $remote_recorder_ip, $input["remote_recorder_ip"]),
                    array('$external_remote_recorder_ip', $external_remote_recorder_ip, $input["external_remote_recorder_ip"]),
                    array('$remote_recorder_username', $remote_recorder_username, $input["remote_recorder_username"]),
                    array('$web_basedir', $web_basedir, $input["web_basedir"]),
                    array('$ezrecorder_web_user', $ezrecorder_web_user, $input["ezrecorder_web_user"]),
                    array('$ezcast_manager_url', $ezcast_manager_url, $input["ezcast_manager_url"]),
                    array('$mailto_admins', $mailto_admins, $input["mailto_admins"]),
                    array('$php_cli_cmd', $php_cli_cmd, $input["php_cli_cmd"]),
                    array('$ffmpeg_cli_cmd', $ffmpeg_cli_cmd, $input["ffmpeg_cli_cmd"]),
                    array('$title_max_length', $title_max_length, $input["title_max_length"]),
                    array('$cam_management_enabled', $cam_management_enabled, $input["cam_management_enabled"]),
                    array('$cam_management_module', $cam_management_module, $input["cam_management_module"]),
                    array('$cam_management_lib', $cam_management_lib, $input["cam_management_lib"]),
                    array('$cam_management_views_dir', $cam_management_views_dir, $input["cam_management_views_dir"]),
                    array('$cam_enabled', $cam_enabled, $input["cam_enabled"]),
                    array('$cam_module', $cam_module, $input["cam_module"]),
                    array('$cam_lib', $cam_lib, $input["cam_lib"]),
                    array('$slide_enabled', $slide_enabled, $input["slide_enabled"]),
                    array('$slide_module', $slide_module, $input["slide_module"]),
                    array('$slide_lib', $slide_lib, $input["slide_lib"]),
                    array('$sound_backup_enabled', $sound_backup_enabled, $input["sound_backup_enabled"]),
                    array('$sound_backup_module', $sound_backup_module, $input["sound_backup_module"]),
                    array('$sound_backup_lib', $sound_backup_lib, $input["sound_backup_lib"]),
                    array('$enable_vu_meter', $enable_vu_meter, $input["enable_vu_meter"])
                );

                for ($i = 0; $i < count($config_variable); $i++) {
                    if ($config_variable[$i][1] != $config_variable[$i][2]) {
                        if ($config_variable[$i][2] == 'true' || $config_variable[$i][2] == 'false') {
                            $config = preg_replace('/(' . preg_quote($config_variable[$i][0]) . ' = )(.*)/', $config_variable[$i][0] . " = " . $config_variable[$i][2] . ";", $config);
                        } else {
                            $config = preg_replace('/(' . preg_quote($config_variable[$i][0]) . ' = ")[^"]+(")/', $config_variable[$i][0] . " = \"" . $config_variable[$i][2] . "\"", $config);

                        }
                    }
                }
                $_SESSION["new_config"] = $config;
            }
            if (isset($input["confirm_modification"])) {
                $global_inc_new_name = time() . '.global_config.inc';
                $tmp_config_file = $ezadmin_basedir . "var/ezrecorder_config_restore/tmp.$masterIP.config.inc";
                file_put_contents($tmp_config_file, $_SESSION["new_config"]);
                $ssh_connect->command('"mv /Library/ezrecorder/global_config.inc /Library/ezrecorder/etc/' . $global_inc_new_name . '"', 2);
                exec('scp -o ConnectTimeout=10 ' . $ezadmin_basedir . 'var/ezrecorder_config_restore/tmp.' . $masterIP . '.config.inc podclient@' . $masterIP . ':/Library/ezrecorder/global_config.inc', $output, $return_var);
                unlink($tmp_config_file);
                $_SESSION["new_config"] = '';
                header("LOCATION:index.php?action=edit_recorder_config&room_name=$room_name&sesskey=" . $input['sesskey'] . "&success=true");
            }
            if (isset($input["restore_default"])) {
                $global_inc_new_name = time() . '.global_config.inc';
                $ssh_connect->command('"mv /Library/ezrecorder/global_config.inc /Library/ezrecorder/etc/' . $global_inc_new_name . '"', 2);
                exec('scp -o ConnectTimeout=10 ' . $ezadmin_basedir . 'var/ezrecorder_config_restore/' . $masterIP . '.config.inc podclient@' . $masterIP . ':/Library/ezrecorder/global_config.inc', $output, $return_var);
                $_SESSION["new_config"] = '';
                header("LOCATION:index.php?action=edit_recorder_config&room_name=$room_name&sesskey=" . $input['sesskey'] . "&success=true");
            }
            if (!file_exists($ezadmin_basedir. "var/ezrecorder_config_restore/$masterIP.config.inc"))
                file_put_contents($ezadmin_basedir . "var/ezrecorder_config_restore/$masterIP.config.inc", $config);

        }
    }

    include template_getpath('div_main_header.php');
    include template_getpath('div_edit_recorder_config.php');
    include template_getpath('div_main_footer.php');
}
?>