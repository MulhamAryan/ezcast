<?php
if (isset($input['album']))
    $album = $input['album'];

if (empty($album))
    echo json_encode('no_album_selected');

else {
    $album = $album . '-pub';

    if (ezmam_album_exists($album) && $ApiNbrElm > 0) {
        $metadata = ezmam_asset_list_metadata($album);
        if (is_array($metadata)) {
            $album_token = ezmam_album_token_get($album);
            $array = array(
                "album_name" => $album,
                "album_token" => $album_token,
                "album_url" => $ezplayer_url . "/?action=view_album_assets&album=$album&token=$album_token"
            );
            $nbrofitem = 0;
            foreach ($metadata as $asset) {

                if ($ApiNbrElm == $nbrofitem) break;

                $metadata = $asset['metadata'];
                $metadata["status"] = isset($metadata["status"]) ? $metadata["status"] : null;
                if ($metadata["status"] == "processed") {
                    $array["assets"][] =
                        array(
                            "asset_name" => $asset['name'],
                            "asset_title" => $metadata["title"],
                            "asset_author" => $metadata["author"],
                            "asset_token" => ezmam_asset_token_get($album, $asset['name']),
                            "asset_url" => $ezplayer_url . "/?action=view_asset_details&album=$album&asset=" . $asset['name'] . "&asset_token=" . ezmam_asset_token_get($album, $asset['name']) . ""
                        );
                    $json = json_encode($array, JSON_UNESCAPED_UNICODE);
                    $nbrofitem++;
                }
            }
            log_append('{"' . $ApiClient . '":{"timestamp":"' . date("d-m-Y H:i:s") . '", "access_to":"' . $ApiAction . '","album":"' . $album . '"}}');
            echo $json;
        } else echo json_encode("album_error");
    } else echo json_encode('album_not_found');
}
?>