<?php
    function index($param = array()){
        global $input;
        global $ssh_pgm;
        global $recorder_user;
        if(isset($input["next"])){
            $error = '';
            $machine_ip = $input["ip_address"];
            $user_client = $input["machine_username"];
            $installdir = $input["machine_base_dir"];

            $ssh = new ssh_connect($machine_ip,$user_client);
            if(isset($input["step"]) == 2) {
                $gitoutput = $ssh->command('"sudo git clone https://github.com/ulbpodcast/ezrecorder.git ' . $installdir . '"', 1);
                $repoutput = $ssh->command('"if [ -e ' . $installdir . ' ]; then echo true; else echo false; fi;"', 1);
                $ouput = var_dump($gitoutput);
                if (empty($machine_ip))
                    $error = template_get_message('missing_ip', get_lang());

                elseif (empty($user_client))
                    $error = template_get_message('missing_user_name', get_lang());

                elseif (empty($installdir))
                    $error = template_get_message('missing_base_dir', get_lang());

                elseif ($repoutput[0] == 'false')
                    $error = template_get_message('recorder_folder_doesnot_exists', get_lang());
            }
            elseif(isset($input["step"]) == 3){
                /*
                 * Install list variable
                 * 1- sudo ./install.sh
                 * 2- Check requirement
                 * 2.A- PHP
                 *  -> Check php dir
                 *  -> Check php curl if is enabled
                 *  -> Check SimpleXML is enabled
                 *  -> Check SQLite is enabled
                 * 2.B- FFMPEG
                 * 2.C- Get apache username
                 * 3- Create Config
                 *  -> Create Master/Slave
                 *  -> Classroom
                 *  -> Static IP 127.0.0.1
                 *  -> Recorder username (podclient)
                 *  -> Video Path (/Users/podclient/Movies/)
                 *  -> Web path (/Library/WebServer/Documents/ezrecorder/)
                 *  -> URL to EZmanager (http://your.ezcast.server/ezmanager/)
                 *  -> Ezrecorder email
                 *  -> User apache from (2.C)
                 *  -> Remote recorder IP. This IP must be accessible from EZmanager server. (leave empty if you don't use a remote) []:
                 *  -> Remote recorder username (leave empty if you don't use a remote) []
                 * 3.A- Create global_config.inc
                 * 3.B- Create ./sbin/localdefs
                 * 3.C- Create setperms.sh from setperms_sample.sh
                 * 4- CAM RECORDING ? yes \ no
                 *  -> local_ffmpeg_hls
                 *  -> local_qtb
                 *  -> remote_ffmpeg_hls
                 *  -> remote_fmle_cutlist
                 *  -> sound_backup
                 * 5- Slide recording ? yes \ no
                 *  -> local_ffmpeg_hls
                 *  -> local_qtb
                 *  -> remote_ffmpeg_hls
                 *  -> remote_fmle_cutlist
                 *  -> sound_backup
                 * 6- Cam management ? yes \ no
                 *  -> onvif_cam_management
                 *  -> ptzoptics_cam_management
                 *  -> visca_cam_management
                 * 6.A- default camera position for cam recording (default)
                 * 6.B- Enter the default camera position for slide recording (this position will be used for slide only record, to record a backup video) [default: 'screen']:
                 * 7- Authentication module
                 *  -> auth_file yes | no
                 * 8- Configure selected modules in 4 and 5 (check every module install var)
                 * 9- Changing values in setperms.sh
                 * */
            }
        }


        include template_getpath('div_main_header.php');
        include template_getpath('div_install_classroom.php');
        include template_getpath('div_main_footer.php');
    }
?>