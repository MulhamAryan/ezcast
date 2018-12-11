<?php
/*
 * EZCAST EZmanager
 *
 * Copyright (C) 2016 Université libre de Bruxelles
 *
 * Written by Michel Jansens <mjansens@ulb.ac.be>
 * 		    Arnaud Wijns <awijns@ulb.ac.be>
 *                   Antoine Dewilde
 * UI Design by Julien Di Pietrantonio
 *
 * This software is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this software; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Before calling this template, please declare the following variables:
// - $asset_name The asset (technical) name
// - $title The asset (user-friendly) title
// - $description The asset's description
// - $public_album Set to true if the album is public, false otherwise (used for the "publish/unpublish option)
// - $has_cam Set to true if the asset has a "live action" media
// - $has_slides Set to true if the asset has a "slides" media
// - $type Can be "cam", "slide" or "camslide"
// - $date The date the asset was created, in a user-friendly format
// - $duration The duration of the asset
// - $filesize_[cam|slides] An assoc array (indexes 'low' and 'high') with the filesize for both the videos 1 and 2, as a string (including the unit!)
//     Example : $filesize_cam['high'] = '640 Mo' means that the live-action video weights 640Mo in HD
// - $dimensions_[cam|slides] An assoc array with the dimensions of the video, as a string
//     Example : $dimensions_cam['high'] = '1024 x 768' means the video in the left column in high-res is in 1024*768
// - $urls An assoc array (indexes 'low' and 'high') with the URL to the asset (web_distribute.php)
// - $embeds An assoc array with embed codes for the assets

/**
 * Helper function, used for pretty print.
 * @param type $info
 */
function print_info($info, $suffix = '') {
    if (isset($info) && $info !== '') {
        echo htmlspecialchars($info) . $suffix;
    } else {
        echo '®Not_available®';
    }
}
?>

