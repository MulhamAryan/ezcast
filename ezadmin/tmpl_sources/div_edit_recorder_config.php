<div class="page_title">®recorder_config® : <?php echo $room_ip;?></div>
<?php
    if($status == false) { ?>
    <div class="alert alert-danger">®recorder_error_edit®</div>
<?php }
    else{ ?>
        <form method="post" class="form-horizontal">

            <div class="btn-group pull-right row">
                <input type="submit" class="btn btn-danger" value="®recorder_restore_default®" name="restore_default">
                <input type="submit" class="btn btn-success" value="®continue®" name="save_new">
            </div>
            <br>
            <div style="clear:both;"></div>
            <?php
            if(isset($input["success"]) == true){ ?>
                <div class="alert alert-success"><?php echo  template_get_message('save_successful', get_lang());?></div>
            <?php }
            if(isset($input["save_new"])){
                for($i = 0; $i < count($config_variable); $i++){
                    if($config_variable[$i][1] != $config_variable[$i][2]) {
                        $modification_list .= '<span class="btn btn-default">' . $config_variable[$i][0] . ' = <span class="btn btn-warning">' . $config_variable[$i][1] . '</span> => <span class="btn btn-success">' . $config_variable[$i][2] . '</span></span><br>';
                    }
                }
                if(!empty($modification_list)) { ?>
                   <div class="alert alert-info" role="alert">
                       <h4 class="alert-heading">®recorder_check_modifcations®</h4>
                       <hr>
                       <p><?php echo $modification_list; ?></p>
                       <input type="submit" class="btn btn-success" value="®recorder_confirm_modifcations®" name="confirm_modification" onclick="return confirm('®confirm_modification®')">
                   </div>
            <?php }
                $modification_list = '';
            }
            ?>

            <div style="clear: both;"></div>
            <br>
            <!-- Cam management modules -->
            <div style="border:1px solid #dadada; padding:10px; margin-bottom: 10px;">
                <div class="page_title">®recorder_cam_config®</div>
                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_enable_cam®</label>
                    <div class="col-sm-5">
                        ®in_recorders® <input type="radio" name="cam_enabled" value="true"
                            <?php
                            if($cam_enabled == 'true')
                                echo'checked';
                            ?>>
                        ®out_recorders® <input type="radio" name="cam_enabled" value="false"
                            <?php
                            if($cam_enabled == 'false')
                                echo'checked';
                            ?>>
                    </div>
                    <kbd>$cam_enabled</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_cam_module®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="cam_module" value="<?php echo $cam_module;?>"></div>
                    <kbd>$cam_module</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_cam_lib®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="cam_lib" value="<?php echo $cam_lib;?>"></div>
                    <kbd>$cam_lib</kbd>
                </div>

                <hr>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_cam_management_enabled®</label>
                    <div class="col-sm-5">
                        ®in_recorders® <input type="radio" name="cam_management_enabled" value="true"
                            <?php
                            if($cam_management_enabled == 'true')
                                echo'checked';
                            ?>>
                        ®out_recorders® <input type="radio" name="cam_management_enabled" value="false"
                            <?php
                            if($cam_management_enabled == 'false')
                                echo'checked';
                            ?>>
                    </div>
                    <kbd>$cam_management_enabled</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_cam_management_enabled®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="cam_management_module" value="<?php echo $cam_management_module;?>"></div>
                    <kbd>$cam_management_module</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_cam_management_lib®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="cam_management_lib" value="<?php echo $cam_management_lib;?>"></div>
                    <kbd>$cam_management_lib</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_cam_management_views_dir®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="cam_management_views_dir" value="<?php echo $cam_management_views_dir;?>"></div>
                    <kbd>$cam_management_views_dir</kbd>
                </div>

            </div>

            <!-- Slide management modules -->

            <div style="border:1px solid #dadada; padding:10px; margin-bottom: 10px;">
                <div class="page_title">®recorder_slide_conf®</div>
                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_enable_slide®</label>
                    <div class="col-sm-5">
                        ®in_recorders® <input type="radio" name="slide_enabled" value="true"
                            <?php
                            if($slide_enabled == 'true')
                                echo'checked';
                            ?>>
                        ®out_recorders® <input type="radio" name="slide_enabled" value="false"
                            <?php
                            if($slide_enabled == 'false')
                                echo'checked';
                            ?>>
                    </div>
                    <kbd>$slide_enabled</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_slide_module®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="slide_module" value="<?php echo $slide_module;?>"></div>
                    <kbd>$slide_module</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_slide_lib®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="slide_lib" value="<?php echo $slide_lib;?>"></div>
                    <kbd>$slide_lib</kbd>
                </div>

            </div>

            <div style="border:1px solid #dadada; padding:10px; margin-bottom: 10px;">
                <div class="page_title">®recorder_sound_conf®</div>
                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_enable_vu_meter®</label>
                    <div class="col-sm-5">
                        ®in_recorders® <input type="radio" name="enable_vu_meter" value="true"
                            <?php
                            if($enable_vu_meter == 'true')
                                echo'checked';
                            ?>>
                        ®out_recorders® <input type="radio" name="enable_vu_meter" value="false"
                            <?php
                            if($enable_vu_meter == 'false')
                                echo'checked';
                            ?>>
                    </div>
                    <kbd>$enable_vu_meter</kbd>
                </div>

                <hr>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_enable_vu_meter®</label>
                    <div class="col-sm-5">
                        ®in_recorders® <input type="radio" name="sound_backup_enabled" value="true"
                            <?php
                            if($sound_backup_enabled == 'true')
                                echo'checked';
                            ?>>
                        ®out_recorders® <input type="radio" name="sound_backup_enabled" value="false"
                            <?php
                            if($sound_backup_enabled == 'false')
                                echo'checked';
                            ?>>
                    </div>
                    <kbd>$sound_backup_enabled</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_sound_backup_module®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="sound_backup_module" value="<?php echo $sound_backup_module;?>"></div>
                    <kbd>$sound_backup_module</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_sound_backup_lib®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="sound_backup_lib" value="<?php echo $sound_backup_lib;?>"></div>
                    <kbd>$sound_backup_lib</kbd>
                </div>

            </div>

            <div style="border:1px solid #dadada; padding:10px; margin-bottom: 10px;">
                <div class="page_title">®recorder_general_conf®</div>
                <input type="hidden" id="sesskey" name="sesskey" value="<?php echo $input['sesskey'];?>">
                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_classroom®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="classroom" value="<?php echo $classroom;?>"></div>
                    <kbd>$classroom</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_ezrecorder_ip®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="ezrecorder_ip" value="<?php echo $ezrecorder_ip;?>"></div>
                    <kbd>$ezrecorder_ip</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_ezrecorder_username®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="ezrecorder_username" value="<?php echo $ezrecorder_username;?>"></div>
                    <kbd>$ezrecorder_username</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_ezrecorder_recorddir®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="ezrecorder_recorddir" value="<?php echo $ezrecorder_recorddir;?>"></div>
                    <kbd>$ezrecorder_recorddir</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_remote_recorder_ip®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="remote_recorder_ip" value="<?php echo $remote_recorder_ip;?>"></div>
                    <kbd>$remote_recorder_ip</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_external_remote_recorder_ip®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="external_remote_recorder_ip" value="<?php echo $external_remote_recorder_ip;?>"></div>
                    <kbd>$external_remote_recorder_ip</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_remote_recorder_username®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="remote_recorder_username" value="<?php echo $remote_recorder_username;?>"></div>
                    <kbd>$remote_recorder_username</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®ecorder_web_basedir®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="web_basedir" value="<?php echo $web_basedir;?>"></div>
                    <kbd>$web_basedir</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_ezrecorder_web_user®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="ezrecorder_web_user" value="<?php echo $ezrecorder_web_user;?>"></div>
                    <kbd>$ezrecorder_web_user</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_ezcast_manager_url®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="ezcast_manager_url" value="<?php echo $ezcast_manager_url;?>"></div>
                    <kbd>$ezcast_manager_url</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">Email admin</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="mailto_admins" value="<?php echo $mailto_admins;?>"></div>
                    <kbd>$mailto_admins</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">Commande PHP Cli</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="php_cli_cmd" value="<?php echo $php_cli_cmd;?>"></div>
                    <kbd>$php_cli_cmd</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">Commande FFMPEG Cli</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="ffmpeg_cli_cmd" value="<?php echo $ffmpeg_cli_cmd;?>"></div>
                    <kbd>$ffmpeg_cli_cmd</kbd>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">®recorder_title_max_length®</label>
                    <div class="col-sm-5"><input type="text" class="form-control col-sm-5" name="title_max_length" value="<?php echo $title_max_length;?>"></div>
                    <kbd>$title_max_length</kbd>
                </div>
            </div>

        </form>
    <?php }
?>
