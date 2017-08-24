
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">®Stats_Descriptives®</h4>
</div>
<div class="modal-body">
    <?php if(isset($stats['display']) && $stats['display']) { ?>
        <div id="container" style="margin: 0 auto"></div>
        
        <center>
            <div style="display:inline-block;width:<?php echo ($has_slides) ? '50%' : '100%'; ?>;" class="popup_video_player"
                id="Popup_Player_<?php echo $asset; ?>_cam"></div>
            <?php if($has_slides) { ?>
                <div style="display:inline-block;width: 49%;" class="popup_video_player" 
                     id="Popup_Player_<?php echo $asset; ?>_slide"></div>
            <?php } ?>
        </center>
        <br />
            
    <?php } else { ?>
        ®Stats_No_stats®
    <?php } ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">®Close_and_return_to_index®</button>
</div>

<script>
<?php if(isset($stats['display']) && $stats['display']) { ?>
    var chart = Highcharts.chart('container', {
        chart: {
            type: 'areaspline',
            events: {
                click: function(e) {
                    adaptVideoTime(Math.round(event.xAxis[0].value));
                }
            }
        },
        title: {
            text: '®Graph_min_per_min_view®'
        },
        plotOptions: {
            areaspline: {
                stacking: 'normal',
                cursor: 'pointer',
                events: {
                    click: function () {
                        adaptVideoTime(event.points.category);
                    }
                }
            }
        },
        xAxis: {
            labels: {
                formatter: 
                    function() {
                        var val = (this.value * <?php echo $video_split_time; ?>);
                        var min = Math.floor(val/60)%60;
                        var sec = val%60;
                        var hour = Math.floor(val/3600);
                        var str = "";
                        if(hour > 0) {
                            str += hour + ":";
                        }
                        if(min > 0 || hour > 0) {
                            if(min < 10 && hour > 0) {
                                str += "0";
                            }
                            str += min + ":";
                        }
                        if(sec < 10) {
                            str += "0";
                        }
                        str += sec;

                        if(min === 0 && hour === 0) {
                            str += " sec";
                        }
                        return str;
                    }
            }
        },
        yAxis: {
            title: {
                text: '®Graph_nbr_view®'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' ®Graph_views®',
            formatter: function() {
                    return '<b>' + this.points[0].series.name + ':</b> ' +
                    	this.points[0].y + ' ®Graph_views®<br />' +
                      '<b>' + this.points[1].series.name + ':</b> ' +
                      this.points[1].y + ' ®Graph_views®<br />'+
                      '<b>Total:</b> ' + (this.points[0].y + this.points[1].y) + 
                        ' ®Graph_views®';
                }
        },
        series: [{
                name: '®Graph_slide_view®',
                data: <?php echo $stats['str_view_time_slide']; ?>
            },{
                name: '®Graph_cam_view®',
                data: <?php echo $stats['str_view_time_cam']; ?>
            }]
    });
<?php } ?>
(function() {
    show_embed_player('<?php echo $album; ?>', '<?php echo $asset; ?>', 'low', 'cam', '<?php 
            echo $asset_token; ?>', 'Popup_Player_<?php echo $asset . '_cam'; ?>', '100%', '100%');
    <?php if($has_slides) { ?>
        setTimeout(function(){ 
            show_embed_player('<?php echo $album; ?>', '<?php echo $asset; ?>', 'low', 'slide', '<?php 
                echo $asset_token; ?>', 'Popup_Player_<?php echo $asset . '_slide'; ?>', '100%', '100%');
        }, 100);
    <?php } ?>
})();

function adaptVideoTime(xValue) {
    var newVideoTime = xValue * <?php echo $video_split_time; ?>;
    var allVideoPlayer = $('.popup_video_player video');
    for(var i = 0; i < allVideoPlayer.length; i++) {
        var video = $('.popup_video_player video')[i];
        video.currentTime = newVideoTime;
    }
    addPlotLine(xValue);
}

function addPlotLine(xValue) {
    console.log('Update plotline');
    chart.xAxis[0].removePlotLine('video-plot-line');
    chart.xAxis[0].addPlotLine({
            color: 'red', // Color value
            value: xValue, // Value of where the line will appear
            width: 1, // Width of the line
            id: 'video-plot-line'
        });
}

</script>