<?php if (strtolower($origin) !== 'streaming') {
    ?>
    <div style="width: 100%">
        <div class="LigneButton">
            <ul>
                <li>
                    <span class="ButtonEdit">
                        <a href="javascript:show_edit_form('<?php echo $asset; ?>');">
                            ®Edit®
                        </a>
                    </span>
                </li>


                <?php if ($public_album) {
                    ?>
                    <li>
                        <span class="ButtonMoveAlbumPrive">
                            <?php
                            if ($status != 'processing' && $status != 'error') {
                                echo '<a href="index.php?action=show_popup&amp;popup=unpublish_asset&amp;title=' . urlencode($title) .
                                '&amp;album=' . urlencode($album) . '&amp;asset=' . urlencode($asset_name) . '" data-remove="false" data-toggle="modal" ' .
                                'data-target="#modal">';
                            } else {
                                echo '<a href="index.php?action=show_popup&amp;popup=popup_not_available" data-remove="false"' .
                                ' data-toggle="modal" data-target="#modal">';
                            }
                            ?>
                            ®Unpublish®</a>
                        </span>
                    </li>
                    <?php
                } else {
                    ?>
                    <li>
                        <span class="ButtonMoveAlbumPublic">
                            <?php
                            if ($status != 'processing' && $status != 'error') {
                                echo '<a href="index.php?action=show_popup&amp;popup=publish_asset&amp;title=' . urlencode($title) .
                                '&amp;album=' . urlencode($album) . '&amp;asset=' . urlencode($asset_name) . '" data-remove="false" data-toggle="modal" ' .
                                'data-target="#modal">';
                            } else {
                                echo '<a href="index.php?action=show_popup&amp;popup=popup_not_available" data-remove="false"' .
                                ' data-toggle="modal" data-target="#modal">';
                            }
                            ?>
                            ®Publish®</a>
                        </span>
                    </li>
                <?php }
                ?>

                <?php if ($trace_on && $display_trace_stats) {
                    ?>
                    <li>
                        <span class="BoutonStatsTitle">
                            <?php
                            if ($status != 'processing' && $status != 'error') {
                                echo '<a href="index.php?action=show_popup&amp;popup=asset_stats&amp;album=' . $album .
                                '&amp;asset=' . $asset_name . '" data-remove="false" data-toggle="modal" ' .
                                'data-target="#modal">';
                            } else {
                                echo '<a href="index.php?action=show_popup&amp;popup=popup_not_available" data-remove="false"' .
                                ' data-toggle="modal" data-target="#modal">';
                            }
                            ?>
                            ®Stats_Descriptives®</a>
                        </span>
                    </li>
                <?php }
                ?>
            </ul>
        </div>

        <div class="LigneButtonRight LigneButton btn-group" role="group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-background-icon-button ButtonMoreOptions" 
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="icon-btn">
                    ®More_options®
                    <span class="caret"></span>
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><span class="ButtonSuppAlbum">
                        <?php
                        if ($status != 'processing' && $status != 'error') {
                            echo '<a href="index.php?action=show_popup&amp;popup=delete_asset&amp;title=' . urlencode($title) .
                            '&amp;album=' . urlencode($album) . '&amp;asset=' . urlencode($asset_name) . '" data-remove="false" data-toggle="modal" ' .
                            'data-target="#modal">';
                        } else {
                            echo '<a href="index.php?action=show_popup&amp;popup=popup_not_available" data-remove="false"' .
                            ' data-toggle="modal" data-target="#modal">';
                        }
                        ?>
                        ®Delete®</a>

                    </span>
                </li>
                <li>
                    <span class="ButtonMove">
                        <?php
                        if ($status != 'processing' && $status != 'error') {
                            echo '<a href="index.php?action=show_popup&amp;popup=move_asset&amp;album=' . urlencode($album) .
                            '&amp;asset=' . urlencode($asset_name) . '" data-remove="false" data-toggle="modal" ' .
                            'data-target="#modal">';
                        } else {
                            echo '<a href="index.php?action=show_popup&amp;popup=popup_not_available" data-remove="false"' .
                            ' data-toggle="modal" data-target="#modal">';
                        }
                        ?>
                        ®Move®</a>
                    </span>
                </li>
                <?php
                global $enable_copy_asset;
                if ($enable_copy_asset) {
                    ?>
                    <li>
                        <span class="ButtonCopy">
                            <?php
                            if ($status != 'processing' && $status != 'error') {
                                echo '<a href="index.php?action=show_popup&amp;popup=copy_asset&amp;album=' . urlencode($album) .
                                '&amp;asset=' . urlencode($asset_name) . '" data-remove="false" data-toggle="modal" ' .
                                'data-target="#modal">';
                            } else {
                                echo '<a href="index.php?action=show_popup&amp;popup=popup_not_available" data-remove="false"' .
                                ' data-toggle="modal" data-target="#modal">';
                            }
                            ?>
                            ®Copy®</a>
                        </span>
                    </li>
                <?php }
                ?>
                <li>
                    <span class="ButtonProgrammer">
                        <?php
                        if ($status != 'processing' && $status != 'error') {
                            echo '<a href="index.php?action=show_popup&amp;popup=schedule_asset&amp;album=' . urlencode($album) .
                            '&amp;asset=' . urlencode($asset_name) . '" data-remove="false" data-toggle="modal" ' .
                            'data-target="#modal">';
                        } else {
                            echo '<a href="index.php?action=show_popup&amp;popup=popup_not_available" data-remove="false"' .
                            ' data-toggle="modal" data-target="#modal">';
                        }
                        ?>
                        ®Program®</a>
                    </span>
                </li>
                <?php
                // add flag title_up_to_date in metadata. get this info. If not show the button => do that in controller, not tmpl
                global $regenerate_title_mode;
                if ($regenerate_title_mode == 'manual' && isset($asset_metadata['add_title']) && ($asset_metadata['add_title'] != "false")) {
                    ?>
                    <li>
                        <span class="ButtonRegenTitle">
                            <?php
                            if ($status != 'processing' && $status != 'error') {
                                echo '<a href="index.php?action=show_popup&amp;popup=regen_title&amp;album=' . urlencode($album) .
                                '&amp;asset=' . urlencode($asset_name) . '&amp;title=' . urlencode($title) . '" data-remove="false" data-toggle="modal" ' .
                                'data-target="#modal">';
                            } else {
                                echo '<a href="index.php?action=show_popup&amp;popup=popup_not_available" data-remove="false"' .
                                ' data-toggle="modal" data-target="#modal">';
                            }
                            ?>
                            ®Regen_Intro®</a>
                        </span>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
    </div>
<?php }
?>

<!-- bouton supp + editer + 2 X Deplacer [Fin]  -->

<!-- Colonne 1 information podcast -->

<div class="col-sm-4" style="padding-left: 0px;">
    <h1>®Information®</h1>
    <ul class="list-unstyled">
        <li>
            <span class="infospodast">®Title®</span><br />
            <span id="title_<?php echo $asset; ?>"><?php print_info($title); ?></span>
            <span id="title_<?php echo $asset; ?>_edit" style="display: none;">
                <input type="text" name="title" id="title_<?php echo $asset; ?>_input" class="form-control input-sm"
                       value="<?php echo htmlentities($title, ENT_COMPAT, "UTF-8"); ?>" />
            </span>
        </li>
        <li>
            <span class="infospodast">®Description®</span><br />
            <span id="description_<?php echo $asset; ?>"><?php print_info($description); ?></span>
            <span id="description_<?php echo $asset; ?>_edit" style="display: none;">
                <textarea name="description" class="form-control input-sm" style="resize: vertical;"
                          id="description_<?php echo $asset; ?>_input"><?php echo htmlentities($description, ENT_COMPAT, "UTF-8");
?></textarea>
            </span>
        </li>
        <li class="text-right">
            <!-- Edit form (submit/cancel buttons) -->
            <div class="btn-group" role="group" id="<?php echo $asset; ?>_submit" style="display: none;margin-top: 5px;">
                <input type="button" class="btn btn-default btn-xs" id="<?php echo $asset; ?>_cancel_button" 
                       onclick="show_edit_form('<?php echo $asset; ?>');" value="®Cancel®" />
                <input type="button" class="btn btn-default btn-xs" id="<?php echo $asset ?>_submit_button" 
                       onclick="edit_asset_data('<?php echo $album; ?>', '<?php echo $asset; ?>');" value="®Valid®" />
            </div>
        </li>
        <li>
            <span class="infospodast">®Author®</span><br />
            <?php print_info($author); ?>
        </li>
        <li>
            <span class="infospodast">®Date®</span><br />
            <?php print_info($date); ?>
        </li>
        <li>
            <span class="infospodast">®Origin®</span><br />
            <?php
            if ($origin == 'SUBMIT') {
                echo '®Manual_submit®';
            } else {
                print_info($origin);
            }
            ?>
        </li>

        <?php if ($status == 'processing') {
            ?>
            <li>
                <span class="infospodast">®Status®</span><br />
                <span style="color: #75940a;">®Processing®</span>
            </li>
            <?php
        } elseif ($status == 'failed') {
            ?>
            <li>
                <span class="infospodast">®Status®</span><br />
                <span style="color: red;">®Processing_error®</span>
            </li>
            <?php
        } else {
            ?>
            <li>
                <span class="infospodast">®Length®</span><br />
                <?php print_info($duration); ?>
            </li>
            <li>
                <span class="infospodast">®Type®</span><br /> 
                <span id="recordtype"><?php print_info($record_type); ?></span>
            </li>
            <?php
        }

        if ($origin == 'SUBMIT') {
            ?>
            <li>
                <span class="infospodast">®Filename®</span><br />
                <?php print_info($file_name); ?>
            </li>
            <?php
        } elseif (strtolower($origin) === 'streaming') {
            ?>
            <li>
                <span class="ButtonEZplayer"> 
                    <a href="index.php?action=show_popup&amp;popup=ezplayer_link&amp;album=<?php echo $album; ?>&amp;asset=<?php echo $asset; ?>" 
                       data-remote="false" data-toggle="modal" data-target="#modal">
                        EZplayer
                    </a>
                </span>
            </li>
        <?php }
        ?>
    </ul>
    <br />
</div>

<?php
// If there is only one media, we display it in the right column.
// 3 possibles scenarios: there was only a slides video, or there were 2 videos
//    That happens if $has_slides is true (inside the "if")
// There was only a cam video. In that case $has_slides is false, so the content of the "else" is displayed
if ($status != 'processing' && $status != 'failed' && strtolower($origin) !== 'streaming') {
    ?>
    <div class="col-sm-8">

        <script>
            var current_asset;
            var trace_on = false;
            var display_threads_notif = false;
            var current_album = <?php echo "'$album'" ?>;
            var current_asset_name = <?php echo "'$asset'" ?>;
            var lvl = 3;
            function get_origin() {
                if (from_shortcut) { // a key has been pressed to run the action
                    from_shortcut = false;
                    return "from_shortcut";
                } else {
                    return "from_button";
                }
            }
            function server_trace(array) {

                return true;
            }
            function player_resize() {
                if (fullscreen) {
                    $('body').css('overflow', 'hidden');
                    $('#video_player').css('width', '100%');
                    $('#video_player').css('height', '100%');
                    $('#video_player').css('position', 'fixed');
                    var window_height = $(window).height();
                    var window_width = $(window).width();
                    var extra_height = 41; // .video_controls = 39px
                    var extra_width = 0;

                    if (show_panel) { // bookmarks panel on the right
                        extra_width += panel_width;
                        $('#video_notifications').addClass('panel-active');
                    }
                    $('.video_controls').css('width', '100%');

                    window_height -= extra_height;
                    window_width -= extra_width;

                    $('#bookmark_form, #thread_form').css('width', window_width + 'px');

                    $('video').css('width', window_width + 'px');
                    $('video').css('height', window_height + 'px');

                } else {
                    var width = 395;
                    var height = 223;
                    $('#video_player').css('height', height + 'px');
                    $('#video_notifications').removeClass('panel-active');
                    $('.video_controls').css('width', width + 'px');
                    $('video').css('width', width + 'px');
                    $('video').css('height', height + 'px');
                    $('#video_player').css('width', width + 'px');
                    $('#video_player').css('position', 'relative');
                    $('body').css('overflow', 'visible');
                }
            }

            function asset_disable_media(album, asset, media) {
                var button = $("#has" + media + "_" + asset);
                if (button.hasClass("btn-success")) {
                    button.removeClass('btn-success');
                    button.addClass('btn-danger');
                    button.text("®remove_media® " + media);
                } else {
                    button.removeClass('btn-danger');
                    button.addClass('btn-success');
                    button.text("®add_media® " + media);
                }
                $.ajax({
                    type: 'POST',
                    url: 'index.php?action=asset_disable_media',
                    data: {
                        'album': album,
                        'asset': asset,
                        'media': media
                    }
                });
            }
        </script>    
        <div id="video_player" class="remove_full">
            <!-- #main_video : HTML5 video player.
                    There is no selected source by default.
            -->
            <video id="main_video" controls controlsList="nodownload" src="<?php echo $asset_metadata['src']; ?>" preload="auto" type="video/mp4">
                <source id="main_video_source" 
                        <?php if (array_key_exists('low_slide_src', $asset_metadata)) { ?> high_slide_src="<?php echo $asset_metadata['high_slide_src'] . '&origin=' . $appname; ?>" low_slide_src="<?php echo $asset_metadata['low_slide_src'] . '&origin=' . $appname; ?>"<?php } ?>

                        <?php if (array_key_exists('low_cam_src', $asset_metadata)) { ?> high_cam_src="<?php echo $asset_metadata['high_cam_src'] . '&origin=' . $appname; ?>" low_cam_src="<?php echo $asset_metadata['low_cam_src'] . '&origin=' . $appname; ?>" <?php }
                        ?>>  
            </video>

            <?php if ($asset_metadata['record_type'] == 'camslide') { ?>
                <video id="secondary_video" controls 
                       controlsList="nodownload" src="<?php echo $asset_metadata['low_slide_src'] . '&origin=' . $appname; ?>" 
                       preload="metadata" type="video/mp4">
                </video>
            <?php } ?>
            <div id="load_warn">®Load_for_switch®</div>

            <script>
                type = 'cam';
    <?php if (isset($_SESSION['loaded_type'])) { ?>
                    type = '<?php echo $_SESSION['loaded_type']; ?>';
        <?php
    } else {
        if (isset($asset_metadata['record_type']) && $asset_metadata['record_type'] != 'camslide') {
            ?>
                        type = '<?php echo $asset_metadata['record_type'] ?>';
            <?php
        }
    }
    ?>
                time = <?php echo(isset($timecode) ? $timecode : 0) ?>;

                player_prepare('low', type, time);
            </script>
            <div class="video_controls">
                <ul>
                    <?php if ($playbackRate) {
                        ?>
                        <li>
                            <a id="toggleRate" href="javascript:player_playbackspeed_toggle();" title="®Change_speedrate®">1.0x</a> 
                        </li>
                        <?php
                    }
                    if (isset($asset_metadata['record_type']) && $asset_metadata['record_type'] == 'camslide') {
                        ?>
                        <li>
                            <a class="movie-button active" title="®Watch_video®" href="javascript:player_video_type_set('cam');"></a>
                            <a class="slide-button" title="®Watch_slide®" href="javascript:player_video_type_set('slide');"></a>
                        </li>
                    <?php }
                    ?>
                    <li>
                        <a class="high-button" title="®Watch_high®" href="javascript:player_video_quality_set('high');"></a>
                        <a class="low-button active" title="®Watch_low®" href="javascript:player_video_quality_set('low');"></a>
                    </li>                    
                    <li>
                        <a class="fullscreen-button" href="javascript:player_video_fullscreen(!fullscreen);" title="®Toggle_fullscreen®"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div style="clear: both;"></div>
        <br><br>
        <?php
        if (ezmam_check_media_exist($album, $asset, 'high_cam') == true && ezmam_check_media_exist($album, $asset, 'high_slide') == true) {
            if ($asset_metadata['record_type'] != 'slide') {
                $camclass = 'danger';
                $camstatus = '®remove_media®';
            } else {
                $camclass = 'success';
                $camstatus = '®add_media®';
            }
            if ($asset_metadata['record_type'] != 'cam') {
                $slideclass = 'danger';
                $slidestatus = '®remove_media®';
            } else {
                $slideclass = 'success';
                $slidestatus = '®add_media®';
            }
            echo '<button class="btn btn-xs btn-' . $camclass . '" onclick="asset_disable_media(\'' . $album . '\',\'' . $asset . '\',\'cam\')" id="hascam_' . $asset . '">' . $camstatus . ' cam</button> ';
            echo '<button class="btn btn-xs btn-' . $slideclass . '" onclick="asset_disable_media(\'' . $album . '\',\'' . $asset . '\',\'slide\')" id="hasslide_' . $asset . '">' . $slidestatus . ' slide</button> ';
            echo '<div style="clear: both;"></div>';
        }
        ?>
    </div>
    <div style="clear: both;"></div>

    <?php
}
?>
<div style="clear: both;"></div>
