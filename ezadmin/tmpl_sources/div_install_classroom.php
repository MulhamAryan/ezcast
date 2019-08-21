<div class="page_title">®install_classroom®</div>
    <form method="post" class="form-horizontal">
        <input type="hidden" id="sesskey" name="sesskey" value="<?php echo $_SESSION['sesskey'];?>">
        <?php
            if(!isset($input["step"])){
        ?>

            <input type="hidden" name="step" value="2">
            <div class="form-group">
                <label for="ip_address" class="col-sm-2 control-label">®recorder_ezrecorder_ip®</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="ip_address">
                </div>
            </div>

            <div class="form-group">
                <label for="machine_username" class="col-sm-2 control-label">®recorder_ezrecorder_username®</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="machine_username" autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                 <label for="machine_base_dir" class="col-sm-2 control-label">®classroom_base_dir®</label>
                 <div class="col-sm-5">
                     <input type="text" class="form-control" name="machine_base_dir" value="/Library/ezrecorder" autocomplete="off">
                 </div>
            </div>

            <div class="col-sm-offset-2 col-sm-5">
                <input type="submit" class="btn btn-success" name="next" value="®continue®">
            </div>
        <?php }
            elseif(isset($input["step"]) == 2){
                echo $ouput;
                if($error)
                    echo '<div class="alert alert-danger">' . $error . '</div>';

                else{

                }
            }
        ?>
    </form>