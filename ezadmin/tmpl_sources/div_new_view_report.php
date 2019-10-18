
<div class="page_title">®report_title®</div>

<form method="post" class="search_event pagination hidden-print" style="width: 100%;">

    <input type="hidden" id="sesskey" name="sesskey" value="<?php echo $_SESSION['sesskey']; ?>" />

    <div class="form-group">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <label for="start_date">®from_date®</label>
                <div class='input-group date' id='start_date'>
                    <input type='text' name='start_date' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar">
                        </span>
                    </span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="end_date">®to_date®</label>
                <div class='input-group date' id='end_date'>
                    <input type='text' name='end_date' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar">
                        </span>
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="checkbox">
                    <br />
                    <label>
                        <input disabled type="checkbox" name="ezplayer"
                            <?php if (isset($input) && array_key_exists('ezplayer', $input)) {
                                echo 'checked';
                            } ?>>
                        ®report_form_ezplayer® not available at this moment
                    </label>
                </div>
            </div>
            <div class="col-md-2">
                <br />
                <button type="submit" class="btn btn-block btn-success"
                        data-loading-text="<img style='height: 16px;' src='img/loading_transparent.gif'/> ®loading®..."
                        onClick="$(this).button('loading');">
                    <span class="glyphicon glyphicon-refresh icon-white"></span>
                    ®report_form_generate®
                </button>
            </div>
        </div>
        <?php echo $resultat; ?>
    </div>

    <script type="text/javascript">
        $(function () {

            $('#start_date').datetimepicker({
                showTodayButton: true,
                showClose: true,
                sideBySide: true,
                format: 'YYYY-MM-DD',
                <?php
                if (isset($input) && array_key_exists('start_date', $input)) {
                    echo "defaultDate: new Date('".$input['start_date']."')";
                } else {
                    echo 'defaultDate: moment().subtract(6, \'month\')';
                }
                ?>
            });

            $('#end_date').datetimepicker({
                showTodayButton: true,
                showClose: true,
                sideBySide: true,
                format: 'YYYY-MM-DD',
                <?php
                if (isset($input) && array_key_exists('end_date', $input)) {
                    echo "defaultDate: new Date('".$input['end_date']."')";
                } else {
                    echo 'defaultDate: moment().add(1, \'days\')';
                }
                ?>
            });

            $("#start_date").on("dp.change", function (e) {
                $('#end_date').data("DateTimePicker").minDate(e.date);
            });
            $("#end_date").on("dp.change", function (e) {
                $('#start_date').data("DateTimePicker").maxDate(e.date);
            });

        });

    </script>

</form>