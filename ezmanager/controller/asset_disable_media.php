<?php

function index($param = array())
{
    global $input;
    global $repository_path;

    if (!acl_session_key_check($input['sesskey'])) {
        echo "Error: Session key is not valid";
        die;
    }

    if (!isset($input['album']) || !isset($input['asset']) || !isset($input['media'])) {
        die;
    }

    ezmam_repository_path($repository_path);

    $metadata = ezmam_asset_metadata_get($input['album'], $input['asset']);

    if ($metadata['record_type'] == 'cam' && $input['media'] == 'slide' || $metadata['record_type'] == 'slide' && $input['media'] == 'cam')
        $metadata['record_type'] = 'camslide';

    elseif ($metadata['record_type'] == 'camslide' && $input['media'] == 'slide')
        $metadata['record_type'] = 'cam';

    elseif ($metadata['record_type'] == 'camslide' && $input['media'] == 'cam')
        $metadata['record_type'] = 'slide';

    $res = ezmam_asset_metadata_set($input['album'], $input['asset'], $metadata);
    if (!$res) {
        error_print_message(ezmam_last_error());
        die;
    }
}