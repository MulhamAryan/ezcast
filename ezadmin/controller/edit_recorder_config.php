<?php
function index($param = array()){
    global $input;
    global $recorder_user;
    global $ezadmin_basedir;

    if (!session_key_check($input['sesskey'])) {
        echo "Usage: Session key is not valid";
        die;
    }
    $room_ip = $input["ip"];
    if(!isset($input["confirm_modification"])){
        $_SESSION["new_config"] = '';
    }

    $url = 'http://'.$room_ip.'/ezrecorder/services/state.php';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in seconds
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output,true);
    if($output["status_general"] == 'recording' || $output["status_general"] == 'paused' || $output["status_general"] == 'stopped' || $output["status_general"] == 'open') {
        $status = false;
    }
    else{
        $status = true;

        function get_string_between($string, $start, $end){
            $string = ' ' . $string;
            $string = str_replace('"','',$string);
            $string = str_replace("'",'',$string);
            $ini = strpos($string, $start);
            if ($ini == 0)
                return '';

            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }


        $ssh_connect = new ssh_connect($room_ip,$recorder_user);
        $config = $ssh_connect->command("cat /Library/ezrecorder/global_config.inc",2);

        $classroom                   = get_string_between($config ,'$classroom = ',';');
        $ezrecorder_ip               = get_string_between($config ,'$ezrecorder_ip = ',';');
        $ezrecorder_username         = get_string_between($config ,'$ezrecorder_username = ',';');
        $ezrecorder_recorddir        = get_string_between($config ,'$ezrecorder_recorddir = ',';');
        $remote_recorder_ip          = get_string_between($config ,'$remote_recorder_ip = ',';');
        $external_remote_recorder_ip = get_string_between($config ,'$external_remote_recorder_ip = ',';');
        $remote_recorder_username    = get_string_between($config ,'$remote_recorder_username = ',';');
        $web_basedir                 = get_string_between($config ,'$web_basedir = ',';');
        $ezrecorder_web_user         = get_string_between($config ,'$ezrecorder_web_user = ',';');
        $ezcast_manager_url          = get_string_between($config ,'$ezcast_manager_url = ',';');
        $mailto_admins               = get_string_between($config ,'$mailto_admins = ',';');
        $php_cli_cmd                 = get_string_between($config ,'$php_cli_cmd = ',';');
        $ffmpeg_cli_cmd              = get_string_between($config ,'$ffmpeg_cli_cmd = ',';');
        $title_max_length            = get_string_between($config ,'$title_max_length = ',';');
        $cam_management_enabled      = get_string_between($config ,'$cam_management_enabled = ',';');
        $cam_management_module       = get_string_between($config ,'$cam_management_module = ',';');
        $cam_management_lib          = get_string_between($config ,'$cam_management_lib = ',';');
        $cam_management_views_dir    = get_string_between($config ,'$cam_management_views_dir = ',';');
        $cam_enabled                 = get_string_between($config ,'$cam_enabled = ',';');
        $cam_module                  = get_string_between($config ,'$cam_module = ',';');
        $cam_lib                     = get_string_between($config ,'$cam_lib = ',';');
        $slide_enabled               = get_string_between($config ,'$slide_enabled = ',';');
        $slide_module                = get_string_between($config ,'$slide_module = ',';');
        $slide_lib                   = get_string_between($config ,'$slide_lib = ',';');
        $sound_backup_enabled        = get_string_between($config ,'$sound_backup_enabled = ',';');
        $sound_backup_module         = get_string_between($config ,'$sound_backup_module = ',';');
        $sound_backup_lib            = get_string_between($config ,'$sound_backup_lib = ',';');
        $enable_vu_meter             = get_string_between($config ,'$enable_vu_meter = ',';');

        if(isset($input["save_new"])) {

            $config_variable = array(
                array('$classroom',$classroom,$input["classroom"]),
                array('$ezrecorder_ip',$ezrecorder_ip,$input["ezrecorder_ip"]),
                array('$ezrecorder_username',$ezrecorder_username,$input["ezrecorder_username"]),
                array('$ezrecorder_recorddir',$ezrecorder_recorddir,$input["ezrecorder_recorddir"]),
                array('$remote_recorder_ip',$remote_recorder_ip,$input["remote_recorder_ip"]),
                array('$external_remote_recorder_ip',$external_remote_recorder_ip,$input["external_remote_recorder_ip"]),
                array('$remote_recorder_username',$remote_recorder_username,$input["remote_recorder_username"]),
                array('$web_basedir',$web_basedir,$input["web_basedir"]),
                array('$ezrecorder_web_user',$ezrecorder_web_user,$input["ezrecorder_web_user"]),
                array('$ezcast_manager_url',$ezcast_manager_url,$input["ezcast_manager_url"]),
                array('$mailto_admins',$mailto_admins,$input["mailto_admins"]),
                array('$php_cli_cmd',$php_cli_cmd,$input["php_cli_cmd"]),
                array('$ffmpeg_cli_cmd',$ffmpeg_cli_cmd,$input["ffmpeg_cli_cmd"]),
                array('$title_max_length',$title_max_length,$input["title_max_length"]),
                array('$cam_management_enabled',$cam_management_enabled,$input["cam_management_enabled"]),
                array('$cam_management_module',$cam_management_module,$input["cam_management_module"]),
                array('$cam_management_lib',$cam_management_lib,$input["cam_management_lib"]),
                array('$cam_management_views_dir',$cam_management_views_dir,$input["cam_management_views_dir"]),
                array('$cam_enabled',$cam_enabled,$input["cam_enabled"]),
                array('$cam_module',$cam_module,$input["cam_module"]),
                array('$cam_lib',$cam_lib,$input["cam_lib"]),
                array('$slide_enabled',$slide_enabled,$input["slide_enabled"]),
                array('$slide_module',$slide_module,$input["slide_module"]),
                array('$slide_lib',$slide_lib,$input["slide_lib"]),
                array('$sound_backup_enabled',$sound_backup_enabled,$input["sound_backup_enabled"]),
                array('$sound_backup_module',$sound_backup_module,$input["sound_backup_module"]),
                array('$sound_backup_lib',$sound_backup_lib,$input["sound_backup_lib"]),
                array('$enable_vu_meter',$enable_vu_meter,$input["enable_vu_meter"])
            );

            for($i = 0; $i < count($config_variable); $i++){
                if($config_variable[$i][1] != $config_variable[$i][2]) {
                    if($config_variable[$i][2] == 'true' || $config_variable[$i][2] == 'false'){
                        $config = preg_replace('/(' . preg_quote($config_variable[$i][0]) . ' = )(.*)/', $config_variable[$i][0] . " = " . $config_variable[$i][2] . ";", $config);
                    }
                    else {
                        $config = preg_replace('/(' . preg_quote($config_variable[$i][0]) .' = ")[^"]+(")/', $config_variable[$i][0] . " = \"" . $config_variable[$i][2] . "\"", $config);

                    }
                }
            }
            $_SESSION["new_config"] = $config;
        }
        if(isset($input["confirm_modification"])){
            $global_inc_new_name = time().'.global_config.inc';
            $tmp_config_file = $ezadmin_basedir."var/ezrecorder_config_restore/tmp.$room_ip.config.inc";
            file_put_contents($tmp_config_file,$_SESSION["new_config"]);
            $ssh_connect->command('"mv /Library/ezrecorder/global_config.inc /Library/ezrecorder/etc/'.$global_inc_new_name.'"',2);
            exec('scp -o ConnectTimeout=10 '.$ezadmin_basedir.'var/ezrecorder_config_restore/tmp.'.$room_ip.'.config.inc podclient@164.15.43.31:/Library/ezrecorder/global_config.inc', $output, $return_var);
            unlink($tmp_config_file);
            $_SESSION["new_config"] = '';
            header("LOCATION:index.php?action=edit_recorder_config&ip=$room_ip&sesskey=".$input['sesskey']."&success=true");
        }
        if(isset($input["restore_default"])){
            $global_inc_new_name = time().'.global_config.inc';
            $ssh_connect->command('"mv /Library/ezrecorder/global_config.inc /Library/ezrecorder/etc/'.$global_inc_new_name.'"',2);
            exec('scp -o ConnectTimeout=10 '.$ezadmin_basedir.'var/ezrecorder_config_restore/'.$room_ip.'.config.inc podclient@164.15.43.31:/Library/ezrecorder/global_config.inc', $output, $return_var);
            $_SESSION["new_config"] = '';
            header("LOCATION:index.php?action=edit_recorder_config&ip=$room_ip&sesskey=".$input['sesskey']."&success=true");
        }
        if(!file_exists("var/ezrecorder_config_restore/$room_ip.config.inc"))
            file_put_contents($ezadmin_basedir."var/ezrecorder_config_restore/$room_ip.config.inc",$config);


    }
    include template_getpath('div_main_header.php');
    include template_getpath('div_edit_recorder_config.php');
    include template_getpath('div_main_footer.php');
}
?>